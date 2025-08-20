<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ClientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name')
                            ->weight('bold'),

                        TextEntry::make('email')
                            ->label('Email'),

                        TextEntry::make('type')
                            ->label('Type')
                            ->badge()
                            ->color(fn(string $state) => $state === 'client' ? 'primary' : 'gray')
                            ->formatStateUsing(fn(string $state) => Str::headline($state)),
                    ]),

                Section::make('Meta')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created')
                            ->dateTime(),

                        TextEntry::make('updated_at')
                            ->label('Updated')
                            ->dateTime(),
                    ]),
            ]);
    }
}
