<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'sup_name' => 'required|string',
                'sup_contact'=> 'required|string',
                'sup_description'=>'string'
            ]);
            $userId= auth()->user()->id;
            DB::beginTransaction();
            $data =[
                    'sup_name' => $request->sup_name,
                    'sup_contact'=>$request->sup_contact,
                    'sup_description'=>$request->sup_description,
                    'user_id' => $userId,
                    'added_by' => $userId,
                    'modified_by'=>$userId,
                    'status'=>1,
            ];
            $category = Supplier::create($data);
            DB::commit();
            return ApiResponse::success(true,'Supplier added successfully',$category,200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        }

    } 
    public function index(Request $request){
        try {
            $per_page=10;
            if($request->per_page){
                $per_page=$request->per_page; 
            }
            $userId= auth()->user()->id;
            $category = Supplier::where('user_id',$userId)->whereIn('status',[0,1])->paginate($per_page);
            return ApiResponse::success(true,'Supplier list fetch successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function destory($id){
        try {
            $category = Supplier::find($id);
            $category->status=2;
            $category->save();
            return ApiResponse::success(true,'Supplier deleted successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function edit($id){
        try {
            $category = Supplier::find($id);
            return ApiResponse::success(true,'Supplier fetch successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function update(Request $request,$id){
        try {
            $category = Supplier::find($id);
            $category->cat_name=$request->cat_name;
            $category->save();
            return ApiResponse::success(true,'Supplier updated successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function changeStatus($id){
        try {
            $category = Supplier::find($id);
            $status = $category->status;
            if($status===1){
                $category->status=0;
            }
            else{
                $category->status=1;
            }
            $category->save();
            return ApiResponse::success(true,'Supplier status updated successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function archive(Request $request){
        try {
            $per_page=10;
            if($request->per_page){
                $per_page=$request->per_page; 
            }
            $userId= auth()->user()->id;
            $category = Supplier::where('user_id',$userId)->where('status',2)->paginate($per_page);
            return ApiResponse::success(true,'Supplier list fetch successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function restoreOrDelete(Request $request,$id){
        try {
            $category = Supplier::find($id);
            if($request->status===1){
                $category->status=1;
            }
            else{
                $category->status=3;
            }
            $category->save();
            return ApiResponse::success(true,'Supplier status updated successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
}
