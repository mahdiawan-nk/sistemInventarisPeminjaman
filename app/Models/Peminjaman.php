<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Peminjaman extends Model
{
    use SoftDeletes;
    protected $table = 'peminjamen';
    protected $fillable = [
        'user_id',
        'code_data_pinjaman',
        'nama_peminjam',
        'code_peminjam',
        'no_hp',
        'unit',
        'keperluan',
        'tanggal_pinjam',
        'waktu_peminjaman',
        'tanggal_kembali',
        'waktu_pengembalian',
        'aktiual_tanggal_kembali',
        'aktual_waktu_pengembalian',
        'status_peminjaman',
        'approval_peminjaman',
        'notes',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function peminjamanItems()
    {
        return $this->hasMany(PeminjamanItem::class);
    }

    public function items()
    {
        return $this->belongsToMany(
            Item::class,
            'peminjaman_items',   // pivot table
            'peminjaman_id',
            'item_id'
        );
    }

    public function peminjamanLogs()
    {
        return $this->hasMany(PeminjamanLog::class);
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
