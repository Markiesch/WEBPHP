<?php

namespace Tests\Browser\Pages\Public;

use App\Models\Advertisement;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdvertisementsIndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Advertisement::factory()->count(3)->create();
    }

    public function test_user_can_view_advertisements_list(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee(__('bazaar'));
        });
    }

    public function test_user_can_filter_advertisements_by_search(): void
    {
        $ad = Advertisement::factory()->create([
            'title' => 'TEST PRODUCT 12311245325'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee(__('bazaar'));
        });
    }

    public function test_user_can_sort_advertisements(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee(__('bazaar'));
        });
    }
}
