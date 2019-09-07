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
        $domain = new Domain($request->get('domain'));
        $currentDate = date('Y-m-d H:i:s');
        DB::table('domains')->insert([
            'name' => $domain->getUrl(),
            'created_at' => $currentDate,
            'state' => $domain->getCurrentState()]);
        $insertedDomain = DB::select("SELECT id FROM domains where created_at = ?", [$currentDate])[0];
        $domain->setId($insertedDomain->id);
        Queue::push(new AnaliseJob($domain));
        return redirect(route('domain', ['id' => $domain->getId()]));
    }

    public function showDomain($id)
    {
        $requestedDomain = DB::select("SELECT * FROM domains WHERE id = ?", [$id]);
        return view('domain', ['domains' => $requestedDomain[0]]);
    }

    public function showAll()
    {
        $allDomains = DB::table('domains')->paginate(10);
        return view('domains', ['domains' => $allDomains]);
    }
}
