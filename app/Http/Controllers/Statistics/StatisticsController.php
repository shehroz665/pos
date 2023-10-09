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
            $todayDate = $currentDate->format('d-m-y');
            $invoiceRequest = new Request([
                'search' => $todayDate,
            ]);
            $invoiceController = new InvoiceController;
            $invoiceResponse = $invoiceController->sales($invoiceRequest);
            $totalStock = DB::table('invoices')->sum(DB::raw('total_cost * total_quantity'));
            $graphStatistics = $this->getWeeklyStatistics()->original;
            $data=[
                'products'=> $products,
                'suppliers'=> $suppliers,
                'categories'=> $category,
                 'sales'=> $invoiceResponse->original['data'],
                 'totalStock'=>$totalStock,
                 'graphStatistics'=>$graphStatistics,
                 'date'=> $todayDate
            ];
            return ApiResponse::success(true, 'Invoice list fetched successfully', $data, 200);
        } catch (\Throwable $e) {
            return ApiResponse::error(false, $e->getMessage(), [], 500);
        } 
    }
    public function getWeeklyStatistics()
    {
        try {
            $weeklyStatistics = [];
            $currentDayOfWeek = Carbon::now()->dayOfWeek;
            $offsetToSunday = ($currentDayOfWeek + 1) % 7;
            for ($i = 0; $i < 7; $i++) {
                $date = Carbon::now()->subDays($offsetToSunday - $i);
                $salesForDay = Invoice::whereDate('updated_date', $date->format('d-m-y'))
                    ->sum('total_price');
    
                $weeklyStatistics[] = [
                    'name' => $date->format('l'), 
                    'date' => $date->format('d-m-y'),
                    'sales' => intval($salesForDay),
                ];
            }
    
            return response()->json($weeklyStatistics);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
}
