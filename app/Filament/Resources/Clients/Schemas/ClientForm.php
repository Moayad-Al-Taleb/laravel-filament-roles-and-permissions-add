<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Info')
                    ->schema([
                        Hidden::make('type')
                            ->default('client')
                            ->dehydrated(true),

                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn(string $operation) => $operation === 'create')
                            ->dehydrated(fn($state) => filled($state))
                            ->confirmed(),

                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->revealable()
                            ->required(fn(string $operation) => $operation === 'create')
                            ->dehydrated(false),
                    ]),
            ]);
    }
}
