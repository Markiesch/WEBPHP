<?php

namespace Tests\Browser\controller;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SellerAdvertisementTest extends DuskTestCase
{
    use DatabaseMigrations;

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
        $this->user->email = 'test' . time() . '@example.com';
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

        // Ensure directories exist
        if (!file_exists(storage_path('app/public/images'))) {
            mkdir(storage_path('app/public/images'), 0777, true);
        }

        // Create a test image file
        file_put_contents(
            storage_path('app/public/images/test-image.jpg'),
            'test image content'
        );

        // Create a test CSV file
        file_put_contents(
            storage_path('app/public/advertisements.csv'),
            "title,description,price,wear_percentage,type\n" .
            "CSV Item 1,CSV Description 1,150.00,5,sale\n" .
            "CSV Item 2,CSV Description 2,75.50,15,sale"
        );
    }

    protected function tearDown(): void
    {
        if (isset($this->saleAd)) $this->saleAd->delete();
        if (isset($this->rentalAd)) $this->rentalAd->delete();
        if (isset($this->business)) $this->business->delete();
        if (isset($this->user)) $this->user->delete();

        // Remove test files
        @unlink(storage_path('app/public/advertisements.csv'));
        @unlink(storage_path('app/public/images/test-image.jpg'));

        parent::tearDown();
    }

    public function testAdvertisementsIndex()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('seller.advertisements.index')
                ->assertSee('Advertisements')
                ->assertSee($this->saleAd->title)
                ->assertSee($this->rentalAd->title);
        });
    }

    public function testAdvertisementsCreate()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('seller.advertisements.create')
                ->assertSee('Create Advertisement');
        });
    }


    public function testAdvertisementsShow()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('seller.advertisements.show', $this->saleAd->id)
                ->assertSee($this->saleAd->title)
                ->assertSee($this->saleAd->description)
                ->assertSee(number_format($this->saleAd->price, 2));
        });
    }

    public function testAdvertisementsEdit()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('seller.advertisements.edit', $this->saleAd->id)
                ->assertInputValue('title', $this->saleAd->title)
                ->assertInputValue('description', $this->saleAd->description)
                ->assertInputValue('price', (string)$this->saleAd->price)
                ->assertSelected('type', $this->saleAd->type);
        });
    }

    public function testAdvertisementsUploadCsv()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('seller.advertisements.upload-csv')
                ->assertSee('Upload CSV');
        });
    }

    public function testEditRelatedAdvertisements()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visitRoute('seller.advertisements.edit-related', $this->saleAd->id)
                ->assertSee('Manage Related Advertisements')
                ->assertSee($this->rentalAd->title);
        });
    }
}
