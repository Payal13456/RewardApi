<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopMobileNo extends Model
{
    use HasFactory;

    protected $table = 'shop_mobile_nos';
    protected $fillable = [
        'vendor_id','phone_code','mobile_no','status'
    ];
}
