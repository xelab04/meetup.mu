<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RSVP extends Model
{
    protected $table = 'r_s_v_p_s';
    
    protected $fillable = [
        'user_id',
        'event_id',
        'attendance',
    ];
    
    protected $casts = [
        'attendance' => 'boolean',
    ];
    
    /**
     * Get the user that owns the RSVP
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the meetup that the RSVP is for
     */
    public function meetup(): BelongsTo
    {
        return $this->belongsTo(Meetup::class, 'event_id');
    }
}
