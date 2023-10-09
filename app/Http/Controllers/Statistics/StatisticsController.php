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
            $data=[
                'products'=> $products,
                'suppliers'=> $suppliers,
                'categories'=> $category,
                 'sales'=> $invoiceResponse->original['data'],
                 'totalStock'=>$totalStock,
                 'date'=> $todayDate
            ];
            return ApiResponse::success(true, 'Invoice list fetched successfully', $data, 200);
        } catch (\Throwable $e) {
            return ApiResponse::error(false, $e->getMessage(), [], 500);
        } 
    }
    public function getWeeklyStatistics()
    {
        $currentDate = Carbon::now();
        $startDate = $currentDate->startOfWeek();
        $endDate = $currentDate->endOfWeek();
    
        $startDateFormatted = $startDate->format('Y-m-d'); // Format as yyyy-mm-dd
        $endDateFormatted = $endDate->format('Y-m-d');     // Format as yyyy-mm-dd
    
        // Fetch the data from the database for the specified week
        $weeklyStatistics = Invoice::selectRaw('SUM(total_price) as total_sales, DATE(created_at) as sale_date')
            ->whereBetween('created_at', [$startDateFormatted, $endDateFormatted])
            ->groupBy('sale_date')
            ->get();
    
        // Initialize an associative array to store category sales
        $formattedData = [];
    
        // Loop through the fetched data and format it
        foreach ($weeklyStatistics as $statistic) {
            // Extract the day from the sale_date
            $day = Carbon::parse($statistic->sale_date)->format('l');
            $formattedData[] = [
                'name' => $day,
                'sales' => $statistic->total_sales,
            ];
        }
    
        return response()->json([
            'start_date' => $startDateFormatted,
            'end_date' => $endDateFormatted,
            'weekly_statistics' => $formattedData,
        ]);
    }
    
    
}
