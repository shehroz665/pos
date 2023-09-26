<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\Product;
class ProductController extends Controller
{
  public function dropdown(Request $request){
    try {
        $suppliers = Supplier::select('sup_id','sup_name')->where('status',1)->get();
        $category = ProductCategory::select('cat_id','cat_name')->where('status',1)->get();
        $data = [
            'suppliers'=> $suppliers,
            'category' => $category
        ];
        return ApiResponse::success(true,'product dropdown list fetch successfully',$data,200);
    } catch (\Throwable $e) {
        return  ApiResponse::error(false, $e->getMessage(),[],500);
    } 
  }
  public function store(Request $request)
  {
      try {
          $request->validate([
              'prod_name' => 'required',
              'prod_sup_id'=> 'required',
              'prod_cat_id'=>'required',
              'prod_cost'=> 'required',
              'prod_selling_price'=>'required'
          ]);
          $userId= auth()->user()->id;
          DB::beginTransaction();
          $data =[
                  'prod_name' => $request->prod_name,
                  'prod_sup_id'=>$request->prod_sup_id,
                  'prod_cat_id'=>$request->prod_cat_id,
                  'prod_cost'=>$request->prod_cost,
                  'prod_selling_price'=> $request->prod_selling_price,
                  'added_by' => $userId,
                  'modified_by'=>$userId,
                  'status'=>1,
          ];
         $product = Product::create($data);
          DB::commit();
          return ApiResponse::success(true,'Product added successfully',$product,200);
      } catch (\Throwable $e) {
          DB::rollBack();
          return  ApiResponse::error(false, $e->getMessage(),[],500);
      }
  } 
}
