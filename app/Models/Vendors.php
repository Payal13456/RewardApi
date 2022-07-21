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

    public function category(){
    	return $this->belongsTo(\App\Models\Categories::class);
    }

    public function offers(){
    	return $this->hasMany(\App\Models\Offers::class , 'vendor_id' , 'id');
    }

    public function shop_cover_image(){
        return $this->hasMany(\App\Models\shopCoverImage::class , 'vendor_id' , 'id');
    }

    public function shop_email(){
        return $this->hasMany(\App\Models\ShopEmail::class , 'vendor_id' , 'id');
    }

    public function shop_landline(){
        return $this->hasMany(\App\Models\ShopLandline::class , 'vendor_id' , 'id');
    }

    public function shop_mobileno(){
        return $this->hasMany(\App\Models\ShopMobileNo::class , 'vendor_id' , 'id');
    }

}
