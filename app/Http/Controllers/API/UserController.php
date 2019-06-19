<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\User as UserResource;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    
    public function index()
    {
        $users = User::all();

        return new UserCollection($users);
    }

    public function show($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['success' => false, 'error' => 'We cant find this user.!'], 422);            
        }

        return new UserCollection($user);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required | email | unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
        Role::create(['user_id' => $user->id]);

        return new UserResource($user->fresh("role"));
    }

    public function update(Request $request, $updatedUser)
    {
        $this->validate($request, [
            'id' => 'required',
            'name' => 'required',
            'email' => 'email',
        ]);

        //$user = User::where("email", $request->email);
        $user = User::find($request->id);

        if (!$user) {
            return response()->json(['success' => false, 'error' => 'We cant find this user.!'], 422);
        }

        $user->name = $request->name;
        if(isset($request->updatePassword)){
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return new UserResource($user);
    }

    public function editRole(Request $request, $userId)
    {        
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'We cant find this user.!'], 422);
        }
        $role = Role::where("user_id", $userId)->first();
        $role->has_administration_rights = $request->has_administration_rights;
        $role->has_onboarding_rights = $request->has_onboarding_rights;
        $role->has_view_user_rights = $request->has_view_user_rights;
        
        $role->save();

        return new UserResource($user->fresh("role"));
    }

    public function destroy($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'We cant find this user.!'], 422);
        }

        
        $user->delete();

        return response()->json(['success' => true], 200);
    }

}
