<?php

namespace App\Livewire\Item;

use Livewire\Component;
use App\Models\Item;
use App\Models\Categori;
use App\Models\Location;
use App\Models\ItemStatusLog;
class Update extends Component
{
    public $category_id, $location_id, $asset_code, $name, $brand, $model, $serial_number, $purchase_date, $purchase_price, $status, $specification, $notes;
    public $selectedId;

    public function mount($selectedId)
    {
        $this->selectedId = $selectedId;
        $item = Item::find($selectedId);
        $this->category_id = $item?->category_id;
        $this->location_id = $item?->location_id;
        $this->asset_code = $item?->asset_code;
        $this->name = $item?->name;
        $this->brand = $item?->brand;
        $this->model = $item?->model;
        $this->serial_number = $item?->serial_number;
        $this->purchase_date = $item?->purchase_date;
        $this->purchase_price = $item?->purchase_price;
        $this->status = $item?->status;
        $this->specification = $item?->specifications;
        $this->notes = $item?->notes;
    }

    public function fillable()
    {
        return [
            [
                'required' => true,
                'name' => 'category_id',
                'label' => 'Category',
                'type' => 'select',
                'col_span' => 2,
                'options' => $this->toOptions(Categori::all()),
            ],
            [
                'required' => true,
                'name' => 'location_id',
                'label' => 'Location',
                'type' => 'select',
                'col_span' => 2,
                'options' => $this->toOptions(Location::all()),
            ],
            [
                'required' => true,
                'name' => 'asset_code',
                'label' => 'Asset Code',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'name' => 'brand',
                'label' => 'Brand',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'name' => 'model',
                'label' => 'Model',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'name' => 'serial_number',
                'label' => 'Serial Number',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => false,
                'name' => 'purchase_date',
                'label' => 'Purchase Date',
                'type' => 'date',
                'col_span' => 1,
            ],
            [
                'required' => false,
                'name' => 'purchase_price',
                'label' => 'Purchase Price',
                'type' => 'number',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'name' => 'status',
                'label' => 'Status',
                'type' => 'select',
                'col_span' => 1,
                'options' => [
                    ['value' => 'available', 'label' => 'Available'],
                    ['value' => 'borrowed', 'label' => 'Borrowed'],
                    ['value' => 'maintenance', 'label' => 'Maintainence'],
                    ['value' => 'damaged', 'label' => 'Damaged'],
                    ['value' => 'lost', 'label' => 'Lost'],
                ]
            ],
            [
                'required' => true,
                'name' => 'specification',
                'label' => 'Specification',
                'type' => 'textarea',
                'col_span' => 4, // Lebar penuh
            ],
            [
                'required' => true,
                'name' => 'notes',
                'label' => 'Notes',
                'type' => 'textarea',
                'col_span' => 4,
            ],
        ];
    }


    public function toOptions($collection, $valueField = 'id', $labelField = 'name')
    {
        return $collection->map(function ($item) use ($valueField, $labelField) {
            return [
                'value' => $item->{$valueField},
                'label' => $item->{$labelField},
            ];
        })->values()->toArray();
    }

    public function update()
    {
        $this->validate(
            [
                'notes' => 'required'
            ],
            [
                'notes.required' => 'Notes harus diisi'
            ]
        );
        $getItem = Item::find($this->selectedId);
        $oldStatus = $getItem->status;
        $getItem->update([
            'category_id' => $this->category_id,
            'location_id' => $this->location_id,
            'asset_code' => $this->asset_code,
            'name' => $this->name,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'purchase_date' => $this->purchase_date,
            'purchase_price' => $this->purchase_price,
            'status' => $this->status,
            'specifications' => $this->specification,
            'notes' => $this->notes,
            'updated_by' => auth()->user()->id
        ]);

        $this->logStatus($oldStatus, $this->status, $getItem->id);
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Item berhasil diupdate',
        ]);
        $this->reset();
        $this->dispatch('slide-close', id: 'formUpdateItem');
        $this->dispatch('refreshPage');
    }

    public function logStatus($oldStatus, $newStatus, $id)
    {
        ItemStatusLog::create([
            'item_id' => $id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => auth()->user()->id,
            'notes' => $this->notes,       // atau pakai field khusus note log
        ]);
    }
    public function render()
    {
        return view('livewire.item.update');
    }
}
