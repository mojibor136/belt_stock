<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
