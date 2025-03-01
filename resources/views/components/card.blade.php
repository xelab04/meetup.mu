@props(['meetup'])

<section class="mt-12 w-full px-4 container mx-auto max-w-4xl group hover:scale-105 duration-300">
    <div class="relative border border-gray-200 dark:border-neutral-700 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow bg-white bg-opacity-90 dark:bg-neutral-800 dark:bg-opacity-90">
        <div class="flex justify-between items-center mb-6">
            <span class="px-3 py-1 rounded-full bg-teal-600 text-white font-semibold uppercase text-sm tracking-wide">
                {{ $meetup->date->format('M d, Y') }}
            </span>
            <a href="{{ route('community', $meetup->community) }}" class="community-website z-10 relative" target="_blank" onclick="event.stopPropagation()">
                <span class="px-3 py-1 rounded-full bg-gray-800 text-white font-semibold uppercase text-sm tracking-wide dark:bg-gray-600 hover:text-blue-400 hover:underline duration-300">
                    {{ $meetup->community }}
                </span>
            </a>
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
                {{ $meetup->type }}
            </span>
        </div>
        <a href="{{ $meetup->registration }}" class="absolute inset-0 z-0" target="_blank" aria-label="Learn more about Devcon"></a>
    </div>
</section>
