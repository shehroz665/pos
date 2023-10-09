<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $primaryKey = 'sup_id';
    protected $fillable = [
        'sup_name', 'sup_contact', 'sup_description', 'added_by', 'modified_by', 'status','created_date','updated_date',
    ];
     public $timestamps = false;
}
