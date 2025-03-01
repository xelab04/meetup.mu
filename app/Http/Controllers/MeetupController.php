<?php

namespace App\Http\Controllers;

use App\Models\Meetup;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MeetupController extends Controller
{
    public function home()
    {
        // $meetups = Meetup::orderBy("date", "asc")->get();
        // $meetups = Meetup::where("date", ">=", Carbon::now())
        //     ->orderBy("date", "asc")
        //     ->get();

        $meetups = Cache::remember("meetups_home", 60, function () {
            return Meetup::where("date", ">=", Carbon::now())
                ->orderBy("date", "asc")
                ->get();
        });

        return view("index", compact("meetups"));
    }

    public function past()
    {
        // $meetups = Meetup::orderBy("date", "asc")->get();
        $meetups = Meetup::where("date", "<=", Carbon::now())
            ->orderBy("date", "asc")
            ->get();
        return view("past", compact("meetups"));
    }

    public function community($community)
    {
        $meetups = Meetup::where("community", $community)
            ->where("date", ">=", Carbon::now())
            ->orderBy("date", "asc")
            ->get();
        return view("community", compact("meetups", "community"));
    }

    public function past_community($community)
    {
        $meetups = Meetup::where("community", $community)
            ->where("date", "<=", Carbon::now())
            ->orderBy("date", "asc")
            ->get();
        return view("past-community", compact("meetups", "community"));
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
