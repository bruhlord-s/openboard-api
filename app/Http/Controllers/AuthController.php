<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register (RegisterRequest $request)
    {
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = \Illuminate\Support\Str::random(10);

        $user = User::create($request->toArray());

        // Group::create([
        //     'name' => $request['name'] . ' group',
        //     'slug' => \Illuminate\Support\Str::slug($request['name'] . ' group')
        // ]);

        $token = $user->createToken('api_token')->accessToken;

        $response = ['token' => $token];
        return response($response);
    }

    public function login (LoginRequest $request)
    {
        $user = User::where('email', $request['email'])->first();

        if ($user) {
            if (Hash::check($request['password'], $user->password)) {
                $token = $user->createToken('api_token')->accessToken;

                $response = ['token' => $token];
                return response($response);
            } else {
                $response = ['message' => 'Пользователя с такой почтой/паролем не существует'];
                return response($response, 422);
            }
        } else {
            $response = ['message' => 'Пользователя с такой почтой/паролем не существует'];
            return response($response, 422);
        }
    }

    public function logout (Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();

        $response = ['message' => 'You have been successfully logged out!'];
        return response($response);
    }
}
