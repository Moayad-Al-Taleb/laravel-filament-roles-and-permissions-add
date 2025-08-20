<?php

namespace App\Filament\Resources\PlatformManagers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PlatformManagerInfolist
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
                            ->color(fn(string $state) => $state === 'admin' ? 'primary' : 'gray')
                            ->formatStateUsing(fn(string $state) => Str::headline($state)),
                    ]),

                Section::make('Permissions')
                    ->columns(1)
                    ->schema([
                        TextEntry::make('permissions_list')
                            ->label('Permissions')
                            ->state(function ($record) {
                                return $record->getAllPermissions()
                                    ->pluck('name')
                                    ->unique()
                                    ->sort()
                                    ->map(fn($name) => Str::of($name)->replace('.', ' › ')->headline())
                                    ->implode(', ');
                            })
                            ->helperText('These are the permissions assigned to this platform manager.')
                            ->columnSpanFull(),
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
