<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RolesTable
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

                TextColumn::make('permissions_count')
                    ->label('Permissions')
                    ->getStateUsing(fn($record) => $record->permissions()->count())
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Filter::make('name')
                    ->label('Name')
                    ->form([
                        TextInput::make('value')
                            ->label('Contains')
                            ->placeholder('e.g. admin'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $value = $data['value'] ?? null;
                        return $query->when($value, fn($q) => $q->where('name', 'like', "%{$value}%"));
                    })
                    ->indicateUsing(
                        fn(array $data) => isset($data['value']) && $data['value'] !== ''
                            ? ['Name: ' . $data['value']]
                            : []
                    ),
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
