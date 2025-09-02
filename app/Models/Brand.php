<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
            $brand->groups()->each(function ($group) {
                $group->sizes()->delete();
                $group->delete();
            });

            $brand->sizes()->delete();
            $brand->stocks()->delete();
        });
    }
}