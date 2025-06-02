@extends('layout')

@section('content')
<h1>Editar Producto</h1>

@if ($errors->any())
<div>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>Nombre:</label>
    <input type="text" name="name" value="{{ old('name', $product->name) }}" required><br>

    <label>Descripción:</label>
    <textarea name="description">{{ old('description', $product->description) }}</textarea><br>

    <label>Precio:</label>
    <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" required><br>

    <label>Categoría:</label>
    <select name="category" required>
        <option value="men" {{ old('category', $product->category) == 'men' ? 'selected' : '' }}>Hombre</option>
        <option value="women" {{ old('category', $product->category) == 'women' ? 'selected' : '' }}>Mujer</option>
    </select><br>

    <label>Marca:</label>
    <select name="brand_id" required>
        @foreach ($brands as $brand)
            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select><br>

    <label>Oferta:</label>
    <select name="offer_id">
        <option value="">Sin oferta</option>
        @foreach ($offers as $offer)
            <option value="{{ $offer->id }}" {{ old('offer_id', $product->offer_id) == $offer->id ? 'selected' : '' }}>
                {{ $offer->name }} {{ $offer->discount }}% de descuento
            </option>
        @endforeach
    </select><br>

    <label>Imagen actual:</label><br>
    @if ($product->image_url)
        <img src="{{ asset('Images/Products/' . $product->image_url) }}" width="120"><br>
    @else
        <p>No hay imagen.</p>
    @endif

    <label>Nueva Imagen:</label>
    <input type="file" name="image"><br><br>

    <button type="submit">Actualizar</button>
</form>
@endsection

