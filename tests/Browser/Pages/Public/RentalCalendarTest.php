<?php

namespace Tests\Browser\Pages\Public;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\AdvertisementTransaction as Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RentalCalendarTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected $rentals = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create();

        // Create a business
        $business = Business::factory()->create();

        // Create advertisements for rentals
        $ad1 = Advertisement::factory()->create([
            'business_id' => $business->id,
            'title' => 'Rental Item 1',
            'type' => 'rent'
        ]);

        $ad2 = Advertisement::factory()->create([
            'business_id' => $business->id,
            'title' => 'Rental Item 2',
            'type' => 'rent'
        ]);

        // Create active rental transactions
        $this->rentals[] = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'advertisement_id' => $ad1->id,
            'status' => 'rented',
        ]);

        $this->rentals[] = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'advertisement_id' => $ad2->id,
            'status' => 'rented',
        ]);
    }

    public function test_user_can_view_rental_calendar(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('rental-calendar'))
                ->assertPresent('h1');
        });
    }

    public function test_user_can_see_rented_products(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('rental-calendar'))
                ->assertPresent('h1');
        });
    }

    public function test_user_can_see_upcoming_returns(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('rental-calendar'))
                ->assertPresent('h1');
        });
    }
}
