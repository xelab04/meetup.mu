<?php

namespace App\Filament\Resources\MeetupResource\Pages;

use App\Filament\Resources\MeetupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMeetup extends EditRecord
{
    protected static string $resource = MeetupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function canEdit(Model $record): bool
    {
        $user = auth()->user();
        
        // Superadmins can edit all meetups
        if ($user->admin === '*') {
            return true;
        }
        
        // Regular admins can only edit meetups for their assigned community
        return $user->admin === $record->community;
    }
}
