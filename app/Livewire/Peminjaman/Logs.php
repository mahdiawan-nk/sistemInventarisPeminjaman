<?php

namespace App\Livewire\Peminjaman;

use Livewire\Component;
use App\Models\PeminjamanLog;

class Logs extends Component
{
    public function getLogsProperty()
    {
        return PeminjamanLog::with(['peminjaman', 'performedBy'])
            ->latest()
            ->get()
            ->groupBy('peminjaman_id')
            ->map(function ($logs) {
                return $logs->groupBy(
                    fn($log) =>
                    $log->created_at->format('Y-m-d')
                );
            });
    }

    public function render()
    {
        return view('livewire.peminjaman.logs', [
            'logs' => $this->logs,
        ]);
    }
}
