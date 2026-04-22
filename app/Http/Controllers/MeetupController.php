<?php

namespace App\Http\Controllers;

use App\Models\Meetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MeetupController extends Controller
{
    public function home()
    {
        $todays = Cache::remember("meetups_today", 600, function () {
            return Meetup::withCount('rsvps')
                ->whereDate('date', '=', Carbon::today())
                ->orderBy('date', 'asc')
                ->get();
        });

        $meetups = Cache::remember("meetups_home", 600, function () {
            return Meetup::withCount('rsvps')
                ->where("date", ">=", Carbon::now())
                ->orderBy("date", "asc")
                ->get();
        });

        return view("index", compact("meetups", "todays"));
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

    public function past()
    {
        $meetups = Cache::remember("meetups_past", 600, function () {
            return Meetup::withCount('rsvps')
                ->where("date", "<=", Carbon::now())
                ->orderBy("date", "desc")
                ->get();
        });

        return view("past", compact("meetups"));
    }

    public function community($community)
    {
        $meetups = Cache::remember("community_{$community}", 600, function () use (
            $community
        ) {
            return Meetup::withCount('rsvps')
                ->where("community", $community)
                ->where("date", ">=", Carbon::today())
                ->orderBy("date", "asc")
                ->get();
        });

        return view("community", compact("meetups", "community"));
    }

    public function past_community($community)
    {
        $meetups = Cache::remember("meetups_past_{$community}", 600, function () use (
            $community
        ) {
            return Meetup::withCount('rsvps')
                ->where("community", $community)
                ->where("date", "<=", Carbon::now())
                ->orderBy("date", "desc")
                ->get();
        });

        return view("past-community", compact("meetups", "community"));
    }

    public function meetup($meetup)
    {
        $meetup = Meetup::withCount('rsvps')->findOrFail($meetup);

        return view("meetup", compact("meetup"));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Meetup $meetup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meetup $meetup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meetup $meetup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meetup $meetup)
    {
        //
    }
}
