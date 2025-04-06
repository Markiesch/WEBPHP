<?php

namespace Tests\Browser\Pages\Seller;

use App\Models\Business;
use App\Models\User;
use App\Models\Advertisement;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ApiTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Business $business;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and business
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'seller@example.com',
        ]);

        $this->business = Business::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Business',
            'contract_status' => 'approved',
        ]);

        // Create some advertisements
        Advertisement::factory()->count(3)->create([
            'business_id' => $this->business->id
        ]);
    }

    /**
     * Test that the API page loads and displays expected content.
     *
     * @return void
     */
    public function testApiPageLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.api.index', $this->business->id))
                ->screenshot('api-page')
                ->assertSee(trans('api.title'))
                ->assertSee(trans('api.url_title'))
                ->assertSee(route('api.advertisements.list', $this->business->id))
                ->assertSee(route('api.advertisements.show', [$this->business->id, 'id']));
        });
    }

    /**
     * Test that the API link works.
     *
     * @return void
     */
    public function testApiLinkWorks(): void
    {
        $apiUrl = route('api.advertisements.list', $this->business->id);

        $response = $this->get($apiUrl);
        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->browse(function (Browser $browser) use ($apiUrl) {
            $browser->loginAs($this->user)
                ->visit(route('seller.api.index', $this->business->id))
                ->assertPresent("a[href='$apiUrl']");
        });
    }

    /**
     * Test that unauthenticated users can't access API page.
     *
     * @return void
     */
    public function testUnauthenticatedAccessDenied(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('seller.api.index', $this->business->id))
                ->screenshot('api-unauthenticated')
                ->assertPathIsNot(route('seller.api.index', $this->business->id));
        });
    }

    /**
     * Test that users can't access API pages for businesses they don't own.
     *
     * @return void
     */
    public function testUnauthorizedAccessDenied(): void
    {
        $otherUser = User::factory()->create();
        $otherBusiness = Business::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $this->browse(function (Browser $browser) use ($otherBusiness) {
            $browser->loginAs($this->user)
                ->visit(route('seller.api.index', $otherBusiness->id))
                ->screenshot('api-unauthorized')
                ->assertPathIsNot(route('seller.api.index', $otherBusiness->id));
        });
    }
}
