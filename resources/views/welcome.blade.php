<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LedgerFlow — Manage Your Money Together</title>
    <meta name="description" content="The all-in-one finance platform for individuals and groups. Budgets, bill splitting, and automated Ajo/Esusu, all in one ledger.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|inter:400,500,600,700|jetbrains-mono:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
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
        html{scroll-behavior:smooth;}
        body{
            font-family:'Inter', ui-sans-serif, system-ui, sans-serif;
            color:var(--ink);
            background:var(--paper);
            -webkit-font-smoothing:antialiased;
        }
        .font-display{font-family:'Space Grotesk', 'Inter', sans-serif;}
        .font-mono{font-family:'JetBrains Mono', ui-monospace, monospace; font-variant-numeric: tabular-nums;}

        /* Ledger perforation divider — the page's signature motif */
        .ledger-tear{
            position:relative;
            border-top:1.5px dashed var(--line);
        }
        .ledger-tear::before, .ledger-tear::after{
            content:"";
            position:absolute;
            top:-9px;
            width:18px;height:18px;
            border-radius:9999px;
            background:var(--paper-soft);
            border:1.5px dashed var(--line);
        }
        .ledger-tear::before{left:-9px;}
        .ledger-tear::after{right:-9px;}

        .receipt-card{
            background:var(--paper);
            border:1px solid var(--line);
            border-radius:18px;
            box-shadow: 0 1px 2px rgba(15,23,42,0.04), 0 12px 32px -12px rgba(15,23,42,0.12);
        }
        .receipt-card .receipt-notch{
            height:8px;
            background-image: radial-gradient(circle at 6px 4px, var(--paper-soft) 4px, transparent 4.5px);
            background-size: 12px 8px;
            background-repeat: repeat-x;
        }

        .grad-cta{
            background: linear-gradient(135deg, var(--emerald) 0%, #0EA5A0 100%);
        }
        .grad-final{
            background: radial-gradient(120% 140% at 10% 0%, #14532D 0%, var(--ink) 45%, #1E1B4B 100%);
        }
        .chip{
            border:1px solid var(--line);
            background:var(--paper-soft);
        }

        .hero-visual{
            position:relative;
            overflow:hidden;
            background:linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }
        .hero-visual::before{
            content:"";
            position:absolute;
            inset:auto -40px -40px auto;
            width:180px;
            height:180px;
            border-radius:9999px;
            background:radial-gradient(circle, rgba(16,185,129,0.18) 0%, rgba(16,185,129,0) 70%);
            pointer-events:none;
        }
        .hero-panel{
            border:1px solid var(--line);
            border-radius:16px;
            background:var(--paper);
            box-shadow:0 10px 30px -16px rgba(15,23,42,0.18);
        }
        .hero-bar{
            height:8px;
            border-radius:9999px;
            background:linear-gradient(90deg, rgba(16,185,129,0.2), rgba(16,185,129,0.8));
        }
        .hero-float{ animation: heroFloat 5s ease-in-out infinite; }
        @keyframes heroFloat{ 0%,100%{ transform: translateY(0) rotate(-1deg);} 50%{ transform: translateY(-10px) rotate(1deg);} }

        .float-1{ animation: float1 7s ease-in-out infinite; }
        .float-2{ animation: float2 8s ease-in-out infinite; }
        @keyframes float1{ 0%,100%{ transform: translateY(0) rotate(-2deg);} 50%{ transform: translateY(-10px) rotate(-1deg);} }
        @keyframes float2{ 0%,100%{ transform: translateY(0) rotate(2deg);} 50%{ transform: translateY(-14px) rotate(3deg);} }

        details > summary{ list-style:none; cursor:pointer; }
        details > summary::-webkit-details-marker{ display:none; }
        details[open] .chev{ transform: rotate(180deg); }

        @media (prefers-reduced-motion: reduce){
            .float-1, .float-2 { animation: none; }
        }
    </style>
</head>
<body class="antialiased">
    <header class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/80 backdrop-blur-md">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="#" class="flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg" style="background:var(--emerald)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 4h16M4 12h10M4 20h16" stroke="white" stroke-width="2.4" stroke-linecap="round"/></svg>
                </span>
                <span class="font-display text-lg font-bold tracking-tight">LedgerFlow</span>
            </a>

            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="hidden text-sm font-medium text-slate-600 hover:text-slate-900 sm:inline-block">Log in</a>
                @endif
                <a href="{{ Route::has('register') ? route('register') : '#start' }}"
                   class="grad-cta rounded-full px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-emerald-200 transition hover:brightness-105">
                    Start Free
                </a>
            </div>
        </nav>
    </header>

    <main>
        <section class="mx-auto max-w-6xl px-6 py-20 lg:py-28">
            <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="max-w-3xl">
                    <span class="chip inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold text-slate-600">
                        <span class="h-1.5 w-1.5 rounded-full" style="background:var(--emerald)"></span>
                        Expense, budget, and group money in one place
                    </span>

                    <h1 class="font-display mt-5 text-4xl font-bold leading-tight tracking-tight text-slate-900 sm:text-5xl">
                        Create expenses, budgets, and shared contributions with ease.
                    </h1>

                    <p class="mt-6 text-lg leading-relaxed text-slate-600">
                        This app helps people manage personal money and group money in one simple place. Create budgets, record expenses, split payments, track who has paid, and keep contributing together like Ajo or Esusu.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a href="#start" class="grad-cta rounded-full px-7 py-3.5 text-base font-semibold text-white shadow-md shadow-emerald-200 transition hover:brightness-105">
                            Get Started
                        </a>
                        <a href="#features" class="rounded-full border border-slate-300 px-6 py-3.5 text-base font-semibold text-slate-700 transition hover:bg-slate-50">
                            See the main features
                        </a>
                    </div>
                </div>

                <div class="hero-float relative mx-auto w-full max-w-xl">
                    <div class="hero-visual receipt-card p-4 sm:p-6">
                        <div class="hero-panel p-4 sm:p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Monthly balance</p>
                                    <p class="mt-1 font-display text-2xl font-semibold text-slate-900">₦250,000</p>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">On track</span>
                            </div>

                            <div class="mt-4 space-y-3">
                                <div>
                                    <div class="flex items-center justify-between text-sm text-slate-600">
                                        <span>Food budget</span>
                                        <span>₦45,000 / ₦60,000</span>
                                    </div>
                                    <div class="mt-2 h-2 rounded-full bg-slate-100">
                                        <div class="hero-bar" style="width:75%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between text-sm text-slate-600">
                                        <span>Group contribution</span>
                                        <span>₦12,000 left</span>
                                    </div>
                                    <div class="mt-2 h-2 rounded-full bg-slate-100">
                                        <div class="hero-bar" style="width:60%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="hero-panel p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Split payment</p>
                                <div class="mt-3 rounded-xl bg-slate-50 p-3">
                                    <p class="text-sm font-semibold text-slate-900">Dinner</p>
                                    <p class="mt-1 text-xs text-slate-500">4 people • ₦20,000 each</p>
                                </div>
                                <div class="mt-3 space-y-2 text-sm text-slate-600">
                                    <div class="flex items-center justify-between"><span>Ada</span><span class="text-emerald-600">Paid</span></div>
                                    <div class="flex items-center justify-between"><span>Bayo</span><span class="text-amber-600">Pending</span></div>
                                </div>
                            </div>

                            <div class="hero-panel p-4">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Fixed contribution</p>
                                <div class="mt-3 rounded-xl bg-slate-50 p-3">
                                    <p class="text-sm font-semibold text-slate-900">Weekly savings</p>
                                    <p class="mt-1 text-xs text-slate-500">Everyone contributes ₦5,000</p>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-sm text-slate-600">
                                    <span>Members paid</span>
                                    <span class="font-semibold text-slate-900">12 / 15</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="mx-auto max-w-6xl px-6 py-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="receipt-card p-6">
                    <h2 class="font-display text-lg font-semibold text-slate-900">Create budgets</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">Set spending limits, track what is left, and keep your money under control.</p>
                </div>

                <div class="receipt-card p-6">
                    <h2 class="font-display text-lg font-semibold text-slate-900">Track expenses</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">Record every expense and see where money is going in real time.</p>
                </div>

                <div class="receipt-card p-6">
                    <h2 class="font-display text-lg font-semibold text-slate-900">Split payments</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">Add who owes, split the amount fairly, and see who has paid and who is still pending.</p>
                </div>

                <div class="receipt-card p-6">
                    <h2 class="font-display text-lg font-semibold text-slate-900">Group contributions</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600">Create fixed contributions for everyone, or keep saving together like Ajo / Esusu.</p>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-6 py-20">
            <div class="grid gap-10 lg:grid-cols-2">
                <div class="receipt-card p-8">
                    <h2 class="font-display text-2xl font-semibold text-slate-900">Group money made simple</h2>
                    <p class="mt-4 text-slate-600">
                        In a group, only the creator can manage and edit by default. Other members can be read-only, everyone can write, or each person can be given specific permissions.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full" style="background:var(--emerald)"></span>Owner controls the group</li>
                        <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full" style="background:var(--emerald)"></span>Members can view or contribute based on permission</li>
                        <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full" style="background:var(--emerald)"></span>Everyone sees shared balances and payments clearly</li>
                    </ul>
                </div>

                <div class="receipt-card p-8">
                    <h2 class="font-display text-2xl font-semibold text-slate-900">How payments work</h2>
                    <p class="mt-4 text-slate-600">
                        You can create a shared expense and assign who owes what. You can also create fixed amounts for every member to pay, then track who has paid and who is still outstanding.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-600">
                        <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full" style="background:var(--indigo)"></span>Split one bill among many people</li>
                        <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full" style="background:var(--indigo)"></span>Set the same contribution for everyone</li>
                        <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full" style="background:var(--indigo)"></span>Keep track of contributions like a rotating savings group</li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="pricing" class="mx-auto max-w-6xl px-6 py-20">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Simple pricing</h2>
                <p class="mt-4 text-lg text-slate-600">Start simple, then upgrade as your budget or group grows.</p>
            </div>

            <div class="mt-10 grid gap-6 lg:grid-cols-3">
                <div class="receipt-card p-8">
                    <p class="font-display text-lg font-semibold text-slate-900">Free</p>
                    <p class="mt-2 text-sm text-slate-500">For personal use and small groups.</p>
                    <p class="mt-6 font-display text-4xl font-semibold text-slate-900">₦0</p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-600">
                        <li>• Create basic budgets</li>
                        <li>• Track expenses</li>
                        <li>• Split simple payments</li>
                    </ul>
                </div>

                <div class="receipt-card border-emerald-500 p-8 shadow-lg shadow-emerald-100">
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Most popular</span>
                    <p class="mt-4 font-display text-lg font-semibold text-slate-900">Premium</p>
                    <p class="mt-2 text-sm text-slate-500">For families, roommates, and growing teams.</p>
                    <p class="mt-6 font-display text-4xl font-semibold text-slate-900">₦1,500</p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-600">
                        <li>• Unlimited budgets</li>
                        <li>• Shared group tracking</li>
                        <li>• Better payment visibility</li>
                    </ul>
                </div>

                <div class="receipt-card p-8">
                    <p class="font-display text-lg font-semibold text-slate-900">Coordinator</p>
                    <p class="mt-2 text-sm text-slate-500">For churches, cooperatives, and large communities.</p>
                    <p class="mt-6 font-display text-4xl font-semibold text-slate-900">₦3,000</p>
                    <ul class="mt-6 space-y-3 text-sm text-slate-600">
                        <li>• Advanced group permissions</li>
                        <li>• Fixed contribution cycles</li>
                        <li>• Ajo / Esusu style tracking</li>
                    </ul>
                </div>
            </div>
        </section>

        <section id="start" class="grad-final py-20">
            <div class="mx-auto max-w-3xl px-6 text-center">
                <h2 class="font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">Start with the basics: budget, spend, share, and contribute.</h2>
                <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-300">
                    This app is built to make everyday money management simple for individuals and groups.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ Route::has('register') ? route('register') : '#' }}" class="grad-cta rounded-full px-7 py-3.5 text-base font-semibold text-white shadow-lg">Create an account</a>
                    <a href="{{ Route::has('login') ? route('login') : '#' }}" class="rounded-full border border-white/25 px-7 py-3.5 text-base font-semibold text-white hover:bg-white/10">Log in</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white py-10">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-4 px-6 text-sm text-slate-400 sm:flex-row">
            <span>© {{ date('Y') }} LedgerFlow. All rights reserved.</span>
            <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</span>
        </div>
    </footer>
</body>
</html>