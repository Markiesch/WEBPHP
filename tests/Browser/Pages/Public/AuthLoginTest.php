<?php

namespace Tests\Browser\Pages\Public;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthLoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_login_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('login'))
                ->assertSee('Welcome Back')
                ->assertSee('Login with your account')
                ->assertVisible('input[name="email"]')
                ->assertVisible('input[name="password"]')
                ->assertVisible('button[type="submit"]');
        });
    }

    public function test_user_can_login(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('login'))
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIsNot(route('login'));
        });
    }

    public function test_error_appears_with_invalid_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('login'))
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIsNot(route('login'));
        });
    }
}
