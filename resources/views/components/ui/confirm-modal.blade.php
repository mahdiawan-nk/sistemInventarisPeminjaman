@props([
    'id' => 'confirmDelete',
    'title' => 'Konfirmasi Hapus',
    'message' => 'Apakah Anda yakin ingin menghapus data ini?',
    'confirmText' => 'Hapus',
    'cancelText' => 'Batal',
    'wireAction' => null, // contoh: delete(1)
])

<div x-data="{ open: false }" x-on:open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
    x-on:close-modal.window="if ($event.detail.id === '{{ $id }}') open = false">
    <div x-cloak x-show="open" x-transition.opacity.duration.200ms x-trap.inert.noscroll="open"
        x-on:keydown.esc.window="open = false" x-on:click.self="open = false"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/40 p-4 pb-8 backdrop-blur-sm sm:items-center lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="{{ $id }}Title">
        <!-- Modal -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200 delay-100"
            x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
            class="w-full max-w-md rounded-xl border border-red-300 bg-white shadow-xl dark:border-red-600 dark:bg-gray-800">
            <!-- Header -->
            <div
                class="flex items-center justify-between border-b border-red-200 bg-red-50 px-4 py-2 dark:border-red-700 dark:bg-red-900/40">
                <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 14h-2v-2h2Zm0-4h-2V6h2Z" />
                    </svg>
                    <h3 id="{{ $id }}Title" class="font-semibold">{{ $title }}</h3>
                </div>
                <button x-on:click="open = false" class="text-gray-600 hover:text-black dark:text-gray-300">✕</button>
            </div>

            <!-- Body -->
            <div class="px-4 py-4 text-center text-gray-700 dark:text-gray-200">
                {{ $message }}
            </div>

            <!-- Footer -->
            <div class="flex items-center justify-end gap-3 border-t px-4 py-3 dark:border-gray-700">
                <button x-on:click="open = false"
                    class="rounded-md border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    {{ $cancelText }}
                </button>

                <button
                    @if ($wireAction) wire:click="{{ $wireAction }}" 
        wire:loading.attr="disabled" @endif
                    class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 flex items-center gap-2">

                    {{-- Spinner ketika loading --}}
                    @if ($wireAction)
                        <span wire:loading wire:target="{{ $wireAction }}"
                            class="inline-block animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
                    @endif

                    {{-- Text berubah saat loading --}}
                    <span wire:loading.remove wire:target="{{ $wireAction }}">
                        {{ $confirmText }}
                    </span>

                    <span wire:loading wire:target="{{ $wireAction }}">
                        Loading...
                    </span>
                </button>

            </div>
        </div>
    </div>
</div>
