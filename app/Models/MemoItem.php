<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_id',
        'brand_id',
        'group_id',
        'piece_rate',
        'inch_rate',
        'cost_inch_rate',
        'cost_piece_rate',
        'item_total',
    ];

    public function memo()
    {
        return $this->belongsTo(Memo::class);
    }

    public function sizes()
    {
        return $this->hasMany(MemoItemSize::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
