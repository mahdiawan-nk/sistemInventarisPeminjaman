<?php

namespace App\Livewire\Item;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;

use App\Models\Item;
use App\Models\Categori as Category;
use App\Models\Location;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Title('Item List')]
class Index extends Component
{
    use WithPagination, WithoutUrlPagination, AuthorizesRequests;

    public string $search = '';
    public int $perPage = 10;
    public ?int $selectedId = null;

    public array $filter = [
        'category_id' => null,
        'location_id' => null,
        'trash' => 'notTrashed', // notTrashed | withTrashed | onlyTrashed
    ];

    /* =======================
     |  COMPUTED PROPERTIES
     ======================= */

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    public function getLocationsProperty()
    {
        return Location::orderBy('name')->get();
    }

    /* =======================
     |  UI ACTIONS
     ======================= */

    public function openCreate()
    {
        $this->resetSelected();
        $this->dispatch('slide-open', id: 'formCreateItem');
    }

    public function openEdit(int $id)
    {
        $this->selectedId = $id;
        $this->dispatch('slide-open', id: 'formUpdateItem');
    }

    public function openDelete(int $id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: 'deleteItem');
    }

    public function openRestore(int $id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: 'restoreItem');
    }

    public function openForceDelete(int $id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: 'forceDeleteItem');
    }

    /* =======================
     |  CRUD ACTIONS
     ======================= */

    public function confirmDelete()
    {
        $item = $this->findItem();

        // $this->authorize('delete', $item);

        $item->delete();

        $this->notify('success', 'Item berhasil dihapus');
        $this->closeModal('deleteItem');
    }

    public function restore()
    {
        $item = $this->findItem(true);

        // $this->authorize('restore', $item);

        $item->restore();

        $this->notify('success', 'Item berhasil dikembalikan');
        $this->closeModal('restoreItem');
    }

    public function forceDelete()
    {
        $item = $this->findItem(true);

        // $this->authorize('forceDelete', $item);

        $item->forceDelete();

        $this->notify('success', 'Item berhasil dihapus permanen');
        $this->closeModal('forceDeleteItem');
    }

    /* =======================
     |  QUERY
     ======================= */

    protected function items()
    {
        return Item::query()
            ->with(['category', 'location', 'creator'])

            // Trash filter
            ->when($this->filter['trash'] === 'withTrashed', fn ($q) => $q->withTrashed())
            ->when($this->filter['trash'] === 'onlyTrashed', fn ($q) => $q->onlyTrashed())

            // Category
            ->when($this->filter['category_id'], fn ($q) =>
                $q->where('category_id', $this->filter['category_id'])
            )

            // Location
            ->when($this->filter['location_id'], fn ($q) =>
                $q->where('location_id', $this->filter['location_id'])
            )

            // Search
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('name', 'like', "%{$this->search}%")
                        ->orWhere('asset_code', 'like', "%{$this->search}%")
                        ->orWhere('brand', 'like', "%{$this->search}%")
                        ->orWhere('model', 'like', "%{$this->search}%");
                });
            });
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

    protected function findItem(bool $withTrashed = false): Item
    {
        return $withTrashed
            ? Item::withTrashed()->findOrFail($this->selectedId)
            : Item::findOrFail($this->selectedId);
    }

    protected function resetSelected(): void
    {
        $this->selectedId = null;
    }

    protected function closeModal(string $id): void
    {
        $this->dispatch('close-modal', id: $id);
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
     |  RENDER
     ======================= */

    public function render()
    {
        return view('livewire.item.index', [
            'data' => $this->items()->paginate($this->perPage),
        ]);
    }
}
