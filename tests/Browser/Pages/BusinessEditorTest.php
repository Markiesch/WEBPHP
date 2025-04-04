<?php

namespace Tests\Browser;

use App\Models\Business;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;

class BusinessEditorTest extends DuskTestCase
{
    private User $user;
    private Business $business;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->business = Business::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Test Business',
            'url' => 'test-business-' . Str::random(8),
        ]);
    }

    public function testCanViewBusinessEditor()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/business/edit')
                ->assertSee('Business settings');
        });
    }

    public function testCanUpdateBusinessSettings()
    {
        $this->browse(function (Browser $browser) {
            $newUrl = 'updated-url-' . Str::random(8);

            $browser->loginAs($this->user)
                ->visit('/business/edit')
                ->type('name', 'Updated Business Name')
                ->type('url', $newUrl)
                ->press('Save')
                ->assertInputValue('name', 'Updated Business Name')
                ->assertInputValue('url', $newUrl);
        });
    }

    public function testCanPreviewBusinessPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->user)
                ->visit('/business/edit')
                ->assertSee('View live page');
        });
    }
}
