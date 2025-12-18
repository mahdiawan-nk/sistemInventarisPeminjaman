<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'old_status',
        'new_status',
        'changed_by',
        'notes',
    ];

    /**
     * Relasi ke Item
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * Relasi ke User yang mengubah status
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
