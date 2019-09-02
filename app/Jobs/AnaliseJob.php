<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use \GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;

class AnaliseJob extends Job
{
    public $tries = 5;
    private $domain;


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
        $client = new Client();
        $request = new Request('GET', "{$this->domain['domain']}");
        $id = $this->domain['id'];
        $currentDate = date('Y-m-d H:i:s');
        DB::insert("UPDATE domains set state = ?, updated_at = ? WHERE id = {$id}", [env('STATE_PENDING'),
            $currentDate]);
        try {
            $promise = $client->sendAsync($request)->then(function ($response) use ($id) {
                $currentDate = date('Y-m-d H:i:s');
                DB::insert("UPDATE domains set state = ?, status = ?, updated_at = ? WHERE id = {$id}", [
                    env('STATE_COMPLETED'), $response->getStatusCode(), $currentDate]);
            });
            $promise->wait();
        } catch (\Exception $error) {
            $currentDate = date('Y-m-d H:i:s');
            DB::insert("UPDATE domains set state = ?, updated_at = ? WHERE id = {$id}", [env('STATE_FAILED'),
                $currentDate]);
        }
    }
}
