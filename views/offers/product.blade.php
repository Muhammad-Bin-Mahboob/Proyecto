@extends('layout')

@section('content')
    <h1>Productos con la oferta: {{ $offer->name }}</h1>

    <a href="{{ route('offers.show') }}">
        <button>Volver a ofertas</button>
    </a>

    <a href="{{ route('offers.addProductsForm', $offer) }}">
        <button>Añadir Productos a esta Oferta</button>
    </a>

    @if($products->isEmpty())
        <p>No hay productos vinculados a esta oferta.</p>
    @else
        <ul>
            @foreach($products as $product)
                <li>
                    <strong>{{ $product->name }}</strong><br>
                    Precio: ${{ number_format($product->price, 2) }}<br>
                    Categoría: {{ ucfirst($product->category) }}<br>
                    Estado:
                    @if($product->is_active)
                        <span style="color: green;">Activo</span>
                    @else
                        <span style="color: red;">Inactivo</span>
                    @endif

                    <form action="{{ route('products.toggleActive', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">
                            {{ $product->is_active ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>

                    <form action="{{ route('products.removeFromOffer', $product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que quieres quitar este producto de la oferta?');">
                        @csrf
                        <button type="submit" style="color: red;">Quitar de la oferta</button>
                    </form>

                </li>
                <hr>
            @endforeach
        </ul>
    @endif
@endsection


