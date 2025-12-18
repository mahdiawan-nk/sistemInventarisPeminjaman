@props([
    'title' => '',
    'value' => '',
    'color' => 'blue',
    'subtitle' => null,
])

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-100 dark:bg-blue-800', 'text' => 'text-blue-600 dark:text-blue-200'],
        'green' => ['bg' => 'bg-green-100 dark:bg-green-800', 'text' => 'text-green-600 dark:text-green-200'],
        'red' => ['bg' => 'bg-red-100 dark:bg-red-800', 'text' => 'text-red-600 dark:text-red-200'],
        'yellow' => ['bg' => 'bg-yellow-100 dark:bg-yellow-800', 'text' => 'text-yellow-600 dark:text-yellow-200'],
        'purple' => ['bg' => 'bg-purple-100 dark:bg-purple-800', 'text' => 'text-purple-600 dark:text-purple-200'],
        'orange' => ['bg' => 'bg-orange-100 dark:bg-orange-800', 'text' => 'text-orange-600 dark:text-orange-200'],
    ];
    $colorClasses = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white dark:bg-neutral-900 shadow rounded-xl p-5 flex items-center justify-between">
    <div>
        <h3 class="text-sm font-medium text-neutral-500 dark:text-neutral-300">{{ $title }}</h3>
        <p class="mt-1 text-2xl font-bold text-neutral-900 dark:text-white">{{ $value }}</p>
        @if ($subtitle)
            <p class="text-xs mt-1 {{ $colorClasses['text'] }}">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="p-3 rounded-full {{ $colorClasses['bg'] }}">
        {{-- Slot icon: bisa SVG inline atau Blade component --}}
        {{ $slot }}
    </div>
</div>
