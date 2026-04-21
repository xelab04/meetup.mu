@extends('layouts.main')

@section('content')
    @php
        $upcomingCount = $meetups->count();
        $pastCount = \App\Models\Meetup::where('community', $community)
            ->where('date', '<=', \Carbon\Carbon::now())
            ->count();
        $tense = 'upcoming';
    @endphp

    @include('partials.island-page')
@endsection

@push('scripts')
    @include('partials.island-scripts')
@endpush
