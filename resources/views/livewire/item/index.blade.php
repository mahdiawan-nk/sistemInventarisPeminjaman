<div>
    <x-ui.header-table label="Tambah Item" url="{{ route('items.create') }}">
        <div class="flex gap-4">
            <flux:field>
                {{-- <flux:label>Category</flux:label> --}}
                <flux:select wire:model.live="filter.category_id" placeholder="Select a category...">
                    @foreach ($this->getCategoriesProperty() as $categori)
                        <flux:select.option value="{{ $categori->id }}">{{ $categori->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>

            <flux:field>
                {{-- <flux:label>Location</flux:label> --}}
                <flux:select wire:model="filter.location_id" placeholder="Select a location...">
                    @foreach ($this->getLocationsProperty() as $location)
                        <flux:select.option value="{{ $location->id }}">{{ $location->name }}</flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            @hasanyrole('Administrator|Staff IT')
                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down">Filter</flux:button>
                    <flux:menu>
                        <flux:menu.radio.group wire:model.live="filter.trash">
                            <flux:menu.radio checked value="notTrashed">Not Trashed</flux:menu.radio>
                            <flux:menu.radio value="onlyTrashed">Only Trashed</flux:menu.radio>
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>
            @endhasanyrole
        </div>

    </x-ui.header-table>
    <div class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
            <thead
                class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                <tr>
                    <th class="p-4">No</th>

                    {{-- Group 1: Informasi Item --}}
                    <th class="p-4">Item</th>

                    {{-- Group 2: Lokasi --}}
                    <th class="p-4">Lokasi</th>

                    {{-- Group 3: Pembelian --}}
                    <th class="p-4">Pembelian</th>

                    {{-- Group 4: Status --}}
                    <th class="p-4">Status</th>

                    {{-- Group 5: Action --}}
                    @hasanyrole('Administrator|Staff IT')
                        <th class="p-4">Action</th>
                    @endhasanyrole
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                @forelse ($data as $index=>$item)
                    <tr class="even:bg-neutral-100/40 dark:even:bg-neutral-800/50">

                        {{-- Nomor --}}
                        <td class="p-4">{{ $data->firstItem() + $index }}</td>

                        {{-- ITEM INFO --}}
                        <td class="p-4 space-y-1">
                            <div class="font-semibold text-neutral-800 dark:text-neutral-200">
                                {{ $item->name }}
                            </div>
                            <div class="text-sm text-neutral-500 dark:text-neutral-400">
                                <div>Kategori: {{ $item->category->name }}</div>
                                <div>Brand: {{ $item->brand }}</div>
                                <div>Model: {{ $item->model }}</div>
                                <div>Serial: {{ $item->serial_number }}</div>
                            </div>
                        </td>

                        {{-- LOKASI --}}
                        <td class="p-4 text-sm text-neutral-600 dark:text-neutral-400">
                            <div class="font-medium text-neutral-800 dark:text-neutral-200">
                                {{ $item->location->name }}
                            </div>
                            <div>Kode: {{ $item->location->code }}</div>
                        </td>

                        {{-- PEMBELIAN --}}
                        <td class="p-4 text-sm text-neutral-600 dark:text-neutral-400">
                            <div>Tanggal: {{ $item->purchase_date }}</div>
                            <div>Harga: Rp{{ number_format($item->purchase_price) }}</div>
                            <div>By: {{ $item->creator->name }}</div>
                            <div>{{ $item->created_at->format('d M Y') }}</div>
                        </td>

                        {{-- STATUS --}}
                        <td class="p-4">
                            <span
                                class="px-2 py-1 rounded text-xs font-medium
                {{ $item->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        {{-- ACTION --}}
                        @hasanyrole('Administrator|Staff IT')
                            <td class="p-4">
                                @if ($filter['trash'] === 'onlyTrashed')
                                    <flux:button variant="primary" color="blue" size="xs"
                                        wire:click="restoreData({{ $item->id }})">Restore</flux:button>
                                    {{-- <flux:button variant="primary" color="red" size="xs"
                                    wire:click="forceDelete({{ $item->id }})">Permanent Delete</flux:button> --}}
                                @else
                                    <x-ui.table-action :id="$item->id" edit-method="openEdit" deleteMethod="openDelete" />
                                @endif
                            </td>
                        @endhasanyrole

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6">
                            <div class="flex flex-col items-center justify-center py-10">
                                <!-- Icon -->
                                <div class="mb-4">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 13h6m-3-3v6m9 1V7a2 2 0 00-2-2h-3.34a2 2 0 01-1.42-.59l-1.32-1.32A2 2 0 0011.6 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2z" />
                                    </svg>
                                </div>

                                <!-- Text -->
                                <p class="text-gray-600 text-lg font-medium mb-2">
                                    Tidak ada data {{ $search ? 'yang cocok ' : '' }}
                                </p>
                                <p class="text-gray-400 text-sm mb-6">
                                    Mulai tambah data baru untuk mengisi daftar ini.
                                </p>
                                @hasanyrole('Administrator|Staff IT')
                                    <flux:button :href="route('items.create')" wire:navigate variant="primary">Tambah Data
                                    </flux:button>
                                @endhasanyrole

                                <!-- Button -->
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
    <div class="pt-3">
        {{ $data->onEachSide(0)->links(data: ['scrollTo' => false]) }}
    </div>

    <x-ui.slide-over title="Edit Data Item" id="formUpdateItem">
        <livewire:item.update wire:key="formUpdateItem-{{ $selectedId }}" :selectedId="$selectedId" />.
    </x-ui.slide-over>
    <x-ui.slide-over id="formCreateItem" title="Tambah Data Item" size="lg">
        <livewire:item.create wire:key="formCreateItem" />
    </x-ui.slide-over>
    <x-ui.confirm-modal id="deleteItem" title="Hapus Data Item"
        message="Apakah kamu yakin ingin menghapus Data ini? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus" cancelText="Batal" wireAction="confirmDelete" />

    <x-ui.confirm-modal id="restoreItem" title="Restore Data Item"
        message="Apakah Kamu yakin ingin restore data ini? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Restore" cancelText="Batal" wireAction="restoreData('{{ $selectedId }}',false)" />
    <x-ui.confirm-modal id="forceDeleteItem" title="Permanent Delete Data Item"
        message="Apakah Kamu yakin ingin permanent delete data ini? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Permanent Delete" cancelText="Batal" wireAction="forceDelete('{{ $selectedId }}',false)" />
</div>
