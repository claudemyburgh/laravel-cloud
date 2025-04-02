<?php

    use App\Http\Controllers\Auth\EthereumAuthController;
    use App\Http\Controllers\HomeIndexPageController;
    use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeIndexPageController::class)->name('home');


    Route::get('/ethereum/login', function () {
        return view('auth.ethereum-login');
    })->name('ethereum.login');

    Route::post('/ethereum/authenticate', [EthereumAuthController::class, 'authenticate'])
        ->name('ethereum.authenticate');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
