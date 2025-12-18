@props([
    'label' => 'Add New',
    'url' => null, // Optional: URL mode
    'click' => null, // Optional: wire:click or x-on:click mode
    'wire' => null,
])


<div class="flex items-center justify-between mb-4 gap-3">
    {{-- Search Input --}}
    <div class="flex items-center gap-2 w-full max-w-xs">
        <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search..."
            class="w-full border rounded-lg px-3 py-2 text-sm" />
    </div>

    <div>
        {{ $slot }}
    </div>
    {{-- Add Button (Supports: URL or wire:click / x-on:click) --}}
    @hasanyrole('Administrator|Staff IT')
        @if ($wire || $url || $click)
            <div>
                @if ($url)
                    <a href="{{ $url }}" wire:navigate
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        {{ $label }}
                    </a>
                @elseif ($wire)
                    <flux:button variant="primary" wire:click="{{ $wire }}">{{ $label }}</flux:button>
                @else
                    <flux:button variant="primary" x-on:click="{{ $click }}">{{ $label }}</flux:button>
                @endif
            </div>
        @endif
    @endhasanyrole
</div>
