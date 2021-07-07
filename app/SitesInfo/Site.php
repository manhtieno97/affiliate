<?php


namespace App\SitesInfo;

use App\Crawler\Browsers\Guzzle;
use App\Libs\IdToPath;
use App\Models\Question;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

abstract class Site
{
    protected $prefix;
    protected $client;
    protected $force;

    public function __construct($force = false) {
        $this->client = new Client( config( 'crawler.browsers.guzzle' ) );
        $this->force = $force;
    }

    public function process( $question ) {
        $data_file = config("crawl.{$question->site}.folder") . '/' . IdToPath::make( $question->id_post, "quiz.json");
        dump('Parsing ' . $question->link );
        $html = ( new Guzzle( $this->client ) )->getHtml( $question->link );
        $data = $this->processHtml( $html );
        if((!empty($data['content']))
            && (!empty($data['source_title']))
            && (!empty($data['grade']))
            && (!empty($data['subject']))
            && (!empty($data['answers']) || !empty($data['suggestion']))
        ){
            $this->saveQuestion($question, $data, $data_file);
        } else {
            $question->update([
                'crawl_status' => Question::CRAWL_STATUS_ERROR_CRAWL
            ]);
            return false;
        }
    }

    abstract protected function processHtml( $html, $url = '' );

    protected function saveQuestion($question, $data, $data_file) {
        if($question->update(
            [
                'question' => $data['content'],
                'album' => $data['source_title'],
                'type' => $data['type'],
                'disk' => config("crawl.".$question->site.".disk"),
                'file' => $data_file,
                'crawl_status' => Question::CRAWL_STATUS_DONE
            ]
        )) {
            $data['url'] = $question->link;
            \Storage::disk(config("crawl.".$question->site.".disk"))->put($data_file, json_encode( $data ));
        }
    }

    public function makeUrl( $id ) {
        $url = str_replace( "__id__", $id, $this->prefix );
        dump('Checking ' . $url);
        try {
            $response = $this->client->head( $url, [
                'timeout'         => 10,
                'allow_redirects' => false,
            ] );
            if ( count( $response->getHeader( 'Location' ) ) ) {
                return $response->getHeader( 'Location' )[0];
            } else {
                return false;
            }
        } catch ( RequestException $exception ) {
            if ( ! $exception->getResponse() ) {
                throw $exception;
            } else {
                return false;
            }
        }
    }
    public function makeUrlNew( $id ) {
        $url = str_replace( "__id__", $id, $this->prefix );
        dump('Checking ' . $url);
        return $url;
    }

    protected function download_images(Crawler $crawler){
        $images = $crawler->filter('img');
        $downloaded = [];
        /** @var \DOMElement $image */
        foreach ($images as $image){
            try{
                $src = $image->getAttribute( 'src');
                $downloaded[] = [
                    'url' => $src,
                    'data-url' => (string)(app('image')->make($src)->encode('data-url')),
                ];
            }catch (\Exception $ex){

            }
        }
        return $downloaded;
    }

    protected function src_to_base64_image($content, $images){

        foreach ($images as $image){
            $content = str_replace( $image['url'], $image['data-url'], $content);
        }

        return $content;
    }
}
