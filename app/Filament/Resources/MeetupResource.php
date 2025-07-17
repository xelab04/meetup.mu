<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetupResource\Pages;
use App\Filament\Resources\MeetupResource\RelationManagers;
use App\Filament\Resources\MeetupResource\RelationManagers\RSVPsRelationManager;
use App\Models\Meetup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeetupResource extends Resource
{
    protected static ?string $model = Meetup::class;

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    protected static ?array $communityOptions = [
        "mscc" => "MSCC",
        "cloudnativemu" => "Cloud Native MU",
        "frontendmu" => "Frontend MU",
        "dodocore" => "DodoCore",
        "pymug" => "PYMUG",
        "laravelmoris" => "LaravelMoris",
        "nugm" => "NUGM",
        "gophersmu" => "Gophers MU",
        "mobilehorizon" => "Mobile Horizon",
        "pydata" => "PyData MU"
    ];

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $isSuperAdmin = $user->admin === '*';

        // For superadmins, show all communities; otherwise, only show their assigned community
        $allCommunities = static::$communityOptions;

        // If user is not a superadmin and has a specific community assigned
        if (!$isSuperAdmin && $user->admin) {
            // Filter to only show the user's assigned community
            if (isset($communityOptions[$user->admin])) {
                $communityOptions = [
                    $user->admin => $allCommunities[$user->admin]
                ];
            }
        }

        return $form->schema([
            Forms\Components\TextInput::make("title")
                ->required()
                ->maxLength(255),
            Forms\Components\MarkdownEditor::make("abstract")
                ->required()
                ->maxLength(2000),
            Forms\Components\TextInput::make("location")
                ->required()
                ->maxLength(255),
            Forms\Components\Toggle::make('registration_enabled')
                ->label('RSVP on Meetup.mu')
                ->default(true)
                ->afterStateHydrated(function (Forms\Components\Toggle $component, $state, $record) {
                        if ($record && $record->registration === null) {
                            $component->state(true);
                        }
                    })
                ->live()
                ->dehydrated(false),
            Forms\Components\TextInput::make("registration")
                ->label('Registration URL')
                ->maxLength(255)
                ->disabled(fn ($get) => $get('registration_enabled')),
            Forms\Components\TextInput::make("capacity")
                ->required()
                ->numeric(),
            Forms\Components\DatePicker::make("date")->required(),
            Forms\Components\Select::make("community")
                ->options($allCommunities)
                ->default((!$isSuperAdmin && $user->admin) ? $user->admin : null)
                ->disabled((!$isSuperAdmin && $user->admin))
                ->required(),
            Forms\Components\Select::make("type")
                ->options([
                    "meetup" => "Meetup",
                    "conference" => "Conference",
                ])
                ->default("meetup")
                ->required(),

        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("community"),
                Tables\Columns\TextColumn::make("title"),
                Tables\Columns\TextColumn::make("location"),
                Tables\Columns\TextColumn::make("date")
                    ->sortable(),
                Tables\Columns\TextColumn::make("rsvp_count")
                    ->label('RSVPs')
                    ->sortable()
                    ->counts('rsvps'),
                Tables\Columns\TextColumn::make("attendance_count")
                    ->label('Attendance')
                    ->sortable()
                    ->getStateUsing(function (Meetup $record) {
                        return $record->getAttendanceCountAttribute() . ' / ' . $record->getRsvpCountAttribute();
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('community')
                    ->options(static::$communityOptions),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // If user is not a superadmin, only show meetups for their community
        if ($user->admin !== '*' && $user->admin) {
            $query->where('community', $user->admin);
        }

        return $query;
    }

    public static function canAccess(): bool
    {
        // Any user with admin rights can access this resource
        return auth()->user()->admin != null;
    }

    public static function getRelations(): array
    {
        return [
            RSVPsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListMeetups::route("/"),
            "create" => Pages\CreateMeetup::route("/create"),
            "edit" => Pages\EditMeetup::route("/{record}/edit"),
        ];
    }
}
