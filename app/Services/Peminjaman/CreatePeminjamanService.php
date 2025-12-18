<?php

namespace App\Services\Peminjaman;
use App\Models\Item;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use App\Models\PeminjamanLog;
use App\Models\ItemStatusLog;
use Illuminate\Support\Facades\DB;

class CreatePeminjamanService
{
    public function execute(array $data, array $items, int $userId): Peminjaman
    {
        return DB::transaction(function () use ($data, $items, $userId) {

            $peminjaman = Peminjaman::create($data);

            $this->attachItems($peminjaman->id, $items);
            $this->logPeminjaman($peminjaman->id, $data, $userId);
            $this->updateItemStatus($items, $data['code_data_pinjaman'], $userId);

            return $peminjaman;
        });
    }

    protected function attachItems(int $peminjamanId, array $items): void
    {
        foreach ($items as $item) {
            PeminjamanItem::create([
                'peminjaman_id' => $peminjamanId,
                'item_id' => $item['id'],
            ]);
        }
    }

    protected function logPeminjaman(int $peminjamanId, array $data, int $userId): void
    {
        PeminjamanLog::create([
            'peminjaman_id' => $peminjamanId,
            'action' => 'create',
            'old_status_peminjaman' => null,
            'new_status_peminjaman' => $data['status_peminjaman'],
            'old_approval_peminjaman' => null,
            'new_approval_peminjaman' => $data['approval_peminjaman'],
            'action_detail' => 'Peminjaman baru',
            'performed_by' => $userId,
        ]);
    }

    protected function updateItemStatus(array $items, string $code, int $userId): void
    {
        $itemIds = collect($items)->pluck('id');

        $models = Item::whereIn('id', $itemIds)->lockForUpdate()->get();

        foreach ($models as $item) {
            if ($item->status !== 'available') {
                throw new \Exception("Item {$item->asset_code} tidak tersedia");
            }

            $oldStatus = $item->status;

            $item->update(['status' => 'borrowed']);

            ItemStatusLog::create([
                'item_id' => $item->id,
                'old_status' => $oldStatus,
                'new_status' => 'borrowed',
                'changed_by' => $userId,
                'notes' => "Dipinjam melalui {$code}",
            ]);
        }
    }
}
