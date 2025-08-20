<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name')
                            ->weight('bold'),

                        TextEntry::make('permissions_count')
                            ->label('Permissions')
                            ->state(fn($record) => $record->permissions()->count())
                            ->badge(),
                    ]),

                Section::make('Permissions')
                    ->schema([
                        TextEntry::make('permissions_grouped')
                            ->label('Assigned Permissions')
                            ->state(function ($record) {
                                $perms = $record->permissions->pluck('name')->all();

                                $grouped = collect($perms)->groupBy(function ($name) {
                                    $prefix = str_contains($name, '.') ? strstr($name, '.', true) : $name;
                                    return \Illuminate\Support\Str::of($prefix)->replace('_', ' ')->headline();
                                })->map(function ($items, $group) {
                                    $actions = collect($items)->map(function ($name) {
                                        $action = str_contains($name, '.') ? substr(strstr($name, '.'), 1) : $name;
                                        return (string) \Illuminate\Support\Str::of($action)->replace('_', ' ')->headline();
                                    })
                                        ->unique()
                                        ->sort()
                                        ->values()
                                        ->implode(' · ');

                                    return "<div><span class='font-semibold'>{$group}:</span> {$actions}</div>";
                                })->values()->implode('');

                                return $grouped ?: '—';
                            })
                            ->html()
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
