<?php

namespace App\Http\Controllers;

use App\Jobs\AnaliseJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use App\Domain;
use Validator;


class DomainsController extends Controller
{
    public function addDomain(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'domain' => 'url|required|max:255'
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return view('index', ['errors' => $errors]);
        }
        $domain = Domain::create(['name' => $request->get('domain'), 'state' => 'initialized' ]);
        Queue::push(new AnaliseJob($domain));
        return redirect(route('domain', ['id' => $domain->id]));
    }

    public function showDomain($id)
    {
        $requestedDomain = Domain::find($id);
        return view('domain', ['domains' => $requestedDomain]);
    }

    public function showAll()
    {
        $allDomains = Domain::paginate(10);
        return view('domains', ['domains' => $allDomains]);
    }
}
