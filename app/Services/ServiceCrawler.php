<?php


namespace App\Services;


use App\Models\Question;
use App\SitesInfo\Site;

class ServiceCrawler
{

    public function run() {
        while ($question = Question::where('crawl_status', Question::CRAWL_STATUS_DEFAULT)
                                    ->first()
        ) {
            //doi status = runing
            $question->update([
                'crawl_status' => Question::CRAWL_STATUS_RUNNING,
            ]);
            $site = "\\App\\SitesInfo\\".$question->site;
            $site = new $site;
            $site->process($question);
        }
    }

    public function init($site_name, $id) {
        //tao url
        $site = "\\App\\SitesInfo\\".$site_name;
        $site = new $site;
        if($site_name == 'Tracnghiem')
        {
            $url = $site->makeUrlNew( $id );
        }else{
            $url = $site->makeUrl( $id );
        }
        
        dump($url);
        if ( $url ) {
            //Luu DB url, name site, id_post, status 0
            Question::firstOrCreate(
                ['link' => $url],
                [
                    'site' => $site_name,
                    'id_post' => $id,
                    'crawl_status' => Question::CRAWL_STATUS_DEFAULT
                ]
            );
        }else{
            Question::firstOrCreate(
                ['link' => $url],
                [
                    'site' => $site_name,
                    'id_post' => $id,
                    'crawl_status' => Question::CRAWL_STATUS_ERROR_LINK
                ]
            );
        }
    }
}
