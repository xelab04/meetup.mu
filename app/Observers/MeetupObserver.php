<?php

namespace App\Observers;

use App\Models\Meetup;
use Illuminate\Support\Facades\Cache;

/**
 * Invalidate the fixed + scope-keyed caches read by MeetupController
 * whenever a meetup is created, updated, or deleted. The cache keys
 * mirror those set in the controller — if you add or rename a cache
 * key there, add or rename it here too.
 */
class MeetupObserver
{
    public function saved(Meetup $meetup): void
    {
        $this->forget($meetup);
    }

    public function deleted(Meetup $meetup): void
    {
        $this->forget($meetup);
    }

    protected function forget(Meetup $meetup): void
    {
        Cache::forget('meetups_upcoming');
        Cache::forget('meetups_recent');
        Cache::forget('event_dots');

        // Year-scoped calendar cache — invalidate both the previous date's
        // year (if the date was changed) and the current one.
        foreach ($this->affectedYears($meetup) as $year) {
            Cache::forget("meetups_year_{$year}");
        }

        // Community-scoped caches, including the old slug if the meetup
        // was moved between groups.
        foreach ($this->affectedCommunities($meetup) as $slug) {
            Cache::forget("community_upcoming_{$slug}");
            Cache::forget("community_recent_{$slug}");
        }
    }

    /**
     * @return array<int>
     */
    protected function affectedYears(Meetup $meetup): array
    {
        $years = [];
        if ($meetup->date) {
            $years[] = (int) $meetup->date->format('Y');
        }
        $original = $meetup->getOriginal('date');
        if ($original) {
            $years[] = (int) \Carbon\Carbon::parse($original)->format('Y');
        }

        return array_values(array_unique(array_filter($years)));
    }

    /**
     * @return array<string>
     */
    protected function affectedCommunities(Meetup $meetup): array
    {
        return array_values(array_unique(array_filter([
            $meetup->community,
            $meetup->getOriginal('community'),
        ])));
    }
}
