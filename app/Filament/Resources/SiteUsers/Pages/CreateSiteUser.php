<?php

namespace App\Filament\Resources\SiteUsers\Pages;

use App\Filament\Resources\SiteUsers\SiteUserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteUser extends CreateRecord
{
    protected static string $resource = SiteUserResource::class;
}
