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
                'borrow_amount'=>$request->borrow_amount,
                'status'=>$request->status
                
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
                        ->orWhereRaw("DATE_FORMAT(updated_at, '%d-%m-%Y') LIKE ?", [$searchTerm]);
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
                    // $q->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y') LIKE ?", [$searchTerm]);
                    $q->whereRaw("DATE_FORMAT(updated_at, '%d-%m-%Y') LIKE ?", [$searchTerm]);
                });
            }
            $query->selectRaw('SUM(total_products) as total_products_sum');
            $query->selectRaw('SUM(total_price) as total_price_sum');
            $query->selectRaw('SUM(total_quantity) as total_quantity_sum');
            $query->selectRaw('SUM(total_cost) as total_cost_sum');
            $query->orderBy('invoice_id', 'desc');
            $invoices = $query->get();
            $sums = [
                'total_products_sum' => $invoices->first()->total_products_sum ?? 0,
                'total_price_sum' => $invoices->first()->total_price_sum ?? 0,
                'total_quantity_sum' => $invoices->first()->total_quantity_sum ?? 0,
                'total_cost_sum' => $invoices->first()->total_cost_sum ?? 0,
            ];
    
            return ApiResponse::success(true, 'Invoice list fetched successfully', $sums, 200);
        } catch (\Throwable $e) {
            return ApiResponse::error(false, $e->getMessage(), [], 500);
        } 
    }
    public function credit(Request $request){
        try {
            $per_page = 10;
            if ($request->per_page) {
                $per_page = $request->per_page; 
            }
    
            $query = Invoice::where('status',2);
    
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

}
