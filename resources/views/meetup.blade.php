@extends('layouts.main')

@section('content')
    @php
        $community = \App\Support\Communities::get($meetup->community);
        $color = $community['color'];
        $monthShort = strtoupper($meetup->date->format('M'));
        $day = $meetup->date->format('j');
        $seatsLeft = max(0, ($meetup->capacity ?? 0) - $rsvpCount);
        $isFull = $meetup->capacity && $rsvpCount >= $meetup->capacity;
    @endphp

    <section class="max-w-4xl mx-auto px-5 md:px-10 pt-10 md:pt-14 pb-16">

        @if (session('message'))
            <div class="mb-6 rounded-xl border border-island-primary/40 bg-island-primary/5 px-4 py-3 text-island-primary text-sm text-center font-medium">
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-island-card border border-island-rule rounded-2xl p-6 md:p-10">

            <div class="flex items-start gap-5 mb-6">
                <div class="w-[72px] shrink-0 rounded-[10px] overflow-hidden bg-island-card"
                     style="border: 1.5px solid {{ $color }};">
                    <div class="text-center text-[10px] uppercase tracking-[0.12em] font-bold text-white py-1.5" style="background: {{ $color }};">{{ $monthShort }}</div>
                    <div class="text-center text-[32px] font-semibold leading-none py-2 text-island-fg tabular-nums">{{ $day }}</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm text-island-muted">
                        {{ $meetup->date->format('l, j F Y · H:i') }}
                    </div>
                    <a href="{{ route('community', $meetup->community) }}"
                       class="inline-flex items-center gap-2 mt-1 text-island-fg hover:opacity-80">
                        <span class="w-[22px] h-[22px] rounded-[5px] flex items-center justify-center text-white text-[10px] font-bold tracking-wide" style="background: {{ $color }};">
                            {{ $community['mono'] }}
                        </span>
                        <span class="text-[14px] font-semibold">{{ $community['label'] }}</span>
                    </a>
                </div>
            </div>

            <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-island-fg leading-tight mb-4">
                {{ $meetup->title }}
            </h1>

            @if ($meetup->type)
                <div class="mb-6">
                    <span class="inline-block px-2.5 py-1 text-[12px] font-medium rounded-md bg-island-bg border border-island-rule text-island-muted">
                        {{ ucfirst($meetup->type) }}
                    </span>
                </div>
            @endif

            <div class="mb-8">
                <h2 class="text-[11px] uppercase tracking-[0.12em] text-island-muted font-semibold mb-2">About</h2>
                <div class="prose dark:prose-invert max-w-none text-island-fg">
                    {!! str($meetup->abstract)->markdown()->sanitizeHtml() !!}
                </div>
            </div>

            @if ($meetup->location)
                <div class="mb-8">
                    <h2 class="text-[11px] uppercase tracking-[0.12em] text-island-muted font-semibold mb-2">Location</h2>
                    <div class="flex items-center gap-2 text-island-fg">
                        <svg width="14" height="14" viewBox="0 0 12 12" fill="currentColor" class="opacity-70">
                            <path d="M6 0C3.8 0 2 1.8 2 4c0 3 4 8 4 8s4-5 4-8c0-2.2-1.8-4-4-4zm0 5.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                        </svg>
                        {{ $meetup->location }}
                    </div>
                </div>
            @endif

            @if ($meetup->capacity)
                <div class="mb-8">
                    <h2 class="text-[11px] uppercase tracking-[0.12em] text-island-muted font-semibold mb-2">Capacity</h2>
                    <div class="text-island-fg">
                        {{ $rsvpCount }} / {{ $meetup->capacity }}
                        @if (!$isFull)
                            <span class="text-island-muted text-sm">— {{ $seatsLeft }} seats left</span>
                        @endif
                    </div>
                </div>
            @endif

            <form action="{{ route('rsvp', $meetup->id) }}" method="POST">
                @csrf
                @method('POST')
                @if ($isFull)
                    <button type="submit" disabled
                            class="px-5 py-3 rounded-lg bg-island-rule text-island-muted font-medium opacity-60 cursor-not-allowed">
                        All seats taken
                    </button>
                @else
                    @php
                        $hasRsvp = Auth::check() && Auth::user()->rsvps()->where('event_id', $meetup->id)->exists();
                    @endphp
                    <button type="submit"
                            class="px-5 py-3 rounded-lg bg-island-fg text-island-bg font-medium hover:opacity-90 transition-opacity">
                        {{ $hasRsvp ? 'Un-RSVP' : 'Register for this event' }}
                    </button>
                @endif
            </form>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row justify-between gap-3">
            <a href="{{ route('home') }}"
               class="px-4 py-2.5 rounded-lg border border-island-rule text-island-fg hover:bg-island-card transition-colors text-center text-sm">
                ← Back to all meetups
            </a>
            @if ($meetup->community)
                <a href="{{ route('community', $meetup->community) }}"
                   class="px-4 py-2.5 rounded-lg bg-island-fg text-island-bg hover:opacity-90 transition-opacity text-center text-sm font-medium">
                    View all {{ $community['label'] }} events →
                </a>
            @endif
        </div>
    </section>
@endsection
