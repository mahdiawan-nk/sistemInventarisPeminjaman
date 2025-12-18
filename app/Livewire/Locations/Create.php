<?php

namespace App\Livewire\Locations;
use App\Models\Location;
use Livewire\Component;

class Create extends Component
{
    public $name, $code, $description;

    public function store()
    {
        // Validasi lebih baik dan aman
        $validated = $this->validate(
            [
                'name' => 'required',
                'code' => 'required',
            ],
            [
                'name.required' => 'Nama harus diisi',
                'code.required' => 'Kode harus diisi',
            ]
        );
        Location::create([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
        ]);
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Location berhasil ditambahkan',
        ]);
        $this->dispatch('form-close-modal', id: 'formCreateLocation');
        $this->dispatch('refreshPage');
    }
    public function render()
    {
        return view('livewire.locations.create');
    }
}
