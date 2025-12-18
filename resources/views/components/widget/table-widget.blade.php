@props([
    'title' => 'Overview Data', // Judul card / table
    'headers' => [], // array untuk judul kolom
    'striped' => true, // false jika tidak ingin row bergantian warna
])

<div class="bg-white dark:bg-neutral-900 shadow rounded-xl overflow-hidden">
    {{-- Card Header --}}
    @if ($title)
        <div class="px-5 py-3 border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800">
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $title }}</h3>
        </div>
    @endif

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
            {{-- Table Header --}}
            <thead
                class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="px-4 py-3">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>

            {{-- Table Body --}}
            <tbody class="{{ $striped ? 'divide-y divide-neutral-300 dark:divide-neutral-700' : '' }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
