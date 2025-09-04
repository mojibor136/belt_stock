<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'amount',
        'status',
        'address',
        'transport',
    ];

    public function memos()
    {
        return $this->hasMany(Memo::class);
    }

    public function transactions()
    {
        return $this->hasMany(CustomerTrx::class);
    }

    protected static function booted()
    {
        static::deleting(function ($customer) {
            if ($customer->memos()->exists() || $customer->transactions()->exists()) {
                throw new \Exception('এই গ্রাহককে মুছে ফেলা সম্ভব নয়। সম্পর্কযুক্ত মেমো বা লেনদেন রয়েছে।');
            }
        });
    }
}
