<?php

namespace App\Console\Commands;

use App\Api\DigitalOcean\Space;
use App\Models\User;
use App\Redis\Models\Media;
use App\Redis\Models\MediaRedis;
use App\Services\Sitemap\SitemapService;
use Illuminate\Console\Command;
use Illuminate\Http\File;

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
        $mediaRedis = app()->make(MediaRedis::class);

        dd($mediaRedis->all());
    }

    private function aws()
    {
        $spaceClient = new Space();
        $file = new File(storage_path('app/public/post-images/2023/04/24/desktop/168234348322827800.jpeg'));
        $spaceClient->uploadFile('test/image.jpg', $file->getContent());
    }

    private function sitemap()
    {
        $sitemapService = new SitemapService();

        $sitemapService->create('sitemap.xml');
    }

    private function kartopelka()
    {
        $baseUrl = 'https://kartopelka.fun/sitemap-pages.xml?month={month}&year={year}';

        $totalCounter = 0;
        foreach ([2022, 2023] as $year) {
            $start = $year == 2022 ? 11 : 1;
            $end = $year == 2023 ? 6 : 12;
            foreach (range($start, $end) as $month) {
                $url = str_replace(['{month}', '{year}'], [$month, $year], $baseUrl);
                $xml = simplexml_load_file($url);

                if ($xml === false) {
                    die('Failed to load XML file.');
                }

                $monthCounter = 0;
                foreach ($xml->url as $url) {
                    $totalCounter++;
                    $monthCounter++;
                }

                $this->info("$year-$month: $monthCounter");
                $this->info('Total: ' . $totalCounter);
                sleep(3);
            }
        }
    }

    private function runTestByItsName(string $testName)
    {
        $this->{$testName}();
    }

    // Unused

    private function createFakeUsers()
    {
        $nickNames = [
            'папірець', 'IronMan', 'Neruhomyi', 'JBaserok',
            'musicIsMyNature', 'Snicker', 'трошкиСобі', 'TheWizard',
            'чарівник', 'мрійник', 'Mriyachka', 'Веселка',
            'StarLord', 'дика перлина', 'kvikvi', 'квітка',
            'tayemnytsa', 'вітряна дівка'
        ];

        foreach ($nickNames as $nick) {
            $email = $nick . '@terevenky.com';
            if (User::whereEmail($email)->first()) {
                continue;
            }

            $user = User::create([
                'login' => $nick,
                'email' => $nick . '@terevenky.com',
                'password' => bcrypt('Fesh717658')
            ]);

            $user->email_verified_at = now();
            $user->created_at = now()->subDays(mt_rand(10, 60));
            $user->save();
        }

        User::all()->map(function ($u) {
            $this->info($u->email);
        });
    }
}
