<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'prod_id';
    protected $fillable = [
        'prod_name',
        'prod_sup_id',
        // 'prod_sup_name',
        'prod_cat_id',
        // 'prod_cat_name',
        'added_by',
        'modified_by',
        'status',
        'prod_cost',
        'prod_selling_price',
        'image',
        'prod_quantity',
        'prod_size_id'
    ];
}
