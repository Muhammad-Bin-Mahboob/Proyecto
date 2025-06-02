@extends('layout')

@section('content')
<h1>Mis Pedidos</h1>

@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif

@forelse($orders as $order)
    <div style="border:1px solid #ccc; margin:15px; padding:15px;">
        <h3>Pedido</h3>
        <p>Estado: <strong>{{ ucfirst($order->status) }}</strong></p>
        <p>Total: {{ number_format($order->total, 2) }}€</p>
        <p>Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</p>

        <ul>
            @foreach($order->items as $item)
                <li>
                    {{ $item->product->name }} - Talla {{ $item->size->size }} -
                    Cantidad: {{ $item->quantity }} - Subtotal: {{ number_format($item->subtotal, 2) }}€
                </li>
            @endforeach
        </ul>
    </div>
    @if($order->status === 'pending')
        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline-block; margin-left:10px;">
            @csrf
            <button type="submit" class="btn btn-danger">Cancelar</button>
        </form>
    @endif
@empty
    <p>No tienes pedidos realizados.</p>
@endforelse
@endsection

