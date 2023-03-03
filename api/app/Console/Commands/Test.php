<?php

namespace App\Console\Commands;

use App\Services\Sitemap\SitemapService;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle(): void
    {
        $testName = explode(':', $this->getName(), 2)[1];
        
        $this->runTestByItsName($testName);
    }
    
    private function sitemap()
    {
        $sitemapService = new SitemapService();
        
        $sitemapService->create('sitemap.xml');
    }
    
    private function runTestByItsName(string $testName)
    {
        $this->{$testName}();
    }
}
