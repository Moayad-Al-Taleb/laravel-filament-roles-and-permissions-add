<?php

namespace App\Filament\Resources\SiteUsers;

use App\Filament\Resources\SiteUsers\Pages\CreateSiteUser;
use App\Filament\Resources\SiteUsers\Pages\EditSiteUser;
use App\Filament\Resources\SiteUsers\Pages\ListSiteUsers;
use App\Filament\Resources\SiteUsers\Pages\ViewSiteUser;
use App\Filament\Resources\SiteUsers\Schemas\SiteUserForm;
use App\Filament\Resources\SiteUsers\Schemas\SiteUserInfolist;
use App\Filament\Resources\SiteUsers\Tables\SiteUsersTable;
use App\Models\SiteUser;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class SiteUserResource extends Resource
{
    // protected static ?string $model = SiteUser::class;
    protected static ?string $model = User::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon   = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel                 = 'Site Users';
    protected static ?string $modelLabel                      = 'Site User';
    protected static ?string $pluralModelLabel                = 'Site Users';

    protected static string | UnitEnum | null $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return SiteUserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SiteUserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteUsersTable::configure($table);
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
            'index' => ListSiteUsers::route('/'),
            'create' => CreateSiteUser::route('/create'),
            'view' => ViewSiteUser::route('/{record}'),
            'edit' => EditSiteUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('type', 'user');
    }

    // public static function canViewAny(): bool
    // {
    //     return auth()->user()?->can('site_users.view') ?? false;
    // }

    // public static function canCreate(): bool
    // {
    //     return auth()->user()?->can('site_users.create') ?? false;
    // }

    // public static function canEdit(Model $record): bool
    // {
    //     return auth()->user()?->can('site_users.update') ?? false;
    // }

    // public static function canDelete(Model $record): bool
    // {
    //     return auth()->user()?->can('site_users.delete') ?? false;
    // }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('site_users.view') ?? false;
    }
}
