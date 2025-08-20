<?php

namespace App\Filament\Resources\SiteUsers\Pages;

use App\Filament\Resources\SiteUsers\SiteUserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSiteUser extends ViewRecord
{
    protected static string $resource = SiteUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
