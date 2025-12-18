@props([
    'id' => 'createForm',
    'title' => 'Form Data',
    'wireAction' => null,
    'width' => 'w-lg',
])

<div x-data="{ open: false }" x-on:form-open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
    x-on:form-close-modal.window="if ($event.detail.id === '{{ $id }}') open = false">

    {{-- Overlay --}}
    <div x-cloak x-show="open" x-transition.opacity.duration.200ms x-trap.inert.noscroll="open"
        x-on:keydown.esc.window="open = false" {{-- Prevent outside click closing --}} x-on:click.self.prevent
        class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4" role="dialog"
        aria-modal="true">

        {{-- Modal --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200 delay-100"
            x-transition:enter-start="opacity-0 scale-75" x-transition:enter-end="opacity-100 scale-100"
            class="{{ $width }} max-w-6xl rounded-xl border border-neutral-300 bg-white dark:border-neutral-600 dark:bg-gray-800 shadow-xl flex flex-col max-h-[90vh]">

            {{-- Header --}}
            <div
                class="flex items-center justify-between border-b border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-3">
                <h3 id="{{ $id }}Title" class="font-semibold text-neutral-900 dark:text-neutral-100">
                    {{ $title }}
                </h3>

                <button x-on:click="open = false"
                    class="text-neutral-500 hover:text-neutral-800 dark:text-neutral-300 dark:hover:text-white">
                    ✕
                </button>
            </div>

            {{-- Body --}}
            <div
                class="px-4 py-4 overflow-y-auto grow scrollbar-thin scrollbar-thumb-neutral-300 dark:scrollbar-thumb-neutral-700">
                {{ $slot }}
            </div>

            {{-- Footer slot --}}
            @if (isset($footer))
                <div
                    class="border-t border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 px-4 py-3 shrink-0">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
