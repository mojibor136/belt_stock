<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'brand_id',
        'group_id',
        'size_id',
        'quantity',
        'alert',
        'created_at'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
