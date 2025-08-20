````md
# Policy-Driven Admin Panel (Laravel 11 + Filament 3 + Spatie Permissions)

A clean starter that wires **Laravel 11**, **Filament 3**, and **Spatie laravel-permission** through **Policies** for centralized, real authorization. Includes optional hardening for protected users/roles and Filament navigation that mirrors permissions.

---

## Features

-   **Roles & Permissions (Spatie):** granular abilities like `roles.view`, `clients.update`, …
-   **Policies:** enforce abilities at the model/resource layer (not just UI).
-   **Filament Integration:** hide resources from the sidebar if the user can’t view them.
-   **Hardening:** protect a critical admin account and the `admin` role from unsafe edits/deletes.

## Requirements

-   PHP **8.2+**
-   Laravel **11**
-   MySQL/PostgreSQL
-   Composer

---

## Quick Start

```bash
# Laravel
composer create-project laravel/laravel:^11.0 myapp
cd myapp
php artisan serve

# Filament (Admin Panel)
composer require filament/filament:"^3.3" -W
php artisan filament:install --panels
php artisan make:filament-user

# Spatie Permissions
composer require spatie/laravel-permission:"^6.0"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```
````

**Register Spatie middlewares** (Laravel 11, `bootstrap/app.php`):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role'               => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission'         => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
    ]);
})
```

**Enable roles on User**:

```php
// app/Models/User.php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles; // can(), hasRole(), assignRole(), ...
}
```

---

## Seed Roles & Permissions

```php
// database/seeders/PermissionsSeeder.php (excerpt)
use Spatie\Permission\Models\{Role, Permission};
use Spatie\Permission\PermissionRegistrar;

public function run(): void
{
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $guard = config('auth.defaults.guard', 'web');

    $groups = [
        'roles'             => ['view','create','update','delete'],
        'platform_managers' => ['view','create','update','delete'],
        'site_users'        => ['view','create','update','delete'],
        'clients'           => ['view','create','update','delete'],
    ];

    foreach ($groups as $prefix => $actions) {
        foreach ($actions as $action) {
            Permission::firstOrCreate([
                'name'       => "$prefix.$action",
                'guard_name' => $guard,
            ]);
        }
    }

    $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
    $admin->syncPermissions(Permission::all());

    if ($u = \App\Models\User::where('email', 'dev@admin.com')->first()) {
        $u->syncRoles(['admin']);
    }

    app(PermissionRegistrar::class)->forgetCachedPermissions();
}
```

Run:

```bash
php artisan db:seed --class=PermissionsSeeder
```

---

## Policies

**Register policies** in `App\Providers\AppServiceProvider::boot()`:

```php
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Policies\RolePolicy;

public function boot(): void
{
    Gate::policy(User::class, UserPolicy::class);
    Gate::policy(SpatieRole::class, RolePolicy::class);
}
```

**RolePolicy** → maps directly to `roles.*`:

```php
class RolePolicy
{
    public function viewAny(User $u): bool         { return $u->can('roles.view'); }
    public function view(User $u, Role $r): bool   { return $u->can('roles.view'); }
    public function create(User $u): bool          { return $u->can('roles.create'); }
    public function update(User $u, Role $r): bool { return $u->can('roles.update'); }
    public function delete(User $u, Role $r): bool { return $u->can('roles.delete'); }
    public function deleteAny(User $u): bool       { return $u->can('roles.delete'); }
}
```

**UserPolicy** → infers permission prefix from route name:

-   `admin.site-users.*` → `site_users`
-   `admin.platform-managers.*` → `platform_managers`
-   `admin.clients.*` → `clients`

```php
class UserPolicy
{
    private function prefixFromRoute(): ?string
    {
        $name = request()->route()?->getName() ?? '';
        return match (true) {
            str_contains($name, '.site-users.')        => 'site_users',
            str_contains($name, '.platform-managers.') => 'platform_managers',
            str_contains($name, '.clients.')           => 'clients',
            default                                     => null,
        };
    }

    private function allow(User $user, string $action): bool
    {
        $p = $this->prefixFromRoute();
        return $p ? $user->can("$p.$action") : false;
    }

    public function viewAny(User $u): bool              { return $this->allow($u, 'view'); }
    public function view(User $u, User $r): bool        { return $this->allow($u, 'view'); }
    public function create(User $u): bool               { return $this->allow($u, 'create'); }
    public function update(User $u, User $r): bool      { return $this->allow($u, 'update'); }
    public function delete(User $u, User $r): bool      { return $this->allow($u, 'delete'); }
    public function deleteAny(User $u): bool            { return $this->allow($u, 'delete'); }
}
```

---

## Filament Integration (Hide by Ability)

```php
// In each Resource
public static function shouldRegisterNavigation(): bool
{
    return auth()->user()?->can('site_users.view') ?? false; // adjust per resource
}
```

---

## Optional Hardening

-   **Protected admin email** (`config/platform.php` + `.env`) to prevent deleting/changing the critical account/type.
-   **Panel access**: only `type=admin` can access the Filament admin panel.
-   **Custom Role model**: extend Spatie’s `Role` to forbid deleting/renaming the `admin` role and (optionally) hide it from index views.

```env
PROTECTED_ADMIN_EMAIL=dev@admin.com
```

> If you swap the default Spatie Role model, point `config/permission.php` to your custom model.

---

## Project Map

| Module            | Model             | Filament Resource         | Route Name Pattern          | Permission Prefix   | Policy       |
| ----------------- | ----------------- | ------------------------- | --------------------------- | ------------------- | ------------ |
| Platform Managers | `App\Models\User` | `PlatformManagerResource` | `admin.platform-managers.*` | `platform_managers` | `UserPolicy` |
| Site Users        | `App\Models\User` | `SiteUserResource`        | `admin.site-users.*`        | `site_users`        | `UserPolicy` |
| Clients           | `App\Models\User` | `ClientResource`          | `admin.clients.*`           | `clients`           | `UserPolicy` |
| Roles             | `App\Models\Role` | `RoleResource`            | `admin.roles.*`             | `roles`             | `RolePolicy` |

> Notes:
>
> -   All three user-facing resources share `User` and filter queries by `type`.
> -   Route names determine the permission prefix that `UserPolicy` checks.

---

## Users Table (Essentials)

-   `name`, `email` (unique), `password`, `email_verified_at`
-   `type`: `admin` | `user` | `client`
-   Add an index on `type` for faster filtering in resources.

---

## Helpful Commands

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan permission:cache-reset   # after changing roles/permissions
```

---

**TL;DR**: Install Filament, seed Spatie permissions, wire Policies, and add light guards. The map above helps onboard new contributors at a glance.

```

```
