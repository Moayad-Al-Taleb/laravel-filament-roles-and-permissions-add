<?php

namespace App\Filament\Resources\PlatformManagers\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class PlatformManagerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Info')
                    ->schema([
                        Hidden::make('type')
                            ->default('admin')
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

                Section::make('Access Control')
                    ->schema([
                        Select::make('roles')
                            ->label('Roles')
                            ->multiple()
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) =>
                                $query->where('guard_name', 'web')->orderBy('name')
                            )
                            ->preload()
                            ->searchable()
                            ->native(false)
                            ->helperText('Select one or more roles to grant base permissions.'),

                        CheckboxList::make('permissions')
                            ->label('Extra Permissions')
                            ->relationship(
                                name: 'permissions',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn(Builder $query) =>
                                $query->where('guard_name', 'web')->orderBy('name')
                            )
                            ->getOptionLabelFromRecordUsing(
                                fn($record) => Str::of($record->name)->replace('.', ' › ')->headline()
                            )
                            ->searchable()
                            ->bulkToggleable()
                            ->helperText('These are in addition to the permissions provided by the selected roles.')
                            ->columnSpanFull(),
                    ])

            ]);
    }
}
