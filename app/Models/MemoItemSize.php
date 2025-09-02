<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoItemSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_item_id',
        'size',
        'quantity',
        'subtotal',
    ];

    public function memoItem()
    {
        return $this->belongsTo(MemoItem::class, 'memo_item_id');
    }
}
