@extends('layout')

@section('title', 'Crear Marca')

@section('content')
    <h1>Crear Nueva Marca</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="name">Nombre de la marca:</label>
        <input type="text" name="name" id="name" required>

        <br>

        <label for="image">Imagen de la marca (opcional):</label>
        <input type="file" name="image" id="image" accept="image/*">

        <br><br>

        <button type="submit">Guardar Marca</button>
    </form>

    <br>
    <a href="{{ route('index') }}">Volver</a>
@endsection

