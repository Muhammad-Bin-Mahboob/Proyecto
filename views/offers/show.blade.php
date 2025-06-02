@extends('layout')

@section('content')
    <h1>Todas las Ofertas</h1>

    <a href="{{ route('offers.create') }}">
        <button>Crear Nueva Oferta</button>
    </a>

    <ul>
        @foreach($offers as $offer)
            <li>
                <a href="{{ route('offers.product', $offer->id) }}">
                    <strong>{{ $offer->name }}</strong>
                </a>
                <br>
                {{ $offer->description }}<br>
                Descuento: {{ $offer->discount }}%<br>
                Estado:
                @if($offer->is_active)
                    <span style="color: green;">Activa</span>
                @else
                    <span style="color: red;">Inactiva</span>
                @endif

                <form action="{{ route('offers.toggle', $offer->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit">
                        {{ $offer->is_active ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>

                <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Seguro que quieres eliminar esta oferta?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: red;">
                        Eliminar
                    </button>
                </form>

                <a href="{{ route('offers.edit', $offer->id) }}">
                    <button>Editar</button>
                </a>

            </li>
            <hr>
        @endforeach
    </ul>
@endsection


