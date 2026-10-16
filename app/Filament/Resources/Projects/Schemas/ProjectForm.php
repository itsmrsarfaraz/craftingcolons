<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('title')
                    ->required(),
                TextInput::make('kind')
                    ->required(),
                TextInput::make('categories')
                    ->required(),
                TextInput::make('tagline')
                    ->required(),
                Textarea::make('details')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
