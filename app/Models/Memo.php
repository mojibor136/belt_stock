<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Memo extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_no',
        'created_at',
        'customer_id',
        'debit_credit',
        'debit_credit_status',
        'memo_status',
        'grand_total',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(MemoItem::class);
    }
}
