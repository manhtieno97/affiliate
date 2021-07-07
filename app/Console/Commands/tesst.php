<?php

namespace App\Console\Commands;

use App\Crawler\Browsers\Guzzle;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
class tesst extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:run';

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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $bots_type = [];
//        $handle = fopen("/var/www/123doc/tool/cunghocvui/crawl/public/test/robots-2021-04-19.log", "r");
//        if ($handle) {
//            while (($line = fgets($handle)) !== false) {
//                $string = explode( ' ', trim($line));
//                if(!in_array($string[3],$bots_type))
//                {
//                    $bots_type[] = $string[3];
//                }
//            }
//            fclose($handle);
//        }
//
//        dd($bots_type);
        $url = 'https://pub2.accesstrade.vn/campaign';
        $jar = new \GuzzleHttp\Cookie\CookieJar();
        $config_guzzle = [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
            ],
            'verify' => false,
            'cookies' => $jar
        ];
        $client = new Client($config_guzzle);
    
        $client->post('https://id.accesstrade.vn/login', [
                'form_params' => [
                    'username' => 'manhtieno97',
                    'password' => 'sn13071997',
                ]
            ]
        );
        $html = (new Guzzle($client))->getHtml($url);
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $content = $crawler->filter('a')->attr('src');
        dd($content);
    }
}
