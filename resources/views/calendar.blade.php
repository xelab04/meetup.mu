@extends('layouts.main')

@php
    use App\Support\Communities;
    use Carbon\Carbon;

    $today = Carbon::today();
    $prevYear = $year - 1;
    $nextYear = $year + 1;

    // Build a date → dots map and a per-month event count.
    $dotsByDate = [];
    $countsByMonth = array_fill(1, 12, 0);
    foreach ($meetups as $m) {
        $iso = $m->date->format('Y-m-d');
        $c = Communities::get($m->community);
        $dotsByDate[$iso] ??= [];
        $dotsByDate[$iso][] = [
            'slug' => $m->community,
            'label' => $c['label'],
            'color' => $c['color'],
            'color_dark' => $c['color_dark'],
        ];
        $countsByMonth[(int) $m->date->format('n')]++;
    }

    $byMonth = $meetups->groupBy(fn ($m) => (int) $m->date->format('n'));
@endphp

@section('content')
<style>
    .cal-dot { background: var(--dot-c); }
    html.dark .cal-dot { background: var(--dot-cd); }
</style>

{{-- Hero --}}
<section class="max-w-7xl mx-auto px-5 md:px-10 pt-10 md:pt-14 pb-6 md:pb-9">
    <div class="flex flex-wrap items-center gap-x-6 gap-y-3">
        <h1 class="text-[36px] md:text-[58px] leading-[1] tracking-[-0.045em] font-medium">
            <span class="font-mono font-normal tracking-[-0.02em] text-island-primary">Calendar</span> {{ $year }}
        </h1>
        <div class="ml-auto flex items-center gap-1 text-[13px]">
            <a href="{{ route('calendar', ['y' => $prevYear]) }}"
               class="px-3 py-1.5 rounded-lg border border-island-rule hover:bg-island-card transition-colors tabular-nums">
                ‹ {{ $prevYear }}
            </a>
            @if ($year !== $today->year)
                <a href="{{ route('calendar') }}"
                   class="px-3 py-1.5 rounded-lg border border-island-rule hover:bg-island-card transition-colors">
                    Today
                </a>
            @endif
            <a href="{{ route('calendar', ['y' => $nextYear]) }}"
               class="px-3 py-1.5 rounded-lg border border-island-rule hover:bg-island-card transition-colors tabular-nums">
                {{ $nextYear }} ›
            </a>
        </div>
    </div>
    <p class="mt-4 md:mt-[18px] text-[14px] md:text-base text-island-muted max-w-[600px] leading-relaxed">
        {{ $meetups->count() }} meetup{{ $meetups->count() === 1 ? '' : 's' }} across {{ $year }}.
    </p>
</section>

{{-- Year grid --}}
<section class="max-w-7xl mx-auto px-5 md:px-10 pb-10">
    <div class="grid gap-4 md:gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach (range(1, 12) as $m)
            @php
                $monthDate = Carbon::create($year, $m, 1);
                $monthName = $monthDate->format('F');
                $daysInMonth = $monthDate->daysInMonth;
                $firstDay = (int) $monthDate->format('w');
                $monthCount = $countsByMonth[$m];
                $isCurrentMonth = $today->year === $year && $today->month === $m;
                $anchor = 'month-' . $m;
            @endphp
            <div class="bg-island-card border border-island-rule rounded-xl p-4 {{ $isCurrentMonth ? 'ring-1 ring-island-primary/40' : '' }}">
                <div class="flex items-baseline justify-between mb-3">
                    @if ($monthCount > 0)
                        <a href="#{{ $anchor }}" class="font-semibold text-[15px] hover:text-island-primary transition-colors">
                            {{ $monthName }}
                        </a>
                    @else
                        <div class="font-semibold text-[15px] text-island-muted">{{ $monthName }}</div>
                    @endif
                    <div class="text-[11px] text-island-muted tabular-nums">
                        @if ($monthCount > 0)
                            {{ $monthCount }} event{{ $monthCount === 1 ? '' : 's' }}
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-[2px] text-[10px] text-island-muted mb-1">
                    @foreach (['S','M','T','W','T','F','S'] as $d)
                        <div class="text-center py-1">{{ $d }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 gap-[2px]">
                    @for ($i = 0; $i < $firstDay; $i++)
                        <div></div>
                    @endfor
                    @for ($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $iso = sprintf('%d-%02d-%02d', $year, $m, $d);
                            $dayDots = $dotsByDate[$iso] ?? [];
                            $hasEvent = count($dayDots) > 0;
                            $isToday = $today->year === $year && $today->month === $m && $today->day === $d;
                            $uniqueDots = collect($dayDots)->unique('slug')->take(3);
                            $eventsLabel = count($dayDots) . ' event' . (count($dayDots) === 1 ? '' : 's');
                        @endphp
                        @if ($hasEvent)
                            <a href="#{{ $anchor }}"
                               class="group/day relative block text-center text-xs py-1 rounded-md bg-island-primary/10 text-island-primary font-semibold hover:bg-island-primary/20 transition-colors"
                               aria-label="{{ $eventsLabel }} on {{ $monthName }} {{ $d }}">
                                <span class="block leading-none">{{ $d }}</span>
                                <span class="mt-[5px] flex items-center justify-center gap-[3px] h-[6px]">
                                    @foreach ($uniqueDots as $dot)
                                        <span class="cal-dot block w-[6px] h-[6px] rounded-full" style="--dot-c: {{ $dot['color'] }}; --dot-cd: {{ $dot['color_dark'] }};"></span>
                                    @endforeach
                                </span>
                                <div class="pointer-events-none absolute z-20 bottom-[calc(100%+4px)] left-1/2 -translate-x-1/2 opacity-0 group-hover/day:opacity-100 transition-opacity bg-island-fg text-island-bg text-[11px] font-medium px-2 py-1.5 rounded-md shadow-lg whitespace-nowrap">
                                    @foreach (collect($dayDots)->unique('slug') as $dot)
                                        <div class="flex items-center gap-1.5 py-[1px]">
                                            <span class="cal-dot block w-[6px] h-[6px] rounded-full" style="--dot-c: {{ $dot['color'] }}; --dot-cd: {{ $dot['color_dark'] }};"></span>
                                            <span>{{ $dot['label'] }}</span>
                                        </div>
                                    @endforeach
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-4 border-transparent border-t-island-fg"></div>
                                </div>
                            </a>
                        @else
                            <div class="block text-center text-xs py-1 rounded-md
                                        {{ $isToday ? 'text-island-fg font-semibold border border-island-fg' : 'text-island-muted' }}">
                                <span class="block leading-none">{{ $d }}</span>
                                <span class="mt-[5px] block h-[6px]"></span>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- Event list, grouped by month --}}
<section class="max-w-7xl mx-auto px-5 md:px-10 pb-16 space-y-12">
    @forelse ($byMonth as $monthNum => $monthMeetups)
        @php
            $monthName = Carbon::create($year, $monthNum, 1)->format('F');
        @endphp
        <div id="month-{{ $monthNum }}" class="scroll-mt-24">
            <div class="flex items-baseline gap-3 mb-4 pb-2 border-b border-island-rule">
                <h2 class="text-[22px] md:text-[26px] font-semibold tracking-tight">{{ $monthName }}</h2>
                <span class="text-[12px] text-island-muted tabular-nums">
                    {{ $monthMeetups->count() }} event{{ $monthMeetups->count() === 1 ? '' : 's' }}
                </span>
            </div>
            <div class="grid gap-4 md:grid-cols-[repeat(auto-fill,minmax(300px,1fr))]">
                @foreach ($monthMeetups as $meetup)
                    <x-event-card :meetup="$meetup" />
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-16 px-5 bg-island-card border border-dashed border-island-rule rounded-xl">
            <div class="font-serif italic text-[22px] text-island-fg mb-1.5">No events in {{ $year }}.</div>
            <div class="text-[13px] text-island-muted">Try another year with the arrows above.</div>
        </div>
    @endforelse
</section>
@endsection
