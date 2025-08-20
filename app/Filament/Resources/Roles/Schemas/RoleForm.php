<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Info')->schema([
                    TextInput::make('name')
                        ->label('Role Name')
                        ->required()
                        ->maxLength(191)
                        ->unique(ignoreRecord: true),

                    Hidden::make('guard_name')
                        ->default('web')
                        ->dehydrated(true),
                ]),

                Section::make('Permissions')->schema([
                    CheckboxList::make('permissions')
                        ->label('Assign Permissions')
                        ->relationship('permissions')
                        ->options(function (callable $get) {
                            $guard = $get('guard_name') ?? 'web';

                            return Permission::query()
                                ->where('guard_name', $guard)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->map(fn($name) => Str::of($name)->replace('.', ' › ')->headline())
                                ->toArray();
                        })
                        ->searchable()
                        ->bulkToggleable()
                        ->helperText('Tick the permissions for this role.')
                        ->columnSpanFull(),
                ]),
            ]);
    }
}
