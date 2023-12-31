<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\Size;
use App\Models\Product;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function dropdown(Request $request){
        try {
            $suppliers = Supplier::select('sup_id','sup_name')->where('status',1)->orderBy('sup_name', 'asc')->get();
            $category = ProductCategory::select('cat_id','cat_name')->where('status',1)->orderBy('cat_name', 'asc')->get();
            $sizes = Size::all();
            $data = [
                'suppliers'=> $suppliers,
                'category' => $category,
                'sizes' => $sizes
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
                'prod_selling_price'=>'required',
                'prod_quantity'=> 'required',
                'prod_size_id'=> 'required'
            ]);
            $userId= auth()->user()->id;
            DB::beginTransaction();
            $data =[
                    'prod_name' => $request->prod_name,
                    'prod_sup_id'=>$request->prod_sup_id,
                    'prod_cat_id'=>$request->prod_cat_id,
                    'prod_cost'=>$request->prod_cost,
                    'prod_selling_price'=> $request->prod_selling_price,
                    'prod_quantity'=> $request->prod_quantity,
                    'prod_size_id'=> $request->prod_size_id,
                    'added_by' => $userId,
                    'modified_by'=>$userId,
                    'status'=>1,
                    'created_date'=>Carbon::now()->format('d-m-y'),
                    'updated_date'=>Carbon::now()->format('d-m-y'),
            ];
            $product = Product::create($data);
            DB::commit();
            return ApiResponse::success(true,'Product added successfully',$product,200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        }
    } 
    public function index(Request $request){
        try {
            $per_page = 10;
            if ($request->per_page) {
                $per_page = $request->per_page;
            }
            $query = Product::whereIn('products.status', [0, 1])
                ->join('suppliers', 'products.prod_sup_id', '=', 'suppliers.sup_id')
                ->join('product_categories', 'products.prod_cat_id', '=', 'product_categories.cat_id')
                ->join('sizes', 'products.prod_size_id', '=', 'sizes.size_id')
                ->select('products.*', 'suppliers.sup_name', 'product_categories.cat_name', 'sizes.size_name');  
            if ($request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('products.prod_name', 'LIKE', $searchTerm)
                      ->orWhere('products.prod_id', 'LIKE', $searchTerm)
                    //   ->orWhere('products.prod_cost', 'LIKE', $searchTerm)
                    //   ->orWhere('products.prod_selling_price', 'LIKE', $searchTerm)
                    //   ->orWhere('products.prod_quantity', 'LIKE', $searchTerm)
                      ->orWhere('suppliers.sup_name', 'LIKE', $searchTerm) 
                      ->orWhere('sizes.size_name', 'LIKE', $searchTerm)
                      ->orWhere('product_categories.cat_name', 'LIKE', $searchTerm);
                    //   ->orWhere('sizes.size_name', 'LIKE', $searchTerm);
                });
            }
            $products = $query->paginate($per_page);
            return ApiResponse::success(true, 'Product list fetched successfully', $products, 200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function availableProducts(Request $request){
        try {
            $per_page = 10;
            if ($request->per_page) {
                $per_page = $request->per_page;
            }
            $query = Product::whereIn('products.status', [0, 1])
                ->join('suppliers', 'products.prod_sup_id', '=', 'suppliers.sup_id')
                ->join('product_categories', 'products.prod_cat_id', '=', 'product_categories.cat_id')
                ->join('sizes', 'products.prod_size_id', '=', 'sizes.size_id')
                ->select('products.*', 'suppliers.sup_name', 'product_categories.cat_name', 'sizes.size_name')
                ->where('products.prod_quantity', '>', 0);  
            if ($request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('products.prod_name', 'LIKE', $searchTerm)
                      ->orWhere('products.prod_id', 'LIKE', $searchTerm)
                    //   ->orWhere('products.prod_cost', 'LIKE', $searchTerm)
                    //   ->orWhere('products.prod_selling_price', 'LIKE', $searchTerm)
                    //   ->orWhere('products.prod_quantity', 'LIKE', $searchTerm)
                      ->orWhere('suppliers.sup_name', 'LIKE', $searchTerm)
                      ->orWhere('product_categories.cat_name', 'LIKE', $searchTerm);
                    //   ->orWhere('sizes.size_name', 'LIKE', $searchTerm);
                });
            }
            $products = $query->paginate($per_page);
            return ApiResponse::success(true, 'Product list fetched successfully', $products, 200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function destory($id){
        try {
            $products = Product::find($id);
            $products->status=2;
            $products->updated_date=Carbon::now()->format('d-m-y');
            $products->save();
            return ApiResponse::success(true,'Product deleted successfully',$products,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function edit($id,Request $request){
        try {
            $product = Product::where('products.prod_id',$id)
            ->join('suppliers', 'products.prod_sup_id', '=', 'suppliers.sup_id')
            ->join('product_categories', 'products.prod_cat_id', '=', 'product_categories.cat_id')
            ->join('sizes','products.prod_size_id','=','sizes.size_id')
            ->select('products.*', 'suppliers.sup_name', 'product_categories.cat_name','sizes.size_name')
            ->get();
            return ApiResponse::success(true,'Product fetch successfully',$product,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function update(Request $request,$id){
        try {
            $request->validate([
                'prod_name' => 'required',
                'prod_sup_id'=> 'required',
                'prod_cat_id'=>'required',
                'prod_cost'=> 'required',
                'prod_selling_price'=>'required',
                'prod_quantity'=> 'required',
                'prod_size_id'=> 'required'
            ]);
            $userId= auth()->user()->id;
            $product = Product::find($id);
            $product->prod_name=$request->prod_name;
            $product->prod_sup_id=$request->prod_sup_id;
            $product->prod_cat_id=$request->prod_cat_id;
            $product->prod_cost=$request->prod_cost;
            $product->prod_selling_price=$request->prod_selling_price;
            $product->prod_quantity=$request->prod_quantity;
            $product->prod_size_id=$request->prod_size_id;
            $product->modified_by=$userId;
            $product->updated_date=Carbon::now()->format('d-m-y');
            $product->save();
            return ApiResponse::success(true,'Supplier updated successfully',$product,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function changeStatus($id){
        try {
            $products = Product::find($id);
            $status = $products->status;
            if($status===1){
                $products->status=0;
            }
            else{
                $products->status=1;
            }
            $products->updated_date=Carbon::now()->format('d-m-y');
            $products->save();
            return ApiResponse::success(true,'Product status updated successfully',$products,200);
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
            $products = Product::where('products.status', 2)
            ->join('suppliers', 'products.prod_sup_id', '=', 'suppliers.sup_id')
            ->join('product_categories', 'products.prod_cat_id', '=', 'product_categories.cat_id')
            ->join('sizes','products.prod_size_id','=','sizes.size_id')
            ->select('products.*', 'suppliers.sup_name', 'product_categories.cat_name','sizes.size_name')
            ->paginate($per_page);
            return ApiResponse::success(true,'Product archive list fetch successfully',$products,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function restoreOrDelete(Request $request,$id){
        try {
            $products = Product::find($id);
            if($request->status===1){
                $products->status=1;
            }
            else{
                $products->status=3;
            }
            $products->updated_date=Carbon::now()->format('d-m-y');
            $products->save();
            return ApiResponse::success(true,'Product status updated successfully',$products,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function outOfStock(Request $request) {
        try {
            $per_page = 10;
            if ($request->per_page) {
                $per_page = $request->per_page;
            }
            $query = Product::whereIn('products.status', [0, 1])
                ->join('suppliers', 'products.prod_sup_id', '=', 'suppliers.sup_id')
                ->join('product_categories', 'products.prod_cat_id', '=', 'product_categories.cat_id')
                ->join('sizes', 'products.prod_size_id', '=', 'sizes.size_id')
                ->select('products.*', 'suppliers.sup_name', 'product_categories.cat_name', 'sizes.size_name')
                ->where('products.prod_quantity','<',11);  
            if ($request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('products.prod_name', 'LIKE', $searchTerm)
                      ->orWhere('products.prod_id', 'LIKE', $searchTerm)
                      ->orWhere('suppliers.sup_name', 'LIKE', $searchTerm)
                      ->orWhere('product_categories.cat_name', 'LIKE', $searchTerm);
                });
            }
            $products = $query->paginate($per_page);
            return ApiResponse::success(true, 'Product list fetched successfully', $products, 200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
}
