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
        $upcoming = Cache::remember("meetups_upcoming", 600, function () {
            return Meetup::withCount('rsvps')
                ->where("date", ">=", Carbon::today())
                ->orderBy("date", "asc")
                ->get();
        });

        $recent = Cache::remember("meetups_recent", 600, function () {
            return Meetup::withCount('rsvps')
                ->where("date", ">=", Carbon::today()->subWeeks(2))
                ->where("date", "<", Carbon::today())
                ->orderBy("date", "asc")
                ->get();
        });

        return $this->renderList($upcoming, $recent);
    }

    public function community($community)
    {
        $upcoming = Cache::remember("community_upcoming_{$community}", 600, function () use ($community) {
            return Meetup::withCount('rsvps')
                ->where("community", $community)
                ->where("date", ">=", Carbon::today())
                ->orderBy("date", "asc")
                ->get();
        });

        $recent = Cache::remember("community_recent_{$community}", 600, function () use ($community) {
            return Meetup::withCount('rsvps')
                ->where("community", $community)
                ->where("date", ">=", Carbon::today()->subWeeks(2))
                ->where("date", "<", Carbon::today())
                ->orderBy("date", "asc")
                ->get();
        });

        return $this->renderList($upcoming, $recent, $community);
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

    protected function renderList($upcoming, $recent, ?string $community = null)
    {
        return view('meetup-list', [
            'upcoming' => $upcoming,
            'recent' => $recent,
            'eventDots' => $this->eventDots(),
            'todayIso' => Carbon::today()->toDateString(),
            'community' => $community,
        ]);
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
