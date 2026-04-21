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
            <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
            <style>
                body { font-family: 'Space Grotesk', ui-sans-serif, system-ui, sans-serif; background: #f3ebdc; color: #1f1a15; }
                html.dark body { background: #141613; color: #f2ead8; }
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
            })();
        </script>
    </head>
    <body class="bg-island-bg text-island-fg dark:bg-island-bg-dark dark:text-island-fg-dark antialiased">

        {{-- Top bar --}}
        <header class="sticky top-0 z-20 border-b border-island-rule dark:border-island-rule-dark bg-island-bg/80 dark:bg-island-bg-dark/80 backdrop-blur">
            <div class="max-w-7xl mx-auto px-5 md:px-10 py-3 md:py-4 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/favicon.svg') }}" alt="meetup.mu" class="w-8 h-8 shrink-0">
                    <span class="font-semibold text-base tracking-tight">meetup.mu</span>
                </a>
                <div class="flex items-center gap-2 md:gap-3">
                    <a href="https://github.com/xelab04/meetups" target="_blank" rel="noopener"
                       class="hidden md:inline-block text-sm text-island-muted dark:text-island-muted-dark hover:text-island-fg dark:hover:text-island-fg-dark px-2 py-1.5 transition-colors">
                        Submit an event
                    </a>
                    @auth
                        <a href="{{ route('profile.edit') }}"
                           class="hidden md:inline-block text-sm text-island-muted dark:text-island-muted-dark hover:text-island-fg dark:hover:text-island-fg-dark px-2 py-1.5 transition-colors">
                            Profile
                        </a>
                    @endauth
                    <button id="theme-toggle" type="button"
                            class="w-[34px] h-[34px] rounded-full border border-island-rule dark:border-island-rule-dark flex items-center justify-center text-island-fg dark:text-island-fg-dark hover:bg-island-card dark:hover:bg-island-card-dark transition-colors"
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

        <footer class="mt-16 border-t border-island-rule dark:border-island-rule-dark bg-island-card dark:bg-island-card-dark">
            <div class="max-w-7xl mx-auto px-5 md:px-10 py-6 flex flex-col md:flex-row justify-between gap-3 text-sm text-island-muted dark:text-island-muted-dark">
                <div>
                    Made by the wider Mauritian tech community &lt;3
                </div>
                <div class="flex flex-wrap gap-x-5 gap-y-1">
                    <span>v {{ trim(file_get_contents(base_path('version.txt'))) }}</span>
                    <a href="{{ route('privacypolicy') }}" class="hover:text-island-fg dark:hover:text-island-fg-dark">Privacy Policy</a>
                    <a href="http://ug4ypgpdplfhm3vhulzoao3mnfqzemqai5yyflavnv2n4zscqfygytyd.onion/" class="hover:text-island-fg dark:hover:text-island-fg-dark">TOR endpoint</a>
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

        @stack('scripts')
    </body>
</html>
