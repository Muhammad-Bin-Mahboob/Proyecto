@extends('layout')

@section('content')
    <h1>Editar Oferta: {{ $offer->name }}</h1>

    <form method="POST" action="{{ route('offers.update', $offer) }}">
        @csrf
        @method('PUT')

        <label>Nombre:</label><br>
        <input type="text" name="name" value="{{ old('name', $offer->name) }}"><br><br>

        <label>Descripción:</label><br>
        <textarea name="description">{{ old('description', $offer->description) }}</textarea><br><br>

        <label>Descuento (%):</label><br>
        <input type="number" name="discount" min="1" max="100" value="{{ old('discount', $offer->discount) }}"><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>

    <a href="{{ route('offers.show') }}">
        <button>Cancelar</button>
    </a>
@endsection

