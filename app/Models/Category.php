<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    #Table Definitions -START-
    protected $table = 'category';
    protected $fillable = ['id','name','created_at','updated_at'];
	#Table Definitions -END-


   
}
