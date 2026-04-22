@props(['meetup'])

@php
    $community = \App\Support\Communities::get($meetup->community);
    $date = $meetup->date;
    $isToday = $date->isToday();
    $monthShort = strtoupper($date->format('M'));
    $day = $date->format('j');
    $dayOfWeek = $date->format('D');
    $time = $date->format('H:i');
    $color = $community['color'];
    $colorDark = $community['color_dark'];
    $hasRsvpLink = !empty($meetup->registration);
    $blurb = $meetup->abstract ? \Illuminate\Support\Str::limit($meetup->abstract, 140) : null;
    $venue = $meetup->location ?: 'Location TBA';

    // ics data for client-side calendar link
    $icsStart = $date->format('Ymd\THis');
    $icsEnd = $date->copy()->addHours(2)->format('Ymd\THis');
    $calTitle = $meetup->title;
    $calDesc = $blurb ?? '';
    $calLoc = $venue;
    $googleUrl = 'https://www.google.com/calendar/render?action=TEMPLATE'
        . '&text=' . urlencode($calTitle)
        . '&dates=' . $icsStart . '/' . $icsEnd
        . '&details=' . urlencode($calDesc)
        . '&location=' . urlencode($calLoc);
    $outlookUrl = 'https://outlook.live.com/calendar/0/deeplink/compose?path=%2Fcalendar%2Faction%2Fcompose&rru=addevent'
        . '&subject=' . urlencode($calTitle)
        . '&startdt=' . urlencode($date->toIso8601String())
        . '&enddt=' . urlencode($date->copy()->addHours(2)->toIso8601String())
        . '&body=' . urlencode($calDesc)
        . '&location=' . urlencode($calLoc);
@endphp

@php
    $titleHref = $hasRsvpLink ? $meetup->registration : route('meetup', $meetup->id);
    $titleTarget = $hasRsvpLink ? '_blank' : '_self';
    $titleRel = $hasRsvpLink ? 'noopener' : null;
@endphp

<article data-event-card data-group="{{ $meetup->community }}"
         class="group/card relative flex flex-col rounded-2xl p-5 md:p-[22px] overflow-hidden bg-island-card dark:bg-island-card-dark border border-island-rule dark:border-island-rule-dark hover:border-island-fg/50 dark:hover:border-island-fg-dark/50 hover:shadow-md transition-all duration-150">

    {{-- Full-card click target (sits below other interactive elements) --}}
    <a href="{{ $titleHref }}" target="{{ $titleTarget }}" @if($titleRel) rel="{{ $titleRel }}" @endif
       aria-label="{{ $meetup->title }}"
       class="absolute inset-0 z-0"></a>

    <div class="relative z-20 flex items-start gap-3 mb-3.5">
        {{-- Date badge (click to add to calendar) --}}
        <div class="relative group/date"
             x-data="{ open: false }"
             @click.outside="open = false">
            <button type="button"
                    @click="open = !open"
                    class="block w-[60px] shrink-0 rounded-[10px] overflow-hidden bg-island-bg dark:bg-island-bg-dark border border-island-rule dark:border-island-rule-dark hover:border-island-primary dark:hover:border-island-primary-dark transition-colors"
                    aria-haspopup="menu"
                    :aria-expanded="open">
                <span class="block text-center text-[10px] uppercase tracking-[0.12em] font-bold py-1 bg-island-rule/60 dark:bg-white/[0.08] text-island-muted dark:text-island-fg-dark/75">
                    {{ $monthShort }}
                </span>
                <span class="block text-center text-[26px] font-semibold leading-none py-2 text-island-fg dark:text-island-fg-dark tabular-nums">
                    {{ $day }}
                </span>
            </button>

            {{-- Tooltip --}}
            <div x-show="!open"
                 class="pointer-events-none absolute left-full top-1/2 -translate-y-1/2 ml-1.5 opacity-0 group-hover/date:opacity-100 transition-opacity z-10 bg-island-fg dark:bg-island-fg-dark text-island-bg dark:text-island-bg-dark text-[11px] font-medium px-2 py-1 rounded-md whitespace-nowrap">
                Add to Calendar
            </div>

            {{-- Calendar dropdown --}}
            <div x-show="open" x-cloak x-transition.opacity
                 class="absolute top-full left-0 mt-1.5 w-44 bg-island-bg dark:bg-island-bg-dark border border-island-rule dark:border-island-rule-dark rounded-[10px] shadow-lg p-1 z-20">
                <a href="{{ $googleUrl }}" target="_blank" rel="noopener"
                   @click="open = false"
                   class="block px-3 py-2 text-[13px] rounded-md text-island-fg dark:text-island-fg-dark hover:bg-island-card dark:hover:bg-island-card-dark">Google Calendar</a>
                <button type="button"
                        @click="downloadIcs(); open = false"
                        data-ics-title="{{ $calTitle }}"
                        data-ics-start="{{ $icsStart }}"
                        data-ics-end="{{ $icsEnd }}"
                        data-ics-loc="{{ $calLoc }}"
                        data-ics-desc="{{ $calDesc }}"
                        class="block w-full text-left px-3 py-2 text-[13px] rounded-md text-island-fg dark:text-island-fg-dark hover:bg-island-card dark:hover:bg-island-card-dark">
                    Apple Calendar
                </button>
                <a href="{{ $outlookUrl }}" target="_blank" rel="noopener"
                   @click="open = false"
                   class="block px-3 py-2 text-[13px] rounded-md text-island-fg dark:text-island-fg-dark hover:bg-island-card dark:hover:bg-island-card-dark">Outlook</a>
                <button type="button"
                        @click="downloadIcs(); open = false"
                        data-ics-title="{{ $calTitle }}"
                        data-ics-start="{{ $icsStart }}"
                        data-ics-end="{{ $icsEnd }}"
                        data-ics-loc="{{ $calLoc }}"
                        data-ics-desc="{{ $calDesc }}"
                        class="block w-full text-left px-3 py-2 text-[13px] rounded-md text-island-fg dark:text-island-fg-dark hover:bg-island-card dark:hover:bg-island-card-dark">
                    Download .ics
                </button>
            </div>
        </div>

        {{-- Community mono + label (top-right) --}}
        <a href="{{ route('community', $meetup->community) }}"
           class="relative z-10 flex-1 min-w-0 flex items-center justify-end gap-2 no-underline text-island-fg dark:text-island-fg-dark hover:opacity-80 transition-opacity">
            <span class="text-[13px] font-semibold truncate text-right">{{ $community['label'] }}</span>
            <span class="w-[22px] h-[22px] shrink-0 rounded-[5px] flex items-center justify-center text-white text-[10px] font-bold tracking-wide"
                  style="background: {{ $color }};">
                {{ $community['mono'] }}
            </span>
        </a>
    </div>

    {{-- Title --}}
    <h3 class="text-[18px] font-semibold tracking-tight leading-tight mb-2 text-island-fg dark:text-island-fg-dark">
        {{ $meetup->title }}
    </h3>

    {{-- Blurb --}}
    @if ($blurb)
        <p class="font-serif italic text-[15px] text-island-muted dark:text-island-muted-dark leading-relaxed mb-3.5">
            &ldquo;{{ $blurb }}&rdquo;
        </p>
    @endif

    {{-- Meta row: day+time, venue, RSVP count --}}
    <div class="mt-auto pt-3 flex items-center flex-wrap gap-2 text-[12px] text-island-muted dark:text-island-muted-dark">
        @if ($isToday)
            <span class="inline-flex items-center gap-1 px-2 py-[2px] rounded-md text-[11px] font-semibold uppercase tracking-[0.08em] bg-island-today dark:bg-island-today-dark text-white shadow-sm shadow-island-today/30 dark:shadow-island-today-dark/40">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                Today
            </span>
            <span>{{ $time }}</span>
        @else
            <svg width="12" height="12" viewBox="0 0 12 12" class="shrink-0" fill="none" stroke="currentColor" stroke-width="1.2">
                <circle cx="6" cy="6" r="5"/><path d="M6 1.5v4.5L9 7.5" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <span>{{ $dayOfWeek }} · {{ $time }}</span>
        @endif
        <span class="opacity-50">·</span>
        <svg width="12" height="12" viewBox="0 0 12 12" class="shrink-0" fill="currentColor">
            <path d="M6 0C3.8 0 2 1.8 2 4c0 3 4 8 4 8s4-5 4-8c0-2.2-1.8-4-4-4zm0 5.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
        </svg>
        <span class="truncate max-w-[220px]">{{ $venue }}</span>
        @if ($meetup->rsvp_count > 0)
            <span class="opacity-50">·</span>
            <span>{{ $meetup->rsvp_count }} going</span>
        @endif
    </div>

</article>
