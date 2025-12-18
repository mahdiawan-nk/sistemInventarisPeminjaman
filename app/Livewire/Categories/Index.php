<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Categori;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination;
    public $search = '';
    public $perPage = 10;
    public $showCreate = false;
    public $showUpdate = false;
    public $selectedId;

    #[Title('Categories List')]
    public function openModalCreate()
    {
        $this->dispatch('form-open-modal', id: 'formCreateCategori');
    }
    public function edit($id)
    {
        $this->selectedId = $id;
        $this->dispatch('form-open-modal', id: 'formUpdateCategori');
    }

    public function delete($id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: 'deleteCategori');
    }

    public function confirmDelete()
    {
        try {
            Categori::find($this->selectedId)->delete();
            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => 'Berhasil',
                'message' => 'Categori berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            $this->dispatch('notify', [
                'variant' => 'error',
                'title' => 'Gagal',
                'message' => 'Categori gagal dihapus',
            ]);
        }

        $this->dispatch('close-modal', id: 'deleteCategori');
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
        $data = Categori::query()
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate($this->perPage);
        return view('livewire.categories.index', compact('data'));
    }
}
