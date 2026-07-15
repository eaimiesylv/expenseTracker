<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LedgerFlow') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|inter:400,500,600,700|jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root{
                --emerald:#10B981;
                --emerald-dark:#059669;
                --indigo:#4F46E5;
                --ink:#0B1120;
                --ink-soft:#475569;
                --paper:#FFFFFF;
                --paper-soft:#F6F8FA;
                --line:#E2E8F0;
            }
            body{
                font-family:'Inter', ui-sans-serif, system-ui, sans-serif;
                color:var(--ink);
                background:var(--paper-soft);
                -webkit-font-smoothing:antialiased;
            }
            .font-display{font-family:'Space Grotesk', 'Inter', sans-serif;}
            .font-mono{font-family:'JetBrains Mono', ui-monospace, monospace; font-variant-numeric: tabular-nums;}

            .receipt-card{
                background:var(--paper);
                border:1px solid var(--line);
                border-radius:18px;
                box-shadow: 0 1px 2px rgba(15,23,42,0.04), 0 12px 32px -12px rgba(15,23,42,0.10);
            }
            .grad-cta{ background: linear-gradient(135deg, var(--emerald) 0%, #0EA5A0 100%); }

            .side-link{
                display:flex; align-items:center; gap:.65rem;
                padding:.6rem .85rem; border-radius:10px;
                font-size:.875rem; font-weight:500; color:var(--ink-soft);
                transition: background .15s ease, color .15s ease;
            }
            .side-link:hover{ background:var(--paper-soft); color:var(--ink); }
            .side-link.active{ background:#ECFDF5; color:var(--emerald-dark); font-weight:600; }
            .side-link svg{ flex-shrink:0; }
        </style>
    </head>
    <body class="antialiased">
        <div class="flex min-h-screen">

            <!-- Sidebar -->
            <aside class="hidden w-64 shrink-0 flex-col border-r border-slate-200 bg-white lg:flex">
                <div class="flex items-center gap-2 px-6 py-5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:var(--emerald)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 4h16M4 12h10M4 20h16" stroke="white" stroke-width="2.4" stroke-linecap="round"/></svg>
                    </span>
                    <span class="font-display text-lg font-bold tracking-tight">LedgerFlow</span>
                </div>

                <nav class="flex-1 space-y-1 px-3 py-2">
                    <a href="{{ route('dashboard') }}" class="side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10.5Z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('budgets') }}" class="side-link {{ request()->routeIs('budgets') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 4v0"/></svg>
                        Budgets
                    </a>
                    <a href="{{ route('expenses') }}" class="side-link {{ request()->routeIs('expenses') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v18M17 7.5c0-1.9-2.2-2.5-5-2.5s-5 1-5 2.7 2.2 2.4 5 2.8 5 1.1 5 2.8-2.2 2.7-5 2.7-5-.6-5-2.5"/></svg>
                        Expenses
                    </a>
                    <a href="{{ route('bills') }}" class="side-link {{ request()->routeIs('bills') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h13l3 4v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/><path d="M8 11h8M8 15h5"/></svg>
                        Bills &amp; Splits
                    </a>
                    <a href="{{ route('groups') }}" class="side-link {{ request()->routeIs('groups') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3.5 19c.7-3 3-5 5.5-5s4.8 2 5.5 5M14.5 19c.3-2 1.6-3.6 3.3-4.3"/></svg>
                        Groups
                    </a>

                    <a href="{{ route('savings') }}" class="side-link {{ request()->routeIs('savings') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3c-2 0-4 1-5 3-1 2 0 4 2 5 2 1 3 3 3 5 0 2 2 3 4 3s4-1 4-3c0-2-1-4-3-5-2-1-3-3-3-5 0-1-1-3-2-3z"/></svg>
                        Record savings
                    </a>

                    <div class="my-3 border-t border-slate-100"></div>

                    <a href="{{ route('settings') }}" class="side-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/></svg>
                        Settings
                    </a>
                </nav>

                <div class="border-t border-slate-100 p-4 lg:hidden">
                    <livewire:layout.navigation />
                </div>
            </aside>

            <!-- Main column -->
            <div class="flex min-h-screen flex-1 flex-col">
                <!-- Topbar -->
                <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 backdrop-blur-md">
                    <div class="flex items-center justify-between px-6 py-4">
                        <div>
                            @if (isset($header))
                                {{ $header }}
                            @endif
                        </div>

                        <div class="flex items-center gap-4">
                            <button class="relative rounded-full p-2 text-slate-500 hover:bg-slate-100">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8a6 6 0 1 1 12 0c0 4 1.5 5.5 2 6H4c.5-.5 2-2 2-6Z"/><path d="M10 21a2 2 0 0 0 4 0"/></svg>
                                <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-emerald-500"></span>
                            </button>
                            <div class="flex items-center gap-2">
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-100 text-xs font-semibold text-emerald-700">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <span class="hidden text-sm font-medium text-slate-700 sm:inline">{{ auth()->user()->name ?? 'User' }}</span>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 px-6 py-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>