<div>
    @hasrole('User')
        <div
            class="relative mb-5 w-full rounded-lg border border-transparent bg-blue-600 p-4 [&>svg]:absolute [&>svg]:text-foreground [&>svg]:left-4 [&>svg]:top-4 [&>svg+div]:translate-y-[-3px] [&:has(svg)]:pl-11 text-white">
            <svg class="w-5 h-5 -translate-y-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <h5 class="mb-1 font-medium leading-none tracking-tight">Informasi </h5>
            <div class="text-sm opacity-80">harap diisi dengan benar, karena data peminjaman tidak dapat diubah setelah
                disimpan</div>
        </div>
    @endhasrole
    <form wire:submit.prevent="store">
        <div class="flex justify-end mb-4">
            <flux:button variant="primary" type="submit">Simpan</flux:button>

        </div>
        <div class="grid grid-cols-2 grid-rows-2 gap-4">
            <x-card>
                <x-card.header>
                    <x-card.title>Identitas Peminjam</x-card.title>
                </x-card.header>
                <x-card.content class="flex flex-col gap-4">
                    <flux:field>
                        <flux:label>Nama Peminjam</flux:label>
                        <flux:input wire:model="nama_peminjam" type="text" />
                        <flux:error name="email" />
                    </flux:field>
                    <flux:field>
                        <flux:label>NIM/NRP</flux:label>
                        <flux:input wire:model="code_peminjam" type="number" />
                        <flux:error name="code_peminjam" />
                    </flux:field>
                    <flux:field>
                        <flux:label>No HP</flux:label>
                        <flux:input wire:model="no_hp" type="text" />
                        <flux:error name="no_hp" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Unit </flux:label>
                        <flux:input wire:model="unit" type="text" placeholder="Mahasiswa/Unit Kerja" />
                        <flux:error name="unit" />
                    </flux:field>
                </x-card.content>
            </x-card>
            <x-card>
                <x-card.header>
                    <x-card.title>Info Peminjaman</x-card.title>
                </x-card.header>
                <x-card.content class="flex flex-col gap-4">
                    <flux:field>
                        <flux:label>Kode Peminjaman</flux:label>
                        <flux:input wire:model="code_data_peminjaman" type="text" readonly />
                        <flux:error name="email" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Keperluan</flux:label>
                        <flux:input wire:model="keperluan" type="text" />
                        <flux:error name="keperluan" />
                    </flux:field>
                    <div class="grid grid-cols-2 gap-2">
                        <flux:field>
                            <flux:label>Tanggal Peminjaman</flux:label>
                            <flux:input wire:model="tanggal_pinjam" type="date" readonly />
                            <flux:error name="tanggal_pinjam" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Jam Peminjaman</flux:label>
                            <flux:input wire:model="waktu_peminjaman" type="time" readonly />
                            <flux:error name="waktu_peminjaman" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Tanggal Pengembalian</flux:label>
                            <flux:input wire:model="tanggal_kembali" type="date" />
                            <flux:error name="tanggal_kembali" />
                        </flux:field>
                        <flux:field>
                            <flux:label>Jam Pengembalian</flux:label>
                            <flux:input wire:model="waktu_pengembalian" type="time" />
                            <flux:error name="waktu_pengembalian" />
                        </flux:field>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        @hasanyrole('Administrator|Staff IT')
                            <flux:field>
                                <flux:label>Status Peminjaman</flux:label>
                                <flux:radio.group wire:model="status_peminjaman" variant="segmented">
                                    <flux:radio label="Pinjam" value="pinjam" />
                                    <flux:radio label="Pengembalian" value="kembali" />
                                </flux:radio.group>
                                <flux:error name="status_peminjaman" />
                            </flux:field>

                            <flux:field>
                                <flux:label>Approval Peminjaman</flux:label>
                                <flux:radio.group wire:model="approval_peminjaman" variant="segmented">
                                    <flux:radio label="Proses" value="process" />
                                    <flux:radio label="Approved" value="approved" />
                                    <flux:radio label="Rejected" value="rejected" />
                                </flux:radio.group>
                                <flux:error name="approval_peminjaman" />
                            </flux:field>
                        @endhasanyrole
                    </div>

                </x-card.content>
            </x-card>
            <x-card class="col-span-2">
                <x-card.header>
                    <x-card.title>Item Peminjaman</x-card.title>
                </x-card.header>
                <x-card.content>
                    <div class="flex flex-col gap-3">
                        <div class="relative w-full max-w-md">
                            <!-- SEARCH INPUT -->
                            <div class="relative flex flex-col gap-1 text-neutral-600 dark:text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-neutral-600/50 dark:text-neutral-300/50"
                                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>

                                <input type="search" wire:model.live.debounce.500ms="searchItem"
                                    placeholder="Cari item (kode / nama / brand)"
                                    class="w-full rounded-sm border border-neutral-300 bg-neutral-50 py-2 pl-10 pr-3 text-sm
                   focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black
                   dark:border-neutral-700 dark:bg-neutral-900/50 dark:focus-visible:outline-white" />
                            </div>

                            <!-- RESULT LIST -->
                            @if ($searchItem)
                                <div
                                    class="absolute z-50 mt-1 w-full overflow-hidden rounded-md border border-neutral-200 
                bg-white shadow-lg dark:border-neutral-700 dark:bg-neutral-900">
                                    @forelse ($listItem as $list)
                                        <div
                                            class="flex items-center justify-between gap-3 px-3 py-2 
                   hover:bg-neutral-100 dark:hover:bg-neutral-800">

                                            <div class="flex flex-col text-sm">
                                                <span class="font-semibold text-neutral-800 dark:text-neutral-200">
                                                    {{ $list->asset_code }} · {{ $list->name }}
                                                </span>
                                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                                    Model: {{ $list->model }} · Brand: {{ $list->brand }} · Status:
                                                    @php
                                                        $statusColors = [
                                                            'available' => 'text-green-600 dark:text-green-400',
                                                            'borrowed' => 'text-blue-600 dark:text-blue-400',
                                                            'maintenance' => 'text-yellow-600 dark:text-yellow-400',
                                                            'damaged' => 'text-red-600 dark:text-red-400',
                                                            'lost' => 'text-neutral-500 dark:text-neutral-400',
                                                        ];
                                                    @endphp

                                                    <span
                                                        class="font-medium {{ $statusColors[$list->status] ?? 'text-neutral-600 dark:text-neutral-400' }}">
                                                        {{ ucfirst($list->status) }}
                                                    </span>
                                                </span>
                                            </div>

                                            <button wire:click="addItem({{ $list->id }})" type="button"
                                                class="rounded bg-blue-600 px-2 py-1 text-xs font-medium text-white 
                       hover:bg-blue-700">
                                                Add
                                            </button>
                                        </div>
                                    @empty
                                        <div
                                            class="px-3 py-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                                            Item tidak ditemukan
                                        </div>
                                    @endforelse
                                </div>
                            @endif

                        </div>


                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full">
                                <div
                                    class="overflow-hidden rounded-lg border border-neutral-200 dark:border-neutral-700">
                                    <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">

                                        <!-- HEADER -->
                                        <thead class="bg-neutral-50 dark:bg-neutral-800">
                                            <tr class="text-neutral-500 dark:text-neutral-400">

                                                <th class="px-5 py-3 text-xs font-medium uppercase text-left">Item</th>
                                                <th class="px-5 py-3 text-xs font-medium uppercase text-left">Lokasi
                                                </th>
                                                <th class="px-5 py-3 text-xs font-medium uppercase text-left">Status
                                                    Item
                                                </th>
                                            </tr>
                                        </thead>

                                        <!-- BODY -->
                                        <tbody
                                            class="divide-y divide-neutral-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900">

                                            @forelse ($itemPeminjaman as $item)
                                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800">
                                                    <td class="px-5 py-4 text-sm font-medium">
                                                        {{ $item['asset_code'] }} · {{ $item['name'] }}
                                                    </td>

                                                    <td class="px-5 py-4 text-sm">
                                                        {{ $item['location'] ?? '-' }}
                                                    </td>

                                                    <td class="px-5 py-4 text-sm flex items-center gap-3">
                                                        @php
                                                            $statusColors = [
                                                                'available' => 'text-green-600 dark:text-green-400',
                                                                'borrowed' => 'text-blue-600 dark:text-blue-400',
                                                                'maintenance' => 'text-yellow-600 dark:text-yellow-400',
                                                                'damaged' => 'text-red-600 dark:text-red-400',
                                                                'lost' => 'text-neutral-500 dark:text-neutral-400',
                                                            ];
                                                        @endphp

                                                        <span
                                                            class="font-medium {{ $statusColors[$item['status']] ?? '' }}">
                                                            {{ ucfirst($item['status']) }}
                                                        </span>

                                                        <button wire:click="removeItem({{ $item['id'] }})"
                                                            class="text-xs text-red-600 hover:underline">
                                                            Remove
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3"
                                                        class="px-5 py-6 text-center text-sm text-neutral-500">
                                                        Belum ada item dipilih
                                                    </td>
                                                </tr>
                                            @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </x-card.content>

            </x-card>
        </div>

    </form>
</div>
