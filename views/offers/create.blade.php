@extends('layout')

@section('content')
    <h1>Crear Nueva Oferta</h1>

    <form action="{{ route('offers.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Nombre:</label>
            <input type="text" name="name" required>
        </div>

        <div>
            <label for="description">Descripción:</label>
            <textarea name="description"></textarea>
        </div>

        <div>
            <label for="discount">Descuento (%):</label>
            <input type="number" name="discount" min="1" max="100" step="0.01" required>
        </div>

        <div>
            <label>
                <input type="checkbox" name="is_active" value="1" checked>
                ¿Está activa?
            </label>
        </div>

        <button type="submit">Guardar Oferta</button>
    </form>
@endsection


