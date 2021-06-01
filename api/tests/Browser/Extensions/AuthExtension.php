<?php


namespace Tests\Browser\Extensions;


use Laravel\Dusk\Browser;

trait AuthExtension
{
    use RouteNamesExtension;

    public function loginAs(Browser $browser, string $email, string $password = 'password'): Browser
    {
        return $browser
            ->waitFor('@gm-guest-menu')
            ->click('@gm-guest-menu')
            ->waitFor('@gm-login-link')
            ->click('@gm-login-link')
            ->waitFor('@l-email-input')
            ->type('@l-email-input', $email)
            ->type('@l-password-input', $password)
            ->press('@l-login-form-submit')
            ->waitUntilMissing('@l-login-form-submit')
            ->waitFor('@um-user-menu');
    }

    public function logout(Browser $browser): Browser
    {
        return $browser
            ->waitFor('@um-user-menu')
            ->click('@um-user-menu')
            ->waitFor('@um-logout-link')
            ->click('@um-logout-link')
            ->waitUntilMissing('@um-user-menu');
    }
}
