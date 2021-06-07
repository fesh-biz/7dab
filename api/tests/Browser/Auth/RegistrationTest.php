<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Extensions\ClearCacheExtension;
use Tests\Browser\Extensions\RouteNamesExtension;
use Tests\Browser\Extensions\UserAssertions;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    use RouteNamesExtension, UserAssertions;

    /**
     * @test
     * @group auth
     * @group registration
     */
    public function userCanRegister()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->make();

            $browser->visit($this->routeByName('home'))
                ->waitFor('@ml-menu')
                ->click('@ml-menu')

                ->waitFor('@gm-registration-link')
                ->click('@gm-registration-link')

                ->waitFor('@r-registration-form')

                ->type('@r-name-input', $user->name)
                ->type('@r-email-input', $user->email)
                ->type('@r-password-input', 'password')
                ->type('@r-password-confirmation-input', 'password')

                ->click('@r-registration-button')
                ->waitUntilMissing('@r-registration-button');

            $this->assertThatUserLoggedIn($browser, $user->name);
        });
    }
}
