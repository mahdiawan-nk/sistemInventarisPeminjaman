<?php

namespace App\Livewire\Locations;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;
use App\Models\Location;
use Livewire\Attributes\Title;

class Index extends Component
{

    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $perPage = 10;
    public $showCreate = false;
    public $showUpdate = false;
    public $selectedId;

    #[Title('Lokasi List')]
    public function openModalCreate()
    {
        $this->dispatch('form-open-modal', id: 'formCreateLocation');
    }
    public function edit($id)
    {
        $this->selectedId = $id;
        $this->dispatch('form-open-modal', id: 'formUpdateLocation');
    }

    public function delete($id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: 'deleteLocation');
    }

    public function confirmDelete()
    {
        try {
            Location::find($this->selectedId)->delete();
            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => 'Berhasil',
                'message' => 'Location berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('notify', [
                'variant' => 'error',
                'title' => 'Gagal',
                'message' => 'Location gagal dihapus',
            ]);
        }

        $this->dispatch('close-modal', id: 'deleteLocation');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage(); // reset ke halaman 1 saat ganti perPage

    }
    #[On('refreshPage')]
    public function render()
    {
        $data = Location::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate($this->perPage);
        return view('livewire.locations.index', compact('data'));
    }
}
