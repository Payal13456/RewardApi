<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopLandline extends Model
{
    use HasFactory;

    protected $table = 'shop_landlines';
    protected $fillable = [
        'vendor_id','phone_code','landline_no','status'
    ];
}
