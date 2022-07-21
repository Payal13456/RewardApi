<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $fillable = [
        'type', 'msg', 'user_id', 'received_id', 'is_read', 'status'
    ];

    public function user(){
        return $this->belongsTo(\App\Models\User::class)->select('name' , 'last_name','email','mobile_no','country_code');
    }
}
