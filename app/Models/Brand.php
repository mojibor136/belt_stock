<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;

class Brand extends Model
{
    protected $fillable = [
        'brand'
    ];

    public function groups()
    {
        return $this->hasMany(Group::class);
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

        static::deleting(function ($brand) {
            if ($brand->stocks()->exists()) {
                throw new \Exception('এই ব্র্যান্ডটি ডিলিট করা সম্ভব নয়। সম্পর্কিত স্টক আছে।');
            }

            $brand->groups()->each(function ($group) {
                $group->sizes()->delete();
                $group->delete();
            });

            $brand->sizes()->delete();
        });
    }
}