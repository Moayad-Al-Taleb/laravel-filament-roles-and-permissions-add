<?php

namespace App\Filament\Resources\PlatformManagers\Pages;

use App\Filament\Resources\PlatformManagers\PlatformManagerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPlatformManager extends EditRecord
{
    protected static string $resource = PlatformManagerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
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
