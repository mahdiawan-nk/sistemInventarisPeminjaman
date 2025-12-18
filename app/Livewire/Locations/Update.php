<?php

namespace App\Livewire\Locations;

use Livewire\Component;
use App\Models\Location;

class Update extends Component
{
    public $name, $code, $description;
    public $selectedId;

    public function mount($selectedId)
    {
        $this->selectedId = $selectedId;
        $getLocation = Location::where('id', $this->selectedId)->first();
        $this->name = $getLocation?->name;
        $this->code = $getLocation?->code;
        $this->description = $getLocation?->description;
    }

    public function update()
    {
        Location::where('id', $this->selectedId)->update([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
        ]);
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Location berhasil diupdate',
        ]);
        $this->dispatch('form-close-modal', id: 'formUpdateLocation');
        $this->dispatch('refreshPage');
    }
    public function render()
    {
        return view('livewire.locations.update');
    }
}
