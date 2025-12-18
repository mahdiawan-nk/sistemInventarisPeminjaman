<x-layouts.app.sidebarwithtop :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebarwithtop>
