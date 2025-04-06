<?php

namespace Tests\Browser;

use App\Models\Advertisement;
use App\Models\Business;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AgendaTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected User $user;
    protected Business $business;
    protected array $advertisements;

    public function setUp(): void
    {
        parent::setUp();

        // Create user, business and sample advertisements
        $this->user = User::factory()->create();
        $this->business = Business::factory()->create([
            'user_id' => $this->user->id
        ]);

        // Create different types of advertisements
        $this->createSampleAdvertisements();
    }

    protected function createSampleAdvertisements(): void
    {
        $now = Carbon::now();

        // Regular sale advertisement
        Advertisement::factory()->create([
            'business_id' => $this->business->id,
            'title' => 'Sale Item',
            'type' => 'sale',
            'expiry_date' => $now->copy()->addDays(10),
        ]);

        // Rental advertisement
        Advertisement::factory()->create([
            'business_id' => $this->business->id,
            'title' => 'Rental Item',
            'type' => 'rental',
            'rental_start_date' => $now->copy()->subDays(2),
            'rental_end_date' => $now->copy()->addDays(15),
        ]);

        // Auction advertisement
        Advertisement::factory()->create([
            'business_id' => $this->business->id,
            'title' => 'Auction Item',
            'type' => 'auction',
            'auction_end_date' => $now->copy()->addDays(5),
        ]);
    }

    public function testAgendaPageLoads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.agenda.index'))
                ->screenshot('agenda-page')
                ->assertPresent('.uk-container')
                ->assertPresent('.uk-card-default')
                ->assertSee('Sale Item');
        });
    }

    public function testAgendaShowsAllProductTypes(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.agenda.index'))
                ->assertSee('Sale Item')
                ->assertSee('Rental Item')
                ->assertSee('Auction Item');
        });
    }

    public function testCalendarDisplaysCurrentMonth(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.agenda.index'))
                ->assertPresent('.grid.grid-cols-7');
        });
    }

    public function testUpcomingEndDatesSection(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.agenda.index'))
                ->pause(1000)
                ->screenshot('upcoming-dates-section')
                ->assertPresent('.uk-card-small.uk-card-default.uk-card-body')
                ->assertPresent('.space-y-2 .p-2.rounded-md.border');

            $ad = Advertisement::where('business_id', $this->business->id)->first();
            if ($ad) {
                $browser->assertSee($ad->title);
            }
        });
    }

    public function testAdvertisementDetailsAreDisplayed(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit(route('seller.agenda.index'))
                ->pause(2000)
                ->screenshot('advertisement-details');

            $ad = Advertisement::where('business_id', $this->business->id)->first();
            if ($ad) {
                $browser->assertSee($ad->title);
            }

            $browser->assertPresent('.uk-card-default')
                ->assertPresent('.grid-cols-7');
        });
    }

    public function testAdvertisementLinkWorks(): void
    {
        // Get the first advertisement
        $ad = Advertisement::where('business_id', $this->business->id)->first();

        $this->browse(function (Browser $browser) use ($ad) {
            $browser->loginAs($this->user)
                ->visit(route('seller.agenda.index'))
                ->clickLink($ad->title)
                ->assertUrlIs(route('seller.advertisements.show', $ad->id));
        });
    }
}
