@props([
    'id' => null, // item id (optional)
    'editUrl' => null, // url string → /users/1/edit
    'editClick' => null, // "wire:click='edit(1)'" atau "x-on:click='openEdit(1)'"
    'deleteClick' => null, // "wire:click='delete(1)'" atau Alpine,
    'size' => 'xs',
    'editMethod' => 'edit',
    'deleteMethod' => 'delete',
])


<div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-28">
    {{-- Edit Button: URL Mode OR Action Mode --}}
    @if ($editUrl)
        <a href="{{ $editUrl }}" class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
            Edit
        </a>
    @elseif ($editMethod)
        <flux:button variant="primary" color="blue" size="{{ $size }}"
            wire:click="{{ $editMethod }}({{ $id }})">
            Edit</flux:button>
    @else
        <flux:button variant="primary" color="blue" size="{{ $size }}" wire:click="edit({{ $id }})">
            Edit
        </flux:button>
    @endif

    @if ($deleteMethod)
        <flux:button variant="danger" size="{{ $size }}" wire:click="{{ $deleteMethod }}({{ $id }})">
            Hapus
        </flux:button>
    @else
        <flux:button variant="danger" size="{{ $size }}" wire:click="delete({{ $id }})">Hapus
        </flux:button>
    @endif
    <div class="col-span-full">
        {{ $slot }}

    </div>

</div>
