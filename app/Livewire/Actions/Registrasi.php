<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use App\Models\User;
class Registrasi extends Component
{
    public $name, $email, $no_hp, $password, $password_confirmation;
    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'no_hp' => $this->no_hp,
            'password' => bcrypt($this->password),
        ]);

        $user->assignRole('User');

        auth()->login($user);
        $this->redirect(route('dashboard'), navigate: true);
    }
    public function render()
    {
        return view('livewire.actions.registrasi');
    }
}
