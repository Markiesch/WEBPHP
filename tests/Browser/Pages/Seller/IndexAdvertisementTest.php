<?php

namespace Tests\Browser;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IndexAdvertisementTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Business $business;
    protected Advertisement $saleAd;
    protected Advertisement $rentalAd;

    public function setUp(): void
    {
        parent::setUp();

        // Create user with business
        $this->user = User::factory()->create();
        $this->business = Business::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Shop',
        ]);

        // Create sample sale advertisement
        $this->saleAd = Advertisement::factory()->create([
            'business_id' => $this->business->id,
            'title' => 'Test Sale Product',
            'description' => 'This is a test sale product description',
            'price' => 99.99,
            'type' => 'sale',
            'wear_percentage' => 25,
        ]);

        // Create sample rental advertisement
        $this->rentalAd = Advertisement::factory()->create([
            'business_id' => $this->business->id,
            'title' => 'Test Rental Product',
            'description' => 'This is a test rental product description',
            'price' => 49.99,
            'type' => 'rental',
            'wear_percentage' => 40,
            'wear_per_day' => 0.5,
            'rental_start_date' => now(),
            'rental_end_date' => now()->addDays(30),
        ]);
    }

    public function testAdvertisementIndexPageLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.advertisements.index'))
                ->screenshot('advertisement-index')
                ->assertPresent('.uk-card')
                ->assertSee('Test Sale Product')
                ->assertSee('Test Rental Product');
        });
    }

    public function testTypeFilteringWorks(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.advertisements.index'))
                ->click('.uk-btn-sm.min-w-\[100px\]:nth-child(3)')
                ->pause(500)
                ->screenshot('advertisement-filter-rental')
                ->assertSee('Test Rental Product')
                ->assertDontSee('Test Sale Product');
        });
    }

    public function testActionButtonsArePresent(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.advertisements.index'))
                ->assertPresent('a[href="' . route('seller.advertisements.create') . '"]')
                ->assertPresent('a[href="' . route('seller.advertisements.upload-csv') . '"]')
                ->assertPresent('a[href="' . route('seller.advertisements.edit', $this->saleAd->id) . '"]')
                ->assertPresent('a[href="' . route('seller.advertisements.show', $this->saleAd->id) . '"]');
        });
    }

    public function testTableDisplaysCorrectColumns(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.advertisements.index'))
                ->assertSee('€99.99')
                ->assertSee('25%')
                ->assertSee('0.50%')
                ->assertPresent('.uk-badge-success') // For wear percentage < 50
                ->assertPresent('span.uk-badge:nth-child(1)'); // Type badge
        });
    }
}
