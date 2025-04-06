<?php

namespace Tests\Browser;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\BusinessBlock;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BusinessTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Business $business;

    public function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create business for the user
        $this->business = Business::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Business',
            'url' => 'test-business'
        ]);

        // Create a sample block
        BusinessBlock::create([
            'business_id' => $this->business->id,
            'type' => 'intro_text',
            'content' => [
                'title' => 'Welcome Section',
                'text' => '<p>This is a test description</p>',
            ],
            'order' => 1,
        ]);
    }

    public function testBusinessPageLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.business.index'))
                ->pause(1000)
                ->screenshot('business-page')
                ->assertPresent('.uk-container')
                ->assertPresent('.uk-card-body');
        });
    }

    public function testBlockManagement(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.business.index'))
                ->assertSee('Welcome Section')
                ->assertSee('This is a test description')
                ->assertPresent('#blockContainer');
        });
    }

    public function testBusinessSettingsForm(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.business.index'))
                ->assertInputValue('name', 'Test Business')
                ->assertInputValue('url', 'test-business');
        });
    }
}
