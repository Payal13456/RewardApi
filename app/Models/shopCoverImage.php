<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shopCoverImage extends Model
{
    use HasFactory;

    protected $table = 'shop_cover_images';
    protected $fillable = [
        'vendor_id', 'cover_image', 'status'
    ];
}
