<div>
    <x-form wire:submit.prevent="update" class="space-y-4">
        <x-form.input wire:model="name" label="Nama Kategori" placeholder="Contoh: Router Board" />
        <div class="grid w-full gap-1.5">
            <x-label htmlFor="message">Description</x-label>
            <x-textarea placeholder="Type your message here." id="message" wire:model="description" />
        </div>
        <x-button type="submit" variant="secondary" class="dark:bg-white dark:text-neutral-900">Submit</x-button>
    </x-form>
</div>
