<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetupResource\Pages;
use App\Filament\Resources\MeetupResource\RelationManagers;
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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("title")
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make("abstract")
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make("location")
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make("registration")
                ->required()
                ->maxLength(255),
            Forms\Components\DatePicker::make("date")->required(),
            Forms\Components\Select::make("community")
                ->options([
                    "mscc" => "MSCC",
                    "cloudnativemu" => "Cloud Native MU",
                    "frontendmu" => "Frontend MU",
                    "dodocore" => "DodoCore",
		    "pymug" => "PYMUG",
		    "laravelmoris" => "LaravelMoris",
                ])
                ->required(),
            Forms\Components\Select::make("type")
                ->options([
                    "meetup" => "Meetup",
                    "conference" => "Conference",
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("community"),
                Tables\Columns\TextColumn::make("title"),
                Tables\Columns\TextColumn::make("location"),
                Tables\Columns\TextColumn::make("date"),
            ])
            ->filters([
                //
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                //
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
