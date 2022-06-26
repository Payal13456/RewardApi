<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;

    protected $table = 'plan';
    protected $fillable = ['name', 'validity', 'amount', 'tax', 'total', 'status',];
}
