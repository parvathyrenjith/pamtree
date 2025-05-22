<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RedeemedCoupons extends Model
{  
    protected $table = 'redeemed_coupons';
    protected $primaryKey = 'id'; 
}
