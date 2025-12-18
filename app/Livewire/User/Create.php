<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Validation\Rule;

class Create extends Component
{
    public $name, $email, $password, $role;

    public function getUserRoleProperty()
    {
        return UserRole::all();
    }

    public function store()
    {
        // Validasi lebih baik dan aman
        $validated = $this->validate(
            [
                'name' => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email'),
                ],
                'role' => 'required',
                'password' => 'required|min:8'
            ],
            [
                'name.required' => 'Nama harus diisi',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Email tidak valid',
                'role.required' => 'Role harus dipilih',
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 8 karakter'
            ]
        );

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password'])
        ]);

        // Assign Role (mengambil role berdasarkan ID yang valid)
        $role = UserRole::findOrFail($validated['role']);
        $user->syncRoles([$role->name]);

        // Reset hanya form field
        $this->reset(['name', 'email', 'password', 'role']);

        // Notifikasi
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Pengguna Berhasil Ditambahkan',
            'duration' => 2500,
        ]);

        // Tutup modal + refresh
        $this->dispatch('form-close-modal', id: 'formCreateUser');
        $this->dispatch('refreshPage');
    }

    public function render()
    {
        return view('livewire.user.create');
    }
}
