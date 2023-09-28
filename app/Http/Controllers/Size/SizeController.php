<?php

namespace App\Http\Controllers\Size;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\Size;
class SizeController extends Controller
{
    public function index(Request $request){
        try {
            $sizes = Size::all();
            return ApiResponse::success(true,'Sizes list fetch successfully',$sizes,200);
        } catch (\Throwable $e) {
            return  ApiResponse::error(false, $e->getMessage(),[],500);
        } 
    }
}
