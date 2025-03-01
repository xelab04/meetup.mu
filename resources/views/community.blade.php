@extends('layouts.main')

@section('nav')
    <section class="mt-12 w-full px-4 container mx-auto max-w-4xl group hover:scale-105 duration-300">
        <div class="relative border border-gray-200 dark:border-neutral-700 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-shadow bg-white bg-opacity-90 dark:bg-neutral-800 dark:bg-opacity-90 text-gray-900 dark:text-white dark:hover:text-blue-500">
            <h3 class="text-2xl md:text-3xl font-bold mb-3 text-center">
                View Past Meetups
            </h3>
        <a href="{{ route('past-community', $community) }}" class="absolute inset-0 z-0" aria-label="Past Meetups"></a>
        </div>
    </section>
@endsection
