<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'cust_name' => 'required',
                'cust_number' => 'required',
                'products' => 'required',
                'total_products' => 'required',
                'total_price' => 'required',
                'total_quantity' => 'required',
                'total_cost' => 'required'
            ]);
            DB::beginTransaction();
            foreach ($request->products as $product) {
                $productId = $product['prod_id'];
                $quantity = $product['quantity'];
                $product = Product::find($productId);
                $product->prod_quantity -= $quantity;
                $product->save();
            }
            $data = [
                'cust_name' => $request->cust_name,
                'cust_number' => $request->cust_number,
                'products' => $request->products,
                'total_products' => $request->total_products,
                'total_price' => $request->total_price,
                'total_quantity' => $request->total_quantity,
                'total_cost'=> $request->total_cost,
                'status'=>1
                
            ];
            $invoice=Invoice::create($data);
            DB::commit();
            return ApiResponse::success(true,'Invoice created successfully',$invoice,200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        }

    } 
    public function edit($id){
        try {
            $invoice = Invoice::find($id);
            return ApiResponse::success(true,'Invoice fetch successfully',$invoice,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
    public function index(Request $request){
        try {
            $per_page = 10;
            if ($request->per_page) {
                $per_page = $request->per_page; 
            }
    
            $query = Invoice::whereIn('status', [0, 1]);
    
            if ($request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('cust_name', 'LIKE', $searchTerm)
                        ->orWhere('cust_number', 'LIKE', $searchTerm)
                        ->orWhere('invoice_id', 'LIKE', $searchTerm)
                        ->orWhereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?", [$searchTerm]);
                });
            }
            $query->orderBy('invoice_id', 'desc');
            $invoices = $query->paginate($per_page);
    
            return ApiResponse::success(true, 'Invoice list fetched successfully', $invoices, 200);
        } catch (\Throwable $e) {
            return ApiResponse::error(false, $e->getMessage(), [], 500);
        } 
    }
    public function sales(Request $request){
        try {
            $query = Invoice::whereIn('status', [0, 1]);
            if ($request->search) {
                $searchTerm = '%' . $request->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?", [$searchTerm]);
                });
            }
            $query->orderBy('invoice_id', 'desc');
            $invoices = $query->get();
            return ApiResponse::success(true, 'Invoice list fetched successfully', $invoices, 200);
        } catch (\Throwable $e) {
            return ApiResponse::error(false, $e->getMessage(), [], 500);
        } 
    }    

}
