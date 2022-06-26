<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = ['name','image','status'];

    public static function getCategoryImagePath ($ImgName)
    {
        if ($ImgName) {
            if(\File::exists(public_path('/uploads/category/'.$ImgName))) {
                return asset('public/uploads/category/'.$ImgName);
            }
            else {
                return asset('public/uploads/dummy-banner.jpg');
            }
        } else {
            return asset('public/uploads/dummy-banner.jpg');
        }
    }
    
}
