@extends('layout')

@section('content')
    <div class="login-container">
        {{-- los errors --}}
        @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li class="error-item">{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        {{-- formulario para el login --}}
        <form action="{{ route('login') }}" method="post" novalidate class="login-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nombre de usuario:</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-input">
            </div>

            <input type="submit" value="Enviar" class="form-submit">
        </form>
    </div>
@endsection
