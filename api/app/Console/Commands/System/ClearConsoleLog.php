<?php

namespace App\Console\Commands\System;

use Illuminate\Console\Command;

class ClearConsoleLog extends Command
{
    protected $signature = 'log:clear';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // exec('rm -f ' . storage_path('logs/*.log'));
        exec('truncate -s 0 ./storage/logs/laravel.log');

        $this->comment('Logs have been cleared!');
    }
}
