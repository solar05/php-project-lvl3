<?php

namespace App\Http\Controllers;

use App\Jobs\AnaliseJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DomainsController extends Controller
{
    public function addDomain(Request $request)
    {
        $this->validate($request, [
            'domain' => 'url|required'
        ]);
        $domain = $request->get('domain');
        $currentDate = date('Y-m-d H:i:s');
        DB::table('domains')->insert(['name' => $domain, 'created_at' => $currentDate, 'state' => env('STATE_INIT')]);
        $insertedDomain = DB::select("SELECT id FROM domains where id = last_insert_rowid()");
        dispatch(new AnaliseJob(['id' => $insertedDomain[0]->id, 'domain' => $domain]));
        return redirect(route('domain', ['id' => $insertedDomain[0]->id]));
    }

    public function showDomain($id)
    {
        $requestedDomain = DB::select("SELECT * FROM domains WHERE id = ?", [$id]);
        return view('domain', ['domains' => $requestedDomain]);
    }

    public function showAll()
    {
        $allDomains = DB::table('domains')->paginate(5);
        return view('domains', ['domains' => $allDomains]);
    }
}
