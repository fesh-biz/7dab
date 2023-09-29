<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Dusk\Browser;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected static bool $dbSeeded = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$dbSeeded) {
            $this->artisan('migrate:fresh');
            $this->artisan('db:seed');
            self::$dbSeeded = true;
        }
    }
}
