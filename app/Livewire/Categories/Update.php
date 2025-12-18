<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use App\Models\Categori;

class Update extends Component
{
    public $name,$description;
    public $selectedId;

    public function mount($selectedId){
        $this->selectedId = $selectedId;
        $getCategori = Categori::where('id', $this->selectedId)->first();
        $this->name = $getCategori?->name;
        $this->description = $getCategori?->description;
    }

    public function update(){
        Categori::where('id', $this->selectedId)->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Categori berhasil diupdate',
        ]);
        $this->dispatch('form-close-modal', id: 'formUpdateCategori');
        $this->dispatch('refreshPage');
    }
    public function render()
    {
        return view('livewire.categories.update');
    }
}
