<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = [
        'size',
        'cost_rate',
        'sales_rate',
        'rate_type',
        'group_id',
        'brand_id',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function salesHistories()
    {
        return $this->hasMany(StockHistory::class, 'size', 'size')
            ->where('brand', $this->brand->brand ?? '')
            ->where('group', $this->group->group ?? '')
            ->where('type', 'sales');
    }

    protected static function booted()
    {
        static::deleting(function ($size) {
            if ($size->stocks()->exists()) {
                throw new \Exception('এই সাইজটি ডিলিট করা সম্ভব নয়। সম্পর্কযুক্ত স্টক আছে।');
            }
        });
    }
}
