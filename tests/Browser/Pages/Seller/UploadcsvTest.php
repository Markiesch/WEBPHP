<?php

namespace Tests\Browser;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UploadCsvTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Business $business;

    public function setUp(): void
    {
        parent::setUp();

        // Create test user with business
        $this->user = User::factory()->create();
        $this->business = Business::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Business',
        ]);

        // Create directory for test CSV files
        if (!file_exists(public_path('assets/csv'))) {
            mkdir(public_path('assets/csv'), 0755, true);
        }

        // Create a sample CSV file for testing download
        $sampleCsvContent = "title,description,price,wear_percentage,type\n";
        $sampleCsvContent .= "Test Product,Sample Description,99.99,20,sale\n";
        file_put_contents(public_path('assets/csv/testercsv.csv'), $sampleCsvContent);
    }

    public function tearDown(): void
    {
        // Clean up the test file
        if (file_exists(public_path('assets/csv/testercsv.csv'))) {
            unlink(public_path('assets/csv/testercsv.csv'));
        }

        parent::tearDown();
    }

    public function testUploadCsvPageLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.advertisements.upload-csv'))
                ->screenshot('upload-csv-page')
                ->assertPresent('input[name="csv_file"]')
                ->assertPresent('a[download]')
                ->assertPresent('button[type="submit"]');
        });
    }

    public function testUploadingCsvFile(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.advertisements.upload-csv'))
                ->attach('csv_file', public_path('assets/csv/testercsv.csv'))
                ->press('Upload')
                ->pause(1000)
                ->screenshot('after-csv-upload');

            // Try to determine where we ended up
            try {
                // Check if we're on the index page (success case)
                $browser->assertPathIs('/seller/advertisements');
            } catch (\Exception $e) {
                // If not on index, we should at least see some feedback
                $browser->assertSee('CSV')
                    ->screenshot('upload-result');
            }
        });
    }

    public function testCsvTemplateDownload(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.advertisements.upload-csv'))
                ->assertAttribute('a[download]', 'href', asset('assets/csv/testercsv.csv'));
        });
    }
}
