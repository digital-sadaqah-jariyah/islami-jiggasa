<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TermResource\Pages;
use App\Filament\Resources\TermResource\RelationManagers;
use App\Filament\Roles;
use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;
use Lecturize\Taxonomies\Models\Term;

class TermResource extends Resource
{
    public static $icon = 'heroicon-o-collection';

    public static $model = Term::class;

    public static function form(Form $form)
    {
        return $form
            ->schema([
                Components\TextInput::make('title')->autofocus()->required(),
                Components\Textarea::make('content')->nullable(),
            ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('title')->primary(),
                Columns\Text::make('content'),
                Columns\Text::make('slug'),
            ])
            ->filters([
                //
            ]);
    }

    public static function relations()
    {
        return [
            //
        ];
    }

    public static function routes()
    {
        return [
            Pages\ListTerms::routeTo('/', 'index'),
            Pages\CreateTerm::routeTo('/create', 'create'),
            Pages\EditTerm::routeTo('/{record}/edit', 'edit'),
        ];
    }
}
