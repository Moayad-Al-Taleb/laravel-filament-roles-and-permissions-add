<?php

namespace App\Filament\Resources\SiteUsers\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiteUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('type')
                    ->default('user')
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

            ]);
    }
}
