<div>
    <x-ui.header-table label="Tambah Lokasi" wire="openModalCreate" />
    <div class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
            <thead
                class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                <tr>
                    <th scope="col" class="p-4">No</th>
                    <th scope="col" class="p-4">Nama Lokasi</th>
                    <th scope="col" class="p-4">Kode Lokasi</th>
                    <th scope="col" class="p-4">Keterangan</th>
                    <th scope="col" class="p-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                @forelse ($data as $index=>$item)
                    <tr class="even:bg-black/5 dark:even:bg-white/10">
                        <td class="p-4">{{ $data->firstItem() + $index }}</td>
                        <td class="p-4">{{ $item->name }}</td>
                        <td class="p-4">{{ $item->code }}</td>
                        <td class="p-4">{{ $item->description }}</td>
                        <td class="p-4">
                            <x-ui.table-action :id="$item->id" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6">
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
                                <flux:button variant="primary" wire:click="openModalCreate">Tambah Data</flux:button>
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
    <x-ui.modal id="formCreateLocation" title="Tambah Data Location">
        <livewire:locations.create wire:key="formCreateLocation" />
    </x-ui.modal>
    <x-ui.modal id="formUpdateLocation" title="Edit Data Location">
        <livewire:locations.update wire:key="formUpdateLocation-{{ $selectedId }}" :selectedId="$selectedId" />
    </x-ui.modal>
    <x-ui.confirm-modal id="deleteLocation" title="Hapus Data Location"
        message="Apakah kamu yakin ingin menghapus Data ini? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus" cancelText="Batal" wireAction="confirmDelete" />
</div>
