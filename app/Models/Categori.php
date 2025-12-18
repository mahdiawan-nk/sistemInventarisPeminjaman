<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Categori extends Model
{
    use HasFactory;

    // Jika nama tabel tidak baku (categories), wajib didefinisikan:
    protected $table = 'categoris';

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi ke items (satu kategori punya banyak item)
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'category_id');
    }
}
