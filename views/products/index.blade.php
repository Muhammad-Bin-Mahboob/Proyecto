@extends('layout')

@section('content')
<div>
    <h1>Listado de Productos</h1>

     @auth
        @if(auth()->user()->rol === 'admin')
            <a href="{{ route('products.create') }}">
                Crear Producto
            </a>
        @endif
    @endauth


    <div>
        <form method="GET" action="{{ route('products.index') }}">
        <label>Filtrar por nombre:</label>
        <input type="text" name="name" placeholder="Nombre" value="{{ request('name') }}"><br>

        <label>Filtrar por descripción:</label>
        <input type="text" name="description" placeholder="Descripción" value="{{ request('description') }}"><br>

        <label>Filtrar por precio:</label>
        <input type="number" name="price_min" placeholder="Precio mínimo" step="0.01" value="{{ request('price_min') }}">
        <input type="number" name="price_max" placeholder="Precio máximo" step="0.01" value="{{ request('price_max') }}"><br>

        <label>Filtrar por marca:</label>
        <select name="brand_id">
            <option value="">Todas las marcas</option>
            @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select><br>

        <label>Filtrar por categoría:</label>
        <select name="category">
            <option value="">Todas las categorías</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                    {{ ucfirst($category) }}
                </option>
            @endforeach
        </select><br>

        <label>Filtrar por oferta:</label>
        <select name="has_offer">
            <option value="">Con/Sin Oferta</option>
            <option value="1" {{ request('has_offer') === '1' ? 'selected' : '' }}>Con oferta</option>
            <option value="0" {{ request('has_offer') === '0' ? 'selected' : '' }}>Sin oferta</option>
        </select><br>

        <button type="submit">Filtrar</button>
    </form>
        @foreach ($products as $product)
            <div>
                <div>
                    @php
                        $imagePath = public_path('Images/Products/' . $product->image_url);
                        $imageUrl = file_exists($imagePath) && $product->image_url
                            ? asset('Images/Products/' . $product->image_url)
                            : asset('Images/Products/extra.jpg');
                    @endphp

                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" width="150">

                    <div>
                        <a href="{{ route('products.show', $product->id) }}">
                            <h2>{{ $product->name }}</h2>
                        </a>
                        <p>{{ $product->description }}</p>
                        <p>Marca: {{ $product->brand->name }}</p>

                        @if ($product->offer)
                            @php
                                $discountedPrice = $product->price * (1 - ($product->offer->discount / 100));
                            @endphp
                            <p>
                                Precio original: <del>{{ number_format($product->price, 2) }}€</del><br>
                                Descuento: {{ $product->offer->discount }}%<br>
                                Precio con descuento: {{ number_format($discountedPrice, 2) }}€
                            </p>
                        @else
                            <p>Precio: {{ number_format($product->price, 2) }}€</p>
                        @endif

                        {{-- Mostrar botones solo para admin --}}
                        @auth
                            @if(auth()->user()->rol === 'admin')
                                <form action="{{ route('products.toggleActive', $product) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit">
                                        {{ $product->is_active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;"
                                    onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">
                                        Eliminar
                                    </button>
                                </form>

                                <a href="{{ route('products.edit', $product) }}">
                                    Editar
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                <hr>
            </div>
        @endforeach
        {{$products->links()}}

    </div>
@endsection


