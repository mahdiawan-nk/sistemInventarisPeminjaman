<?php

namespace App\Livewire\Item;

use Livewire\Component;

use App\Models\Item;
use App\Models\Categori;
use App\Models\Location;
use Carbon\Carbon;
class Create extends Component
{
    public $category_id, $location_id, $asset_code, $name, $brand, $model, $serial_number, $purchase_date, $purchase_price, $status, $specification, $notes;

    public function mount()
    {
        $this->reset();
    }

    protected function makeAcronym(string $text): string
    {
        // Ambil huruf awal tiap kata
        $words = preg_split('/\s+/', trim($text));

        $acronym = collect($words)
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');

        // Jika cuma 1 kata → ambil 3 huruf pertama
        return strlen($acronym) >= 2
            ? $acronym
            : strtoupper(substr($text, 0, 3));
    }


    protected function generateAssetCode()
    {
        if (!$this->category_id || !$this->location_id) {
            return null;
        }

        $category = Categori::find($this->category_id);
        $location = Location::find($this->location_id);

        if (!$category || !$location) {
            return null;
        }

        $year = now()->format('Y');

        $prefix = 'ICT';

        $catCode = $this->makeAcronym($category->name); // 🔥 dari name
        $locCode = strtoupper($location->code);         // location sudah punya code

        $lastItem = Item::where('category_id', $this->category_id)
            ->where('location_id', $this->location_id)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $number = 1;

        if ($lastItem && $lastItem->asset_code) {
            $lastNumber = (int) substr($lastItem->asset_code, -4);
            $number = $lastNumber + 1;
        }
        // dump($catCode, $locCode, $year, $number);
        return sprintf(
            'ICT-%s-%s-%s-%04d',
            $catCode,
            $locCode,
            $year,
            $number
        );
    }

    public function updatedCategoryId()
    {
        $this->asset_code = $this->generateAssetCode();
    }

    public function updatedLocationId()
    {
        $this->asset_code = $this->generateAssetCode();
    }

    public function fillable()
    {
        return [
            [
                'required' => true,
                'readonly' => false,
                'name' => 'category_id',
                'label' => 'Category',
                'type' => 'select',
                'col_span' => 2,
                'options' => $this->toOptions(Categori::all()),
            ],
            [
                'required' => true,
                'readonly' => false,
                'name' => 'location_id',
                'label' => 'Location',
                'type' => 'select',
                'col_span' => 2,
                'options' => $this->toOptions(Location::all()),
            ],
            [
                'required' => true,
                'readonly' => true,
                'name' => 'asset_code',
                'label' => 'Asset Code',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'readonly' => false,
                'name' => 'name',
                'label' => 'Name',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'readonly' => false,
                'name' => 'brand',
                'label' => 'Brand',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'readonly' => false,
                'name' => 'model',
                'label' => 'Model',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'readonly' => false,
                'name' => 'serial_number',
                'label' => 'Serial Number',
                'type' => 'text',
                'col_span' => 1,
            ],
            [
                'required' => false,
                'readonly' => false,
                'name' => 'purchase_date',
                'label' => 'Purchase Date',
                'type' => 'date',
                'col_span' => 1,
            ],
            [
                'required' => false,
                'readonly' => false,
                'name' => 'purchase_price',
                'label' => 'Purchase Price',
                'type' => 'number',
                'col_span' => 1,
            ],
            [
                'required' => true,
                'readonly' => false,
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
                'readonly' => false,
                'name' => 'specification',
                'label' => 'Specification',
                'type' => 'textarea',
                'col_span' => 4, // Lebar penuh
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


    public function store()
    {
        $this->validate([
            'category_id' => 'required',
            'location_id' => 'required',
            'asset_code' => 'required',
            'name' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
            'status' => 'required',
            'specification' => 'required',
        ]);
        Item::create([
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
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ]);
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Berhasil',
            'message' => 'Item berhasil ditambahkan',
        ]);
        $this->reset();
        $this->dispatch('slide-close', id: 'formCreateItem');
        $this->dispatch('refreshPage');
    }
    public function render()
    {
        return view('livewire.item.create');
    }
}
