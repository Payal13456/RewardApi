<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    use HasFactory;

    protected $table = "helps";

    protected $fillable = ['question','answer','status','category'];

    public function category(){
    	return $this->hasOne(\App\Models\HelpCategory::class , 'id' , 'category');
    }
}
