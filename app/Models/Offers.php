<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;

    protected $table = 'offers';
    protected $fillable = [
        'vendor_id', 'offer_desc', 'start_date', 'end_date', 'status'
    ];
}
