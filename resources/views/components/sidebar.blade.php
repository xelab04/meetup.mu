@props(['tense' => 'upcoming', 'upcomingCount' => 0, 'pastCount' => 0, 'eventDots' => [], 'todayIso' => null])

@php
    $communities = \App\Support\Communities::all();
    $eventDotsJson = json_encode((object) $eventDots);
@endphp

<style>
    .cal-dot { background: var(--dot-c); }
    html.dark .cal-dot { background: var(--dot-cd); }
</style>

<aside class="md:sticky md:top-24 space-y-4 self-start">

    {{-- Upcoming / Past toggle --}}
    <div class="flex bg-island-card border border-island-rule rounded-xl p-1">
        <a href="{{ route('home') }}"
           class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg text-sm font-medium transition-colors
                  {{ $tense === 'upcoming' ? 'bg-island-fg text-island-bg' : 'text-island-fg hover:bg-island-bg' }}">
            Upcoming
            <span class="text-[11px] tabular-nums px-1.5 py-[1px] rounded-lg
                         {{ $tense === 'upcoming' ? 'bg-white/15 text-current' : 'bg-black/5 dark:bg-white/10' }}">
                {{ $upcomingCount }}
            </span>
        </a>
        <a href="{{ route('past') }}"
           class="flex-1 flex items-center justify-center gap-1.5 py-2 px-3 rounded-lg text-sm font-medium transition-colors
                  {{ $tense === 'past' ? 'bg-island-fg text-island-bg' : 'text-island-fg hover:bg-island-bg' }}">
            Past
            <span class="text-[11px] tabular-nums px-1.5 py-[1px] rounded-lg
                         {{ $tense === 'past' ? 'bg-white/15 text-current' : 'bg-black/5 dark:bg-white/10' }}">
                {{ $pastCount }}
            </span>
        </a>
    </div>

    {{-- Mini calendar --}}
    <div x-data="islandCalendar({ today: '{{ $todayIso }}', eventDots: {{ $eventDotsJson }} })"
         class="bg-island-card border border-island-rule rounded-xl p-4">
        <div class="flex justify-between items-center mb-3">
            <div class="font-semibold text-[15px]">
                <span x-text="monthLabel"></span>
                <span class="text-island-muted font-normal" x-text="year"></span>
            </div>
            <div class="flex gap-1 items-center">
                <button type="button" @click="goToday()"
                        x-show="viewYear !== today.getFullYear() || viewMonth !== today.getMonth()"
                        class="mr-1 px-1.5 py-0.5 text-[11px] text-island-muted hover:text-island-fg">today</button>
                <button type="button" @click="prev()"
                        class="w-6 h-6 border border-island-rule rounded-md text-[10px] leading-none hover:bg-island-bg">‹</button>
                <button type="button" @click="next()"
                        class="w-6 h-6 border border-island-rule rounded-md text-[10px] leading-none hover:bg-island-bg">›</button>
            </div>
        </div>
        <div class="grid grid-cols-7 gap-[2px] text-[10px] text-island-muted mb-1">
            <template x-for="d in ['S','M','T','W','T','F','S']"><div class="text-center py-1" x-text="d"></div></template>
        </div>
        <div class="grid grid-cols-7 gap-[2px]">
            <template x-for="blank in emptyCells"><div></div></template>
            <template x-for="day in days" :key="day">
                <div class="group relative text-center text-xs py-1.5 rounded-md"
                     :class="{
                         'bg-island-primary/10 text-island-primary font-semibold cursor-pointer': hasEvent(day),
                         'text-island-fg font-semibold border border-island-fg': isToday(day) && !hasEvent(day),
                         'text-island-muted': !hasEvent(day) && !isToday(day),
                     }">
                    <span x-text="day"></span>
                    <span x-show="hasEvent(day)" class="absolute bottom-[3px] left-1/2 -translate-x-1/2 flex gap-[2px]">
                        <template x-for="(dot, i) in dotsForDay(day)" :key="i">
                            <span class="cal-dot w-[3px] h-[3px] rounded-full" :style="`--dot-c: ${dot.c}; --dot-cd: ${dot.cd};`"></span>
                        </template>
                    </span>

                    {{-- Tooltip --}}
                    <div x-show="hasEvent(day)"
                         class="absolute z-20 bottom-[calc(100%+4px)] left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity bg-island-fg text-island-bg text-[11px] font-medium px-2 py-1.5 rounded-md shadow-lg whitespace-nowrap">
                        <template x-for="(dot, i) in dotsForDay(day)" :key="i">
                            <div class="flex items-center gap-1.5 py-[1px]">
                                <span class="cal-dot w-[6px] h-[6px] rounded-full" :style="`--dot-c: ${dot.c}; --dot-cd: ${dot.cd};`"></span>
                                <span x-text="dot.n"></span>
                            </div>
                        </template>
                        <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-4 border-transparent border-t-island-fg"></div>
                    </div>
                </div>
            </template>
        </div>
        <a href="{{ route('calendar') }}"
           class="mt-3 pt-3 block border-t border-dashed border-island-rule text-[12px] text-island-muted hover:text-island-fg text-center">
            Open full calendar →
        </a>
    </div>

    {{-- Group filter --}}
    <div class="bg-island-card border border-island-rule rounded-xl p-4"
         x-data="islandGroupFilter()">
        <div class="flex justify-between items-center mb-2.5">
            <div class="text-[11px] uppercase tracking-[0.1em] text-island-muted font-semibold">
                Groups
                <span class="font-normal ml-1" x-text="`(${active.size}/${total})`"></span>
            </div>
            <button type="button" @click="toggleAll()"
                    class="text-[11px] text-island-primary font-medium">
                <span x-text="active.size === total ? 'Clear' : 'All'"></span>
            </button>
        </div>
        <div class="max-h-[340px] overflow-y-auto -mx-1 px-1">
            @foreach ($communities as $slug => $c)
                <div class="relative group" data-group="{{ $slug }}">
                    <button type="button"
                            @click="toggle('{{ $slug }}')"
                            :class="active.has('{{ $slug }}') ? 'opacity-100' : 'opacity-55'"
                            class="w-full flex items-center gap-2.5 py-[7px] pr-16 text-left transition-opacity">
                        <span class="w-[22px] h-[22px] rounded-md shrink-0 flex items-center justify-center text-[10px] font-bold tracking-wide"
                              :style="active.has('{{ $slug }}')
                                ? 'background: {{ $c['color'] }}; color: #fff; border: 1.5px solid {{ $c['color'] }};'
                                : 'background: transparent; color: {{ $c['color'] }}; border: 1.5px solid {{ $c['color'] }};'">
                            {{ $c['mono'] }}
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block text-[13px] font-medium truncate">{{ $c['label'] }}</span>
                            <span class="block text-[11px] text-island-muted truncate">{{ $c['topic'] }}</span>
                        </span>
                    </button>
                    <button type="button"
                            @click.stop="only('{{ $slug }}')"
                            class="absolute right-0 top-1/2 -translate-y-1/2 px-1.5 py-0.5 text-[11px] text-island-muted hover:text-island-fg opacity-0 group-hover:opacity-100 focus-visible:opacity-100 transition-opacity">
                        only
                    </button>
                </div>
            @endforeach
        </div>
        <a href="https://github.com/xelab04/meetups/issues/new" target="_blank" rel="noopener"
           class="mt-3 pt-3 block border-t border-dashed border-island-rule text-[12px] text-island-muted hover:text-island-fg">
            + Suggest a group
        </a>
    </div>
</aside>
