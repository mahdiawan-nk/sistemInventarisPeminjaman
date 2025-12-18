<?php

namespace App\Livewire\Peminjaman;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Peminjaman;
use App\Models\ItemStatusLog;
use App\Services\Peminjaman\CreateLogPeminjamanService;

#[Title('List Peminjaman')]
class Index extends Component
{
    use WithPagination, WithoutUrlPagination, AuthorizesRequests;

    public string $search = '';
    public int $perPage = 10;
    public ?int $selectedId = null;
    public string $tanggal_pengembalian, $jam_pengembalian;
    public string $approval_peminjaman;
    #[Url]
    public array $filter = [
        'trash' => 'notTrashed', // notTrashed | withTrashed | onlyTrashed
    ];


    /* =======================
     |  UI ACTIONS
     ======================= */

    public function openDelete(int $id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: 'deletePeminjaman');
    }

    public function openRestore(int $id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: 'restorePeminjaman');
    }

    public function openPengembalian(int $id)
    {
        $this->selectedId = $id;
        $this->tanggal_pengembalian = date('Y-m-d');
        $this->jam_pengembalian = date('H:i');
        $this->dispatch('form-open-modal', id: 'pengembalianPeminjaman');
    }

    public function openApproval(int $id)
    {
        $this->selectedId = $id;
        $item = $this->findItem();
        $this->approval_peminjaman = $item->approval_peminjaman;
        $this->dispatch('form-open-modal', id: 'approvalPeminjaman');
    }

    /* =======================
     |  CRUD ACTIONS
     ======================= */

    public function confirmDelete(CreateLogPeminjamanService $service)
    {
        $item = $this->findItem();

        $this->authorize('delete', $item);

        $item->delete();

        $service->execute(
            $item,
            'delete',
            auth()->id(),
            'User ' . auth()->user()->name . ' telah menghapus item ' . $item->code_data_pinjaman,
            $item->status_peminjaman,
            null,
            $item->approval_peminjaman,
            null
        );

        $this->notify('success', 'Item berhasil dihapus');
        $this->closeModal('deletePeminjaman');
    }

    public function restore(CreateLogPeminjamanService $service)
    {
        $item = $this->findItem(true);

        $this->authorize('restore', $item);

        $item->restore();

        $service->execute(
            $item,
            'restore',
            auth()->id(),
            'User ' . auth()->user()->name . ' telah mengembalikan item ' . $item->code_data_pinjaman,
            $item->status_peminjaman,
            null,
            $item->approval_peminjaman,
            null
        );

        $this->notify('success', 'Item berhasil dikembalikan');
        $this->closeModal('restorePeminjaman');
    }

    public function forceDelete()
    {
        $item = $this->findItem(true);

        $this->authorize('forceDelete', $item);

        $item->forceDelete();

        $this->notify('success', 'Item berhasil dihapus permanen');
        $this->closeModal('forceDeleteItem');
    }

    public function storePengembalian(CreateLogPeminjamanService $service)
    {
        $item = $this->findItem();
        // dump($item);

        $service->execute(
            $item,
            'status_change',
            auth()->id(),
            'User ' . auth()->user()->name . ' telah update status item ' . $item->code_data_pinjaman,
            $item->status_peminjaman,
            'kembali',
            $item->approval_peminjaman,
            null
        );
        $item->update([
            'status_peminjaman' => 'kembali',
            'aktiual_tanggal_kembali' => $this->tanggal_pengembalian,
            'aktual_waktu_pengembalian' => $this->jam_pengembalian,
        ]);
        $item->items()->update(['status' => 'available']); // update status item
        $this->logStatus('borrowed', 'available', $item->items()->get()->pluck('id')->toArray()); // log status
        $this->notify('success', 'Item berhasil diupdate status');
        $this->closeModal('pengembalianPeminjaman');
    }

    public function logStatus($oldStatus, $newStatus, $id)
    {
        foreach ($id as $item_id) {
            ItemStatusLog::create([
                'item_id' => $item_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'changed_by' => auth()->user()->id,
                'notes' => 'Item Di kembalikan melalui Peminjaman',       // atau pakai field khusus note log
            ]);
        }

    }

    public function storeApproval(CreateLogPeminjamanService $service)
    {
        $item = $this->findItem();
        // $this->authorize('pengembalian', $item);

        $service->execute(
            $item,
            'status_change',
            auth()->id(),
            'User ' . auth()->user()->name . ' telah update status item ' . $item->code_data_pinjaman,
            $item->status_peminjaman,
            null,
            $item->approval_peminjaman,
            null
        );
        $item->update([
            'approval_peminjaman' => $this->approval_peminjaman,
        ]);


        $this->notify('success', 'Item berhasil diupdate status');
        $this->closeModal('approvalPeminjaman');
    }

    /* =======================
     |  LIVEWIRE HOOKS
     ======================= */

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    #[On('refreshPage')]
    public function refresh()
    {
        $this->resetPage();
    }


    /* =======================
     |  HELPERS
     ======================= */

    protected function findItem(bool $withTrashed = false): Peminjaman
    {
        return $withTrashed
            ? Peminjaman::withTrashed()->findOrFail($this->selectedId)
            : Peminjaman::with('items')->findOrFail($this->selectedId);
    }

    protected function resetSelected(): void
    {
        $this->selectedId = null;
    }

    protected function closeModal(string $id): void
    {
        $this->dispatch('close-modal', id: $id);
        $this->dispatch('form-close-modal', id: $id);
        $this->resetSelected();
    }

    protected function notify(string $variant, string $message): void
    {
        $this->dispatch('notify', [
            'variant' => $variant,
            'title' => ucfirst($variant),
            'message' => $message,
        ]);
    }

    /* =======================
     |  QUERY
     ======================= */
    protected function items()
    {
        return Peminjaman::query()
            ->with(['peminjamanItems.item', 'createdUser'])

            // Trash filter
            ->when($this->filter['trash'] === 'withTrashed', fn($q) => $q->withTrashed())
            ->when($this->filter['trash'] === 'onlyTrashed', fn($q) => $q->onlyTrashed())

            // Search
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('nama_peminjam', 'like', "%{$this->search}%")
                        ->orWhere('code_data_pinjaman', 'like', "%{$this->search}%");
                });
            })

            ->when(auth()->user()->hasRole('User'), fn($q) => $q->where('created_by', auth()->id()));
    }

    /* =======================
     |  RENDER
     ======================= */
    public function render()
    {
        return view('livewire.peminjaman.index', [
            'data' => $this->items()->paginate($this->perPage),
        ]);
    }
}
