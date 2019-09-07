<?php

namespace App\Jobs;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Domain;

class AnaliseJob extends Job
{
    public $tries = 5;
    protected $domain;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Domain $domain)
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
        $request = App::makeWith('GuzzleHttp\Psr7\Request', ['method' => 'GET', 'URL' => "{$this->domain->getUrl()}"]);
        $id = $this->domain->getId();
        $currentDate = date('Y-m-d H:i:s');
        try {
            $this->domain->pending();
            DB::insert(
                "UPDATE domains set state = '{$this->domain->getCurrentState()}',
                updated_at = '{$currentDate}' WHERE id = ?",
                [$id]
            );
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
                $this->domain->completed();
                DB::insert("UPDATE domains set state = '{$this->domain->getCurrentState()}', status = ?, updated_at = ?,
                    content_length = ?, header = '{$pageData['header']}',
                    content = '{$pageData['header']}' WHERE id = ?", [
                    $pageData['code'],
                    $currentDate,
                    $pageData['content_length'],
                    $pageData['domain_id']]);
            });
            $promise->wait();
        } catch (\Exception $error) {
            info($error);
            $currentDate = date('Y-m-d H:i:s');
            $this->domain->failed();
            DB::insert(
                "UPDATE domains set state = '{$this->domain->getCurrentState()}', updated_at = ? WHERE id = ?",
                [$currentDate, $id]
            );
        }
    }
}
