<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Categori;
use App\Models\Location;
use App\Models\Item;
use App\Models\Peminjaman;
use App\Models\PeminjamanItem;
use Livewire\Attributes\Title;


#[Title('Dashboard')]
class Dashboard extends Component
{
    public $userCount, $categoryCount, $locationCount, $itemCount, $peminjamanCount;
    public $itemAvailableCount, $itemDamagedCount, $itemBorrowedCount;

    public $currentYear = null;

    public function mount()
    {
        $this->currentYear = now()->year;
        $this->userCount = User::count();
        $this->categoryCount = Categori::count();
        $this->locationCount = Location::count();
        $this->itemCount = Item::count();
        $this->peminjamanCount = Peminjaman::count();
        $this->itemAvailableCount = Item::where('status', 'available')->count();
        $this->itemDamagedCount = Item::where('status', 'aamaged')->count();
        $this->itemBorrowedCount = Item::where('status', 'borrowed')->count();
    }

    public function chartItemsummary()
    {
        $listStatus = [
            'available',
            'damaged',
            'borrowed',
            'broken',
            'lost',
            'maintenance'
        ];

        $GetItem = Item::all();
        $statusCounts = [];
        foreach ($listStatus as $status) {
            $statusCounts[ucfirst($status)] = $GetItem->where('status', $status)->count();
        }
        return $statusCounts;
    }

    public function trendUnitVsItem()
    {
        $getAllItem = Item::all();
        $getBorrowedItem = PeminjamanItem::all();

        return [
            'Borrowed' => $getBorrowedItem->count(),
            'Available' => $getAllItem->count(),
        ];
    }

    public function trendPeminjaman()
    {
        $currentYear = now()->year;

        // Query data dari database pada tahun berjalan
        $rows = Peminjaman::selectRaw('MONTH(tanggal_pinjam) as month, COUNT(*) as total')
            ->whereYear('tanggal_pinjam', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month'); // hasil: [1 => 5, 3 => 2, ...]

        // Generate secara lengkap 12 bulan
        return collect(range(1, 12))->map(function ($monthNumber) use ($rows) {
            return [
                'label' => date('M', mktime(0, 0, 0, $monthNumber, 1)),
                'y' => (int) $rows->get($monthNumber, 0) // jika tidak ada data → default 0
            ];
        })->toArray();
    }

    public function overviewNewItemAdded()
    {
        return Item::orderBy('created_at', 'desc')->limit(5)->get();
    }

    public function overviewPeminjaman()
    {
        return Peminjaman::orderBy('created_at', 'desc')->limit(5)->get();
    }
    public function render()
    {
        return view('livewire.dashboard');
    }
}
