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
        $request = App::makeWith('GuzzleHttp\Psr7\Request', ['method' => 'GET', 'URL' => $this->domain->name]);
        $id = $this->domain->id;
        try {
            $this->domain->pending();
            $this->domain->save();
            $promise = $client->sendAsync($request)->then(function ($response) use ($id) {
                $this->domain->status =  $response->getStatusCode();
                $this->domain->content_length = ($response->getHeader('Content-Length')) ?
                    implode('', $response->getHeader('Content-Length')) :
                    strlen($response->getBody());
                $document = App::makeWith('DiDom\Document', ['document' => $response->getBody()]);
                $header = $document->first('h1');
                $this->domain->header = !empty($header) ? $header->text() : 'Not provided';
                $keywords = $document->find('meta[name=keywords]');
                $proceededKeywords = count($keywords) ? $keywords[0]->attr('content') : null;
                $description = $document->find('meta[name=description]');
                $proceededDescription = count($description) > 0 ? $description[0]->attr('content') : null;
                $this->domain->content = "{$proceededKeywords}{$proceededDescription}";
                $this->domain->completed();
                $this->domain->save();
            });
            $promise->wait();
        } catch (\Exception $error) {
            info($error);
            $this->domain->failed();
            $this->domain->save();
        }
    }
}
