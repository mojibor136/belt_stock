<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    protected $fillable = [
        'brand',
        'group',
        'size',
        'quantity',
        'type'
    ];
}
