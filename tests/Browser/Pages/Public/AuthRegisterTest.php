<?php

namespace Tests\Browser\Pages\Public;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthRegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_register_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('signup'))
                ->assertSee('Create Account')
                ->assertSee('Sign up to get started')
                ->assertVisible('input[name="name"]')
                ->assertVisible('input[name="email"]')
                ->assertVisible('input[name="password"]');
        });
    }

    public function test_seller_type_toggle_works(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('signup', ['type' => 'seller']))
                ->assertVisible('#seller_fields')
                ->assertMissing('#business_name_field:not(.hidden)')
                ->radio('seller_type', 'zakelijk')
                ->assertVisible('#business_name_field:not(.hidden)')
                ->radio('seller_type', 'particulier')
                ->assertMissing('#business_name_field:not(.hidden)');
        });
    }
}
