<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategory;
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
                    'modified_by'=>$userId
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
            $category = ProductCategory::where('user_id',$userId)->paginate($per_page);
            return ApiResponse::success(true,'Product Category list fetch successfully',$category,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
}
