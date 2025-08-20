<?php

namespace App\Filament\Resources\PlatformManagers\Pages;

use App\Filament\Resources\PlatformManagers\PlatformManagerResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPlatformManager extends ViewRecord
{
    protected static string $resource = PlatformManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
