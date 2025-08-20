<?php

namespace App\Filament\Resources\PlatformManagers;

use App\Filament\Resources\PlatformManagers\Pages\CreatePlatformManager;
use App\Filament\Resources\PlatformManagers\Pages\EditPlatformManager;
use App\Filament\Resources\PlatformManagers\Pages\ListPlatformManagers;
use App\Filament\Resources\PlatformManagers\Pages\ViewPlatformManager;
use App\Filament\Resources\PlatformManagers\Schemas\PlatformManagerForm;
use App\Filament\Resources\PlatformManagers\Schemas\PlatformManagerInfolist;
use App\Filament\Resources\PlatformManagers\Tables\PlatformManagersTable;
use App\Models\PlatformManager;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class PlatformManagerResource extends Resource
{
    // protected static ?string $model = PlatformManager::class;
    protected static ?string $model = User::class;

    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|BackedEnum|null $navigationIcon   = Heroicon::OutlinedShieldCheck;
    protected static ?string $navigationLabel                 = 'Platform Managers';
    protected static ?string $modelLabel                      = 'Platform Manager';
    protected static ?string $pluralModelLabel                = 'Platform Managers';

    protected static string | UnitEnum | null $navigationGroup = 'Platform Management';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return PlatformManagerForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PlatformManagerInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlatformManagersTable::configure($table);
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
            'index' => ListPlatformManagers::route('/'),
            'create' => CreatePlatformManager::route('/create'),
            'view' => ViewPlatformManager::route('/{record}'),
            'edit' => EditPlatformManager::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $currentId = auth()->id();

        return parent::getEloquentQuery()
            ->where('type', 'admin')
            ->where('email', '!=', config('platform.protected_admin_email'))
            ->when($currentId, fn(Builder $q) => $q->whereKeyNot($currentId));
    }

    // public static function canViewAny(): bool
    // {
    //     return auth()->user()?->can('platform_managers.view') ?? false;
    // }

    // public static function canCreate(): bool
    // {
    //     return auth()->user()?->can('platform_managers.create') ?? false;
    // }

    // public static function canEdit(Model $record): bool
    // {
    //     return auth()->user()?->can('platform_managers.update') ?? false;
    // }

    // public static function canDelete(Model $record): bool
    // {
    //     return auth()->user()?->can('platform_managers.delete') ?? false;
    // }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->can('platform_managers.view') ?? false;
    }
}
