<?php

namespace App\Livewire\ItemLogs;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Item;
use App\Models\ItemStatusLog;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $perPage = 10;

    #[Title('Item Logs')]

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function getItemsProperty()
    {
        return Item::query()
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('brand', 'like', "%{$this->search}%")
                    ->orWhere('model', 'like', "%{$this->search}%")
                    ->orWhere('asset_code', 'like', "%{$this->search}%")
                    ->orWhere('serial_number', 'like', "%{$this->search}%");
            })
            ->with([
                'statusLogs' => function ($q) {
                    $q->with('user')
                        ->latest();
                }
            ])
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.item-logs.index', [
            'items' => $this->items,
        ]);
    }
}
