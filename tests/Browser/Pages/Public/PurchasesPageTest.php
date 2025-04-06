<?php

namespace Tests\Browser\Pages\Public;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\AdvertisementTransaction as Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PurchasesPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected array $advertisements;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user
        $this->user = User::factory()->create();

        // Create a business
        $business = Business::factory()->create();

        // Create advertisements with transactions
        $this->advertisements = [];

        // Create active purchase
        $ad1 = Advertisement::factory()->create([
            'business_id' => $business->id,
            'title' => 'Active Purchase Item',
            'price' => 99.99
        ]);
        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'advertisement_id' => $ad1->id,
            'status' => 'completed'
        ]);
        $this->advertisements[] = $ad1;

        // Create returned purchase
        $ad2 = Advertisement::factory()->create([
            'business_id' => $business->id,
            'title' => 'Returned Item',
            'price' => 149.99
        ]);
        Transaction::factory()->create([
            'user_id' => $this->user->id,
            'advertisement_id' => $ad2->id,
            'status' => 'returned',
            'return_date' => now()
        ]);
        $this->advertisements[] = $ad2;
    }

    public function test_user_can_see_search_filters(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('purchase.history'))
                ->assertPresent('input[name="search"]')
                ->assertPresent('select[name="sort"]')
                ->assertPresent('input[name="favorite"]');
        });
    }
}
