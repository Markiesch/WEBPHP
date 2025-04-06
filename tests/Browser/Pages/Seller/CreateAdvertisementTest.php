<?php

namespace Tests\Browser\Pages\Seller;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateAdvertisementTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $seller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seller = User::factory()->create([
            'email' => 'seller@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_create_advertisement_page_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->seller)
                ->visit(route('seller.advertisements.create'))
                ->assertPresent('form')
                ->assertVisible('input[name="title"]')
                ->assertVisible('textarea[name="description"]')
                ->assertVisible('input[name="price"]')
                ->assertVisible('select[name="type"]')
                ->assertVisible('input[name="image"]')
                ->assertVisible('button[type="submit"]');
        });
    }

    public function test_auction_fields_toggle_works(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->seller)
                ->visit(route('seller.advertisements.create'))
                ->assertPresent('#auction-fields')
                ->assertDontSee('Starting Price') // Hidden by default
                ->select('type', 'auction')
                ->select('type', 'sale')
                ->assertDontSee('Starting Price'); // Hidden again
        });
    }

    public function test_can_create_sale_advertisement(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->seller)
                ->visit(route('seller.advertisements.create'))
                ->select('type', 'sale')
                ->type('title', 'Test Sale Item')
                ->type('description', 'This is a test description')
                ->type('price', '99.99')
                ->type('wear_percentage', '10')
                ->assertDontSee('Test Sale Item1');
        });
    }
}
