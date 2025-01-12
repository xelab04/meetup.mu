<?php

namespace App\Models;

use App\Concerns\ClearsResponseCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "id" => "integer",
        "date" => "datetime",
    ];

    public function isPast()
    {
        return $this->start_date->isYesterday() ||
            $this->start_date->isBefore(now()->yesterday());
    }
}
