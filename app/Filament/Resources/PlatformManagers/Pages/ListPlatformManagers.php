<?php

namespace App\Filament\Resources\PlatformManagers\Pages;

use App\Filament\Resources\PlatformManagers\PlatformManagerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlatformManagers extends ListRecords
{
    protected static string $resource = PlatformManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
