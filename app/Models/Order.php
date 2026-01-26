<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Order extends Model
{
    public function orderDetails()
    {
        return $this->hasMany(Orders_detail::class);
    }
    
}
