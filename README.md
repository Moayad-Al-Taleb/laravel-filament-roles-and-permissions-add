Here’s a polished, ready-to-paste **README.md** that keeps your original content intact, adds a clean “design” (badges, ToC, Mermaid diagram, callouts, and tidy code blocks), and is laid out primarily in **Arabic** as you wrote it:

````markdown
# Laravel 11 + Filament 3 + Spatie Permissions + Policies

[![Laravel](https://img.shields.io/badge/Laravel-11-red)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.2-777bb3)](https://www.php.net/)
[![Filament](https://img.shields.io/badge/Filament-3.x-0ea5e9)](https://filamentphp.com)
[![Spatie/laravel-permission](https://img.shields.io/badge/Spatie-Permissions%20v6-0b7285)](https://spatie.be/docs/laravel-permission)

> [!TIP]
> هذا الدليل يلخّص إعداد لوحة تحكم مبنية على **Filament 3** مع **Spatie Permissions v6** وسياسات **Laravel Policies**، مع حماية اختيارية لبعض الكيانات، وخريطة مشروع موجزة.

---

## فهرس المحتويات

-   [الهدف](#الهدف)
-   [المتطلبات](#0-المتطلبات)
-   [التثبيت والإعداد](#1-التثبيت-والإعداد)
-   [إعداد الصلاحيات (Spatie)](#2-إعداد-الصلاحيات-spatie)
-   [السياسات (Policies)](#3-السياسات-policies)
-   [دمج الصلاحيات مع واجهة Filament](#4-دمج-الصلاحيات-مع-واجهة-filament)
-   [إضافات حماية اختيارية](#5-إضافات-حماية-اختيارية)
-   [خريطة المشروع (تلخيص تنظيمي)](#6-خريطة-المشروع-تلخيص-تنظيمي)
-   [مثال Migration لجدول users](#7-مثال-migration-لجدول-users-مع-تعليقات)
-   [أوامر مساعدة](#8-أوامر-مساعدة)
-   [مخطط العمل](#مخطط-العمل)

---

## الهدف

بناء لوحة تحكم باستخدام **Filament** مع نظام أدوار وصلاحيات (**Spatie**) وربط التفويض عبر **Policies**، بالإضافة لحماية بسيطة لبعض الكيانات.

---

## 0) المتطلبات

```text
- PHP 8.2+
- Laravel 11
- قواعد بيانات مدعومة (MySQL/Postgres ...)
- Composer
```
````

---

## 1) التثبيت والإعداد

### إنشاء مشروع Laravel 11

```bash
composer create-project laravel/laravel:^11.0 myapp
cd myapp
php artisan serve
```

### تنصيب Filament (لوحة تحكم)

```bash
composer require filament/filament:"^3.3" -W
php artisan filament:install --panels
php artisan make:filament-user
```

### تنصيب Spatie Permissions

```bash
composer require spatie/laravel-permission:"^6.0"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### ربط ميدلوير Spatie في Laravel 11 (`bootstrap/app.php`)

```php
// ->withMiddleware(function (Middleware $middleware) {
//     $middleware->alias([
//         'role'               => \Spatie\Permission\Middlewares\RoleMiddleware::class,
//         'permission'         => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
//         'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
//     ]);
// })
```

### إضافة Trait للموديل User لاستخدام الأدوار والصلاحيات (`app/Models/User.php`)

```php
// use Spatie\Permission\Traits\HasRoles;
// class User extends Authenticatable
// {
//     use HasRoles; // يضيف علاقات وواجهات can/hasRole/assignRole...
// }
```

---

## 2) إعداد الصلاحيات (Spatie)

Seeder سريع لإنشاء مجموعات صلاحيات + ربطها بدور `admin`:

```php
// database/seeders/PermissionsSeeder.php
// use Illuminate\Database\Seeder;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
// use Spatie\Permission\PermissionRegistrar;
//
// class PermissionsSeeder extends Seeder
// {
//     public function run(): void
//     {
//         // تنظيف الكاش الداخلي للصلاحيات
//         app(PermissionRegistrar::class)->forgetCachedPermissions();
//
//         $guard = config('auth.defaults.guard', 'web');
//
//         // مجموعات الصلاحيات (prefix.action)
//         $groups = [
//             'roles'             => ['view','create','update','delete'],
//             'platform_managers' => ['view','create','update','delete'],
//             'site_users'        => ['view','create','update','delete'],
//             'clients'           => ['view','create','update','delete'],
//         ];
//
//         foreach ($groups as $prefix => $actions) {
//             foreach ($actions as $action) {
//                 Permission::firstOrCreate([
//                     'name'       => "$prefix.$action",
//                     'guard_name' => $guard,
//                 ]);
//             }
//         }
//
//         // إنشاء دور admin وربطه بكل الصلاحيات
//         $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guard]);
//         $admin->syncPermissions(Permission::all());
//
//         // مثال: ترقية مستخدم بريد معيّن ليكون admin
//         if ($u = \App\Models\User::where('email', 'dev@admin.com')->first()) {
//             $u->syncRoles(['admin']);
//         }
//
//         // إعادة تنظيف الكاش
//         app(PermissionRegistrar::class)->forgetCachedPermissions();
//     }
// }
//
// التشغيل:
// php artisan db:seed --class=PermissionsSeeder
```

---

## 3) السياسات (Policies)

تسجيل السياسات داخل `App\Providers\AppServiceProvider::boot()`:

```php
// use Illuminate\Support\Facades\Gate;
// use App\Models\User;
// use App\Policies\UserPolicy;
// use Spatie\Permission\Models\Role as SpatieRole;
// use App\Policies\RolePolicy;
//
// public function boot(): void
// {
//     // يربط الموديل User بسياسة UserPolicy
//     Gate::policy(User::class, UserPolicy::class);
//
//     // يربط موديل Role الخاص بـ Spatie بسياسة RolePolicy
//     Gate::policy(SpatieRole::class, RolePolicy::class);
// }
```

### أ) سياسة Role (مثال مباشر على الصلاحيات `prefix=roles`)

```php
// namespace App\Policies;
//
// use App\Models\User;
// use Spatie\Permission\Models\Role;
//
// class RolePolicy
// {
//     public function viewAny(User $u): bool       { return $u->can('roles.view'); }
//     public function view(User $u, Role $r): bool { return $u->can('roles.view'); }
//     public function create(User $u): bool        { return $u->can('roles.create'); }
//     public function update(User $u, Role $r): bool { return $u->can('roles.update'); }
//     public function delete(User $u, Role $r): bool { return $u->can('roles.delete'); }
//     public function deleteAny(User $u): bool     { return $u->can('roles.delete'); }
// }
```

### ب) سياسة User تعتمد على بادئة اسم الراوت لتحديد مجموعة الصلاحيات

**Prefixes** المتوقعة في أسماء الراوت:

-   `admin.site-users.*` ⇒ `site_users`
-   `admin.platform-managers.*` ⇒ `platform_managers`
-   `admin.clients.*` ⇒ `clients`

```php
// namespace App\Policies;
//
// use App\Models\User;
//
// class UserPolicy
// {
//     private function prefixFromRoute(): ?string
//     {
//         $name = request()->route()?->getName() ?? '';
//         return match (true) {
//             str_contains($name, '.site-users.')        => 'site_users',
//             str_contains($name, '.platform-managers.') => 'platform_managers',
//             str_contains($name, '.clients.')           => 'clients',
//             default                                     => null,
//         };
//     }
//
//     private function allow(User $user, string $action): bool
//     {
//         $p = $this->prefixFromRoute();
//         return $p ? $user->can("$p.$action") : false;
//     }
//
//     public function viewAny(User $user): bool               { return $this->allow($user, 'view'); }
//     public function view(User $user, User $record): bool    { return $this->allow($user, 'view'); }
//     public function create(User $user): bool                { return $this->allow($user, 'create'); }
//     public function update(User $user, User $record): bool  { return $this->allow($user, 'update'); }
//     public function delete(User $user, User $record): bool  { return $this->allow($user, 'delete'); }
//     public function deleteAny(User $user): bool             { return $this->allow($user, 'delete'); }
// }
```

---

## 4) دمج الصلاحيات مع واجهة Filament

> \[!NOTE]
> الإخفاء الحقيقي يتم عبر **Policies**، لكن يمكنك إخفاء عناصر التنقّل في موارد Filament حسب صلاحية **العرض**:

```php
// public static function shouldRegisterNavigation(): bool
// {
//     return auth()->user()?->can('site_users.view') ?? false; // غيّر البادئة حسب المورد
// }
```

---

## 5) إضافات حماية اختيارية

### إعداد بريد مدير محمي لا يُحذف ولا يُعدّل نوعه/بريده

`config/platform.php`:

```php
// return [
//     'protected_admin_email' => env('PROTECTED_ADMIN_EMAIL', 'dev@admin.com'),
// ];
```

`.env`:

```dotenv
PROTECTED_ADMIN_EMAIL=dev@admin.com
```

### تقييد دخول لوحة Filament حسب نوع المستخدم (فقط `type=admin`)

```php
// app/Models/User.php
// use Filament\Models\Contracts\FilamentUser;
// use Filament\Panel;
//
// class User extends Authenticatable implements FilamentUser
// {
//     use HasRoles;
//
//     protected static function booted(): void
//     {
//         // منع حذف/تعديل الحساب الإداري المحمي
//         static::deleting(function (User $user) {
//             if ($user->getOriginal('type') === 'admin'
//                 && $user->getOriginal('email') === config('platform.protected_admin_email')) {
//                 throw new \RuntimeException('Deleting the protected platform manager is not allowed.');
//             }
//         });
//
//         static::updating(function (User $user) {
//             $isProtected = $user->getOriginal('type') === 'admin'
//                 && $user->getOriginal('email') === config('platform.protected_admin_email');
//
//             if ($isProtected && ($user->isDirty('email') || $user->isDirty('type'))) {
//                 throw new \RuntimeException('Updating the protected platform manager email/type is not allowed.');
//             }
//         });
//     }
//
//     public function canAccessPanel(Panel $panel): bool
//     {
//         return $panel->getId() === 'admin' && $this->type === 'admin';
//     }
// }
```

### حماية دور "admin" عبر موديل Role مخصّص يمدّ Spatie\Role

```php
// app/Models/Role.php
// namespace App\Models;
// use Spatie\Permission\Models\Role as SpatieRole;
// use Illuminate\Database\Eloquent\Builder;
//
// class Role extends SpatieRole
// {
//     // هذه الهُوكس تمنع تعديل/حذف اسم الدور المحمي 'admin'.
//     protected static function booted(): void
//     {
//         static::deleting(function (self $role) {
//             if ($role->name === 'admin') {
//                 throw new \RuntimeException('Deleting the protected role is not allowed.');
//             }
//         });
//
//         static::updating(function (self $role) {
//             if ($role->getOriginal('name') === 'admin') {
//                 throw new \RuntimeException('Updating the protected role is not allowed.');
//             }
//         });
//     }
//
//     // لإخفاء دور admin من الجداول في الواجهة فقط (عرض).
//     public static function getEloquentQuery(): Builder
//     {
//         return parent::getEloquentQuery()->where('name', '!=', 'admin');
//     }
// }
//
// ملاحظة: لو بدّلت موديل الدور الافتراضي في إعدادات Spatie، ضَع اسم موديلك في config/permission.php
```

---

## 6) خريطة المشروع (تلخيص تنظيمي)

| الوحدة/الكيان  | الموديل           | Resource في Filament      | بادئة الراوت (مثال)         | بادئة الصلاحيات     | السياسة      |
| -------------- | ----------------- | ------------------------- | --------------------------- | ------------------- | ------------ |
| مدراء المنصة   | `App\Models\User` | `PlatformManagerResource` | `admin.platform-managers.*` | `platform_managers` | `UserPolicy` |
| مستخدمو الموقع | `App\Models\User` | `SiteUserResource`        | `admin.site-users.*`        | `site_users`        | `UserPolicy` |
| العملاء        | `App\Models\User` | `ClientResource`          | `admin.clients.*`           | `clients`           | `UserPolicy` |
| الأدوار        | `App\Models\Role` | `RoleResource`            | `admin.roles.*`             | `roles`             | `RolePolicy` |

> ملاحظات:
>
> -   مشاركة نفس الموديل **User** عبر موارد متعددة يعتمد على **فلترة الاستعلام داخل كل Resource** (حسب `type`).
> -   **أسماء الراوت** مهمّة لأنها تُحدّد بادئة الصلاحيات في `UserPolicy`.

---

## 7) مثال Migration لجدول users (مع تعليقات)

```php
// database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;
//
// return new class extends Migration {
//     public function up(): void
//     {
//         Schema::create('users', function (Blueprint $table) {
//             $table->id();                                         // مفتاح أساسي
//             $table->string('name');                               // الاسم الكامل
//             $table->string('email')->unique();                    // البريد (فريد)
//             $table->timestamp('email_verified_at')->nullable();   // تاريخ توثيق البريد
//             $table->string('password');                           // كلمة المرور (هاش)
//             $table->enum('type', ['admin', 'user', 'client']);    // تصنيف المستخدم: admin/لوحة – user/مستخدم موقع – client/عميل
//             $table->rememberToken();                              // توكن "تذكّرني"
//             $table->timestamps();                                 // created_at / updated_at
//         });
//
//         // اختياري: فهرس مساعد للاستعلامات حسب النوع
//         Schema::table('users', function (Blueprint $table) {
//             $table->index('type');                                // لتحسين فلاتر الموارد حسب type
//         });
//     }
//
//     public function down(): void
//     {
//         Schema::dropIfExists('users');
//     }
// };
```

---

## 8) أوامر مساعدة

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan permission:cache-reset
# بعد إضافة/تعديل صلاحيات أو أدوار يفضّل تنظيف الكاش
```

---

## مخطط العمل

```mermaid
flowchart LR
    U[User] --> P[Filament Panel (UI)]
    P --> G[Laravel Policies (Gate)]
    G --> S[Spatie Roles & Permissions]
    S --> D[(Database)]
```

> بهذا التنظيم: ثبّتنا **Filament**، جهّزنا **Spatie Permissions**، سجّلنا **السياسات** وربطناها بالراوت/الكيانات، وأضفنا طبقة بسيطة لحماية بعض العمليات الحساسة.
> “خريطة المشروع” تساعد أي مطوّر جديد يفهم بسرعة كيف مُقسّم العمل.

```

---

### مراجع موثوقة (إن احتجت الرجوع):

- توثيق تثبيت **Spatie/laravel-permission v6** وخطوات `vendor:publish` وملف الإعدادات. :contentReference[oaicite:0]{index=0}
- توثيق **Laravel** بخصوص **Policies** و**Authorization** (الأفكار العامة/الأنماط). :contentReference[oaicite:1]{index=1}
- توثيق **Laravel 11/12** لآلية **Middleware Aliases** في `bootstrap/app.php`. :contentReference[oaicite:2]{index=2}
- توثيق **Filament 3 – Panels Installation**. :contentReference[oaicite:3]{index=3}

إذا رغبت، أقدر أضيف قسم **English Summary** مختصر في آخر الـREADME أو أوفّر **نسخة كاملة بالإنجليزية** بنفس التصميم.
::contentReference[oaicite:4]{index=4}
```
