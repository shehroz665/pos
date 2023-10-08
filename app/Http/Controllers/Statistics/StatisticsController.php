<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use Carbon\Carbon;
use App\Http\Controllers\Invoice\InvoiceController;

class StatisticsController extends Controller
{
    public function index(Request $request){
        try {
            $products= Product::where('status',1)->count();
            $suppliers=Supplier::where('status',1)->count();
            $category=ProductCategory::where('status',1)->count();
            $currentDate = Carbon::now();
            $todayDate = $currentDate->format('d-m-Y');
            $invoiceRequest = new Request([
                'search' => $todayDate,
            ]);
            $invoiceController = new InvoiceController;
            $invoiceResponse = $invoiceController->sales($invoiceRequest);
            $data=[
                'products'=> $products,
                'suppliers'=> $suppliers,
                'categories'=> $category,
                 'sales'=> $invoiceResponse->original['data'],
                 'date'=> $todayDate
            ];
            return ApiResponse::success(true, 'Invoice list fetched successfully', $data, 200);
        } catch (\Throwable $e) {
            return ApiResponse::error(false, $e->getMessage(), [], 500);
        } 
    }
}
