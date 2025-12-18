<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
class Index extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $search = '';
    public $perPage = 10;
    public $showCreate = false;
    public $showUpdate = false;
    public $selectedId;

    public $idCurrentSessionUser;

    public function boot()
    {
        $this->idCurrentSessionUser = auth()->user()->id;
        $this->resetPage();
    }

    #[Title('User List')]

    public function openModalCreate()
    {
        $this->dispatch('form-open-modal', id: 'formCreateUser');
    }
    public function edit($id)
    {
        $this->selectedId = $id;
        $this->dispatch('form-open-modal', id: 'formUpdateUser');
    }

    public function delete($id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: 'deleteUser');
    }

    public function confirmDelete()
    {
        $isCurrentSession = $this->selectedId == $this->idCurrentSessionUser;
        if ($isCurrentSession) {
            $this->dispatch('notify', [
                'variant' => 'warning',
                'title' => 'Gagal Hapus',
                'message' => 'Anda tidak bisa menghapus diri sendiri',
                'duration' => 5000,
                'sound' => true,
                'position' => 'center'
            ]);
            $this->dispatch('close-modal', id: 'deleteUser');
            return;
        }
        User::find($this->selectedId)->delete();
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Pengguna berhasil dihapus',
        ]);
        $this->dispatch('close-modal', id: 'deleteUser');
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
        $data = User::query()
            ->with('roles')
            ->when($this->search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate($this->perPage);
        return view('livewire.user.index', compact('data'));
    }
}
