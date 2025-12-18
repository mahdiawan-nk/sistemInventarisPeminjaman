<div>
    <x-form wire:submit.prevent="store" class="space-y-4">
        <x-form.input wire:model="name" label="Nama Pengguna" placeholder="shadcn" />
        <x-form.input wire:model="email" label="Email Pengguna" placeholder="shadcn" />
        <x-form.item>
            <x-form.label>Role Pengguna</x-form.label>
            <x-select class="w-fill" x-form:control wire:model="role" >
                {{-- <option value="" selected disabled>
                    Pilih Role pengguna
                </option> --}}
                @foreach ($this->getUserRoleProperty() as $role)
                    <option value="{{ $role->id }}" class="dark:text-white dark:bg-slate-800">{{ $role->name }}</option>
                @endforeach
            </x-select>
            <x-form.message name="role" />
        </x-form.item>
        <x-form.input wire:model="password" label="Password" placeholder="shadcn" />
        <x-button type="submit" variant="secondary" class="dark:bg-white dark:text-neutral-900">Submit</x-button>
    </x-form>
</div>
