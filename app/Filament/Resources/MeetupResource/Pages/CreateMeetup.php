<?php

namespace App\Filament\Resources\MeetupResource\Pages;

use App\Filament\Resources\MeetupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMeetup extends CreateRecord
{
    protected static string $resource = MeetupResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        
        // If the user is not a superadmin, force the community to be the user's assigned community
        if ($user->admin !== '*' && $user->admin) {
            $data['community'] = $user->admin;
        }
        
        return $data;
    }
}
