<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class AnaliseJob extends Job
{
    public $tries = 5;
    protected $domain;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = App::make('GuzzleHttp\Client');
        $request = App::makeWith('GuzzleHttp\Psr7\Request', ['method' => 'GET', 'URL' => "{$this->domain['domain']}"]);
        $id = $this->domain['id'];
        $currentDate = date('Y-m-d H:i:s');
        DB::insert("UPDATE domains set state = ?, updated_at = ? WHERE id = {$id}", [env('STATE_PENDING'),
            $currentDate]);
        try {
            $promise = $client->sendAsync($request)->then(function ($response) use ($id) {
                $currentDate = date('Y-m-d H:i:s');
                $pageData = array('domain_id' => $id);
                $code =  $response->getStatusCode();
                $pageData['code'] = $code;
                $contentLength = ($response->getHeader('Content-Length')) ?
                    implode('', $response->getHeader('Content-Length')) :
                    strlen($response->getBody());
                $pageData['content_length'] = $contentLength;
                $body = $response->getBody()->__toString();
                $pageData['body'] = $body;
                $document = App::makeWith('DiDom\Document', ['document' => $body]);
                $header = $document->find('h1');
                $pageData['header'] = count($header) > 0 ? $header[0]->text() : null;
                $keywords = $document->find('meta[name=keywords]');
                $pageData['keywords'] = count($keywords) ? $keywords[0]->attr('content') : null;
                $description = $document->find('meta[name=description]');
                $pageData['description'] = count($description) > 0 ? $description[0]->attr('content') : null;
                $pageData['content'] = "{$pageData['keywords']} {$pageData['description']}";
                DB::insert("UPDATE domains set state = ?, status = ?, updated_at = ?, content_length = ?,
                    body = ?, header = ?, content = ? WHERE id = ?", [
                    env('STATE_COMPLETED'),
                    $pageData['code'],
                    $currentDate,
                    $pageData['content_length'],
                    $pageData['body'],
                    $pageData['content'],
                    $pageData['header'],
                    $pageData['domain_id']]);
            });
            $promise->wait();
        } catch (\Exception $error) {
            info($error);
            $currentDate = date('Y-m-d H:i:s');
            DB::insert("UPDATE domains set state = ?, updated_at = ? WHERE id = ?", [env('STATE_FAILED'),
                $currentDate, $id]);
        }
    }
}
