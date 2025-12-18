<div>

    <x-ui.header-table label="Tambah Peminjaman" url="{{ route('peminjaman.create') }}">
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
    </x-ui.header-table>
    <div
        class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-900">
        <table class="w-full text-left text-sm text-neutral-700 dark:text-neutral-300">
            <thead
                class="border-b border-neutral-200 bg-neutral-100/70 text-xs uppercase tracking-wide text-neutral-700
                   dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-300">
                <tr>
                    <th class="px-4 py-3 w-12">No</th>
                    <th class="px-4 py-3">Kode Peminjaman</th>
                    <th class="px-4 py-3">Detail Peminjam</th>
                    <th class="px-4 py-3">Item Dipinjam</th>
                    <th class="px-4 py-3">Keperluan</th>
                    <th class="px-4 py-3">Pinjam</th>
                    <th class="px-4 py-3">Rencana Kembali</th>
                    <th class="px-4 py-3">Aktual Kembali</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Approval</th>
                    <th class="px-4 py-3">Dibuat Oleh</th>
                    @hasanyrole('Administrator|Staff IT')
                        <th class="px-4 py-3 text-center w-20">Aksi</th>
                    @endhasanyrole
                </tr>
            </thead>

            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                @forelse ($data as $index => $item)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors">
                        <td class="px-4 py-3 text-neutral-500">
                            {{ $data->firstItem() + $index }}
                        </td>

                        <td class="px-4 py-3 font-medium text-neutral-900 dark:text-white">
                            {{ $item->code_data_pinjaman }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="space-y-1">
                                <div class="font-semibold text-neutral-900 dark:text-white">
                                    {{ $item->nama_peminjam ?? '-' }}
                                </div>

                                <div class="flex flex-col gap-x-4 gap-y-1 text-xs text-neutral-500">
                                    <span>
                                        <span class="font-medium">NIM/NRP:</span>
                                        {{ $item->code_peminjam ?? '-' }}
                                    </span>

                                    <span>
                                        <span class="font-medium">No. HP:</span>
                                        {{ $item->no_hp ?? '-' }}
                                    </span>

                                    <span>
                                        <span class="font-medium">Unit:</span>
                                        {{ $item->unit ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </td>


                        <td class="px-4 py-3">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($item->peminjamanItems ?? [] as $unit)
                                    <li>{{ $unit->item->name }}</li>
                                @endforeach
                            </ul>
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->keperluan }}
                        </td>

                        <td class="px-4 py-3 text-xs">
                            {{ $item->tanggal_pinjam }} {{ $item->waktu_peminjaman }}
                        </td>

                        <td class="px-4 py-3 text-xs">
                            {{ $item->tanggal_kembali }} {{ $item->waktu_pengembalian }}
                        </td>

                        <td class="px-4 py-3 text-xs">
                            {{ $item->aktiual_tanggal_kembali ?? '-' }} {{ $item->aktual_waktu_pengembalian }}
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' =>
                                    $item->status_peminjaman === 'pinjam',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' =>
                                    $item->status_peminjaman === 'kembali',
                            ])>
                                {{ ucfirst($item->status_peminjaman) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-center">
                            <span @class([
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' =>
                                    $item->approval_peminjaman === 'process',
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' =>
                                    $item->approval_peminjaman === 'approved',
                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' =>
                                    $item->approval_peminjaman === 'rejected',
                            ])>
                                {{ ucfirst($item->approval_peminjaman) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $item->createdUser->name ?? '-' }}
                        </td>
                        @hasanyrole('Administrator|Staff IT')
                            <td class="px-4 py-3 text-center">

                                @if ($filter['trash'] === 'onlyTrashed')
                                    <flux:button variant="primary" color="blue" size="xs"
                                        wire:click="openRestore({{ $item->id }})">Restore</flux:button>
                                @else
                                    <x-ui.table-action :id="$item->id" deleteMethod="openDelete"
                                        editUrl="{{ route('peminjaman.edit', ['peminjaman' => $item->id]) }}">
                                        @if ($item->status_peminjaman == 'pinjam' && $item->approval_peminjaman == 'approved')
                                            <flux:button type="button" variant="primary" color="indigo"
                                                wire:click="openPengembalian({{ $item->id }})" size="xs">
                                                Pengembalian
                                            </flux:button>
                                        @endif
                                        @if ($item->approval_peminjaman == 'process')
                                            <flux:button type="button" variant="primary" color="lime"
                                                wire:click="openApproval({{ $item->id }})" size="xs"
                                                class="w-full">
                                                Approval
                                            </flux:button>
                                        @endif

                                    </x-ui.table-action>
                                @endif
                            </td>
                        @endhasanyrole
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="p-10">
                            <div class="flex flex-col items-center text-center">
                                <div
                                    class="flex h-14 w-14 items-center justify-center rounded-full bg-neutral-100 dark:bg-neutral-800 mb-4">
                                    <svg class="h-7 w-7 text-neutral-400" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 13h6m-3-3v6m9 1V7a2 2 0 00-2-2h-3.34a2 2 0 01-1.42-.59l-1.32-1.32A2 2 0 0011.6 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2z" />
                                    </svg>
                                </div>

                                <h3 class="text-sm font-medium text-neutral-900 dark:text-white">
                                    Data tidak ditemukan / Kosong
                                </h3>
                                <p class="mt-1 text-sm text-neutral-500">
                                    {{ $search ? 'Tidak ada data yang cocok dengan pencarian.' : 'Belum ada data peminjaman.' }}
                                </p>

                                <flux:button variant="primary" size="sm" class="mt-5"
                                    :href="route('peminjaman.create')">
                                    Tambah Peminjaman
                                </flux:button>
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
    <x-ui.modal id="pengembalianPeminjaman" title="Pengembalian Peminjaman">
        <form wire:submit.prevent="storePengembalian" class="flex flex-col gap-3">
            <div class="grid grid-cols-2 gap-3">
                <flux:field>
                    <flux:label>Tanggal Aktual Pengembalian</flux:label>
                    <flux:input wire:model="tanggal_pengembalian" type="date" readonly />
                    <flux:error name="tanggal_pengembalian" />
                </flux:field>
                <flux:field>
                    <flux:label>Jam Aktual Pengembalian</flux:label>
                    <flux:input wire:model="jam_pengembalian" type="time" readonly />
                    <flux:error name="jam_pengembalian" />
                </flux:field>
            </div>
            <flux:button type="submit" variant="primary">Simpan</flux:button>
        </form>
    </x-ui.modal>
    <x-ui.modal id="approvalPeminjaman" title="Approval Peminjaman">
        <form wire:submit.prevent="storeApproval" class="flex flex-col gap-3">
            <div class="grid grid-cols-1 gap-3">
                <flux:field>
                    <flux:label>Approval Peminjaman</flux:label>
                    <flux:radio.group wire:model="approval_peminjaman" variant="segmented">
                        <flux:radio label="Proses" value="process" />
                        <flux:radio label="Approved" value="approved" />
                        <flux:radio label="Rejected" value="rejected" />
                    </flux:radio.group>
                    <flux:error name="approval_peminjaman" />
                </flux:field>
            </div>
            <flux:button type="submit" variant="primary">Simpan</flux:button>
        </form>
    </x-ui.modal>
    <x-ui.confirm-modal id="deletePeminjaman" title="Hapus Data Peminjaman"
        message="Apakah kamu yakin ingin menghapus Data ini? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus" cancelText="Batal" wireAction="confirmDelete" />
    <x-ui.confirm-modal id="restorePeminjaman" title="Restore Data Peminjaman"
        message="Apakah Kamu yakin ingin restore data ini? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Restore" cancelText="Batal" wireAction="restore" />
</div>
