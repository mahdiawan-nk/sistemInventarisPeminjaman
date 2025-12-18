<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Categori;
use Illuminate\Validation\Rule;
class Create extends Component
{
    public $name,$description;
    public function store()
    {
        // Validasi lebih baik dan aman
        $validated = $this->validate(
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'Nama harus diisi',
            ]
        );

       
        Categori::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Categori berhasil ditambahkan',
        ]);
        $this->dispatch('form-close-modal', id: 'formCreateCategori');
        $this->dispatch('refreshPage');
    }  
    public function render()
    {
        return view('livewire.categories.create');
    }
}
