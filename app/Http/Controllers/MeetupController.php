<?php

namespace App\Http\Controllers;

use App\Models\Meetup;
use App\Support\Communities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MeetupController extends Controller
{
    public function home()
    {
        $meetups = Cache::remember("meetups_home", 600, function () {
            return Meetup::withCount('rsvps')
                ->where("date", ">=", Carbon::today())
                ->orderBy("date", "asc")
                ->get();
        });

        return $this->renderList('upcoming', $meetups);
    }

    public function past()
    {
        $meetups = Cache::remember("meetups_past", 600, function () {
            return Meetup::withCount('rsvps')
                ->where("date", "<", Carbon::today())
                ->orderBy("date", "desc")
                ->get();
        });

        return $this->renderList('past', $meetups);
    }

    public function community($community)
    {
        $meetups = Cache::remember("community_{$community}", 600, function () use ($community) {
            return Meetup::withCount('rsvps')
                ->where("community", $community)
                ->where("date", ">=", Carbon::today())
                ->orderBy("date", "asc")
                ->get();
        });

        return $this->renderList('upcoming', $meetups, $community);
    }

    public function past_community($community)
    {
        $meetups = Cache::remember("meetups_past_{$community}", 600, function () use ($community) {
            return Meetup::withCount('rsvps')
                ->where("community", $community)
                ->where("date", "<", Carbon::today())
                ->orderBy("date", "desc")
                ->get();
        });

        return $this->renderList('past', $meetups, $community);
    }

    public function calendar(Request $request)
    {
        $year = (int) ($request->query('y') ?? Carbon::now()->year);
        $start = Carbon::create($year, 1, 1)->startOfDay();
        $end = Carbon::create($year, 12, 31)->endOfDay();

        $meetups = Cache::remember("meetups_year_{$year}", 600, function () use ($start, $end) {
            return Meetup::withCount('rsvps')
                ->whereBetween('date', [$start, $end])
                ->orderBy('date', 'asc')
                ->get();
        });

        return view('calendar', compact('meetups', 'year'));
    }

    public function meetup($meetup)
    {
        $meetup = Meetup::withCount('rsvps')->findOrFail($meetup);

        return view("meetup", compact("meetup"));
    }

    protected function renderList(string $tense, $meetups, ?string $community = null)
    {
        [$upcomingCount, $pastCount] = $this->counts($community);

        return view('meetup-list', [
            'meetups' => $meetups,
            'tense' => $tense,
            'upcomingCount' => $upcomingCount,
            'pastCount' => $pastCount,
            'eventDots' => $this->eventDots(),
            'todayIso' => Carbon::today()->toDateString(),
            'community' => $community,
        ]);
    }

    /**
     * Per-community (or global) upcoming/past counts for the sidebar toggle.
     */
    protected function counts(?string $community = null): array
    {
        $key = $community ? "counts_community_{$community}" : 'counts';

        return Cache::remember($key, 600, function () use ($community) {
            $base = Meetup::query();
            if ($community) {
                $base->where('community', $community);
            }
            $today = Carbon::today();

            return [
                (clone $base)->where('date', '>=', $today)->count(),
                (clone $base)->where('date', '<', $today)->count(),
            ];
        });
    }

    /**
     * Map of YYYY-MM-DD → up-to-three community dots, used by the sidebar
     * mini-calendar and the full-year calendar page. Bounded to ±1 year so
     * the payload stays small even as the dataset grows.
     */
    protected function eventDots(): array
    {
        return Cache::remember('event_dots', 600, function () {
            $start = Carbon::now()->subYear();
            $end = Carbon::now()->addYear();

            return Meetup::whereBetween('date', [$start, $end])
                ->get(['date', 'community'])
                ->groupBy(fn ($m) => $m->date->format('Y-m-d'))
                ->map(function ($dayEvents) {
                    return $dayEvents
                        ->map(function ($m) {
                            $c = Communities::get($m->community);
                            return ['n' => $c['label'], 'c' => $c['color'], 'cd' => $c['color_dark']];
                        })
                        ->unique('c')
                        ->take(3)
                        ->values()
                        ->all();
                })
                ->toArray();
        });
    }
}
