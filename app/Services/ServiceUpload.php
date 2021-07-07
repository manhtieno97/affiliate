<?php


namespace App\Services;


use App\Models\Question;
use App\SitesInfo\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ServiceUpload
{

    public function upload() {
        while ($question = Question::where('crawl_status', Question::CRAWL_STATUS_DONE)
                                    ->where('public_status', Question::UPLOAD_STATUS_DEFAULT)
                                    ->first()
        ) {
            //doi status = runing
            $question->update([
                'public_status' => Question::UPLOAD_STATUS_RUNNING,
            ]);
            if (\Storage::disk($question->disk)->exists($question->file)
            ) {
                dump("upload id: " .$question->id);
                $contents = json_decode(\Storage::disk($question->disk)->get($question->file),true);
                $api = config('crawl.api');
                $client = new \GuzzleHttp\Client();
                $data = [];
                $data['time'] = strtotime(Carbon::now());
                $data['data'] = $contents;
                $data['hash'] = $this->getHash($contents, $data['time']);
                try {
                    $response = $client->request('POST', $api, [
                        'headers' => [
                            'Accept'     => 'application/json',
                        ],
                        'form_params' => $data
                    ]);
                    $repon = json_decode($response->getBody()->getContents(),true);
                    if(isset($response) && $repon['status'] == true)
                    {
                        $question->update([
                            'public_status' => Question::UPLOAD_STATUS_DONE,
                            'id_public' => $repon['id_public']
                        ]);
                        dump("upload id: " .$question->id ." success");
                    }else{
                        dump("upload id: " .$question->id ." repon error");
                        $question->update(['public_status' => Question::UPLOAD_STATUS_ERROR]);
                    }
                }catch (\Exception $ex )
                {
                    dump("upload id: " .$question->id ." request error ");
                    Log::error("upload id: " .$question->id ." request error ". $ex->getMessage());
                    $question->update(['public_status' => Question::UPLOAD_STATUS_ERROR]);
                }

            }else{
                dump("upload id: " .$question->id .' error file not exit');
                $question->update([
                    'public_status' => Question::UPLOAD_STATUS_ERROR,
                ]);
            }

        }
    }
    public function getHash($data ,$time)
    {
        $hash = '';
        if(!empty($data['content']))
        {
            $hash = config('crawl.private_key').$data['content'] . $time;
        }
        return md5($hash);
    }

}
