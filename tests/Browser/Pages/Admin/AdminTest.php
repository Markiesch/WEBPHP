<?php

namespace Tests\Browser\Pages\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\DuskTestCase;

class AdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpPermissionsAndRoles();
        $this->admin = $this->createAdminUser();
    }

    /**
     * Set up permissions and assign them to the admin role.
     */
    protected function setUpPermissionsAndRoles(): void
    {
        if (!class_exists(Role::class) || !class_exists(Permission::class)) {
            return;
        }

        // Define necessary permissions
        $permissions = collect([
            'view contracts',
            'create contracts',
            'edit contracts',
        ]);

        // Create or fetch permissions
        $permissions->each(function ($permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        });

        // Create or update admin role and sync permissions
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $adminRole->syncPermissions($permissions->toArray());
    }

    /**
     * Create and return an admin user with the admin role.
     */
    protected function createAdminUser(): User
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        if (class_exists(Role::class)) {
            $admin->assignRole('admin');
        }

        return $admin;
    }

    /**
     * Test that an admin can log in and access the contracts page.
     *
     * @return void
     */
    public function testAdminLogin(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit('/admin/contracts')
                ->screenshot('admin-login')
                ->assertPathIs('/admin/contracts');
        });
    }
}
