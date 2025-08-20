<?php

namespace App\Filament\Resources\SiteUsers\Pages;

use App\Filament\Resources\SiteUsers\SiteUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiteUsers extends ListRecords
{
    protected static string $resource = SiteUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
