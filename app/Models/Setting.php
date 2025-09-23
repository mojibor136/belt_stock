<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'fav_icon',
        'site_logo',
        'invoice',
        'vendor_stock',
        'memo_status',
        'description',
        'shop_name',
        'shop_address',
        'shop_phone',
    ];

    protected $casts = [
        'shop_name' => 'array',
        'shop_address' => 'array',
        'shop_phone' => 'array',
    ];
}
