<?php

namespace App\Services\Peminjaman;

use App\Models\Item;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use App\Models\PeminjamanLog;
use App\Models\ItemStatusLog;
use Illuminate\Support\Facades\DB;

class UpdatePeminjamanService
{
    public function execute(
        Peminjaman $peminjaman,
        array $data,
        array $items,
        int $userId
    ): Peminjaman {
        return DB::transaction(function () use ($peminjaman, $data, $items, $userId) {

            // 1. Simpan status lama
            $oldStatus = $peminjaman->status_peminjaman;
            $oldApproval = $peminjaman->approval_peminjaman;

            // 2. Update data peminjaman
            $peminjaman->update($data);

            // 3. Sinkronisasi item
            $this->syncItems($peminjaman, $items, $userId);

            // 4. Log perubahan
            $this->logUpdate(
                $peminjaman->id,
                $oldStatus,
                $data['status_peminjaman'],
                $oldApproval,
                $data['approval_peminjaman'],
                $userId,
                $data['notes']
            );

            return $peminjaman;
        });
    }

    /* =======================
     |  ITEM SYNC
     ======================= */

    protected function syncItems(Peminjaman $peminjaman, array $items, int $userId): void
    {
        $newItemIds = collect($items)->pluck('id')->toArray();

        $oldItemIds = PeminjamanItem::where('peminjaman_id', $peminjaman->id)
            ->pluck('item_id')
            ->toArray();

        $toAttach = array_diff($newItemIds, $oldItemIds);
        $toDetach = array_diff($oldItemIds, $newItemIds);

        // Attach new items
        $this->attachItems($peminjaman->id, $toAttach, $userId);

        // Detach removed items
        $this->detachItems($peminjaman->id, $toDetach, $userId);
    }

    protected function attachItems(int $peminjamanId, array $itemIds, int $userId): void
    {
        if (empty($itemIds))
            return;

        $items = Item::whereIn('id', $itemIds)->lockForUpdate()->get();

        foreach ($items as $item) {
            if ($item->status !== 'available') {
                throw new \Exception("Item {$item->asset_code} tidak tersedia");
            }

            PeminjamanItem::create([
                'peminjaman_id' => $peminjamanId,
                'item_id' => $item->id,
            ]);

            $this->updateItemStatus(
                $item,
                'borrowed',
                $userId,
                "Ditambahkan ke peminjaman"
            );
        }
    }

    protected function detachItems(int $peminjamanId, array $itemIds, int $userId): void
    {
        if (empty($itemIds))
            return;

        $items = Item::whereIn('id', $itemIds)->lockForUpdate()->get();

        foreach ($items as $item) {

            PeminjamanItem::where([
                'peminjaman_id' => $peminjamanId,
                'item_id' => $item->id,
            ])->delete();

            $this->updateItemStatus(
                $item,
                'available',
                $userId,
                "Dikeluarkan dari peminjaman"
            );
        }
    }

    /* =======================
     |  ITEM STATUS
     ======================= */

    protected function updateItemStatus(
        Item $item,
        string $newStatus,
        int $userId,
        string $note
    ): void {
        $oldStatus = $item->status;

        if ($oldStatus === $newStatus)
            return;

        $item->update(['status' => $newStatus]);

        ItemStatusLog::create([
            'item_id' => $item->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $userId,
            'notes' => $note,
        ]);
    }

    /* =======================
     |  LOG PEMINJAMAN
     ======================= */

    protected function logUpdate(
        int $peminjamanId,
        ?string $oldStatus,
        string $newStatus,
        ?string $oldApproval,
        string $newApproval,
        int $userId,
        ?string $actionDetail
    ): void {
        PeminjamanLog::create([
            'peminjaman_id' => $peminjamanId,
            'action' => 'update',
            'old_status_peminjaman' => $oldStatus,
            'new_status_peminjaman' => $newStatus,
            'old_approval_peminjaman' => $oldApproval,
            'new_approval_peminjaman' => $newApproval,
            'action_detail' => 'Update peminjaman',
            'performed_by' => $userId,
        ]);
    }
}
