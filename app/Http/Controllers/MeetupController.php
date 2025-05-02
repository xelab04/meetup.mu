<?php

namespace App\Http\Controllers;

use App\Models\Meetup;
use App\Models\RSVP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class MeetupController extends Controller
{
    public function home()
    {
        $todays = Cache::remember("meetups_today", 600, function () {
            return Meetup::whereDate('date', '=', Carbon::today())
                ->orderBy('date', 'asc')
                ->get();
        });

        // dd($today);

        $meetups = Cache::remember("meetups_home", 600, function () {
            return Meetup::where("date", ">=", Carbon::now())
                ->orderBy("date", "asc")
                ->get();
        });

        return view("index", compact("meetups", "todays"));
    }

    public function past()
    {
        // $meetups = Meetup::orderBy("date", "asc")->get();
        $meetups = Cache::remember("meetups_past", 600, function () {
            return Meetup::where("date", "<=", Carbon::now())
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
            return Meetup::where("community", $community)
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
            return Meetup::where("community", $community)
                ->where("date", "<=", Carbon::now())
                ->orderBy("date", "desc")
                ->get();
        });

        return view("past-community", compact("meetups", "community"));
    }

    public function meetup($meetup)
    {
        $meetup = Meetup::where("id", $meetup)->firstOrFail();
        $rsvpCount = RSVP::where("meetup_id", $meetup->id)->count();
        dd($rsvpCount);
        return view("meetup", compact("meetup", "rsvpCount"));
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
