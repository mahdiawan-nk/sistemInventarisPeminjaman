<?php

namespace App\Livewire\Peminjaman;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Item;
use App\Models\Peminjaman;
use App\Services\Peminjaman\UpdatePeminjamanService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Title('Update Peminjaman')]
class Update extends Component
{
    use AuthorizesRequests;

    public Peminjaman $peminjaman;

    public $code_data_peminjaman;
    public $nama_peminjam, $code_peminjam, $no_hp, $unit, $keperluan,$notes;
    public $tanggal_pinjam, $waktu_peminjaman;
    public $tanggal_kembali, $waktu_pengembalian;

    public $status_peminjaman;
    public $approval_peminjaman;

    public $itemPeminjaman = [];
    public $searchItem = '';
    public $listItem = [];

    public function mount(Peminjaman $peminjaman)
    {
        // $this->authorize('update', $peminjaman);

        $this->peminjaman = $peminjaman;
        // dd($this->peminjaman->items()->get());
        $this->fillFromModel();
    }

    protected function fillFromModel(): void
    {
        $this->code_data_peminjaman = $this->peminjaman->code_data_pinjaman;
        $this->nama_peminjam = $this->peminjaman->nama_peminjam;
        $this->code_peminjam = $this->peminjaman->code_peminjam;
        $this->no_hp = $this->peminjaman->no_hp;
        $this->unit = $this->peminjaman->unit;
        $this->keperluan = $this->peminjaman->keperluan;

        $this->tanggal_pinjam = Carbon::parse($this->peminjaman->tanggal_pinjam)->format('Y-m-d');
        $this->waktu_peminjaman = $this->peminjaman->waktu_peminjaman;
        $this->tanggal_kembali = Carbon::parse($this->peminjaman->tanggal_kembali)->format('Y-m-d');
        $this->waktu_pengembalian = $this->peminjaman->waktu_pengembalian;

        $this->status_peminjaman = $this->peminjaman->status_peminjaman;
        $this->approval_peminjaman = $this->peminjaman->approval_peminjaman;

        $this->notes = $this->peminjaman->notes;

        $this->itemPeminjaman = $this->peminjaman
            ->items()
            ->with('location')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'asset_code' => $item->asset_code,
                'name' => $item->name,
                'location' => $item->location?->name,
                'status' => $item->status,
            ])
            ->toArray();
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
            'notes' => 'required',
        ];
    }

    public function update(UpdatePeminjamanService $service)
    {
        $this->validate();

        $service->execute(
            $this->peminjaman,
            $this->payload(),
            $this->itemPeminjaman,
            auth()->id()
        );

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Peminjaman berhasil diperbarui',
        ]);

        return redirect()->route('peminjaman.index');
    }

    protected function payload(): array
    {
        return [
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
            'notes' => $this->notes,
        ];
    }

    /* =======================
     |  ITEM SEARCH
     ======================= */

    public function updatedSearchItem()
    {
        $this->listItem = Item::where('status', 'available')
            ->orWhereIn(
                'id',
                collect($this->itemPeminjaman)->pluck('id')
            )
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
        return view('livewire.peminjaman.update');
    }
}
