<!-- README: Laravel 11 + Filament 3 + Spatie Permissions + Policies -->
<div class="readme-container">
  <style>
    .readme-container{--bg:#0b0c10;--panel:#12141a;--muted:#8b95a7;--text:#e6e9ef;--accent:#7c3aed;--accent-2:#06b6d4;--ok:#22c55e;--warn:#eab308;--danger:#ef4444;--border:#222633;--code:#0f1117;--kbd:#161a22;--chip:#1a1f2b;--shadow:0 10px 30px rgba(2,6,23,.35);font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,"Helvetica Neue",Arial,"Apple Color Emoji","Segoe UI Emoji";line-height:1.65;background:linear-gradient(180deg,#0a0b10 0%,#0d1018 100%);color:var(--text);padding:28px;border-radius:18px;border:1px solid var(--border)}
    .readme-header{display:flex;gap:14px;align-items:center;flex-wrap:wrap;margin-bottom:18px}
    .badge{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--accent),var(--accent-2));color:white;padding:6px 12px;border-radius:999px;font-weight:600;font-size:12px;letter-spacing:.3px;box-shadow:var(--shadow)}
    .title{font-size:28px;font-weight:800;letter-spacing:.3px;margin:6px 0 4px}
    .subtitle{color:var(--muted);font-size:14px;margin:0 0 8px}
    .grid{display:grid;gap:16px}
    @media(min-width:880px){.grid{grid-template-columns:1.1fr .9fr}}
    .card{background:linear-gradient(180deg,#0f1220 0%,#0c0f18 100%);border:1px solid var(--border);border-radius:14px;padding:18px;box-shadow:var(--shadow)}
    .card h3{margin:0 0 10px;font-size:16px;letter-spacing:.25px}
    .card p{margin:0 0 10px}
    .list{margin:0 0 6px 0;padding-left:18px}
    .list li{margin:4px 0}
    .kpi{display:flex;gap:10px;flex-wrap:wrap;margin:10px 0 2px}
    .chip{background:var(--chip);border:1px solid var(--border);border-radius:999px;padding:6px 10px;font-size:12px}
    .code{background:var(--code);border:1px solid var(--border);border-radius:12px;padding:12px;margin:8px 0;font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;font-size:12.5px;overflow:auto}
    .two-col{display:grid;gap:12px}
    @media(min-width:720px){.two-col{grid-template-columns:1fr 1fr}}
    .table{width:100%;border-collapse:separate;border-spacing:0;border:1px solid var(--border);border-radius:12px;overflow:hidden;background:#0c0f18}
    .table th,.table td{padding:10px 12px;border-bottom:1px solid var(--border);font-size:13px;vertical-align:top}
    .table th{background:#0f1220;text-align:left;color:#cbd5e1;font-weight:700}
    .table tr:last-child td{border-bottom:none}
    .tag{font-weight:700;color:#a5b4fc}
    .ok{color:var(--ok)} .warn{color:var(--warn)} .danger{color:var(--danger)}
    .foot{margin-top:16px;color:var(--muted);font-size:12.5px}
    /* Light mode fallback */
    @media(prefers-color-scheme:light){
      .readme-container{--bg:#ffffff;--panel:#ffffff;--muted:#5b6072;--text:#0b1220;--border:#e6e8ee;--code:#f6f8ff;--kbd:#eef1fb;--chip:#f5f7fe;box-shadow:none;background:#fff}
      .card{background:#fff;box-shadow:0 8px 24px rgba(16,24,40,.06)}
      .table{background:#fff}
    }
  </style>

  <div class="readme-header">
    <span class="badge">Laravel 11 • Filament 3 • Spatie Permissions</span>
    <div>
      <h1 class="title">Policy-Driven Admin Panel Starter</h1>
      <p class="subtitle">Clean setup for roles & permissions, Filament UI, and hardened access rules.</p>
      <div class="kpi">
        <span class="chip">Laravel 11</span>
        <span class="chip">PHP 8.2+</span>
        <span class="chip">Filament 3</span>
        <span class="chip">Spatie/laravel-permission</span>
        <span class="chip">Gate & Policies</span>
      </div>
    </div>
  </div>

  <div class="grid">
    <div class="card">
      <h3>🎯 Goal</h3>
      <p>
        Ship an admin panel that uses <strong>Spatie roles/permissions</strong> enforced via <strong>Laravel Policies</strong>, with Filament navigation that respects abilities and a few protective hooks for critical users/roles.
      </p>

      <h3>🧱 Stack & Requirements</h3>
      <ul class="list">
        <li>PHP <strong>8.2+</strong>, Laravel <strong>11</strong></li>
        <li>Database: MySQL/Postgres</li>
        <li>Composer</li>
      </ul>

      <h3>⚡ Quick Start</h3>
      <pre class="code"><code># Laravel

composer create-project laravel/laravel:^11.0 myapp
cd myapp && php artisan serve

# Filament (Admin Panel)

composer require filament/filament:"^3.3" -W
php artisan filament:install --panels
php artisan make:filament-user

# Spatie Permissions

composer require spatie/laravel-permission:"^6.0"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# Register Spatie middlewares (bootstrap/app.php)

# ->withMiddleware(fn (Middleware $m) => $m->alias([

# 'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,

# 'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,

# 'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,

# ]));</code></pre>

    </div>

    <div class="card">
      <h3>🔐 Core Ideas</h3>
      <ul class="list">
        <li><strong>Spatie Permissions</strong>: fine-grained abilities like <code>roles.view</code>, <code>clients.update</code>…</li>
        <li><strong>Policies</strong>: real authorization at the model/resource layer.</li>
        <li><strong>Filament</strong>: hide resources in navigation via <code>shouldRegisterNavigation()</code> so UI mirrors auth.</li>
        <li><strong>Hardening</strong>: guard a protected admin and the <code>admin</code> role from deletion/rename.</li>
      </ul>

      <div class="two-col">
        <div>
          <h3>🧩 Trait on User</h3>
          <pre class="code"><code>// app/Models/User.php

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
use HasRoles; // adds can()/hasRole()/assignRole()...
}</code></pre>
</div>
<div>
<h3>🗂️ Seed Permissions</h3>
<pre class="code"><code>// database/seeders/PermissionsSeeder.php (gist)
groups = {
roles: ['view','create','update','delete'],
platform_managers: ['view','create','update','delete'],
site_users: ['view','create','update','delete'],
clients: ['view','create','update','delete'],
};

# Create permissions, assign all to role 'admin'

# Optionally promote a known email to admin

php artisan db:seed --class=PermissionsSeeder</code></pre>
</div>
</div>
</div>

  </div>

  <div class="grid" style="margin-top:16px">
    <div class="card">
      <h3>🛡️ Policies Wiring</h3>
      <p>Register policies in <code>AppServiceProvider::boot()</code> for both your <code>User</code> model and Spatie’s <code>Role</code> model.</p>
      <ul class="list">
        <li><strong>RolePolicy</strong>: maps directly to <code>roles.*</code> permissions.</li>
        <li><strong>UserPolicy</strong>: infers permission prefix from route name:
          <div class="kpi" style="margin-top:6px">
            <span class="chip"><code>admin.site-users.* → site_users</code></span>
            <span class="chip"><code>admin.platform-managers.* → platform_managers</code></span>
            <span class="chip"><code>admin.clients.* → clients</code></span>
          </div>
        </li>
      </ul>
      <p class="subtitle">This keeps authorization centralized and consistent with route naming.</p>

      <h3>🧭 Filament Integration</h3>
      <pre class="code"><code>// In each Resource

public static function shouldRegisterNavigation(): bool
{
return auth()->user()?->can('site_users.view') ?? false; // adjust per resource
}</code></pre>
</div>

    <div class="card">
      <h3>🔒 Optional Hardening</h3>
      <ul class="list">
        <li><strong>Protected Admin Email</strong> in <code>config/platform.php</code> and <code>.env</code> prevents deleting or changing the critical account/type.</li>
        <li><strong>Panel Access</strong>: only <code>type=admin</code> can access the admin panel.</li>
        <li><strong>Custom Role Model</strong>: extend Spatie’s Role to forbid deleting/renaming the <code>admin</code> role and optionally hide it from index views.</li>
      </ul>
      <pre class="code"><code>// .env

PROTECTED_ADMIN_EMAIL=dev@admin.com</code></pre>
</div>

  </div>

  <div class="card" style="margin-top:16px">
    <h3>🗺️ Project Map</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Module</th>
          <th>Model</th>
          <th>Filament Resource</th>
          <th>Route Name Pattern</th>
          <th>Permission Prefix</th>
          <th>Policy</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Platform Managers</td>
          <td><code>App\Models\User</code></td>
          <td><code>PlatformManagerResource</code></td>
          <td><code>admin.platform-managers.*</code></td>
          <td class="tag">platform_managers</td>
          <td><code>UserPolicy</code></td>
        </tr>
        <tr>
          <td>Site Users</td>
          <td><code>App\Models\User</code></td>
          <td><code>SiteUserResource</code></td>
          <td><code>admin.site-users.*</code></td>
          <td class="tag">site_users</td>
          <td><code>UserPolicy</code></td>
        </tr>
        <tr>
          <td>Clients</td>
          <td><code>App\Models\User</code></td>
          <td><code>ClientResource</code></td>
          <td><code>admin.clients.*</code></td>
          <td class="tag">clients</td>
          <td><code>UserPolicy</code></td>
        </tr>
        <tr>
          <td>Roles</td>
          <td><code>App\Models\Role</code></td>
          <td><code>RoleResource</code></td>
          <td><code>admin.roles.*</code></td>
          <td class="tag">roles</td>
          <td><code>RolePolicy</code></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="two-col" style="margin-top:16px">
    <div class="card">
      <h3>🧾 Users Table (Essentials)</h3>
      <p>Typical columns:</p>
      <ul class="list">
        <li><code>name</code>, <code>email</code> (unique), <code>password</code>, <code>email_verified_at</code></li>
        <li><code>type</code>: <code>admin</code> | <code>user</code> | <code>client</code> (helps filter resources)</li>
        <li>Indexes on <code>type</code> recommended</li>
      </ul>
    </div>
    <div class="card">
      <h3>🧹 Helpful Commands</h3>
      <pre class="code"><code>php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan permission:cache-reset   # after changing roles/permissions</code></pre>
      <p class="subtitle">Tip: reset the Spatie cache whenever you add/edit permissions.</p>
    </div>
  </div>

  <p class="foot">
    Built for a fast, opinionated bootstrap: install Filament, seed Spatie permissions, wire Policies, and add light guards. The map above helps onboard new contributors at a glance.
  </p>
</div>
