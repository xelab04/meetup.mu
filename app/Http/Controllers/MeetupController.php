<?php

namespace App\Http\Controllers;

use App\Models\Meetup;
use Illuminate\Http\Request;

class MeetupController extends Controller
{
    public function __invoke(Request $request)
    {
        $meetups = Meetup::orderBy("date", "asc")->get();
        return view("main", compact("meetups"));
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
