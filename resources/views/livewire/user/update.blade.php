<div>
    <x-form wire:submit.prevent="update" class="space-y-4">
        <x-form.input wire:model="name" label="Nama Pengguna" placeholder="shadcn" />
        <x-form.input wire:model="email" label="Email Pengguna" placeholder="shadcn" />
        <x-form.item>
            <x-form.label>Role Pengguna</x-form.label>
            <x-select class="w-fill" x-form:control wire:model="role">
                <option>
                    Pilih Role pengguna
                </option>
                @foreach ($this->getUserRoleProperty() as $role)
                    <option value="{{ $role->id }}" class="dark:text-white dark:bg-slate-800">{{ $role->name }}
                    </option>
                @endforeach
            </x-select>
            <x-form.message name="role" />
        </x-form.item>
        <div class="flex items-center space-x-2">
            <x-checkbox id="terms2" wire:model="editPassword" />
            <x-label htmlFor="terms2"
                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                Edit Password
            </x-label>
        </div>
        <x-form.item wire:show="editPassword">
            <x-form.input wire:model="password" label="Password" placeholder="shadcn" />
        </x-form.item>
        <x-button type="submit" variant="secondary" class="dark:bg-white dark:text-neutral-900">Submit</x-button>
    </x-form>
</div>
