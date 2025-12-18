<?php

namespace App\Services\Peminjaman;

use App\Models\Peminjaman;
use App\Models\PeminjamanLog;

class CreateLogPeminjamanService
{
    public function execute(
        Peminjaman $peminjaman,
        string $action,
        int $performedBy,
        ?string $actionDetail = null,
        ?string $oldStatus = null,
        ?string $newStatus = null,
        ?string $oldApproval = null,
        ?string $newApproval = null
    ): PeminjamanLog {
        return PeminjamanLog::create([
            'peminjaman_id' => $peminjaman->id,
            'action' => $action,
            'old_status_peminjaman' => $oldStatus,
            'new_status_peminjaman' => $newStatus,
            'old_approval_peminjaman' => $oldApproval,
            'new_approval_peminjaman' => $newApproval,
            'action_detail' => $actionDetail,
            'performed_by' => $performedBy,
        ]);
    }
}
