<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $table = 'product_categories';
    protected $primaryKey = 'cat_id';
    protected $fillable = ['cat_name', 'user_id', 'added_by', 'modified_by','created_date','updated_date','status'];
    public $timestamps = false;
}
