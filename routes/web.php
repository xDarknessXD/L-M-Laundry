<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\TransactionCreate;
use App\Livewire\TransactionEdit;
use App\Livewire\Transactions;
use App\Livewire\Inventory;
use App\Livewire\MachineLogs;
use App\Livewire\UserManagement;
use App\Livewire\Settings;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

Route::get('/waiting', function () {
    return view('waiting');
})->name('waiting');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated + Active User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.status'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/transactions', Transactions::class)->name('transactions');
    Route::get('/transactions/create', TransactionCreate::class)->name('transactions.create');
    Route::get('/transactions/{transaction}/edit', TransactionEdit::class)->name('transactions.edit');
    Route::get('/inventory', Inventory::class)->name('inventory');
    Route::get('/machine-logs', MachineLogs::class)->name('machine-logs');

    /*
    |----------------------------------------------------------------------
    | Admin Only Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {
        Route::get('/users', UserManagement::class)->name('users');
        Route::get('/settings', Settings::class)->name('settings');
    });
});
