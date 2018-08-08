<?php

namespace App\Http\Controllers;

use App\Models\Db\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function signin(Request $request)
    {
        $this->validate($request, [
            'email'     => 'required|string',
            'password'  => 'required|string'
        ]);

        $user = User::getInstance()->fetchEntryByEmail($request->input('email'));

        if (is_object($user) && Hash::check($request->input('password'), $user->password)) {

            $lifetime = env('TOKEN_LIFETIME');
            $expired = time() + $lifetime;
            $token = base64_encode(str_random(40));

            User::getInstance()->updateToken($user->getId(), $token, $expired);

            return response()->json(['status' => 'success', 'token' => $token, 'token_expired' => $expired], 200);

        }

        return response()->json(['status' => 'Authentication failed'],401);
    }

    public function signup(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|string',
            'email'     => 'required|string|email|unique:users',
            'password'  => 'required|string'
        ]);

        $user = new \App\Models\User();
        $user->setName($request->input('name'));
        $user->setEmail($request->input('email'));
        $user->setPassword(app('hash')->make($request->input('password')));

        $dbUser = new User();
        $dbUser->save($user);

        return response()->json($dbUser->modelToArray($user), 201);
    }
}
