<?php


namespace Tests\Browser\Extensions;


use Laravel\Dusk\Browser;

trait AuthExtension
{
    use RouteNamesExtension;

    public function loginAs(Browser $browser, string $email, string $password = 'password'): Browser
    {
        return $browser
            ->waitFor('@ml-menu')
            ->click('@ml-menu')
            ->waitFor('@gm-login-link')
            ->click('@gm-login-link')
            ->waitFor('@l-email-input')
            ->type('@l-email-input', $email)
            ->type('@l-password-input', $password)
            ->press('@l-login-form-submit')
            ->waitUntilMissing('@l-login-form-submit');
    }

    public function logout(Browser $browser): Browser
    {
        return $browser
            ->waitFor('@ml-menu')
            ->click('@ml-menu')
            ->waitFor('@um-logout-link')
            ->click('@um-logout-link')
            ->waitUntilMissing('@um-logout-link');
    }
}
