<?php

namespace App\Filament\Resources\MeetupResource\RelationManagers;

use App\Models\RSVP;
use Illuminate\Support\Facades\DB;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Infolist;

class RSVPsRelationManager extends RelationManager
{
    protected static string $relationship = 'rsvps';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $title = 'RSVPs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('attendance')
                    ->label('Marked as attended')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->heading('Attendees')
            ->description(function () {
                $rsvpCount = $this->ownerRecord->rsvps()->count();
                $attendanceCount = $this->ownerRecord->rsvps()->where('attendance', true)->count();

                $vegCount = DB::table('r_s_v_p_s')
                    ->join('users', 'r_s_v_p_s.user_id', '=', 'users.id')
                    ->where('r_s_v_p_s.event_id', $this->ownerRecord->id)
                    ->where('users.veg', true)
                    ->count();

                return "Total RSVPs: {$rsvpCount} | Marked as attended: {$attendanceCount} | Veg: {$vegCount} | This: {$this->ownerRecord->id}";
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\CheckboxColumn::make('attendance')
                    ->label('Attended')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('RSVP Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('attended')
                    ->label('Show only attendees who showed up')
                    ->query(fn (Builder $query): Builder => $query->where('attendance', true)),
                Tables\Filters\Filter::make('not_attended')
                    ->label('Show only attendees who did not show up yet')
                    ->query(fn (Builder $query): Builder => $query->where('attendance', false)),
            ])
            ->headerActions([
                // Removed the create action since RSVPs are created through the public interface
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Toggle Attendance'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markAsAttended')
                        ->label('Mark as Attended')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            $records->each(function (RSVP $record): void {
                                $record->update(['attendance' => true]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('markAsNotAttended')
                        ->label('Mark as Not Attended')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                            $records->each(function (RSVP $record): void {
                                $record->update(['attendance' => false]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove RSVPs'),
                ]),
            ]);
    }
}
