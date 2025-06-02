@extends('layout')

@section('content')
    <div class="account-container">
        <h1 class="account-title">Mi Cuenta</h1>

        {{-- Mostrar información del usuario --}}
        <div class="account-info">
            <h3 class="account-subtitle">Información del Usuario</h3>
            <p class="account-detail"><strong>Nombre:</strong> {{ Auth::user()->name }}</p>
            <p class="account-detail"><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p class="account-detail"><strong>Birthday:</strong> {{ Auth::user()->birthday }}</p>
            <p class="account-detail"><strong>Address:</strong> {{ Auth::user()->address }}</p>
            <p class="account-detail"><strong>Phone number:</strong> {{ Auth::user()->phone }}</p>
        </div>

        {{-- Botón para editar perfil --}}
        <a href="{{ route('users.edit') }}" class="account-edit-button">Editar perfil</a>

        {{-- Botón para eliminar cuenta --}}
        <form action="{{ route('users.destroy', Auth::user()->id) }}" method="post" novalidate class="account-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="account-delete-button">Eliminar cuenta</button>
        </form>
    </div>
@endsection

