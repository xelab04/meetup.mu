{{-- Hero --}}
<section class="max-w-7xl mx-auto px-5 md:px-10 pt-10 md:pt-14 pb-6 md:pb-9">
    <h1 class="text-[36px] md:text-[58px] leading-[1] tracking-[-0.045em] font-medium">
        Mauritius Tech <span class="font-mono font-normal tracking-[-0.02em] text-island-primary">Meetups</span>
    </h1>
    <p class="mt-4 md:mt-[18px] text-[14px] md:text-base text-island-muted max-w-[600px] leading-relaxed">
        All the tech meetups from the many local community groups, in one place!
    </p>
</section>

{{-- Main layout --}}
<section class="max-w-7xl mx-auto px-5 md:px-10 pb-10 grid gap-6 md:gap-10 md:grid-cols-[280px_minmax(0,1fr)] items-start">

    <div x-data="{ showRecent: false }">
        @if ($upcoming->isEmpty() && $recent->isEmpty())
            <div class="text-center py-16 px-5 bg-island-card border border-dashed border-island-rule rounded-xl">
                <div class="font-serif italic text-[22px] text-island-fg mb-1.5">Nothing to show.</div>
                <div class="text-[13px] text-island-muted">No upcoming events scheduled yet. Check back soon.</div>
            </div>
        @else
            @if ($recent->isNotEmpty())
                {{-- Recent toggle --}}
                <button type="button"
                        @click="showRecent = !showRecent"
                        class="mb-4 inline-flex items-center gap-1.5 text-[12px] text-island-muted hover:text-island-fg transition-colors">
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.4"
                         class="transition-transform"
                         :class="showRecent ? 'rotate-180' : ''">
                        <path d="M2 4l3 3 3-3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span x-text="showRecent ? 'Hide recent events' : 'Show recent events'"></span>
                    <span class="text-island-muted/70">· last 2 weeks · {{ $recent->count() }}</span>
                </button>

                {{-- Recent events, grouped by month --}}
                <div class="grid grid-rows-[0fr] opacity-0 transition-[grid-template-rows,opacity] duration-300 ease-out"
                     :class="showRecent && '!grid-rows-[1fr] !opacity-100'">
                    <div class="min-h-0 overflow-hidden">
                        <div class="space-y-8 pb-8">
                            @foreach ($recent->groupBy(fn ($m) => $m->date->format('Y-m')) as $monthMeetups)
                                <div data-month-group>
                                    <div class="flex items-baseline gap-3 mb-4 pb-2 border-b border-island-rule">
                                        <h2 class="text-[18px] md:text-[20px] font-semibold tracking-tight text-island-fg">
                                            {{ $monthMeetups->first()->date->format('F Y') }}
                                        </h2>
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
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Upcoming, grouped by month --}}
            <div id="event-grid" class="space-y-8">
                @foreach ($upcoming->groupBy(fn ($m) => $m->date->format('Y-m')) as $monthMeetups)
                    <div data-month-group>
                        <div class="flex items-baseline gap-3 mb-4 pb-2 border-b border-island-rule">
                            <h2 class="text-[18px] md:text-[20px] font-semibold tracking-tight text-island-fg">
                                {{ $monthMeetups->first()->date->format('F Y') }}
                            </h2>
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
                @endforeach
            </div>

            <div id="event-grid-empty" class="hidden text-center py-16 px-5 bg-island-card border border-dashed border-island-rule rounded-xl">
                <div class="font-serif italic text-[22px] text-island-fg mb-1.5">Nothing to show.</div>
                <div class="text-[13px] text-island-muted">Re-enable a group in the sidebar to see events.</div>
            </div>
        @endif
    </div>

    <x-sidebar :event-dots="$eventDots" :today-iso="$todayIso" class="md:order-first" />
</section>
