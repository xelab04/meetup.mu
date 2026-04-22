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

    <x-sidebar :tense="$tense"
               :upcoming-count="$upcomingCount"
               :past-count="$pastCount"
               :event-dots="$eventDots"
               :today-iso="$todayIso" />

    <div>
        @if ($meetups->isEmpty())
            <div class="text-center py-16 px-5 bg-island-card border border-dashed border-island-rule rounded-xl">
                <div class="font-serif italic text-[22px] text-island-fg mb-1.5">Nothing to show.</div>
                <div class="text-[13px] text-island-muted">
                    @if ($tense === 'upcoming')
                        No upcoming events scheduled yet. Check back soon.
                    @else
                        No past events on record.
                    @endif
                </div>
            </div>
        @else
            <div id="event-grid" class="grid gap-4 md:grid-cols-[repeat(auto-fill,minmax(300px,1fr))]">
                @foreach ($meetups as $meetup)
                    <x-event-card :meetup="$meetup" />
                @endforeach
            </div>

            <div id="event-grid-empty" class="hidden text-center py-16 px-5 bg-island-card border border-dashed border-island-rule rounded-xl">
                <div class="font-serif italic text-[22px] text-island-fg mb-1.5">Nothing to show.</div>
                <div class="text-[13px] text-island-muted">Re-enable a group in the sidebar to see events.</div>
            </div>
        @endif
    </div>
</section>
