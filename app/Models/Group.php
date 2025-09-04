<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;

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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($group) {
            if ($group->stocks()->exists()) {
                throw new \Exception('এই গ্রুপটি ডিলিট করা সম্ভব নয়। সম্পর্কিত স্টক আছে।');
            }

            $group->sizes()->delete();
        });
    }
};
