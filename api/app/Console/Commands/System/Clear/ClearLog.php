<?php

namespace App\Console\Commands\System\Clear;

use Illuminate\Console\Command;

class ClearLog extends Command
{
    protected $signature = 'clear:log';

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
