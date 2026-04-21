@extends('layouts.main')

@section('content')
    @php
        $pastCount = $meetups->count();
        $upcomingCount = \App\Models\Meetup::where('community', $community)
            ->where('date', '>=', \Carbon\Carbon::now())
            ->count();
        $tense = 'past';
    @endphp

    @include('partials.island-page')
@endsection

@push('scripts')
    @include('partials.island-scripts')
@endpush
