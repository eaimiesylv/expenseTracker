<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome');

Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// App pages
Volt::route('budgets', 'pages.budgets')
    ->middleware(['auth', 'verified'])
    ->name('budgets');

Volt::route('expenses', 'pages.expenses')
    ->middleware(['auth', 'verified'])
    ->name('expenses');

Volt::route('bills', 'pages.bills')
    ->middleware(['auth', 'verified'])
    ->name('bills');

Volt::route('groups', 'pages.groups')
    ->middleware(['auth', 'verified'])
    ->name('groups');

Volt::route('savings', 'pages.savings')
    ->middleware(['auth', 'verified'])
    ->name('savings');

Route::view('settings', 'settings')
    ->middleware(['auth', 'verified'])
    ->name('settings');

Volt::route('notifications', 'pages.notifications')
    ->middleware(['auth', 'verified'])
    ->name('notifications');

Volt::route('analytics', 'pages.analytics')
    ->middleware(['auth', 'verified'])
    ->name('analytics');


require __DIR__.'/auth.php';
