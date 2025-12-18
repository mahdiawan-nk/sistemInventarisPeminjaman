<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanItem extends Model
{
    protected $table = 'peminjaman_items';

    protected $fillable = [
        'peminjaman_id',
        'item_id',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
