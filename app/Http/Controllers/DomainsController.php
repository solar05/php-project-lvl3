<?php

namespace App\Http\Controllers;

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
        DB::table('domains')->insert(['name' => $domain, 'created_at' => $currentDate]);
        $insertedDomain = DB::select("SELECT id FROM domains WHERE name = ? and created_at = ?", [$domain,
            $currentDate]);
        return redirect("/domains/{$insertedDomain[0]->id}");
    }

    public function showDomain($id)
    {
        $requestedDomain = DB::select("SELECT * FROM domains WHERE id = ?", [$id]);
        return view('domain', ['domains' => $requestedDomain]);
    }
}
