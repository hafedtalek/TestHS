<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientCollection;
use App\Http\Resources\Client as ClientResource;
use App\Client;
use App\Role;
use Illuminate\Support\Facades\Hash;
class ClientController extends Controller
{
    
    public function index()
    {
        $clients = Client::all();

        return new ClientCollection($clients);
    }

    public function show($clientId)
    {
        $client = Client::find($clientId);

        if (!$client) {
            return response()->json(['success' => false, 'error' => 'We cant find this client.!'], 422);            
        }

        return new ClientCollection($client);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required | email | unique:clients',
            'phone' => 'required',
        ]);

        $client = Client::create([
                    'firstname' => $request->firstname,
                    'lastname' => $request->lastname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'comment' => $request->comment,
                ]);
        
        return new ClientResource($client);
    }

    public function update(Request $request, $updatedClient)
    {
        $this->validate($request, [
            'id' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'email',
            'phone' => 'required',
        ]);

        //$client = Client::where("email", $request->email);
        $client = Client::find($request->id);

        if (!$client) {
            return response()->json(['success' => false, 'error' => 'We cant find this client.!'], 422);
        }

        $client->firstname = $request->firstname;
        $client->lastname = $request->lastname;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->comment = $request->comment;
        
        
        $client->save();

        return new ClientResource($client);
    }

    public function editRole(Request $request, $clientId)
    {        
        $client = Client::find($clientId);
        if (!$client) {
            return response()->json(['success' => false, 'error' => 'We cant find this client.!'], 422);
        }
        $role = Role::where("client_id", $clientId)->first();
        $role->has_administration_rights = $request->has_administration_rights;
        $role->has_onboarding_rights = $request->has_onboarding_rights;
        $role->has_view_client_rights = $request->has_view_client_rights;
        
        $role->save();

        return new ClientResource($client->fresh("role"));
    }

    public function destroy($clientId)
    {
        $client = Client::find($clientId);
        if (!$client) {
            return response()->json(['success' => false, 'error' => 'We cant find this client.!'], 422);
        }

        
        $client->delete();

        return response()->json(['success' => true], 200);
    }

}
