@extends('layout')

@section('title','Home')
@section('content')
<div>
   <h1>Ofertas</h1>
    @foreach ($products as $product)
        <div>
            @if(file_exists(public_path( 'Images/Brands/' . $product->image_url)))
                <img src="{{ asset( 'Images/Brands/' . $product->image_url) }}" alt="{{ $product->name }}" width="150">
            @else
                <img src="{{ asset('Images/Brands/extra.jpg') }}" alt="Imagen no disponible" width="150">
            @endif

            <a href="{{ route('products.show', $product->id) }}">
                <h2>{{ $product->name }}</h2>
            </a>
            <p>Marca: {{ $product->brand->name }}</p>
            <p>Description: {{ $product->description }}</p>
            <p>Category: {{ $product->category }}</p>
            <p>Discount: {{ $product->offer->name }}  {{ $product->offer->discount }}%</p>
            <p>Precio original: <del>{{ $product->price }} €</del></p>
            <p>Precio con descuento:
                <strong>
                    {{ round($product->price * (1 - $product->offer->discount / 100), 2) }} €
                </strong>
            </p>
        </div>
        <hr>
    @endforeach

    <h1>Marcas con que trabajamos</h1>
    {{-- Mostrar botón Crear solo si el usuario es admin --}}
    @auth
        @if(auth()->user()->rol === 'admin')
            <a href="{{ route('brands.create') }}">
                <button>Crear nueva marca</button>
            </a>
        @endif
    @endauth
    <hr>

    @foreach($brands as $brand)
        <div>
            @php
                $imagePath = 'Images/Brands/' . $brand->image_url;
                $imageExists = !empty($brand->image_url) && File::exists(public_path($imagePath));
            @endphp

            @if($imageExists)
                <img src="{{ asset($imagePath) }}" alt="{{ $brand->name }}" width="150">
            @else
                <img src="{{ asset('Images/Brands/extra.jpg') }}" alt="Imagen no disponible" width="150">
            @endif

            <p>{{ $brand->name }}</p>

            {{-- Mostrar botones editar y eliminar solo para admin --}}
            @auth
                @if(auth()->user()->rol === 'admin')
                    <a href="{{ route('brands.edit', $brand) }}">
                        <button>Editar</button>
                    </a>

                    <form action="{{ route('brands.destroy', $brand) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                @endif
            @endauth
        </div>
        <hr>
    @endforeach
</div>
@endsection
