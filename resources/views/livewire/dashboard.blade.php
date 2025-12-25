<div>
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <x-widget.welcome-card name="{{ auth()->user()->name }}"
            role="{{ auth()->user()->roles->pluck('name')->first() }}"
            message="Selamat datang di sistem inventaris ICT. Periksa status item terbaru dan laporan peminjaman hari ini." />
        @hasanyrole('Administrator|Staff IT')
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <x-widget.card-stat title="Total Users" :value="$userCount">
                    <x-heroicon-o-users class="w-6 h-6 text-yellow-600 dark:text-yellow-200" />
                </x-widget.card-stat>

                <x-widget.card-stat title="Total Category" :value="$categoryCount">
                    <x-heroicon-o-users class="w-6 h-6 text-yellow-600 dark:text-yellow-200" />
                </x-widget.card-stat>

                <x-widget.card-stat title="Total Location" :value="$locationCount">
                    <x-heroicon-o-users class="w-6 h-6 text-yellow-600 dark:text-yellow-200" />
                </x-widget.card-stat>

                <x-widget.card-stat title="Total Asset" :value="$itemCount">
                    <x-heroicon-o-users class="w-6 h-6 text-yellow-600 dark:text-yellow-200" />
                </x-widget.card-stat>

                <x-widget.card-stat title="Total Asset Available" :value="$itemAvailableCount">
                    <x-heroicon-o-users class="w-6 h-6 text-yellow-600 dark:text-yellow-200" />
                </x-widget.card-stat>

                <x-widget.card-stat title="Total Asset Damaged" :value="$itemDamagedCount">
                    <x-heroicon-o-users class="w-6 h-6 text-yellow-600 dark:text-yellow-200" />
                </x-widget.card-stat>

                <x-widget.card-stat title="Total Asset Borrow" :value="$itemBorrowedCount">
                    <x-heroicon-o-users class="w-6 h-6 text-yellow-600 dark:text-yellow-200" />
                </x-widget.card-stat>

                <x-widget.card-stat title="Total Peminjaman" :value="$peminjamanCount">
                    <x-heroicon-o-users class="w-6 h-6 text-yellow-600 dark:text-yellow-200" />
                </x-widget.card-stat>

            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <x-widget.item-status-chart :statusCounts="$this->chartItemsummary()" />
                <x-widget.inventory-units-chart :totalUnits="$this->trendUnitVsItem()['Available']" :borrowedUnits="$this->trendUnitVsItem()['Borrowed']" type="bar" />

            </div>
            <x-widget.borrowing-trend-chart title="Trend Peminjaman Tahun {{ $currentYear }}" type="line"
                :dataPoints="$this->trendPeminjaman()" />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">

                <x-widget.table-widget title="Overview Item Baru Ditambahkan" :headers="['Code Item', 'Item', 'Status', 'Created At']">
                    @foreach ($this->overviewNewItemAdded() as $itemAdd)
                        <tr>
                            <td class="px-4 py-3">{{ $itemAdd->asset_code }}</td>
                            <td class="px-4 py-3">{{ $itemAdd->name }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                    {{ $itemAdd->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $itemAdd->created_at }}</td>
                        </tr>
                    @endforeach

                </x-widget.table-widget>

                <x-widget.table-widget title="Overview Aktivitas Peminjaman" :headers="['Code Peminjaman', 'Peminjam', 'Status', 'Created At']">
                    @foreach ($this->overviewPeminjaman() as $peminjaman)
                        <tr>
                            <td class="px-4 py-3">{{ $peminjaman->code_data_pinjaman }}</td>
                            <td class="px-4 py-3">{{ $peminjaman->nama_peminjam }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                    {{ $peminjaman->status_peminjaman }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $peminjaman->created_at }}</td>

                        </tr>
                    @endforeach

                </x-widget.table-widget>

            </div>
        @endhasanyrole
        @hasrole('User')
            <section
                class="hidden md:flex items-center justify-center py-12 sm:py-16 md:py-24 lg:py-32
           bg-slate-950 text-neutral-100 h-full
           dark:bg-slate-950 dark:text-neutral-100">

                <div class="relative max-w-4xl px-8 mx-auto lg:px-0">

                    <div class="relative flex flex-col md:flex-row md:items-center gap-12">

                        <!-- LEFT CONTENT -->
                        <div class="flex-1">
                            <h1
                                class="flex flex-col text-5xl font-extrabold leading-tight
                           text-neutral-100 dark:text-white">
                                E-Inventory
                                <span class="text-[oklch(0.69_0.16_63.06)]">
                                    ICT Management
                                </span>
                            </h1>

                            <p
                                class="mt-6 max-w-xl text-base leading-relaxed
                           text-neutral-600 dark:text-neutral-400">
                                Sistem terpusat untuk mengelola aset teknologi informasi dan komunikasi,
                                mulai dari pencatatan inventaris, peminjaman, hingga pelacakan status
                                secara real-time dan terstruktur.
                            </p>

                            <!-- BADGES -->
                            <div class="mt-8 flex flex-wrap items-center gap-3">
                                <span
                                    class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-semibold
                               bg-[oklch(0.69_0.16_63.06/0.15)]
                               text-[oklch(0.69_0.16_63.06)]
                               dark:bg-[oklch(0.69_0.16_63.06/0.25)]">
                                    Inventory Control
                                </span>

                                <span
                                    class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium
                               bg-neutral-100 text-neutral-700
                               dark:bg-white/10 dark:text-neutral-300">
                                    Borrow & Tracking
                                </span>
                            </div>
                        </div>

                        <!-- RIGHT IMAGE -->
                        <div class="relative md:w-96">
                            <img src="https://cdn.devdojo.com/images/december2020/designs3d.png"
                                alt="ICT Inventory Illustration"
                                class="w-full h-auto rounded-xl
                           shadow-sm
                           dark:shadow-white/5">
                        </div>

                    </div>

                    <!-- DIVIDER -->
                    <div
                        class="my-16 border-t
                   border-neutral-200
                   dark:border-white/10">
                    </div>

                    <!-- BOTTOM TEXT -->
                    <div class="max-w-3xl">
                        <p
                            class="text-base leading-relaxed
                       text-neutral-600
                       dark:text-neutral-400">
                            Aplikasi ini membantu tim ICT menjaga akurasi data aset,
                            meningkatkan efisiensi operasional, serta memastikan setiap
                            perangkat tercatat, terlacak, dan digunakan sesuai prosedur.
                        </p>
                    </div>

                </div>
            </section>
        @endhasrole
    </div>
</div>
