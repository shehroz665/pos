<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosUser extends Model
{
    use HasFactory;
    protected $table = 'pos_users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'email', 'password',
    ];
}
