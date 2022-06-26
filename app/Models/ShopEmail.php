<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopEmail extends Model
{
    use HasFactory;

    protected $table = 'shop_emails';
    protected $fillable = [
        'vendor_id', 'shop_email', 'status'
    ];
}
