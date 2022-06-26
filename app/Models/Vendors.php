<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    use HasFactory;

    protected $table = 'vendor';
    protected $fillable = [
        'name', 'phone_code','mobile_no', 'email', 'shop_name', 'website', 'description', 'category_id', 'location', 'lat', 'long', 'shop_logo', 'status', 'is_blocked'
    ];
}
