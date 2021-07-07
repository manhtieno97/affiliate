<?php

namespace App\Console\Commands;

use App\Crawler\Entities\Quiz;
use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;

class TestCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'test:run';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Test something';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*$jar = new \GuzzleHttp\Cookie\CookieJar();
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
                'Referer' => 'http://www.nettruyen.com'
                ],
            'verify' => false,
            'cookies' => $jar,
            
        ]);*/
//        $client = new Client([
//            'headers' => [
//                'Referer' => 'http://www.nettruyen.com'
//            ],
//        ]);
//        $repon = $client->get('http://truyengroup.org/data/images/13223/408830/003-fix.jpg?data=net');
//        \Storage::disk('data01')->put('test/test.jpg', $repon->getBody()->getContents());
        $link = 'https://doctailieu.com/trac-nghiem/hap-thu-hoan-toan-336-lit-khi-co_2-dkc-vao-dung-dich-chua-005-mol-naoh-va-10008';
        $html = Browsershot::url($link)->userAgent( 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1' )
            ->waitUntilNetworkIdle()->bodyHtml();
        dd($html);
   
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
