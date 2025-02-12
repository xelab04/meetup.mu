<!doctype html>
<html lang="en" class="scroll-smooth">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Mauritius Meetups</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        <style>
            /* Custom styles (if needed) */
            .hero-section {
                background-image: url('/images/so-white.png');
                background-repeat: repeat;
            }

            html.dark .hero-section{
                background-image: url('/images/so-dark.png');
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-800 font-sans dark:bg-gray-900 dark:text-white">
        
        <!-- Top Gradient Strip -->
        <div class="h-2 w-full" style="background: linear-gradient(135deg, #EA2839 0%, #151F6D 25%, #FFD500 50%, #00A551 75%, #EA2839 100%);"></div>

        <!-- Theme Switcher Button -->
        <div class="absolute top-4 right-2">
            <button id="theme-toggle" class="px-4 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:outline-none *:w-6 *:h-6">
                <svg class="dark:hidden block" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M15 2h2v5h-2zm6.688 6.9l3.506-3.506l1.414 1.414l-3.506 3.506zM25 15h5v2h-5zm-3.312 8.1l1.414-1.413l3.506 3.506l-1.414 1.414zM15 25h2v5h-2zm-9.606.192L8.9 21.686l1.414 1.414l-3.505 3.506zM2 15h5v2H2zm3.395-8.192l1.414-1.414L10.315 8.9L8.9 10.314zM16 12a4 4 0 1 1-4 4a4.005 4.005 0 0 1 4-4m0-2a6 6 0 1 0 6 6a6 6 0 0 0-6-6"/></svg>
                <svg class="hidden dark:block" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M29.844 15.035a1.52 1.52 0 0 0-1.23-.866a5.36 5.36 0 0 1-3.41-1.716a6.47 6.47 0 0 1-1.286-6.392a1.6 1.6 0 0 0-.299-1.546a1.45 1.45 0 0 0-1.36-.493l-.019.003a7.93 7.93 0 0 0-6.22 7.431A7.4 7.4 0 0 0 13.5 11a7.55 7.55 0 0 0-7.15 5.244A5.993 5.993 0 0 0 8 28h11a5.977 5.977 0 0 0 5.615-8.088a7.5 7.5 0 0 0 5.132-3.357a1.54 1.54 0 0 0 .097-1.52M19 26H8a3.993 3.993 0 0 1-.673-7.93l.663-.112l.145-.656a5.496 5.496 0 0 1 10.73 0l.145.656l.663.113A3.993 3.993 0 0 1 19 26m4.465-8.001h-.021a5.96 5.96 0 0 0-2.795-1.755a7.5 7.5 0 0 0-2.6-3.677c-.01-.101-.036-.197-.041-.3a6.08 6.08 0 0 1 3.79-6.05a8.46 8.46 0 0 0 1.94 7.596a7.4 7.4 0 0 0 3.902 2.228a5.43 5.43 0 0 1-4.175 1.958"/></svg>
            </button>
        </div>
        
        <main class="py-20 hero-section">
            <!-- Hero Section -->
            <section class="py-[20vh] px-4 bg-white dark:bg-neutral-800/50 shadow-sm dark:border-t-0 border-t border-t-gray-100 dark:border-t-gray-700">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-5xl md:text-5xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        Mauritius Tech Meetups
                    </h1>
                    <p class="mt-4 text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        All the tech meetups from the many local community groups, in one place!
                    </p>
                </div>
            </section>

            <!-- Cards Section -->
            @foreach($meetups as $meetup)
                <section class="mt-12 w-full px-4 container mx-auto max-w-4xl">
                    <a href="{{ $meetup->registration }}" class="group relative block">
                        <div class="relative border border-gray-200 dark:border-neutral-700 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow bg-white bg-opacity-90 dark:bg-neutral-800 dark:bg-opacity-90">
                            <div class="flex justify-between items-center mb-6">
                                <span class="px-3 py-1 rounded-full bg-teal-600 text-white font-semibold uppercase text-sm tracking-wide">
                                    {{ $meetup->date->format('M d, Y') }}
                                </span>
                                <span class="px-3 py-1 rounded-full bg-gray-800 text-white font-semibold uppercase text-sm tracking-wide dark:bg-gray-600">
                                    {{ $meetup->community }}
                                </span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-3">
                                {{ $meetup->title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-5">
                                {{ Str::limit($meetup->abstract, 150) }}
                            </p>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600 dark:text-gray-300" viewBox="0 0 32 32">
                                        <path fill="currentColor" d="M16 18a5 5 0 1 1 5-5a5.006 5.006 0 0 1-5 5m0-8a3 3 0 1 0 3 3a3.003 3.003 0 0 0-3-3"/>
                                        <path fill="currentColor" d="m16 30l-8.436-9.949a35 35 0 0 1-.348-.451A10.9 10.9 0 0 1 5 13a11 11 0 0 1 22 0a10.9 10.9 0 0 1-2.215 6.597l-.001.003s-.3.394-.345.447ZM8.813 18.395s.233.308.286.374L16 26.908l6.91-8.15c.044-.055.278-.365.279-.366A8.9 8.9 0 0 0 25 13a9 9 0 1 0-18 0a8.9 8.9 0 0 0 1.813 5.395"/>
                                    </svg>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300 text-sm font-medium">
                                        {{ $meetup->location }}
                                    </span>
                                </div>
                                <span class="text-sm font-bold text-gray-600 dark:text-gray-300">
                                    #{{ $meetup->type }}
                                </span>
                            </div>
                        </div>
                    </a>
                </section>
            @endforeach
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 dark:bg-neutral-900 text-white py-6">
            <div class="container mx-auto text-center">
                <p class="text-sm md:text-base">
                    &copy; 2023. Built with care for Mauritius meetups.
                </p>
            </div>
        </footer>

        <!-- Theme Switcher Script -->
        <script>
            // Wait until the DOM is fully loaded before attaching event listeners
            document.addEventListener('DOMContentLoaded', function () {
                const themeToggleBtn = document.getElementById('theme-toggle');
                const userTheme = localStorage.getItem("theme");
                const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

                // Set theme on initial load
                if (userTheme === "dark" || (!userTheme && systemPrefersDark)) {
                    document.documentElement.classList.add("dark");
                } else {
                    document.documentElement.classList.remove("dark");
                }

                themeToggleBtn.addEventListener("click", () => {
                    document.documentElement.classList.toggle("dark");
                    // Save the preference in localStorage
                    if (document.documentElement.classList.contains("dark")) {
                        localStorage.setItem("theme", "dark");
                    } else {
                        localStorage.setItem("theme", "light");
                    }
                });
            });
        </script>
    </body>
</html>
