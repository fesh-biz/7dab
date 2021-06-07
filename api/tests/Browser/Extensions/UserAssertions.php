<?php


namespace Tests\Browser\Extensions;


use Laravel\Dusk\Browser;

trait UserAssertions
{
    public function assertThatUserLoggedIn(Browser $browser, string $userName): Browser
    {
        return $browser->click('@ml-menu')
            ->waitFor('@um-user-name')
            ->assertSeeIn('@um-user-name', $userName);
    }
}
