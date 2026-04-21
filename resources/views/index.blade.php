@extends('layouts.main')

@section('content')
    @php
        // Combine today's + upcoming; dedupe by id in case today's show up twice
        $merged = collect($todays ?? [])->concat($meetups ?? [])->unique('id')->values();
        $upcomingCount = $merged->count();
        $pastCount = \App\Models\Meetup::where('date', '<=', \Carbon\Carbon::now())->count();
        $tense = 'upcoming';
        $meetups = $merged;
    @endphp

    @include('partials.island-page')
@endsection

@push('scripts')
    @include('partials.island-scripts')
@endpush
