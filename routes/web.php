<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// App pages
Route::view('budgets', 'budgets')
    ->middleware(['auth', 'verified'])
    ->name('budgets');

Route::view('expenses', 'expenses')
    ->middleware(['auth', 'verified'])
    ->name('expenses');

Route::view('bills', 'bills')
    ->middleware(['auth', 'verified'])
    ->name('bills');

Route::view('groups', 'groups')
    ->middleware(['auth', 'verified'])
    ->name('groups');

Route::view('savings', 'savings')
    ->middleware(['auth', 'verified'])
    ->name('savings');

Route::view('settings', 'settings')
    ->middleware(['auth', 'verified'])
    ->name('settings');

require __DIR__.'/auth.php';
