<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'cust_name',
        'cust_number',
        'products',
        'total_products',
        'total_price',
        'total_quantity',
        'total_cost',
        'borrow_amount',
        'status',
        'created_date',
        'updated_date', 

    ];
    protected $casts = [
        'products' => 'json',
        'created_at' => 'date:d-m-Y',
        'updated_at' => 'date:d-m-Y',
    ];
}
