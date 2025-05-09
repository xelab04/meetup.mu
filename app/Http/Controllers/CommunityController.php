<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meetup;
use App\Models\RSVP;

class CommunityController extends Controller
{
    public function fetch($community)
    {
        $meetups = Meetup::where('community', $community)->get();
        foreach ($meetups as $mtp) {
            // get number of rsvps
            $number_rsvp = RSVP::where('event_id', $mtp->id)->count();
            $mtp->rsvp = $number_rsvp;

            // get number of attendees
            $number_attending = RSVP::where('event_id', $mtp->id)->where('attendance', true)->count();
            $mtp->attendees = $number_attending;

            // create reg link if missing
            if ($mtp->registration == null){
                $url = route('meetup', ['meetup' => $mtp->id]);
                $mtp->registration = $url;
            }

            // remove useless data
            unset($mtp['created_at'], $mtp['updated_at']);
        }

        return $meetups;
    }
}
