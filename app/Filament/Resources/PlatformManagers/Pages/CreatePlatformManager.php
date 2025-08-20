<?php

namespace App\Filament\Resources\PlatformManagers\Pages;

use App\Filament\Resources\PlatformManagers\PlatformManagerResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePlatformManager extends CreateRecord
{
    protected static string $resource = PlatformManagerResource::class;

    protected function afterCreate(): void
    {
        $this->removePermissionsDuplicatedByRoles();
    }

    private function removePermissionsDuplicatedByRoles(): void
    {
        $user = $this->record;

        $viaRoleIds = $user->getPermissionsViaRoles()->pluck('id')->all();

        if (empty($viaRoleIds)) {
            return;
        }

        $directIds = $user->permissions()->pluck('id')->all();

        $final = array_values(array_diff($directIds, $viaRoleIds));

        $user->permissions()->sync($final);
    }
}
