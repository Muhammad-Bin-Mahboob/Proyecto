@extends('layout')

@section('content')
<h2>Editar Talla</h2>
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
<form method="POST" action="{{ route('sizes.update', $size) }}">
    @csrf
    @method('PUT')

    <input type="hidden" name="product_id" value="{{ $product->id }}">

    <label for="size">Talla:</label>
    <input  type="number" name="size" min="30" max="60" step="1" value="{{ old('size', $size->size) }}" required><br>

    <label for="stock">Stock:</label>
    <input  type="number" name="stock" min="0" step="1" value="{{ old('stock', $size->stock) }}" min="0" required><br>

    <button type="submit">Actualizar</button>
</form>
@endsection

