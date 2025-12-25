<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Dashboard;
use App\Livewire\User\Index as UserIndex;
use App\Livewire\Categories\Index as CategoryIndex;
use App\Livewire\Locations\Index as LocationIndex;
use App\Livewire\Item\Index as ItemIndex;
use App\Livewire\Item\Create as ItemCreate;
use App\Livewire\ItemLogs\Index as ItemLogsIndex;
use App\Livewire\Peminjaman\Index as PeminjamanIndex;
use App\Livewire\Peminjaman\Create as PeminjamanCreate;
use App\Livewire\Peminjaman\Update as PeminjamanUpdate;
use App\Livewire\Peminjaman\Logs as PeminjamanLogs;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('users', UserIndex::class)->name('users.index');
    Volt::route('categories', CategoryIndex::class)->name('categories.index');
    Volt::route('locations', LocationIndex::class)->name('locations.index');
    Volt::route('items', ItemIndex::class)->name('items.index');
    Volt::route('items/create', ItemCreate::class)->name('items.create');
    Volt::route('item-logs', ItemLogsIndex::class)->name('item-logs.index');

    Volt::route('peminjaman', PeminjamanIndex::class)->name('peminjaman.index');
    Volt::route('peminjaman/create', PeminjamanCreate::class)->name('peminjaman.create');
    Volt::route('peminjaman/{peminjaman}/edit', PeminjamanUpdate::class)->name('peminjaman.edit');
    Volt::route('peminjaman/logs', PeminjamanLogs::class)->name('peminjaman.logs');


    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
