@extends('layout')

@section('title', 'Editar Marca')

@section('content')
    <h1>Editar Marca</h1>

    @if ($errors->any())
        <div>
            <strong>Se encontraron los siguientes errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Nombre de la Marca:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" required>
        </div>

        <div>
            <label>Imagen actual:</label><br>
            @if($brand->image_url && file_exists(public_path('Images/Brands/' . $brand->image_url)))
                <img src="{{ asset('Images/Brands/' . $brand->image_url) }}" width="150">
            @else
                <p>No hay imagen disponible.</p>
            @endif
        </div>

        <div>
            <label for="image">Subir nueva imagen (opcional):</label>
            <input type="file" name="image" id="image" accept=".jpg,.jpeg,.png">
        </div>

        <button type="submit">Actualizar Marca</button>
    </form>
@endsection

