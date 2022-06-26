<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryCode extends Model
{
    use HasFactory;

    protected $table = 'country_codes';
    protected $fillable = [
        'phone_code','country_code','country_name','symbol','capital','currency','continent','continent_code','alpha_3'
    ];
}
