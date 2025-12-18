<?php

namespace App\Livewire\Peminjaman;

use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

use App\Models\Item;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Carbon\Carbon;
use App\Models\PeminjamanLog;
use App\Models\ItemStatusLog;
class Create extends Component
{
    public $code_data_peminjaman, $nama_peminjam, $code_peminjam, $no_hp, $unit, $keperluan, $tanggal_pinjam, $waktu_peminjaman, $tanggal_kembali, $waktu_pengembalian;
    public $status_peminjaman = 'pinjam';
    public $approval_peminjaman = 'process';
    public $itemPeminjaman = [];
    public $searchItem = '';
    public $listItem = [];
    #[Title('Create Peminjaman')]

    public function mount()
    {
        $this->code_data_peminjaman = $this->generateCodePeminjaman();
    }

    protected function generateCodePeminjaman()
    {
        $date = Carbon::now()->format('Ymd');

        // Ambil data terakhir hari ini
        $last = Peminjaman::whereDate('created_at', Carbon::today())
            ->orderBy('id', 'desc')
            ->first();

        $number = 1;


        if ($last && str_contains($last->code_data_pinjaman, $date)) {
            $lastNumber = (int) substr($last->code_data_pinjaman, -4);
            $number = $lastNumber + 1;
        }

        return 'PMJ-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    public function addItem($itemId)
    {
        // Cegah duplikasi
        if (collect($this->itemPeminjaman)->contains('id', $itemId)) {
            return;
        }

        $item = Item::with('location')->findOrFail($itemId);

        $this->itemPeminjaman[] = [
            'id' => $item->id,
            'asset_code' => $item->asset_code,
            'name' => $item->name,
            'location' => $item->location?->name,
            'status' => $item->status,
        ];

        $this->searchItem = '';
    }

    public function removeItem($itemId)
    {
        $this->itemPeminjaman = collect($this->itemPeminjaman)
            ->reject(fn($item) => $item['id'] == $itemId)
            ->values()
            ->toArray();
    }

    public function updatedSearchItem()
    {
        $this->listItem = Item::where(function ($q) {
            $q->where('asset_code', 'like', "%{$this->searchItem}%")
                ->orWhere('name', 'like', "%{$this->searchItem}%")
                ->orWhere('brand', 'like', "%{$this->searchItem}%")
                ->orWhere('model', 'like', "%{$this->searchItem}%");
        })
            ->where('status', 'available') // hanya item siap dipinjam
            ->get();
    }

    public function store()
    {

        $this->validate([
            'code_data_peminjaman' => 'required',
            'nama_peminjam' => 'required',
            'code_peminjam' => 'required',
            'no_hp' => 'required',
            'unit' => 'required',
            'keperluan' => 'required',
            'tanggal_pinjam' => 'required',
            'waktu_peminjaman' => 'required',
            'tanggal_kembali' => 'required',
            'waktu_pengembalian' => 'required',
            'status_peminjaman' => 'required',
            'approval_peminjaman' => 'required',
        ]);

        if (count($this->itemPeminjaman) == 0) {
            $this->dispatch('notify', [
                'variant' => 'warning',
                'title' => 'Gagal',
                'message' => 'Item tidak boleh kosong',
                'position' => 'center',
                'duration' => 5000
            ]);
            return;
        }

        $peminjaman = Peminjaman::create([
            'code_data_pinjaman' => $this->code_data_peminjaman,
            'nama_peminjam' => $this->nama_peminjam,
            'code_peminjam' => $this->code_peminjam,
            'no_hp' => $this->no_hp,
            'unit' => $this->unit,
            'keperluan' => $this->keperluan,
            'tanggal_pinjam' => $this->tanggal_pinjam,
            'waktu_peminjaman' => $this->waktu_peminjaman,
            'tanggal_kembali' => $this->tanggal_kembali,
            'waktu_pengembalian' => $this->waktu_pengembalian,
            'status_peminjaman' => $this->status_peminjaman,
            'approval_peminjaman' => $this->approval_peminjaman,
            'created_by' => auth()->user()->id
        ]);

        $newId = $peminjaman->id;

        foreach ($this->itemPeminjaman as $item) {
            PeminjamanItem::create([
                'peminjaman_id' => $peminjaman->id,
                'item_id' => $item['id'],
            ]);
        }
        $this->createLogPeminjaman($newId);
        $this->updateStatusItem();
        $this->reset();
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Peminjaman berhasil dibuat',
        ]);

        $this->redirectRoute('peminjaman.index');

    }

    public function createLogPeminjaman($idPeminjaman)
    {
        PeminjamanLog::create([
            'peminjaman_id' => $idPeminjaman,
            'action' => 'create',
            'old_status' => null,
            'new_status' => $this->status_peminjaman,
            'old_approval_peminjaman' => null,
            'new_approval_peminjaman' => $this->approval_peminjaman == 'process' ? 'prosess' : $this->approval_peminjaman,
            'action_detail' => 'Peminjaman baru',
            'performed_by' => auth()->user()->id
        ]);
    }

    public function updateStatusItem()
    {
        DB::transaction(function () {

            foreach ($this->itemPeminjaman as $itemData) {

                $item = Item::findOrFail($itemData['id']);

                // Simpan status lama
                $oldStatus = $item->status;
                $newStatus = 'borrowed';

                // Update status item
                $item->update([
                    'status' => $newStatus,
                ]);

                // Simpan log perubahan status
                ItemStatusLog::create([
                    'item_id' => $item->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'changed_by' => auth()->id(),
                    'notes' => 'Dipinjam melalui peminjaman ' . $this->code_data_peminjaman,
                ]);
            }

        });
    }
    public function render()
    {
        return view('livewire.peminjaman.create');
    }
}
