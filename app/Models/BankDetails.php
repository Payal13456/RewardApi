<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    use HasFactory;

    protected $table = "bank_info";

    protected $fillable = ["user_id", "bank_name","branch","acc_no","ifsc_code","status","iban_no","beneficiary_name"];
}
