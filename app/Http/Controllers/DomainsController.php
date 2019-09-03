<?php

namespace App\Http\Controllers;

use App\Jobs\AnaliseJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;


class DomainsController extends Controller
{
    public function addDomain(Request $request)
    {
        $this->validate($request, [
            'domain' => 'url|required|max:255'
        ]);
        $domain = $request->get('domain');
        $currentDate = date('Y-m-d H:i:s');
        DB::table('domains')->insert(['name' => $domain, 'created_at' => $currentDate, 'state' => env('STATE_INIT')]);
        $insertedDomain = DB::select("SELECT id FROM domains where id = last_insert_rowid()")[0];
        dispatch(new AnaliseJob(['id' => $insertedDomain->id, 'domain' => $domain]));
        return redirect(route('domain', ['id' => $insertedDomain->id]));
    }

    public function showDomain($id)
    {
        $requestedDomain = DB::select("SELECT * FROM domains WHERE id = ?", [$id]);
        return view('domain', ['domains' => $requestedDomain[0]]);
    }

    public function showAll()
    {
        $allDomains = DB::table('domains')->paginate(5);
        return view('domains', ['domains' => $allDomains]);
    }
}
