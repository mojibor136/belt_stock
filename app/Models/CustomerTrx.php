<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerTrx extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_type',
        'payment',
        'invoice',
        'debit_credit',
        'invoice_status',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
