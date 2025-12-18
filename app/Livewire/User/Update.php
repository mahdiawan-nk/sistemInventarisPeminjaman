<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use App\Models\UserRole;
class Update extends Component
{
    public $name, $email, $password, $role;
    public $selectedId;
    public $editPassword = false;

    public function mount($selectedId)
    {
        $this->selectedId = $selectedId;
        $getUser = User::with('roles')->where('id', $this->selectedId)->first();
        $this->name = $getUser?->name;
        $this->email = $getUser?->email;
        $this->role = $getUser?->roles?->first()?->id;
    }

    public function getUserRoleProperty()
    {
        return UserRole::all();
    }

    public function update()
    {
        // Ambil user
        $user = User::findOrFail($this->selectedId);

        // Update data dasar
        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        // Jika password diubah
        if ($this->editPassword && $this->password) {
            $data['password'] = bcrypt($this->password);
        }

        $user->update($data);

        // Sync role (hapus role lama, pasang role baru)
        $role = UserRole::findOrFail($this->role);
        $user->syncRoles([$role->name]);

        // Reset hanya property form
        $this->reset(['selectedId', 'name', 'email', 'password', 'editPassword', 'role']);

        // Notifikasi
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Pengguna berhasil diupdate',
            'duration' => 2500,
        ]);

        // Tutup modal & refresh
        $this->dispatch('form-close-modal', id: 'formUpdateUser');
        $this->dispatch('refreshPage');
    }

    public function render()
    {
        return view('livewire.user.update');
    }
}
