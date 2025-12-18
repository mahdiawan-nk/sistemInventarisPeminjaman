<?php

namespace App\Livewire\Peminjaman;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Item;
use App\Models\Peminjaman;
use App\Services\Peminjaman\CreatePeminjamanService;
use Carbon\Carbon;

#[Title('Create Peminjaman')]
class Create extends Component
{
    public $code_data_peminjaman;
    public $nama_peminjam, $code_peminjam, $no_hp, $unit, $keperluan;
    public $tanggal_pinjam, $waktu_peminjaman;
    public $tanggal_kembali, $waktu_pengembalian;

    public $status_peminjaman = 'pinjam';
    public $approval_peminjaman = 'process';

    public $itemPeminjaman = [];
    public $searchItem = '';
    public $listItem = [];

    public function mount()
    {
        $this->code_data_peminjaman = $this->generateCode();
        $this->tanggal_pinjam = Carbon::now()->format('Y-m-d');
        $this->waktu_peminjaman = Carbon::now()->format('H:i');
        if (auth()->user()->hasRole('User')) {
            $this->nama_peminjam = auth()->user()->name;
        }
    }

    protected function rules()
    {
        return [
            'nama_peminjam' => 'required',
            'code_peminjam' => 'required',
            'no_hp' => 'required',
            'unit' => 'required',
            'keperluan' => 'required',
            'tanggal_pinjam' => 'required|date',
            'waktu_peminjaman' => 'required',
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'waktu_pengembalian' => 'required',
            'itemPeminjaman' => 'required|array|min:1',
        ];
    }

    public function store(CreatePeminjamanService $service)
    {
        $this->validate();

        $peminjaman = $service->execute(
            $this->payload(),
            $this->itemPeminjaman,
            auth()->id()
        );

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Peminjaman berhasil dibuat',
        ]);

        return redirect()->route('peminjaman.index');
    }

    protected function payload(): array
    {
        $userId = auth()->user()->hasRole('User') ? auth()->id() : null;
        return [
            'user_id' => $userId,
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
            'created_by' => auth()->id(),
        ];
    }

    protected function generateCode(): string
    {
        $date = Carbon::now()->format('Ymd');

        $last = Peminjaman::whereDate('created_at', Carbon::today())
            ->lockForUpdate()
            ->latest()
            ->first();

        $number = $last
            ? ((int) substr($last->code_data_pinjaman, -4) + 1)
            : 1;

        return 'PMJ-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function updatedSearchItem()
    {
        $this->listItem = Item::where('status', 'available')
            ->where(
                fn($q) =>
                $q->where('asset_code', 'like', "%{$this->searchItem}%")
                    ->orWhere('name', 'like', "%{$this->searchItem}%")
            )
            ->limit(10)
            ->get();
    }

    public function addItem(int $id)
    {
        if (collect($this->itemPeminjaman)->contains('id', $id))
            return;

        $item = Item::with('location')->findOrFail($id);
        $this->itemPeminjaman[] = [
            'id' => $item->id,
            'asset_code' => $item->asset_code,
            'name' => $item->name,
            'location' => $item->location?->name,
            'status' => $item->status,
        ];
        $this->searchItem = '';
    }

    public function removeItem(int $id)
    {
        $this->itemPeminjaman = collect($this->itemPeminjaman)
            ->reject(fn($item) => $item['id'] === $id)
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.peminjaman.create');
    }
}
