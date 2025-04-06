<?php

namespace Tests\Browser\Pages\Public;

use App\Models\Business;
use App\Models\BusinessReview;
use App\Models\Advertisement;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BusinessPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected Business $business;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->business = Business::factory()->create([
            'name' => 'Test Business',
            'url' => 'test-business'
        ]);

        // Create some advertisements for the business
        Advertisement::factory()->count(3)->create([
            'business_id' => $this->business->id,
        ]);

        // Create a user
        $this->user = User::factory()->create();

        // Create some reviews for the business
        BusinessReview::factory()->count(2)->create([
            'business_id' => $this->business->id,
        ]);
    }

    public function test_user_can_view_business_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('business-page', $this->business->url))
                ->assertSee($this->business->name)
                ->assertSee('Advertisements')
                ->assertSee('Reviews');
        });
    }

    public function test_user_can_see_business_advertisements(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('business-page', $this->business->url));
            
            // Check if advertisements are visible
            foreach ($this->business->advertisements as $advertisement) {
                $browser->assertSee($advertisement->title);
            }
        });
    }

    public function test_user_can_see_business_reviews(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('business-page', $this->business->url));
            
            // Check if reviews section is visible
            $browser->assertPresent('.uk-card-body')
                ->assertSee('Reviews');
            
            // Check if reviews are displayed
            foreach ($this->business->reviews as $review) {
                $browser->assertSee($review->user->name);
            }
        });
    }
}
