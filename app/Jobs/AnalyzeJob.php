<?php

namespace App\Jobs;

use Illuminate\Support\Facades\App;
use App\Domain;

class AnalyzeJob extends Job
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
        $client = App::make('GuzzleClient');
        $request = App::makeWith('GuzzleRequest7', ['method' => 'GET', 'URL' => $this->domain->name]);
        $id = $this->domain->id;
        $stateMachine = App::makeWith('SM', ['domain' => $this->domain]);
        try {
            $stateMachine->apply('send');
            $this->domain->state = $stateMachine->getState();
            $this->domain->save();
            $promise = $client->sendAsync($request)->then(function ($response) use ($id, $stateMachine) {
                $this->domain->status =  $response->getStatusCode();
                $this->domain->content_length = ($response->getHeader('Content-Length')) ?
                    implode('', $response->getHeader('Content-Length')) :
                    strlen($response->getBody());
                $document = App::makeWith('DiDoc', ['document' => $response->getBody()->__ToString()]);
                $header = $document->first('h1');
                $this->domain->header = !empty($header) ? $header->text() : 'Not provided';
                $keywords = $document->find('meta[name=keywords]');
                $proceededKeywords = count($keywords) ? $keywords[0]->attr('content') : null;
                $description = $document->find('meta[name=description]');
                $proceededDescription = count($description) > 0 ? $description[0]->attr('content') : null;
                $this->domain->content = "{$proceededKeywords}{$proceededDescription}";
                $stateMachine->apply('complete');
                $this->domain->state = $stateMachine->getState();
                $this->domain->save();
            });
            $promise->wait();
        } catch (\Exception $error) {
            info($error);
            $stateMachine->apply('cancel');
            $this->domain->state = $stateMachine->getState();
            $this->domain->save();
        }
    }
}
