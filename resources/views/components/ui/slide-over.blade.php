@props([
    'title' => 'Slide Over Title',
    'size' => 'lg', // sm, md, lg, xl
    'position' => 'right', // right or left
    'id' => 'slideOver',
])

<div x-data="{ slideOverOpen: false }" x-on:slide-open.window="if ($event.detail.id === '{{ $id }}') slideOverOpen = true"
    x-on:slide-close.window="if ($event.detail.id === '{{ $id }}') slideOverOpen = false"
    class="relative z-50 w-auto h-auto">

    <template x-teleport="body">
        <div x-show="slideOverOpen" @keydown.window.escape="slideOverOpen=false" class="relative z-[99]">
            <div x-show="slideOverOpen" x-transition.opacity.duration.600ms @click="slideOverOpen = false"
                class="fixed inset-0 bg-black/10 "></div>
            <div class="overflow-hidden fixed inset-0">
                <div class="overflow-hidden absolute inset-0">
                    <!-- Remove the pt-11 from the element below, this was needed only for the demo -->
                    <div class="flex fixed inset-y-0 right-0 pt-1 pl-10 max-w-full">
                        <div x-show="slideOverOpen" @click.away="slideOverOpen = false"
                            x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                            class="w-3xl max-w-full">
                            <div
                                class="flex overflow-y-scroll flex-col py-5 h-full bg-white border-l shadow-lg border-neutral-100/70 dark:bg-neutral-800">
                                <div class="px-4 sm:px-5">
                                    <div class="flex justify-between items-start pb-1">
                                        <h2 class="text-base font-semibold leading-6 text-gray-900 dark:text-neutral-100"
                                            id="slide-over-title">Slide Over Title</h2>
                                        <div class="flex items-center ml-3 h-auto">
                                            <button @click="slideOverOpen=false"
                                                class="flex absolute right-0 z-30 justify-center items-center px-3 py-2 mt-6 mr-5 space-x-1 text-xs font-medium uppercase rounded-md border border-neutral-200 text-neutral-600 hover:bg-neutral-100 dark:text-neutral-300 dark:border-neutral-600 dark:hover:bg-neutral-700">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span>Close</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative flex-1 px-4 mt-5 sm:px-5">
                                    <div class="absolute inset-0 px-4 sm:px-5">
                                        <div
                                            class="overflow-y-auto relative h-full">
                                            {{ $slot }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
