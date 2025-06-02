@extends('layout')

@section('content')
<div class="edit-container">
    <h1>Editar Perfil</h1>
    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li class="error-item">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('users.update', Auth::user()->id) }}" method="POST" class="edit-form">
        @csrf
        @method('PUT')

        <label>Nombre:
            <input type="text" name="name" value="{{ old('name', $user->name) }}">
        </label>

        <label>Email:
            <input type="email" name="email" value="{{ old('email', $user->email) }}">
        </label>

        <label>Birthday:
            <input type="date" name="birthday" value="{{ old('birthday', $user->birthday) }}">
        </label>

        <label>Dirección:
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
        </label>

        <label>Teléfono:
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
        </label>

        <label>Nueva Contraseña:
            <input type="password" name="password">
        </label>

        <label>Confirmar Contraseña:
            <input type="password" name="password_confirmation">
        </label>

        <button type="submit">Guardar cambios</button>
    </form>
</div>
@endsection

