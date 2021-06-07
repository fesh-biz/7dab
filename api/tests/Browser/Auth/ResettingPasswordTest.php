<?php

namespace Tests\Browser\Auth;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\Browser\Extensions\AuthExtension;
use Tests\Browser\Extensions\ClearCacheExtension;
use Tests\Browser\Extensions\RouteNamesExtension;
use Tests\Browser\Extensions\UserAssertions;
use Tests\DuskTestCase;

class ResettingPasswordTest extends DuskTestCase
{
    use RouteNamesExtension, UserAssertions, AuthExtension, ClearCacheExtension;

    /**
     * @test
     * @group auth
     * @group resettingPassword
     */
    public function userCanRequestResettingLink()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {

            $browser->visit($this->routeByName('home'))
                ->waitFor('@ml-menu')
                ->click('@ml-menu')
                ->waitFor('@gm-login-link')
                ->click('@gm-login-link')
                ->waitFor('@l-forgot-password-link')
                ->click('@l-forgot-password-link');

            $this->fillResetFormAndSubmit($browser, $user->email)
                ->assertSeeIn('@fp-info-message', trans('passwords.sent'));
        });
    }


    /**
     * @test
     * @group auth
     * @group resettingPassword
     */
    public function userCanResetHisPassword()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $this->resetPassword($browser, $user);

            $this->assertThatUserLoggedIn($browser, $user->name);
        });
    }


    /**
     * @test
     * @group auth
     * @group resettingPassword
     */
    public function userCanUseNewPassword()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();
            $newPassword = 'newPassword';

            $this->resetPassword($browser, $user, $newPassword);
            $browser = $this->logout($browser);
            $this->loginAs($browser, $user->email, $newPassword);

            $this->assertThatUserLoggedIn($browser, $user->name);
        });
    }

    private function resetPassword(Browser $browser, User $user, string $newPassword = 'newPassword'): Browser
    {
        $browser->visit($this->routeByName('forgotPassword'));

        $browser = $this->fillResetFormAndSubmit($browser, $user->email);
        $token = \DB::table('password_resets')->where('email', $user->email)->first()->token;

        return $browser->visit($this->routeByName('resetPassword') . '/' . $token)
            ->waitFor('@rp-password-input')
            ->type('@rp-password-input', $newPassword)
            ->type('@rp-password-confirmation-input', $newPassword)
            ->click('@rp-submit-button')
            ->waitUntilMissing('@rp-submit-button');
    }

    private function fillResetFormAndSubmit(Browser $browser, string $email): Browser
    {
        return $browser->waitFor('@fp-forgot-password-form')
            ->type('@fp-email-input', $email)
            ->click('@fp-submit-button')
            ->waitFor('@fp-info-message');
    }
}
