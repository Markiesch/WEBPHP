<?php

namespace Tests\Browser\Pages\Public;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\AdvertisementReview as Review;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdvertisementsShowTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected Advertisement $advertisement;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $business = Business::factory()->create();
        $this->advertisement = Advertisement::factory()->create([
            'business_id' => $business->id,
            'title' => 'Test Advertisement',
            'description' => 'This is a test advertisement description',
            'price' => 99.99,
            'type' => 'sale'
        ]);

        $this->user = User::factory()->create();

        // Create some reviews for the advertisement
        Review::factory()->count(3)->create([
            'advertisement_id' => $this->advertisement->id,
        ]);
    }

    public function test_user_can_view_advertisement_details(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('advertisement', $this->advertisement->id))
                ->assertSee($this->advertisement->title)
                ->assertSee($this->advertisement->description)
                ->assertSee(number_format($this->advertisement->price, 2));
        });
    }

    public function test_user_can_see_business_information(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('advertisement', $this->advertisement->id))
                ->assertSee($this->advertisement->business->name)
                ->assertVisible('a[href="' . route('business-page', $this->advertisement->business->url) . '"]');
        });
    }
}
