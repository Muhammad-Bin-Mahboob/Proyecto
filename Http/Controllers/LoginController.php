<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function signupForm(): View
    {
        return view('auth.signup');
    }

    public function signup(SignupRequest $request): RedirectResponse
    {
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->birthday = $request->get('birthday');
        $user->password = Hash::make($request->get('password'));
        $user->address = $request->get('address');
        $user->phone = $request->get('phone');
        $user->rol = $request->get('rol') ?: 'client';

        $user->save();

        Auth::login($user);

        return redirect()->route('users.account');
    }



    public function loginForm(): View
    {
        if (Auth::viaRemember()) {
            return view('auth.login');
        } else if (Auth::check()) {
            return view('auth.login');
        } else {
            return view('auth.login');
        }
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('name', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('users.account');
        } else {
            return back()->withErrors([
                'error' => 'Usuario o la contraseña incorrecta.',
            ]);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('index');
    }
}
