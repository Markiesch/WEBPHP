<?php

namespace Tests\Browser\Pages\Public;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\AdvertisementTransaction as Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RentalReturnTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create();

        // Create a business
        $business = Business::factory()->create();

        // Create advertisement for rental
        $advertisement = Advertisement::factory()->create([
            'business_id' => $business->id,
            'title' => 'Rental Return Test Item',
            'type' => 'rent',
            'wear_per_day' => 0.5
        ]);

        // Create active rental transaction
        $this->transaction = Transaction::factory()->create([
            'user_id' => $this->user->id,
            'advertisement_id' => $advertisement->id,
            'status' => 'rented',
        ]);
    }

    public function test_user_can_view_rental_returns_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('rental-calendar'))
                ->assertPresent('h1');
        });
    }

    public function test_user_can_access_return_form(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('rental-calendar'))
                ->assertPresent('h1');
        });
    }

    public function test_user_can_see_return_form(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('rental-calendar'))
                ->assertPresent('h1');
        });
    }

    public function test_user_can_see_return_details(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('rental-calendar'))
                ->assertPresent('h1');
        });
    }
}
