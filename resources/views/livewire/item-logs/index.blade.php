<div class="space-y-8">

    @forelse ($items as $item)
        <div class="bg-neutral-secondary-soft border border-default rounded-base">

            <!-- Header Item -->
            <div class="px-4 py-3 border-b border-default">
                <p class="text-sm font-semibold text-heading">
                    {{ $item->name }}
                </p>
                <p class="text-xs text-muted">
                    {{ $item->asset_code }} • {{ $item->brand }} {{ $item->model }}
                </p>
            </div>

            <!-- Logs -->
            @php
                $groupedLogs = $item->statusLogs->groupBy(fn($log) => $log->created_at->format('Y-m-d'));
            @endphp

            @foreach ($groupedLogs as $date => $logs)
                <div class="px-4 py-2 text-xs font-semibold text-muted">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                </div>

                <ol class="divide-y divide-default">
                    @foreach ($logs as $log)
                        <li class="px-4 py-3">
                            <div class="flex gap-4">

                                <!-- Icon -->
                                <div
                                    class="w-8 h-8 rounded-full bg-neutral-primary-soft flex items-center justify-center">
                                    🔁
                                </div>

                                <!-- Content -->
                                <div class="flex-1">
                                    <p class="text-sm text-body">
                                        Status berubah:
                                        <span class="font-medium text-heading">
                                            {{ $log->old_status }}
                                        </span>
                                        →
                                        <span class="font-medium text-heading">
                                            {{ $log->new_status }}
                                        </span>
                                    </p>

                                    <p class="text-sm text-muted mt-1">
                                        {{ $log->notes ?? '-' }}
                                    </p>

                                    <div class="text-xs text-muted mt-1">
                                        {{ $log->created_at->format('H:i') }}
                                        • {{ $log->user?->name ?? 'System' }}
                                    </div>
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ol>
            @endforeach

        </div>
    @empty
        <div class="text-center text-sm text-muted py-6">
            Tidak ada log item
        </div>
    @endforelse

    <!-- Pagination -->
    <div>
        {{ $items->links() }}
    </div>

</div>
