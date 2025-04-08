<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Str;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $isThereUser = User::where([
            ['email', '=', $request->email],
        ])->first();

        if ($isThereUser) {
            return response()->json([
                'data' => [
                    'message' => 'Пользователь с таким email уже существует'
                ]
            ]);
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return response()->json([
            'data' => [
                'message' => 'Регистрация прошла успешно!',
                'newUser' => $newUser,
            ]
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where([
            ['name', '=', $request->name],
            ['password', '=', $request->password],
        ])->first();

        if ($user) {
            $user->token = Str::random(32);
            $user->save();

            return response()->json([
                'data' => [
                    'message' => 'Авторизация успешна!',
                    'token' => $user->token,
                ]
            ]);
        } else {
            return response()->json([
                'data' => [
                    'message' => 'Неправильная почта или пароль!',
                ]
            ]);
        }
    }

    public function logout()
    {
        $user = Auth::user();
        dd($user);

        $user->token = null;
        $user->save();

        return response()->json([
            'data' => [
                'message' => 'logout',
            ]
        ], 200);
    }

}
