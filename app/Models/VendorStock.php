<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorStock extends Model
{
    protected $fillable = [
        'vendor_id',
        'group_id',
        'brand_id',
        'size',
        'quantity',
        'status',
    ];
}
