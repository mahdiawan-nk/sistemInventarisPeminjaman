@props([
    'name' => 'User',
    'role' => null,
    'message' => 'Selamat datang di dashboard!',
    'icon' => null, // optional, bisa SVG / Blade icon
])
<div class="bg-white dark:bg-neutral-900 shadow rounded-xl p-6 flex flex-col md:flex-row items-center gap-6">
    {{-- Icon / Illustration --}}
    @if ($icon)
        <div
            class="flex-shrink-0 w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300">
            {!! $icon !!}
        </div>
    @else
        <div
            class="flex-shrink-0 w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A8.962 8.962 0 0112 15a8.962 8.962 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
    @endif

    {{-- Greeting & Info --}}
    <div class="flex-1 text-center md:text-left">
        <h2 class="text-2xl font-bold text-neutral-900 dark:text-white">
            Halo, {{ $name }}!
        </h2>
        @if ($role)
            <p class="text-sm text-neutral-500 dark:text-neutral-300 mt-1">Role: {{ $role }}</p>
        @endif
        <p class="mt-2 text-neutral-600 dark:text-neutral-400">{{ $message }}</p>
    </div>

    {{-- Optional Button / Shortcut --}}
    <div class="mt-4 md:mt-0 text-center md:text-right">
        <div x-data="clock()" x-init="init()" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow">
            <span x-text="time"></span>
        </div>
    </div>
</div>
<script>
    function clock() {
        return {
            time: '',
            init() {
                this.updateTime();
                setInterval(() => this.updateTime(), 1000);
            },
            updateTime() {
                const now = new Date();
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const dayName = days[now.getDay()];
                const day = now.getDate().toString().padStart(2, '0');
                const month = (now.getMonth() + 1).toString().padStart(2, '0');
                const year = now.getFullYear();
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                this.time = `${dayName}, ${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
            }
        }
    }
</script>
