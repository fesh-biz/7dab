<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Redis\EmailNotificationQueues\PendingPostNotificationQueue;
use App\Redis\Redis;
use App\Services\Sitemap\SitemapService;
use Illuminate\Console\Command;

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
    
    private function createFakeUsers()
    {
        $nickNames = ['папірець', 'IronMan', 'Neruhomyi', 'JBaserok', 'musicIsMyNature', 'Snicker',
            'трошкиСобі'];
        
        foreach ($nickNames as $nick) {
            User::create([
                'login' => $nick,
                'email' => $nick . '@terevenky.com',
                'email_verified_at' => now(),
                'password' => bcrypt('Fesh717658')
            ]);
        }
    
        User::all()->map(function ($u) {
            $this->info($u->email);
        });
    }
    
    private function redis()
    {
        $r = app()->make(Redis::class);
        
        $this->info('Key is: ' . $r->getKey());
        
        $r->create(1, ['value' => 1]);
        $r->create(2, ['value' => 2]);
        $this->info('All:');
        dump($r->all());
        
        $r->deleteAll();
        $this->info('Deleted all:');
        dump($r->all());
        $this->info('Finished');
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
