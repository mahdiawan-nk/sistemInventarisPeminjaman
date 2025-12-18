<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanLog extends Model
{
    protected $table = 'peminjaman_logs';

    protected $fillable = [
        'peminjaman_id',
        'action',
        'old_status_peminjaman',
        'new_status_peminjaman',
        'old_approval_peminjaman',
        'new_approval_peminjaman',
        'action_detail',
        'performed_by',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
