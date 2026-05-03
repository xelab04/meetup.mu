<!doctype html>
<html lang="en" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="A communal ledger of tech meetups happening around Mauritius." />
        <title>Mauritius Meetups</title>
        <link rel="icon" href="/images/favicon.svg" sizes="any" type="image/svg+xml">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif; background: #eef2f7; color: #111827; }
                html.dark body { background: #0f1419; color: #e5ecf4; }
            </style>
        @endif

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>

        <script>
            (function () {
                const stored = localStorage.getItem('theme');
                const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (stored === 'dark' || (!stored && systemDark)) {
                    document.documentElement.classList.add('dark');
                }
                const hue = localStorage.getItem('hue');
                if (hue !== null) {
                    document.documentElement.style.setProperty('--hue', hue);
                }
            })();
        </script>
    </head>
    <body class="bg-island-bg text-island-fg antialiased">

        {{-- Top bar --}}
        <header class="sticky top-0 z-40 border-b border-island-rule bg-island-bg/95 backdrop-blur-md">
            <div class="max-w-7xl mx-auto px-5 md:px-10 py-3 md:py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/favicon.svg') }}" alt="meetup.mu" class="w-8 h-8 shrink-0">
                    <span class="font-semibold text-base tracking-tight">meetup.mu</span>
                </a>
                <div class="flex items-center gap-2 md:gap-3">
                    <a href="https://github.com/xelab04/meetups" target="_blank" rel="noopener"
                       class="w-[34px] h-[34px] rounded-full border border-island-rule flex items-center justify-center text-island-fg hover:bg-island-card transition-colors"
                       aria-label="Submit an event on GitHub"
                       title="Submit an event on GitHub">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4" aria-hidden="true">
                            <path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.302 3.438 9.8 8.205 11.387.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.4 3-.405 1.02.005 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/>
                        </svg>
                    </a>
                    <div x-data="huePicker" @click.outside="open = false" class="relative">
                        <button type="button" @click="open = !open"
                                class="w-[34px] h-[34px] rounded-full border border-island-rule flex items-center justify-center hover:bg-island-card transition-colors"
                                aria-label="Change palette hue"
                                title="Palette hue">
                            <span class="w-4 h-4 rounded-full border border-island-rule"
                                  :style="`background: oklch(0.6 0.15 ${hue})`"></span>
                        </button>
                        <div x-show="open" x-cloak x-transition.opacity
                             class="absolute right-0 top-full mt-2 w-64 bg-island-card border border-island-rule rounded-xl shadow-lg p-3 z-30">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[12px] font-semibold text-island-fg">Hue</span>
                                <span class="text-[11px] tabular-nums text-island-muted" x-text="hue + '°'"></span>
                            </div>
                            <input type="range" min="0" max="360" step="1" x-model.number="hue" @input="apply()"
                                   class="hue-slider w-full cursor-pointer" />
                            <div class="grid grid-cols-4 gap-1 mt-3">
                                <template x-for="preset in presets" :key="preset.h">
                                    <button type="button" @click="hue = preset.h; apply()"
                                            class="flex flex-col items-center gap-1 py-1.5 rounded-md border border-island-rule hover:bg-island-bg transition-colors">
                                        <span class="w-3 h-3 rounded-full"
                                              :style="`background: oklch(0.6 0.15 ${preset.h})`"></span>
                                        <span class="text-[10px] text-island-muted" x-text="preset.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    @auth
                        <a href="{{ route('profile.edit') }}"
                           class="hidden md:inline-block text-sm text-island-muted hover:text-island-fg px-2 py-1.5 transition-colors">
                            Profile
                        </a>
                    @endauth
                    <button id="theme-toggle" type="button"
                            class="w-[34px] h-[34px] rounded-full border border-island-rule flex items-center justify-center text-island-fg hover:bg-island-card transition-colors"
                            aria-label="Toggle theme">
                        <svg class="hidden dark:block w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                        </svg>
                        <svg class="block dark:hidden w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="mt-16 border-t border-island-rule bg-island-card">
            <div class="max-w-7xl mx-auto px-5 md:px-10 py-6 flex flex-col md:flex-row justify-between gap-3 text-sm text-island-muted">
                <div>
                    Made by the wider Mauritian tech community &lt;3
                </div>
                <div class="flex flex-wrap gap-x-5 gap-y-1">
                    <span>v {{ trim(file_get_contents(base_path('version.txt'))) }}</span>
                    <a href="{{ route('privacypolicy') }}" class="hover:text-island-fg">Privacy Policy</a>
                    <a href="http://ug4ypgpdplfhm3vhulzoao3mnfqzemqai5yyflavnv2n4zscqfygytyd.onion/" class="hover:text-island-fg">TOR endpoint</a>
                </div>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const btn = document.getElementById('theme-toggle');
                btn?.addEventListener('click', () => {
                    const dark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', dark ? 'dark' : 'light');
                });
            });
        </script>

        @include('partials.island-scripts')

        @stack('scripts')
    </body>
</html>
