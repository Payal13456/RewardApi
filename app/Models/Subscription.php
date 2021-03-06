<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    
    protected $table = 'subscription';
    protected $fillable = ['user_id', 'plan_id', 'transaction_id', 'expiry_date', 'is_expired', 'status'];

    public function plan(){
        return $this->belongsTo(\App\Models\Plans::class);
    }
}
