<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Mauritius Meetups</title>
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            /* Custom styles (if needed) */
            .hero-section {
                background-image: url('/images/so-white.png');
                background-repeat: repeat;
            }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-800 font-sans">
        
        <!-- Top Gradient Strip -->
        <div class="h-2 w-full" style="background: linear-gradient(135deg, #EA2839 0%, #151F6D 25%, #FFD500 50%, #00A551 75%, #EA2839 100%);"></div>
        
        <main class="py-20 hero-section">
            <!-- Hero Section -->
            <section class="py-[20vh] px-4 bg-white shadow-sm border-t border-t-gray-100 ">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-5xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                        Mauritius Tech Meetups
                    </h1>
                    <p class="mt-4 text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto">
                        All the tech meetups from the many local community groups, in one place!
                    </p>
                </div>
            </section>

            <!-- Cards Section -->
            @foreach($meetups as $meetup)
                <section class="mt-12 w-full px-4 container mx-auto max-w-4xl">
                    <a href="{{ $meetup->registration }}" class="group relative block">
                        <div
                            class="relative border border-gray-200 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow bg-white bg-opacity-90"
                        >
                            <div class="flex justify-between items-center mb-6">
                                <span class="px-3 py-1 rounded-full bg-teal-600 text-white font-semibold uppercase text-sm tracking-wide">
                                    {{ $meetup->date->format('M d, Y') }}
                                </span>
                                <span class="px-3 py-1 rounded-full bg-gray-800 text-white font-semibold uppercase text-sm tracking-wide">
                                    {{ $meetup->community }}
                                </span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
                                {{ $meetup->title }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed mb-5">
                                {{ Str::limit($meetup->abstract, 150) }}
                            </p>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" viewBox="0 0 32 32">
                                        <path fill="currentColor" d="M16 18a5 5 0 1 1 5-5a5.006 5.006 0 0 1-5 5m0-8a3 3 0 1 0 3 3a3.003 3.003 0 0 0-3-3"/>
                                        <path fill="currentColor" d="m16 30l-8.436-9.949a35 35 0 0 1-.348-.451A10.9 10.9 0 0 1 5 13a11 11 0 0 1 22 0a10.9 10.9 0 0 1-2.215 6.597l-.001.003s-.3.394-.345.447ZM8.813 18.395s.233.308.286.374L16 26.908l6.91-8.15c.044-.055.278-.365.279-.366A8.9 8.9 0 0 0 25 13a9 9 0 1 0-18 0a8.9 8.9 0 0 0 1.813 5.395"/>
                                    </svg>
                                    <span class="ml-2 text-gray-700 text-sm font-medium">
                                        {{ $meetup->location }}
                                    </span>
                                </div>
                                
                                <span class="text-sm font-bold text-gray-600">#{{ $meetup->type }}</span>
                            </div>
                        </div>
                    </a>
                </section>
            @endforeach
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6">
            <div class="container mx-auto text-center">
                <p class="text-sm md:text-base">
                Made by Alex, who is [visibly] not a frontend dev.
                </p>
            </div>
        </footer>

        <script>
            // Custom JavaScript (if needed)
        </script>
    </body>
</html>
