<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategory;
use Carbon\Carbon;
class ProductCategoryController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'cat_name' => 'required|string',
            ]);
            $userId= auth()->user()->id;
            DB::beginTransaction();
            $data =[
                    'cat_name' => $request->cat_name,
                    'user_id' => $userId,
                    'added_by' => $userId,
                    'modified_by'=>$userId,
                    'status'=>1,
                    'created_date'=>Carbon::now()->format('d-m-y'),
                    'updated_date'=>Carbon::now()->format('d-m-y'),
            ];
            $category = ProductCategory::create($data);
            DB::commit();
            return ApiResponse::success(true,'Product Category successfully',$category,200);
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
            $category = ProductCategory::where('user_id',$userId)->whereIn('status',[0,1]);
            if($request->search){
                $searchTerm = '%' . $request->search . '%';
                $category->where(function ($query) use ($searchTerm) {
                    $query->where('cat_name', 'LIKE', $searchTerm)
                          ->orWhere('cat_id', 'LIKE', $searchTerm)
                          ->orWhere('created_date', 'LIKE', $searchTerm)
                          ->orWhere('updated_date', 'LIKE', $searchTerm);
                });
            }
            $category=$category->paginate($per_page);
            return ApiResponse::success(true,'Product Category list fetch successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function destory($id){
        try {
            $category = ProductCategory::find($id);
            $category->status=2;
            $category->updated_date=Carbon::now()->format('d-m-y');
            $category->save();
            return ApiResponse::success(true,'Product Category deleted successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function edit($id){
        try {
            $category = ProductCategory::find($id);
            return ApiResponse::success(true,'Product Category fetch successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function update(Request $request,$id){
        try {
            $category = ProductCategory::find($id);
            $category->cat_name=$request->cat_name;
            $category->updated_date=Carbon::now()->format('d-m-y');
            $category->save();
            return ApiResponse::success(true,'Product Category updated successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function changeStatus($id){
        try {
            $category = ProductCategory::find($id);
            $status = $category->status;
            if($status===1){
                $category->status=0;
            }
            else{
                $category->status=1;
            }
            $category->updated_date=Carbon::now()->format('d-m-y');
            $category->save();
            return ApiResponse::success(true,'Product Category status updated successfully',$category,200);
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
            $category = ProductCategory::where('user_id',$userId)->where('status',2)->paginate($per_page);
            return ApiResponse::success(true,'Product Category list fetch successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function restoreOrDelete(Request $request,$id){
        try {
            $category = ProductCategory::find($id);
            if($request->status===1){
                $category->status=1;
            }
            else{
                $category->status=3;
            }
            $category->updated_date=Carbon::now()->format('d-m-y');
            $category->save();
            return ApiResponse::success(true,'Product Category status updated successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
   

}
