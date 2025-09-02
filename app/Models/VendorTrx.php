<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorTrx extends Model
{
    protected $fillable = [
        'vendor_id',
        'invoice_type',
        'payment',
        'invoice',
        'debit_credit',
        'invoice_status',
        'status',
    ];

    public function transactions()
    {
        return $this->hasMany(VendorTrx::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }
}
