<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ApiPlatform\Metadata\ApiResource; ## API Platform for Laravel Projects: Exposing A Model

#[ApiResource] ## API Platform for Laravel Projects: Exposing A Model

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['dist_no','dist_name','shop_id','net_pay','contacts', 'category','month_of_pay', 'status','description'];
}
