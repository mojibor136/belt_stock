<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'amount',
        'address',
        'status',
    ];

    public function transactions()
    {
        return $this->hasMany(VendorTrx::class, 'vendor_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany(PurchaseItem::class, 'vendor_id', 'id');
    }

    protected static function booted()
    {
        static::deleting(function ($vendor) {
            if ($vendor->transactions()->exists()) {
                throw new Exception('এই গ্রাহক মুছে ফেলা সম্ভব নয়। সম্পর্কযুক্ত লেনদেন আছে।');
            }
        });
    }
}
