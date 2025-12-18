<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Relasi ke items
     * Satu lokasi bisa memiliki banyak barang.
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'location_id');
    }
}
