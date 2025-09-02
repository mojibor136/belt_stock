<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'group',
        'brand_id',
        'rate_type',
        'cost_rate',
        'sales_rate',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
};
