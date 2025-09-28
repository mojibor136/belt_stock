<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'vendor_id',
        'group_id',
        'brand_id',
        'size',
        'quantity',
        'status',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
