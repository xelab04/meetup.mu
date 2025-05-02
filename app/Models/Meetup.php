<?php

namespace App\Models;

use App\Concerns\ClearsResponseCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meetup extends Model
{
    // use ClearsResponseCache;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "type",
        "community",
        "title",
        "abstract",
        "location",
        "registration",
        "date",
        "capacity",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "id" => "integer",
        "date" => "datetime",
        "capacity" => "integer",
    ];

    public function isPast()
    {
        return $this->date->isYesterday() ||
            $this->date->isBefore(now()->yesterday());
    }
    
    /**
     * Get the RSVPs for the meetup
     */
    public function rsvps(): HasMany
    {
        return $this->hasMany(RSVP::class, 'event_id');
    }
    
    /**
     * Get the count of RSVPs for this meetup
     */
    public function getRsvpCountAttribute(): int
    {
        return $this->rsvps()->count();
    }
    
    /**
     * Get the count of attendees who actually attended
     */
    public function getAttendanceCountAttribute(): int
    {
        return $this->rsvps()->where('attendance', true)->count();
    }
}
