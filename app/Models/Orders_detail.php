<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders_detail extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
