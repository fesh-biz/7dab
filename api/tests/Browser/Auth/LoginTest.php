<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Extensions\AuthExtension;
use Tests\Browser\Extensions\ClearCacheExtension;
use Tests\Browser\Extensions\RouteNamesExtension;
use Tests\Browser\Extensions\UserAssertions;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use RouteNamesExtension, UserAssertions, AuthExtension, ClearCacheExtension;


    /**
     * @test
     * @group auth
     * @group login
     */
    public function userSeeWrongCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->routeByName('login'))
                ->type('@l-email-input', 'user@app')
                ->type('@l-password-input', 'wrong-password')
                ->press('@l-login-form-submit')

                ->waitFor('@l-error-message')

                ->assertSeeIn('@l-error-message', trans('auth.wrong_credentials'));
        });
    }

    /**
     * @test
     * @group auth
     * @group login
     */
    public function userCanLogin()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $browser->visit($this->routeByName('home'));
            $browser = $this->loginAs($browser, $user->email, 'password');

            $this->assertThatUserLoggedIn($browser, $user->name);
        });
    }

    /**
     * @test
     * @group auth
     * @group login
     */
    public function userCanLogout()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->visit($this->routeByName('home'));
            $browser = $this->loginAs($browser, $user->email);

            $browser = $this->logout($browser);

            $browser->assertNotPresent('@um-user-name');
        });
    }

    /**
     * @test
     * @group auth
     * @group login
     */
    public function userSeeErrorsIfFieldsAreEmpty() {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->routeByName('login'))
                ->waitFor('@l-login-form')
                ->pressAndWaitFor('@l-login-form-submit')
                ->assertSee(trans('auth.check_your_data'));
        });
    }
}
