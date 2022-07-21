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

    public static function getShopCoverImgPath ($img)
    {
        if(!empty($img)) 
            if(\File::exists(public_path('/uploads/shop/cover/'.$img.'')))
                return asset('public/uploads/shop/cover/'.$img.'');

            else 
                return asset('public/uploads/shop/cover/no-image.png');
        else
            return asset('public/uploads/shop/cover/no-image.png');
    }
}
