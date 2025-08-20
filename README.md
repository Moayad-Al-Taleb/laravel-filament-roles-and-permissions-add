<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title># Organized Summary: Laravel 11 + Filament 3 + Spatie Permissions + Policies</title>
<style>
  :root{
    --bg:#0f0f12;
    --card:#15151b;
    --muted:#8a8fa3;
    --text:#e8eaf1;
    --accent:#7c5cff;
    --accent-2:#2dd4bf;
    --stroke:#262633;
    --code-bg:#0c0c10;
  }
  *{box-sizing:border-box}
  html,body{height:100%}
  body{
    margin:0; background:radial-gradient(1200px 800px at 10% -10%, rgba(124,92,255,.15), transparent 60%),
               radial-gradient(1000px 700px at 110% 10%, rgba(45,212,191,.12), transparent 55%),
               var(--bg);
    color:var(--text); font:16px/1.6 ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
  }
  .wrap{max-width:1080px; margin:auto; padding:32px 20px 80px}
  header{
    position:sticky; top:0; z-index:5; backdrop-filter:saturate(140%) blur(8px);
    background:linear-gradient(180deg, rgba(15,15,18,.85), rgba(15,15,18,.55));
    border-bottom:1px solid var(--stroke); margin:-32px -20px 24px; padding:16px 20px;
  }
  header .title{display:flex; align-items:center; gap:12px}
  header .title .dot{width:10px; height:10px; border-radius:50%; background:linear-gradient(45deg,var(--accent),var(--accent-2))}
  header .title h1{margin:0; font-size:18px; font-weight:700; letter-spacing:.2px}
  .card{
    background:linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,.005)) , var(--card);
    border:1px solid var(--stroke); border-radius:16px; padding:22px; box-shadow:0 8px 24px rgba(0,0,0,.25);
  }
  h2,h3,h4{margin:22px 0 10px; letter-spacing:.2px}
  h2{font-size:22px} h3{font-size:18px} h4{font-size:16px; color:#cfd3e6}
  h1::before, h2::before, h3::before, h4::before{
    content:"# "; color:var(--accent); font-weight:600; letter-spacing:.3px;
  }
  p{margin:8px 0}
  .muted{color:var(--muted)}
  .toc{display:grid; grid-template-columns:repeat(auto-fit,minmax(240px,1fr)); gap:10px; margin:12px 0 8px}
  .toc a{
    display:flex; gap:10px; align-items:center; padding:12px 14px; border:1px solid var(--stroke);
    border-radius:12px; text-decoration:none; color:var(--text); background:rgba(255,255,255,.02);
  }
  .toc a .hash{color:var(--accent)}
  .section{margin:20px 0}
  pre{margin:12px 0 18px; background:var(--code-bg); border:1px solid var(--stroke); border-radius:12px; padding:14px; overflow:auto}
  code{font-family:ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace; font-size:13.5px}
  .grid-2{display:grid; grid-template-columns:1fr; gap:18px}
  @media (min-width:900px){ .grid-2{grid-template-columns:1fr 1fr} }
  table{width:100%; border-collapse:separate; border-spacing:0; border:1px solid var(--stroke); border-radius:14px; overflow:hidden}
  th,td{padding:12px 14px; border-bottom:1px solid var(--stroke); vertical-align:top}
  th{background:#1a1a22; text-align:left; font-weight:700}
  tr:last-child td{border-bottom:none}
  .badge{
    display:inline-block; padding:4px 10px; border-radius:999px; border:1px solid var(--stroke);
    background:rgba(124,92,255,.12); color:#d7d3ff; font-size:12px; letter-spacing:.2px
  }
  .note{border-left:3px solid var(--accent-2); padding:10px 12px; background:rgba(45,212,191,.08); border-radius:10px; color:#befaf0}
  footer{margin-top:32px; color:#c9cce0}
  .copy-btn{
    user-select:none; float:right; margin-top:-36px; margin-right:6px; font-size:12px; padding:6px 10px;
    border-radius:999px; border:1px solid var(--stroke); background:rgba(255,255,255,.04); color:#cfd2e6; cursor:pointer;
  }
</style>
</head>
<body>
<header>
  <div class="title">
    <span class="dot" aria-hidden="true"></span>
    <h1>Organized Summary: Laravel 11 + Filament 3 + Spatie Permissions + Policies</h1>
  </div>
</header>

<div class="wrap">
  <div class="card">
    <p class="muted">— Goal:</p>
    <p>
      Build an admin panel using Filament with a role &amp; permission system (Spatie) and connect authorization via Policies,
      plus simple protection for some entities.
    </p>

    <h2 id="components">Summary Components</h2>
    <div class="toc">
      <a href="#req"><span class="hash">0)</span> Requirements</a>
      <a href="#install"><span class="hash">1)</span> Installation &amp; Setup</a>
      <a href="#permissions"><span class="hash">2)</span> Permissions Setup (Spatie)</a>
      <a href="#policies"><span class="hash">3)</span> Policies</a>
      <a href="#filament-ui"><span class="hash">4)</span> Integrate permissions with Filament UI</a>
      <a href="#extra-protection"><span class="hash">5)</span> Optional Protection Add-ons</a>
      <a href="#map"><span class="hash">6)</span> Project Map (summary table)</a>
      <a href="#migration"><span class="hash">7)</span> Example Migration for <code>users</code> with comments</a>
      <a href="#helpers"><span class="hash">8)</span> Helper Commands</a>
    </div>

  </div>

  <!-- 0) Requirements -->
  <div class="section card" id="req">
    <h2>0) Requirements</h2>
    <pre><code>- PHP 8.2+
- Laravel 11
- Supported databases (MySQL/Postgres ...)
- Composer</code></pre>
  </div>

  <!-- 1) Installation & Setup -->
  <div class="section card" id="install">
    <h2>1) Installation &amp; Setup</h2>

    <h3>Create Laravel 11 project</h3>
    <pre><code>composer create-project laravel/laravel:^11.0 myapp

cd myapp
php artisan serve</code></pre>

    <h3>Install Filament (Admin Panel)</h3>
    <pre><code>composer require filament/filament:"^3.3" -W

php artisan filament:install --panels
php artisan make:filament-user</code></pre>

    <h3>Install Spatie Permissions</h3>
    <pre><code>composer require spatie/laravel-permission:"^6.0"

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate</code></pre>

    <h3>Register Spatie middlewares in Laravel 11 (<code>bootstrap/app.php</code>)</h3>
    <pre><code>// -&gt;withMiddleware(function (Middleware $middleware) {

// $middleware-&gt;alias([
// 'role' =&gt; \Spatie\Permission\Middlewares\RoleMiddleware::class,
// 'permission' =&gt; \Spatie\Permission\Middlewares\PermissionMiddleware::class,
// 'role_or_permission' =&gt; \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
// ]);
// })</code></pre>

    <h3>Add Trait to the <code>User</code> model to use roles &amp; permissions</h3>
    <pre><code>// app/Models/User.php

// use Spatie\Permission\Traits\HasRoles;
// class User extends Authenticatable
// {
// use HasRoles; // adds relations and helpers: can/hasRole/assignRole...
// }</code></pre>

  </div>

  <!-- 2) Permissions Setup (Spatie) -->
  <div class="section card" id="permissions">
    <h2>2) Permissions Setup (Spatie)</h2>
    <p class="muted">Quick seeder to create permission groups and attach them to the <span class="badge">admin</span> role:</p>

    <pre><code>// database/seeders/PermissionsSeeder.php

// use Illuminate\Database\Seeder;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
// use Spatie\Permission\PermissionRegistrar;
//
// class PermissionsSeeder extends Seeder
// {
// public function run(): void
// {
// // clear Spatie's cached permissions
// app(PermissionRegistrar::class)-&gt;forgetCachedPermissions();
//
// $guard = config('auth.defaults.guard', 'web');
//
//         // permission groups (prefix.action)
//         $groups = [
//             'roles'             =&gt; ['view','create','update','delete'],
//             'platform_managers' =&gt; ['view','create','update','delete'],
//             'site_users'        =&gt; ['view','create','update','delete'],
//             'clients'           =&gt; ['view','create','update','delete'],
//         ];
//
//         foreach ($groups as $prefix =&gt; $actions) {
//             foreach ($actions as $action) {
//                 Permission::firstOrCreate([
//                     'name'       =&gt; "$prefix.$action",
//                     'guard_name' =&gt; $guard,
//                 ]);
//             }
//         }
//
//         // create admin role and attach all permissions
//         $admin = Role::firstOrCreate(['name' =&gt; 'admin', 'guard_name' =&gt; $guard]);
//         $admin-&gt;syncPermissions(Permission::all());
//
//         // example: promote a specific user to admin by email
//         if ($u = \App\Models\User::where('email', 'dev@admin.com')-&gt;first()) {
// $u-&gt;syncRoles(['admin']);
// }
//
// // clear cache again
// app(PermissionRegistrar::class)-&gt;forgetCachedPermissions();
// }
// }
//
// Run:
// php artisan db:seed --class=PermissionsSeeder</code></pre>

  </div>

  <!-- 3) Policies -->
  <div class="section card" id="policies">
    <h2>3) Policies</h2>

    <h3>Register policies in <code>App\Providers\AppServiceProvider::boot()</code></h3>
    <pre><code>// use Illuminate\Support\Facades\Gate;

// use App\Models\User;
// use App\Policies\UserPolicy;
// use Spatie\Permission\Models\Role as SpatieRole;
// use App\Policies\RolePolicy;
//
// public function boot(): void
// {
// // bind User model to UserPolicy
// Gate::policy(User::class, UserPolicy::class);
//
// // bind Spatie's Role model to RolePolicy
// Gate::policy(SpatieRole::class, RolePolicy::class);
// }</code></pre>

    <div class="grid-2">
      <div>
        <h3>A) <code>RolePolicy</code> (direct prefix = <code>roles</code>)</h3>
        <pre><code>// namespace App\Policies;

//
// use App\Models\User;
// use Spatie\Permission\Models\Role;
//
// class RolePolicy
// {
// public function viewAny(User $u): bool           { return $u-&gt;can('roles.view'); }
//     public function view(User $u, Role $r): bool     { return $u-&gt;can('roles.view'); }
//     public function create(User $u): bool            { return $u-&gt;can('roles.create'); }
//     public function update(User $u, Role $r): bool   { return $u-&gt;can('roles.update'); }
//     public function delete(User $u, Role $r): bool   { return $u-&gt;can('roles.delete'); }
//     public function deleteAny(User $u): bool         { return $u-&gt;can('roles.delete'); }
// }</code></pre>
      </div>
      <div>
        <h3>B) <code>UserPolicy</code> infers prefix from route name</h3>
        <p class="muted">Expected route name prefixes &rarr; permission prefixes:</p>
        <pre><code>admin.site-users.*        =&gt; site_users
admin.platform-managers.* =&gt; platform_managers
admin.clients.*           =&gt; clients</code></pre>
        <pre><code>// namespace App\Policies;
//
// use App\Models\User;
//
// class UserPolicy
// {
//     private function prefixFromRoute(): ?string
//     {
//         $name = request()-&gt;route()?-&gt;getName() ?? '';
//         return match (true) {
//             str_contains($name, '.site-users.') =&gt; 'site_users',
// str_contains($name, '.platform-managers.') =&gt; 'platform_managers',
//             str_contains($name, '.clients.') =&gt; 'clients',
// default =&gt; null,
// };
// }
//
// private function allow(User $user, string $action): bool
//     {
//         $p = $this-&gt;prefixFromRoute();
//         return $p ? $user-&gt;can("$p.$action") : false;
//     }
//
//     public function viewAny(User $user): bool              { return $this-&gt;allow($user, 'view'); }
// public function view(User $user, User $record): bool   { return $this-&gt;allow($user, 'view'); }
// public function create(User $user): bool               { return $this-&gt;allow($user, 'create'); }
// public function update(User $user, User $record): bool { return $this-&gt;allow($user, 'update'); }
// public function delete(User $user, User $record): bool { return $this-&gt;allow($user, 'delete'); }
// public function deleteAny(User $user): bool            { return $this-&gt;allow($user, 'delete'); }
// }</code></pre>
</div>
</div>

  </div>

  <!-- 4) Integrate with Filament UI -->
  <div class="section card" id="filament-ui">
    <h2>4) Integrate permissions with Filament UI</h2>
    <p>
      Real access control is enforced by Policies. To also hide navigation items in each Filament <em>Resource</em>
      based on the related <span class="badge">view</span> permission:
    </p>
    <pre><code>public static function shouldRegisterNavigation(): bool
{
    return auth()-&gt;user()?-&gt;can('site_users.view') ?? false; // change prefix per resource
}</code></pre>
  </div>

  <!-- 5) Optional Protection Add-ons -->
  <div class="section card" id="extra-protection">
    <h2>5) Optional Protection Add-ons</h2>

    <h3>Config to define a protected admin email (cannot be deleted or have type/email changed)</h3>
    <pre><code>// config/platform.php

// return [
// 'protected_admin_email' =&gt; env('PROTECTED_ADMIN_EMAIL', 'dev@admin.com'),
// ];
//
// .env
// PROTECTED_ADMIN_EMAIL=dev@admin.com</code></pre>

    <h3>Restrict Filament panel access to <code>type=admin</code></h3>
    <pre><code>// app/Models/User.php

// use Filament\Models\Contracts\FilamentUser;
// use Filament\Panel;
//
// class User extends Authenticatable implements FilamentUser
// {
// use HasRoles;
//
// protected static function booted(): void
// {
// // block deleting/updating the protected admin account
// static::deleting(function (User $user) {
//             if ($user-&gt;getOriginal('type') === 'admin'
// &amp;&amp; $user-&gt;getOriginal('email') === config('platform.protected_admin_email')) {
//                 throw new \RuntimeException('Deleting the protected platform manager is not allowed.');
//             }
//         });
//
//         static::updating(function (User $user) {
//             $isProtected = $user-&gt;getOriginal('type') === 'admin'
//                 &amp;&amp; $user-&gt;getOriginal('email') === config('platform.protected_admin_email');
//
//             if ($isProtected &amp;&amp; ($user-&gt;isDirty('email') || $user-&gt;isDirty('type'))) {
// throw new \RuntimeException('Updating the protected platform manager email/type is not allowed.');
// }
// });
// }
//
// public function canAccessPanel(Panel $panel): bool
// {
// return $panel-&gt;getId() === 'admin' &amp;&amp; $this-&gt;type === 'admin';
// }
// }</code></pre>

    <h3>Protect the <code>admin</code> role name via custom Role model extending Spatie Role</h3>
    <pre><code>// app/Models/Role.php

// namespace App\Models;
// use Spatie\Permission\Models\Role as SpatieRole;
// use Illuminate\Database\Eloquent\Builder;
//
// class Role extends SpatieRole
// {
// // these hooks prevent updating/deleting the protected role 'admin'.
// protected static function booted(): void
// {
// static::deleting(function (self $role) {
//             if ($role-&gt;name === 'admin') {
// throw new \RuntimeException('Deleting the protected role is not allowed.');
// }
// });
//
// static::updating(function (self $role) {
//             if ($role-&gt;getOriginal('name') === 'admin') {
// throw new \RuntimeException('Updating the protected role is not allowed.');
// }
// });
// }
//
// // hide the 'admin' role in UI lists (view-only concern).
// public static function getEloquentQuery(): Builder
// {
// return parent::getEloquentQuery()-&gt;where('name', '!=', 'admin');
// }
// }
// Note: if you replace the default Spatie Role model, set your model class in config/permission.php</code></pre>

  </div>

  <!-- 6) Project Map -->
  <div class="section card" id="map">
    <h2>6) Project Map (organizational summary)</h2>
    <div class="note">Sharing the same <code>User</code> model across multiple Resources depends on filtering by <code>type</code> within each Resource. Route names are important because they determine the permissions prefix inside <code>UserPolicy</code>.</div>
    <div style="margin:14px 0"></div>
    <table>
      <thead>
        <tr>
          <th>Unit / Entity</th>
          <th>Model</th>
          <th>Filament Resource</th>
          <th>Route name prefix (example)</th>
          <th>Permissions prefix</th>
          <th>Policy</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Platform Managers</td>
          <td><code>App\Models\User</code></td>
          <td><code>PlatformManagerResource</code></td>
          <td><code>admin.platform-managers.*</code></td>
          <td><code>platform_managers</code></td>
          <td><code>UserPolicy</code></td>
        </tr>
        <tr>
          <td>Site Users</td>
          <td><code>App\Models\User</code></td>
          <td><code>SiteUserResource</code></td>
          <td><code>admin.site-users.*</code></td>
          <td><code>site_users</code></td>
          <td><code>UserPolicy</code></td>
        </tr>
        <tr>
          <td>Clients</td>
          <td><code>App\Models\User</code></td>
          <td><code>ClientResource</code></td>
          <td><code>admin.clients.*</code></td>
          <td><code>clients</code></td>
          <td><code>UserPolicy</code></td>
        </tr>
        <tr>
          <td>Roles</td>
          <td><code>App\Models\Role</code></td>
          <td><code>RoleResource</code></td>
          <td><code>admin.roles.*</code></td>
          <td><code>roles</code></td>
          <td><code>RolePolicy</code></td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- 7) Migration -->
  <div class="section card" id="migration">
    <h2>7) Example Migration for <code>users</code> table with comments</h2>
    <pre><code>// database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php
//
// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;
//
// return new class extends Migration {
//     public function up(): void
//     {
//         Schema::create('users', function (Blueprint $table) {
//             $table-&gt;id();                                       // primary key
//             $table-&gt;string('name');                             // full name
//             $table-&gt;string('email')-&gt;unique();                  // email (unique)
//             $table-&gt;timestamp('email_verified_at')-&gt;nullable(); // email verified at
//             $table-&gt;string('password');                         // password (hash)
//             $table-&gt;enum('type', ['admin','user','client']);    // user classification: admin (panel), user (site), client
//             $table-&gt;rememberToken();                            // "remember me" token
//             $table-&gt;timestamps();                               // created_at / updated_at
//         });
//
//         // optional: helper index for type-based queries
//         Schema::table('users', function (Blueprint $table) {
//             $table-&gt;index('type');                              // improves resource filters by type
//         });
//     }
//
//     public function down(): void
//     {
//         Schema::dropIfExists('users');
//     }
// };</code></pre>
  </div>

  <!-- 8) Helper Commands -->
  <div class="section card" id="helpers">
    <h2>8) Helper Commands</h2>
    <pre><code>php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan permission:cache-reset
// After adding/updating permissions or roles, it's recommended to clear caches</code></pre>
  </div>

  <footer class="card">
    <h3>Conclusion</h3>
    <p>
      With this setup: we installed Filament, prepared Spatie Permissions, registered and wired Policies to routes/entities,
      and added a light protection layer for sensitive operations. The “Project Map” helps any new developer quickly grasp
      how the work is organized.
    </p>
  </footer>
</div>

<script>
  // Optional: add "Copy" buttons for all <pre><code> blocks
  document.querySelectorAll('pre').forEach((pre) => {
    const btn = document.createElement('button');
    btn.className = 'copy-btn';
    btn.textContent = 'Copy';
    btn.addEventListener('click', async () => {
      const text = pre.innerText;
      try {
        await navigator.clipboard.writeText(text);
        const old = btn.textContent;
        btn.textContent = 'Copied ✓';
        setTimeout(() => (btn.textContent = old), 1200);
      } catch {
        btn.textContent = 'Failed';
        setTimeout(() => (btn.textContent = 'Copy'), 1200);
      }
    });
    pre.parentNode.insertBefore(btn, pre);
  });
</script>
</body>
</html>
