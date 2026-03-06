<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected array $extend = ['controller' => 'auth'];

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login', ['extend' => $this->extend]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code'   => 422,
                'msg'    => 'Errores de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = [
            'username' => strtolower($request->input('username')),
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'code' => 401,
                'msg'  => '¡Credenciales incorrectas!',
            ], 401);
        }

        $request->session()->regenerate();
        session(['authUser' => Auth::user()]);

        return response()->json([
            'code'     => 200,
            'msg'      => 'Inicio de sesión correcto',
            'redirect' => route('home'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}