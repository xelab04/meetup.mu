@extends('layouts.main')

<!-- for the record, I hate that this is entirely AI generated -->

@section('today')
<section class="w-full px-4 py-8">
    <div class="container mx-auto max-w-4xl">
        <!-- Meetup Detail Card -->
        <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl shadow-xl p-8 mb-8">
            <!-- Header with Date and Community -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <span class="px-4 py-2 rounded-full bg-teal-600 text-white font-semibold uppercase text-sm tracking-wide">
                    {{ $meetup->date->format('F d, Y') }}
                </span>
                <a href="{{ route('community', $meetup->community) }}" class="community-website">
                    <span class="px-4 py-2 rounded-full bg-gray-800 text-white font-semibold uppercase text-sm tracking-wide dark:bg-gray-600 hover:text-blue-400 hover:underline duration-300">
                        {{ $meetup->community }}
                    </span>
                </a>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                {{ $meetup->title }}
            </h1>

            <!-- Event Type Badge -->
            <div class="mb-6">
                <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-100 rounded-md font-medium">
                    {{ ucfirst($meetup->type) }}
                </span>
            </div>

            <!-- Abstract/Description -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">About this event</h2>
                <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                    <p class="whitespace-pre-line">{!! str($meetup->abstract)->markdown()->sanitizeHtml() !!}</p>
                </div>
            </div>

            <!-- Location Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Location</h2>
                <div class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600 dark:text-gray-300 mt-0.5" viewBox="0 0 32 32">
                        <path fill="currentColor" d="M16 18a5 5 0 1 1 5-5a5.006 5.006 0 0 1-5 5m0-8a3 3 0 1 0 3 3a3.003 3.003 0 0 0-3-3"/>
                        <path fill="currentColor" d="m16 30l-8.436-9.949a35 35 0 0 1-.348-.451A10.9 10.9 0 0 1 5 13a11 11 0 0 1 22 0a10.9 10.9 0 0 1-2.215 6.597l-.001.003s-.3.394-.345.447ZM8.813 18.395s.233.308.286.374L16 26.908l6.91-8.15c.044-.055.278-.365.279-.366A8.9 8.9 0 0 0 25 13a9 9 0 1 0-18 0a8.9 8.9 0 0 0 1.813 5.395"/>
                    </svg>
                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                        {{ $meetup->location }}
                    </span>
                </div>
            </div>

            <!-- Capacity Information (if available) -->
            @if(isset($meetup->capacity) && $meetup->capacity > 0)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-3">Capacity</h2>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600 dark:text-gray-300" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M16 17v2H2v-2s0-4 7-4s7 4 7 4m-3.5-9.5A3.5 3.5 0 1 0 9 11a3.5 3.5 0 0 0 3.5-3.5m3.44 5.5A5.32 5.32 0 0 1 18 17v2h4v-2s0-3.63-6.06-4M15 4a3.39 3.39 0 0 0-1.93.59a5 5 0 0 1 0 5.82A3.39 3.39 0 0 0 15 11a3.5 3.5 0 0 0 0-7"/>
                    </svg>
                    <span class="ml-3 text-gray-700 dark:text-gray-300">
                        {{$rsvpCount}} / {{ $meetup->capacity }}
                    </span>
                </div>
            </div>
            @endif

            <!-- Registration Button -->
            <div class="mt-10">
                <a href="{{ $meetup->registration }}" target="_blank" class="inline-block px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-medium text-lg rounded-lg transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50">
                    Register for this event
                </a>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <a href="/" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white font-medium rounded-lg text-center transition duration-300">
                ← Back to all meetups
            </a>
            @if($meetup->community)
            <a href="{{ route('community', $meetup->community) }}" class="px-6 py-3 bg-gray-800 hover:bg-gray-900 dark:bg-gray-600 dark:hover:bg-gray-500 text-white font-medium rounded-lg text-center transition duration-300">
                View all {{ $meetup->community }} events →
            </a>
            @endif
        </div>
    </div>
</section>
@endsection
