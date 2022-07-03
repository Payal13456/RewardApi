<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    protected $table = "user_information";

    protected $fillable = ['user_id' , 'address_line1' , 'address_line2','landmark','state','city','pincode','country'];
}
