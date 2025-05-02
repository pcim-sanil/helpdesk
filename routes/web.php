<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Tickets\TicketListController;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');


    // Define routes for ticket list views
    Route::get('/tickets/open', [TicketListController::class, 'open'])->name('tickets.open');
    Route::get('/tickets/closed', [TicketListController::class, 'closed'])->name('tickets.closed');
    Route::get('/tickets/all', [TicketListController::class, 'all'])->name('tickets.all');
});



require __DIR__.'/auth.php';

if (config('app.env') === 'local') {
   require __DIR__.'/test.php';
}
