<?php

namespace Tests\Browser\controller;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AdvertisementTest extends DuskTestCase
{
    private User $user;
    private Business $business;
    private Advertisement $saleAd;
    private Advertisement $rentalAd;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = new User();
        $this->user->name = 'Test User';
        $this->user->email = 'test@example.com';
        $this->user->password = bcrypt('password');
        $this->user->save();

        // Create test business
        $this->business = new Business();
        $this->business->name = 'Test Business';
        $this->business->user_id = $this->user->id;
        $this->business->save();

        // Create sale advertisement
        $this->saleAd = new Advertisement();
        $this->saleAd->title = 'Test Sale Item';
        $this->saleAd->description = 'Test Description';
        $this->saleAd->price = 99.99;
        $this->saleAd->wear_percentage = 10;
        $this->saleAd->business_id = $this->business->id;
        $this->saleAd->type = Advertisement::TYPE_SALE;
        $this->saleAd->save();

        // Create rental advertisement
        $this->rentalAd = new Advertisement();
        $this->rentalAd->title = 'Test Rental Item';
        $this->rentalAd->description = 'Rental Description';
        $this->rentalAd->price = 25.00;
        $this->rentalAd->wear_percentage = 20;
        $this->rentalAd->wear_per_day = 0.5;
        $this->rentalAd->business_id = $this->business->id;
        $this->rentalAd->type = Advertisement::TYPE_RENTAL;
        $this->rentalAd->rental_start_date = now();
        $this->rentalAd->rental_end_date = now()->addDays(7);
        $this->rentalAd->save();
    }

    protected function tearDown(): void
    {
        $this->saleAd->delete();
        $this->rentalAd->delete();
        $this->business->delete();
        $this->user->delete();
        parent::tearDown();
    }

    public function testindex() {
        $this->browse(function (Browser $browser) {
            $browser->loginAS(1)
                ->visitRoute('advertisement.index')
                ->assertSee('advertisements');
        });
    }

    public function testViewAdvertisementsList()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/advertisements')
                ->assertSee('Test Sale Item')
                ->assertSee('Test Rental Item')
                ->assertSee('99.99')
                ->assertSee('25.00');
        });
    }

    public function testViewSaleAdvertisement()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/advertisement/' . $this->saleAd->id)
                ->assertSee('Test Sale Item')
                ->assertSee('99.99')
                ->assertSee('For Sale')
                ->assertSee('10%');
        });
    }

    public function testViewRentalAdvertisement()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/advertisement/' . $this->rentalAd->id)
                ->assertSee('Test Rental Item')
                ->assertSee('25.00')
                ->assertSee('For Rent')
                ->assertSee('20%')
                ->assertSee('0.50');
        });
    }

    public function testSortAdvertisements()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/advertisements?sort_by=price&direction=asc')
                ->assertSeeIn('.advertisement-list', 'Test Rental Item')
                ->assertSeeIn('.advertisement-list', 'Test Sale Item');
        });
    }

    public function testFilterByType()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/advertisements?type=' . Advertisement::TYPE_SALE)
                ->assertSee('Test Sale Item')
                ->assertDontSee('Test Rental Item')
                ->visit('/advertisements?type=' . Advertisement::TYPE_RENTAL)
                ->assertSee('Test Rental Item')
                ->assertDontSee('Test Sale Item');
        });
    }

    public function testPurchaseAdvertisement()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/advertisement/' . $this->saleAd->id)
                ->press('Purchase')
                ->waitForText('Purchase successful')
                ->assertUrlIs('/advertisement/' . $this->saleAd->id);
        });
    }
}
