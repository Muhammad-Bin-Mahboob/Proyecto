@extends('layout')

@section('content')
    <h1>Añadir Productos a la Oferta: {{ $offer->name }}</h1>

    <a href="{{ route('offers.product', $offer) }}">
        <button>Volver a productos de la oferta</button>
    </a>

    <form method="GET" action="{{ route('offers.addProductsForm', $offer) }}">
        <input type="text" name="search" placeholder="Buscar por nombre..." value="{{ request('search') }}">
        <button type="submit">Buscar</button>
    </form>


    @if($availableProducts->isEmpty())
        <p>No hay productos disponibles para añadir.</p>
    @else
        <form method="POST" action="{{ route('offers.addProducts', $offer) }}">
            @csrf
            <ul>
                @foreach($availableProducts as $product)
                    <li>
                        <label>
                            <input type="checkbox" name="product_ids[]" value="{{ $product->id }}">
                            {{ $product->name }} ({{ $product->price }}€)
                        </label>
                    </li>
                @endforeach
            </ul>

            <button type="submit">Añadir Seleccionados</button>
        </form>
    @endif
@endsection

