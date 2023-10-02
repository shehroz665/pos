<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
    protected $casts = [
        'products' => 'json',
    ];
}
