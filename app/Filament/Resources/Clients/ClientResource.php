<?php

namespace App\Filament\Resources\Clients;

use App\Filament\Resources\Clients\Pages\CreateClient;
use App\Filament\Resources\Clients\Pages\EditClient;
use App\Filament\Resources\Clients\Pages\ListClients;
use App\Filament\Resources\Clients\Pages\ViewClient;
use App\Filament\Resources\Clients\Schemas\ClientForm;
use App\Filament\Resources\Clients\Schemas\ClientInfolist;
use App\Filament\Resources\Clients\Tables\ClientsTable;
use App\Models\Client;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ClientResource extends Resource
{
    // protected static ?string $model = Client::class;
    protected static ?string $model = User::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon   = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel                 = 'Clients';
    protected static ?string $modelLabel                      = 'Client';
    protected static ?string $pluralModelLabel                = 'Clients';

    protected static string | UnitEnum | null $navigationGroup = 'Client Management';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ClientForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClientInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClients::route('/'),
            'create' => CreateClient::route('/create'),
            'view' => ViewClient::route('/{record}'),
            'edit' => EditClient::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'client');
    }

    // public static function canViewAny(): bool
    // {
    //     return auth()->user()?->can('clients.view') ?? false;
    // }

    // public static function canCreate(): bool
    // {
    //     return auth()->user()?->can('clients.create') ?? false;
    // }

    // public static function canEdit(Model $record): bool
    // {
    //     return auth()->user()?->can('clients.update') ?? false;
    // }

    // public static function canDelete(Model $record): bool
    // {
    //     return auth()->user()?->can('clients.delete') ?? false;
    // }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('clients.view') ?? false;
    }
}
