<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategory;
use App\Models\Supplier;
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
}
