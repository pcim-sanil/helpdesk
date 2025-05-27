<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\Tickets\TicketController;
use App\Livewire\EmailAnalyzer;

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
    Route::get('/tickets/open', [TicketController::class, 'open'])->name('tickets.open');
    Route::get('/tickets/closed', [TicketController::class, 'closed'])->name('tickets.closed');
    Route::get('/tickets/all', [TicketController::class, 'all'])->name('tickets.all');
});


Route::get('/analyze-email', EmailAnalyzer::class);



require __DIR__.'/auth.php';

if (config('app.env') === 'local') {
   require __DIR__.'/test.php';
}
