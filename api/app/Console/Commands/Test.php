<?php

namespace App\Console\Commands;

use App\Services\Sitemap\SitemapService;
use Illuminate\Console\Command;
use Predis\Client;

class Test extends Command
{
    protected $signature = 'test {methodName}';
    protected $description = 'Command description';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->runTestByItsName($this->argument('methodName'));
    }
    
    private function redis()
    {
        $client = new Client();
        
        $value = json_encode([1, 2, 2]);
        $client->set('postIds', $value);
        
        dd(json_decode($client->get('postIds')));
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
