<?php

namespace App\Filament\Resources\PlatformManagers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PlatformManagersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                TagsColumn::make('roles_list')
                    ->label('Roles')
                    ->getStateUsing(
                        fn($record) =>
                        $record->roles->pluck('name')->sort()->values()->all()
                    )
                    ->limitList(3)
                    ->expandableLimitedList(),

                TagsColumn::make('permissions_list')
                    ->label('Permissions')
                    ->getStateUsing(
                        fn($record) =>
                        $record->getAllPermissions()
                            ->pluck('name')
                            ->unique()
                            ->sort()
                            ->values()
                            ->map(fn($name) => Str::of($name)->replace('.', ' › ')->headline())
                            ->all()
                    )
                    ->limitList(4)
                    ->expandableLimitedList()  // تمكين التوسيع
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    DeleteBulkAction::make()->requiresConfirmation(),
                ]),
            ]);
    }
}
