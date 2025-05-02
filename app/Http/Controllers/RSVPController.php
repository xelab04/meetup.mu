<?php

namespace App\Http\Controllers;

use App\Models\Meetup;
use App\Models\RSVP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RSVPController extends Controller
{
    public function rsvp($meetup)
    {
        $user = Auth::user();

        $count = RSVP::where('event_id', $meetup)->count();
        $capacity = Meetup::find($meetup)->first()->capacity;

        // un-rsvp if already rsvped
        if ($user->rsvps()->where('event_id', $meetup)->exists()) {
            $rsvp = $user->rsvps()->where('event_id', $meetup)->first();
            $rsvp->delete();
            $message = "Successfully un-rsvped";
        }

        else {
            if ($count == $capacity)
            {
                $message = "Event is full";
            }
            else
            {
                RSVP::create([
                    'user_id' => $user->id,
                    'event_id' => $meetup,
                    'attendance' => false,
                ]);
                $message = "RSVP successful";
            }
        }


        return redirect()
            ->route('meetup', ['meetup' => $meetup])
            ->with('message', $message);
        // return redirect()->route('home');
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
    public function show(RSVP $rSVP)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RSVP $rSVP)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RSVP $rSVP)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RSVP $rSVP)
    {
        //
    }
}
