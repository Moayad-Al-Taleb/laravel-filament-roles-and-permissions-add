<?php

namespace App\Filament\Resources\SiteUsers\Pages;

use App\Filament\Resources\SiteUsers\SiteUserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSiteUser extends EditRecord
{
    protected static string $resource = SiteUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
