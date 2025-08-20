<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Str;

class UserPolicy
{
    /**
     * استخرج prefix من اسم مسار Filament الحالي:
     * filament.{panel}.resources.{slug}.{page}
     * أمثلة slug: site-users | platform-managers | clients
     * ثم نحولها إلى تنسيق السماحيات: site_users | platform_managers | clients
     */
    private function prefixFromRoute(): ?string
    {
        $name = request()->route()?->getName() ?? '';

        if (! Str::contains($name, '.resources.')) {
            return null;
        }

        $after = Str::after($name, '.resources.'); // مثال: "site-users.index"
        $slug  = Str::before($after, '.');         // مثال: "site-users"

        if ($slug === '') {
            return null;
        }

        return str_replace('-', '_', $slug);       // مثال: "site_users"
    }

    /**
     * فحص مرن: يفضّل صلاحيات الـprefix الحالي،
     * وإلا fallback على أي صلاحية من الأقسام الثلاثة.
     */
    private function allowed(User $actor, string $action): bool
    {
        if ($prefix = $this->prefixFromRoute()) {
            return $actor->can("{$prefix}.{$action}");
        }

        return
            $actor->can("site_users.{$action}") ||
            $actor->can("platform_managers.{$action}") ||
            $actor->can("clients.{$action}");
    }

    public function viewAny(User $actor): bool
    {
        return $this->allowed($actor, 'view');
    }

    public function view(User $actor, User $record): bool
    {
        return $this->allowed($actor, 'view');
    }

    public function create(User $actor): bool
    {
        return $this->allowed($actor, 'create');
    }

    public function update(User $actor, User $record): bool
    {
        return $this->allowed($actor, 'update');
    }

    public function delete(User $actor, User $record): bool
    {
        return $this->allowed($actor, 'delete');
    }

    public function deleteAny(User $actor): bool
    {
        return $this->allowed($actor, 'delete');
    }
}
