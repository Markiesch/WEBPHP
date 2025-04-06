<?php

namespace Tests\Browser;

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

        // Create a sample intro_text block
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

    public function testAddNewBlock(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.business.index'))
                ->select('type', 'featured_ads')
                ->press('.uk-btn-primary.flex-shrink-0')
                ->pause(1000)
                ->screenshot('after-add-block')
                ->assertSee('Featured Listings')
                ->assertPresent('.rounded-none.uk-card-body.border-b:nth-child(2)');
        });
    }

    public function testEditBlockToggleModal(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.business.index'))
                ->click('.uk-btn-sm.uk-btn-default[data-uk-toggle]')
                ->pause(1000)
                ->screenshot('edit-block-modal')
                ->assertPresent('.uk-modal-dialog')
                ->assertPresent('input[name="content[title]"]')
                ->assertInputValue('content[title]', 'Welcome Section');
        });
    }

    public function testBlockOrderingButtons(): void
    {
        BusinessBlock::create([
            'business_id' => $this->business->id,
            'type' => 'featured_ads',
            'content' => [
                'title' => 'Featured Products',
                'count' => 3,
            ],
            'order' => 2,
        ]);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.business.index'))
                ->screenshot('before-ordering')
                ->assertSeeIn('.rounded-none.uk-card-body.border-b:nth-child(1)', 'Welcome Section')
                ->assertSeeIn('.rounded-none.uk-card-body.border-b:nth-child(2)', 'Featured Products')
                ->assertPresent('button[type="submit"] uk-icon[icon="move-down"]');
        });
    }

    public function testEmptyBlocksMessage(): void
    {
        // Delete all blocks to test empty state
        BusinessBlock::where('business_id', $this->business->id)->delete();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.business.index'))
                ->screenshot('empty-blocks')
                ->assertPresent('.bg-gray-100.rounded-lg.p-8');
        });
    }
}
