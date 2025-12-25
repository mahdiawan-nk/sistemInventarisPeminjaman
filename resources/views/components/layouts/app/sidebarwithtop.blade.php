<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')

</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <x-ui.notification duration="3000" sound="true" />
    <div x-data="{ sidebarIsOpen: false }" class="relative flex w-full flex-col md:flex-row">
        <!-- This allows screen readers to skip the sidebar and go directly to the main content. -->
        <a class="sr-only" href="#main-content">skip to the main content</a>

        <!-- dark overlay for when the sidebar is open on smaller screens  -->
        <div x-cloak x-show="sidebarIsOpen" class="fixed inset-0 z-20 bg-neutral-950/10 backdrop-blur-xs md:hidden"
            aria-hidden="true" x-on:click="sidebarIsOpen = false" x-transition.opacity></div>

        <nav x-cloak
            class="fixed left-0 z-30 flex h-svh w-64 flex-col border-r border-slate-200 bg-slate-900 p-4
           transition-transform duration-300 md:relative md:translate-x-0
           dark:border-slate-800 dark:bg-slate-950"
            x-bind:class="sidebarIsOpen ? 'translate-x-0' : '-translate-x-64'" aria-label="Sidebar">

            <!-- Logo -->
            <a href="#" class="mb-6 flex items-center gap-2 px-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-md bg-blue-600 text-white font-bold">
                    ICT
                </div>
                <div class="leading-tight">
                    <p class="text-sm font-bold text-white">E-Inventory</p>
                    <p class="text-xs text-slate-400">Information & Tech</p>
                </div>
            </a>

            <!-- Navigation -->
            <flux:navlist class="w-full">
                <flux:navlist.item href="{{ route('dashboard') }}" wire:navigate icon="home">Dashboard
                </flux:navlist.item>
                @hasanyrole('Administrator|Staff IT')
                    <flux:navlist.item href="{{ route('users.index') }}" wire:navigate icon="users">Management User
                    </flux:navlist.item>
                @endhasanyrole
                <flux:navlist.group heading="Management Inventory" icon="user" expandable>
                    {{-- Semua akses untuk Administrator & Staff IT --}}
                    @hasanyrole('Administrator|Staff IT')
                        <flux:navlist.item href="{{ route('categories.index') }}" wire:navigate>Categories
                        </flux:navlist.item>
                        <flux:navlist.item :href="route('locations.index')" wire:navigate>Locations</flux:navlist.item>
                        <flux:navlist.item :href="route('items.create')" wire:navigate>Form Inventaris</flux:navlist.item>
                        <flux:navlist.item :href="route('items.index')" wire:navigate>Item Inventaris</flux:navlist.item>
                        <flux:navlist.item :href="route('item-logs.index')" wire:navigate>Item Logs</flux:navlist.item>
                    @endhasanyrole

                    {{-- User hanya akses Item Inventaris --}}
                    @hasrole('User')
                        <flux:navlist.item :href="route('items.index')" wire:navigate>Item Inventaris</flux:navlist.item>
                    @endhasrole
                </flux:navlist.group>

                <flux:navlist.group heading="Management Borrow" expandable>
                    {{-- Semua role akses Form Borrow & List Peminjaman --}}
                    <flux:navlist.item href="{{ route('peminjaman.create') }}" wire:navigate>Form Borrow
                    </flux:navlist.item>
                    <flux:navlist.item :href="route('peminjaman.index')" wire:navigate>List Peminjaman
                    </flux:navlist.item>

                    {{-- Logs hanya untuk Administrator & Staff IT --}}
                    @hasanyrole('Administrator|Staff IT')
                        <flux:navlist.item :href="route('peminjaman.logs')" wire:navigate>Logs</flux:navlist.item>
                    @endhasanyrole
                </flux:navlist.group>


            </flux:navlist>
        </nav>


        <!-- top navbar & main content  -->
        <div class="h-svh w-full overflow-y-auto bg-white dark:bg-neutral-950">
            <!-- top navbar  -->
            <nav class="sticky top-0 z-10 flex items-center justify-between border-b border-neutral-300 bg-neutral-50 px-4 py-2 dark:border-neutral-700 dark:bg-neutral-900"
                aria-label="top navibation bar">

                <!-- sidebar toggle button for small screens  -->
                <button type="button" class="md:hidden inline-block text-neutral-600 dark:text-neutral-300"
                    x-on:click="sidebarIsOpen = true">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-5"
                        aria-hidden="true">
                        <path
                            d="M0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5-1v12h9a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1zM4 2H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h2z" />
                    </svg>
                    <span class="sr-only">sidebar toggle</span>
                </button>

                <!-- breadcrumbs  -->
                <nav class="hidden md:inline-block text-sm font-medium text-neutral-600 dark:text-neutral-300"
                    aria-label="breadcrumb">
                    <ol class="flex flex-wrap items-center gap-1">
                        <li class="flex items-center gap-1">
                            <a href="#" class="hover:text-neutral-900 dark:hover:text-white">Dashboard</a>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                                fill="none" stroke-width="2" class="size-4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </li>

                        <li class="flex items-center gap-1 font-bold text-neutral-900 dark:text-white"
                            aria-current="page">{{ $title ?? config('app.name') }}</li>
                    </ol>
                </nav>


                <!-- Profile Menu  -->
                <div x-data="{ userDropdownIsOpen: false }" class="relative" x-on:keydown.esc.window="userDropdownIsOpen = false">
                    <button type="button"
                        class="flex w-full items-center rounded-sm gap-2 p-2 text-left text-neutral-600 hover:bg-black/5 hover:text-neutral-900 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white dark:focus-visible:outline-white"
                        x-bind:class="userDropdownIsOpen ? 'bg-black/10 dark:bg-white/10' : ''" aria-haspopup="true"
                        x-on:click="userDropdownIsOpen = ! userDropdownIsOpen"
                        x-bind:aria-expanded="userDropdownIsOpen">
                        <img src="https://penguinui.s3.amazonaws.com/component-assets/avatar-7.webp"
                            class="size-8 object-cover rounded-sm" alt="avatar" aria-hidden="true" />
                        <div class="hidden md:flex flex-col">
                            <span
                                class="text-sm font-bold text-neutral-900 dark:text-white">{{ auth()->user()->name }}</span>
                            <span class="text-xs" aria-hidden="true">{{ auth()->user()->email }}</span>
                            <span class="sr-only">{{ auth()->user()->email }}</span>
                        </div>
                    </button>

                    <!-- menu -->
                    <div x-cloak x-show="userDropdownIsOpen"
                        class="absolute top-14 right-0 z-20 h-fit w-48 border divide-y divide-neutral-300 border-neutral-300 bg-white dark:divide-neutral-700 dark:border-neutral-700 dark:bg-neutral-950 rounded-sm"
                        role="menu" x-on:click.outside="userDropdownIsOpen = false"
                        x-on:keydown.down.prevent="$focus.wrap().next()"
                        x-on:keydown.up.prevent="$focus.wrap().previous()" x-transition="" x-trap="userDropdownIsOpen">

                        <div class="flex flex-col py-1.5">
                            <a href="{{ route('profile.edit') }}" wire:navigate
                                class="flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-hidden dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white"
                                role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                    class="size-5 shrink-0" aria-hidden="true">
                                    <path
                                        d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                                </svg>
                                <span>Profile</span>
                            </a>
                        </div>

                        <div class="flex flex-col py-1.5">
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-2 py-1.5 text-sm font-medium text-neutral-600 underline-offset-2 hover:bg-black/5 hover:text-neutral-900 focus-visible:underline focus:outline-hidden dark:text-neutral-300 dark:hover:bg-white/5 dark:hover:text-white"
                                    role="menuitem">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5 shrink-0" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                            clip-rule="evenodd" />
                                        <path fill-rule="evenodd"
                                            d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>Sign Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- main content  -->
            <div id="main-content" class="p-4 bg-white dark:bg-neutral-950 relative z-0">
                <div class="overflow-y-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>


    @fluxScripts
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>

</html>
