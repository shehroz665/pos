<?php

namespace App\Http\Controllers\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
class InvoiceController extends Controller
{
    public function store(Request $request)
    {
        try {
            // $userId= auth()->user()->id;
            $request->validate([
                'cust_name' => 'required',
                'cust_number' => 'required',
                'products' => 'required',
                'total_products' => 'required',
                'total_price' => 'required',
                'total_quantity' => 'required',
            ]);
            DB::beginTransaction();
            $data = [
                'cust_name' => $request->cust_name,
                'cust_number' => $request->cust_number,
                'products' => $request->products,
                'total_products' => $request->total_products,
                'total_price' => $request->total_price,
                'total_quantity' => $request->total_quantity,
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

}
