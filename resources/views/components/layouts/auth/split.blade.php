<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-neutral-100 antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
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
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <a href="#" class="mb-6 flex items-center gap-2 px-2 mx-auto mb-5">
                    <div
                        class="flex h-16 w-16 items-center justify-center text-2xl rounded-md bg-blue-600 text-white font-extrabold">
                        {{-- ICT --}}
                        <img class="h-12 max-w-xs" src="{{ asset('images/logo-polkam.png') }}" alt="image description">
                    </div>
                    <span
                        class="flex flex-col text-2xl font-extrabold leading-tight
                           text-neutral-900 dark:text-white">
                        E-Inventory
                        <span class="text-[oklch(0.69_0.16_63.06)]">
                            ICT Management
                        </span>
                    </span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>
