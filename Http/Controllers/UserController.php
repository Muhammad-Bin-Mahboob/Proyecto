<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EditRequest;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return view('users.account');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('users.edit', ['user' => Auth::user()]);
    }

    public function update(EditRequest $request, User $user)
    {
        if (Auth::id() !== $user->id) {
            return redirect()->route('users.account');
        }

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->birthday = $request->get('birthday');
        $user->address = $request->get('address');
        $user->phone = $request->get('phone');

        // Solo actualizar contraseña si se proporciona
        if ($request->filled('password')) {
            $user->password = Hash::make($request->get('password'));
        }

        // No permitir cambio de rol
        $user->save();

        return redirect()->route('users.account');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Verifica si el usuario autenticado es el mismo que quiere eliminarse
        if (Auth::id() !== $user->id) {
            return redirect('/cuenta');
        }

        // Cierra la sesión antes de eliminar
        Auth::logout();

        // Elimina al usuario
        $user->delete();

        return redirect('/');
    }

    public function changeRole(UserRequest $request, User $user)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }

        $user->rol = $request->rol;
        $user->save();

        return redirect()->route('users.index');
    }

    public function adminIndex(Request $request)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->get();

        return view('users.index', compact('users'));
    }
}
