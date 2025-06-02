@extends('layout')

@section('content')
<h1>Crear Producto</h1>

@if ($errors->any())
<div>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Nombre:</label><br>
    <input type="text" name="name" value="{{ old('name') }}" required><br><br>

    <label>Descripción:</label><br>
    <textarea name="description">{{ old('description') }}</textarea><br><br>

    <label>Precio:</label><br>
    <input type="number" name="price" step="0.01" value="{{ old('price') }}" min="1" required><br><br>

    <label>Marca:</label><br>
    <select name="brand_id" required>
        <option value="">Selecciona una marca</option>
        @foreach ($brands as $brand)
            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select><br><br>

    <label>Categoría:</label><br>
    <select name="category" required>
        <option value="">Selecciona una categoría</option>
        @foreach ($categories as $category)
            <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                {{ ucfirst($category) }}
            </option>
        @endforeach
    </select><br><br>

    <label>Imagen:</label><br>
    <input type="file" name="image_url" accept="image/*"><br><br>

    <label>Activo:</label>
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>

    <button type="submit">Crear Producto</button>
</form>

<a href="{{ route('products.index') }}">Volver al listado</a>
@endsection

