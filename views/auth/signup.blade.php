@extends('layout')

@section('content')
    <div class="signup-container">
        {{-- los errors --}}
        @if ($errors->any())
            <ul class="error-list">
                @foreach ($errors->all() as $error)
                    <li class="error-item">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        {{-- form para registrar --}}
        <form action="{{ route('signup') }}" method="post" novalidate class="signup-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-input">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-input">
            </div>

            <div class="form-group">
                <label for="birthday" class="form-label">Fecha de nacimiento:</label>
                <input type="date" name="birthday" id="birthday" value="{{ old('birthday') }}" class="form-input">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-input">
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Repite Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Dirección:</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-input">
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Teléfono:</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" minlength="9" maxlength="9" class="form-input">
            </div>

            @auth
                @if (auth()->user()->rol === 'admin')
                    <div class="form-group">
                        <label for="rol" class="form-label">Rol:</label>
                        <select name="rol" id="rol" class="form-input">
                            <option value="">Cliente (por defecto)</option>
                            <option value="client" {{ old('rol') == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                @endif
            @endauth

            <input type="submit" value="Enviar" class="form-submit">
        </form>
    </div>
@endsection
