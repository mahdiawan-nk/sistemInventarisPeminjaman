<div class="space-y-8">
    @forelse ($logs as $peminjamanId => $dates)
        <div class="border border-default rounded-base bg-neutral-secondary-soft">

            <!-- Header peminjaman -->
            <div class="px-4 py-3 border-b border-default">
                <p class="text-sm font-semibold text-heading">
                    {{ $dates->first()->first()->peminjaman->code_data_pinjaman ?? 'Peminjaman #' . $peminjamanId }}
                </p>
            </div>

            @foreach ($dates as $date => $items)
                <div class="px-4 py-2 text-xs font-semibold text-muted">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                </div>

                <ol class="divide-y divide-default">
                    @foreach ($items as $log)
                        <li class="px-4 py-3">
                            <div class="flex gap-4">
                                <div
                                    class="w-8 h-8 rounded-full bg-neutral-primary-soft flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                    </svg>

                                </div>

                                <div class="flex-1">
                                    <p class="text-sm text-body">
                                        <span class="font-medium text-heading">
                                            {{ $log->performedBy?->name ?? 'System' }}
                                        </span>
                                        {{ $log->action }}
                                    </p>

                                    <p class="text-sm text-muted mt-1">
                                        {{ $log->action_detail }}
                                    </p>

                                    <div class="text-xs text-muted mt-1">
                                        {{ $log->created_at->format('H:i') }}
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
            Belum ada log peminjaman
        </div>
    @endforelse
</div>
