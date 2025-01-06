<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServerRequest;
use App\Models\Server;
use App\Models\ServerProfile;
use App\Models\User;
use App\Services\Pear2Service;
use Illuminate\Http\Request;
use PEAR2\Console\CommandLine\Exception;

class ServerController extends Controller
{
    public function index()
    {
        $server = Server::findById(1);
        return view('admin.pages.server.index', compact('server'));
    }

    public function store(ServerRequest $request)
    {
        if(Server::createOrUpdate($request->validated()))
            return back()->with('success', 'Successfully updated');

        return back()->with('error', 'Unable to update');
    }

    public function test()
    {
        try {
            $server = Server::findById(1);
            if($server == null) {
                throw new Exception('Server is not configured yet');
            }
            Pear2Service::checkConnection($server->ip_port, $server->username, $server->password, $server->ssl);
            return redirect()->route('admin.server.index')->with('success', 'Successfully connected');
        }
        catch (\Exception $e){
            return redirect()->route('admin.server.index')->with('error_message', $e->getMessage());
        }
    }

    public function profiles()
    {
        $server = Server::findById(1);
        if($server == null) {
            return redirect()->route('admin.server.index')->with('error', 'Server is not found');
        }
        $profiles = ServerProfile::getAll(1);
        return view('admin.pages.server.profile', compact('profiles'));
    }

    public function storeProfiles(Request $request)
    {
        if(ServerProfile::updateProfiles(1, $request->profiles))
            return back()->with('success', 'Successfully updated');

        return back()->with('error', 'Unable to update');
    }

    public function downloadProfiles()
    {
        if(ServerProfile::importFromMikrotik(1))
            return redirect()->route('admin.server.profile')->with('success', 'Successfully downloaded.');

        return redirect()->route('admin.server.profile')->with('error', 'Unable to download');
    }

    public function clients()
    {
        $clients = [];
        $p2s = new Pear2Service(1);
        if($p2s->client) {
            $clients = $p2s->getAllClients();
        }

        $users = [];
        $users_data = User::getAll();
        foreach ($users_data as $user) {
            $users[$user->username] = $user->toArray();
        }

        return view('admin.pages.server.client', compact('clients', 'users'));
    }

    public function clientLive()
    {
        $clients = [];
        $p2s = new Pear2Service(1);
        if($p2s->client) {
            $clients = $p2s->getAllActiveConnections();
        }

        $users = [];
        $users_data = User::getAll();
        foreach ($users_data as $user) {
            $users[$user->username] = $user->toArray();
        }

        return view('admin.pages.server.live', compact('clients', 'users'));
    }

    public function clientStatus(Request $request)
    {
        try{
            $user = $request->user;
            $status = $request->status;
            if(empty($user)) {
                throw new \Exception("Invalid user or status");
            }
            $p2s = new Pear2Service(1);
            if($p2s->client == null) {
                throw new \Exception($p2s->error);
            }

            if($status) {
                $p2s->enableClient($user);
            } else {
                $p2s->disableClient($user);
            }
            return $this->successResponse('Successfully updated');
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), [], 500);
        }
    }
}
