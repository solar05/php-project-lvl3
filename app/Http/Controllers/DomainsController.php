<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use App\Domain;
use Validator;


class DomainsController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'domain' => 'url|required|max:255'
            ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return view('index', ['errors' => $errors]);
        }
        $domain = Domain::create(['name' => $request->get('domain')]);
        Queue::push(new AnalyzeJob($domain));
        return redirect(route('domains.show', ['id' => $domain->id]));
    }

    public function show($id)
    {
        $requestedDomain = Domain::findOrFail($id);
        return view('domain', ['domains' => $requestedDomain]);
    }

    public function index()
    {
        $allDomains = Domain::paginate(10);
        return view('domains', ['domains' => $allDomains]);
    }
}
