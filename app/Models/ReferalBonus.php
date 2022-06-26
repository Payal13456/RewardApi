<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferalBonus extends Model
{
    use HasFactory;

    protected $table = 'referal_bonus';
    protected $fillable = ['user_id', 'referal_code', 'amount', 'ref_user_id', 'status'];
}
