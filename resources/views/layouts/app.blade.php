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
         @livewireStyles
    </head>
    <body class="antialiased">
        <div class="flex min-h-screen">

            <!-- Sidebar -->
            <aside class="hidden w-64 shrink-0 flex-col border-r border-slate-200 bg-white lg:flex">
                <livewire:layout.navigation />
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
        @livewireScripts
    </body>
</html>