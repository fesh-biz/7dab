<?php


namespace Tests\Browser\Extensions;


use Laravel\Dusk\Browser;

trait ClearCacheExtension
{
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
            $browser->script("localStorage.clear()");
        });
    }
}
