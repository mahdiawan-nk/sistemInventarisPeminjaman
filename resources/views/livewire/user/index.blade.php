<div class="grid grid-cols-12">
    <div class="col-span-12">
        <x-ui.header-table label="Tambah Pengguna" wire="openModalCreate" />
        <div
            class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                <thead
                    class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                    <tr>
                        <th scope="col" class="p-4">No</th>
                        <th scope="col" class="p-4">Nama Pengguna</th>
                        <th scope="col" class="p-4">Email</th>
                        <th scope="col" class="p-4">Role</th>
                        <th scope="col" class="p-4">Terdaftar sejak</th>
                        <th scope="col" class="p-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                    @forelse ($data as $index=>$item)
                        <tr class="even:bg-black/5 dark:even:bg-white/10">
                            <td class="p-4">{{ $data->firstItem() + $index }}</td>
                            <td class="p-4">{{ $item->name }}</td>
                            <td class="p-4">{{ $item->email }}</td>
                            <td class="p-4">{{ $item->roles->pluck('name')->implode(', ') }}</td>
                            <td class="p-4">{{ $item->created_at }}</td>
                            <td class="p-4">
                                <x-ui.table-action :id="$item->id" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center">Tidak ada data</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        <div class="pt-3">
            {{ $data->onEachSide(0)->links(data: ['scrollTo' => false]) }}
        </div>
    </div>

    <x-ui.modal id="formCreateUser" title="Tambah Data User">
        <livewire:user.create wire:key="formCreateUser" />
    </x-ui.modal>
    <x-ui.modal id="formUpdateUser" title="Edit Data User">
        <livewire:user.update wire:key="formUpdateUser-{{ $selectedId }}" :selectedId="$selectedId" />
    </x-ui.modal>
    <x-ui.confirm-modal id="deleteUser" title="Hapus Data User"
        message="Apakah kamu yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan."
        confirmText="Ya, Hapus" cancelText="Batal" wireAction="confirmDelete" />
</div>
