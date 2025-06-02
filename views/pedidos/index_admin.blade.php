@extends('layout')

@section('content')
<h1>Todos los Pedidos</h1>

<form method="GET" action="{{ route('orders.index_admin') }}" style="margin-bottom: 20px;">
    <input type="text" name="name" placeholder="Nombre del usuario" value="{{ request('name') }}">
    <input type="text" name="email" placeholder="Email del usuario" value="{{ request('email') }}">
    <select name="status">
        <option value="">-- Estado --</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
        <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    <button type="submit">Buscar</button>
</form>


@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif


@forelse($orders as $order)
    <div style="border:1px solid #ccc; margin:15px; padding:15px;">
        <h3>Pedido</h3>
        <p><strong>Usuario:</strong> {{ $order->user->name ?? 'Desconocido' }} ({{ $order->user->email ?? 'Sin email' }})</p>
        <p>Estado: <strong>{{ ucfirst($order->status) }}</strong></p>
        <p>Total: {{ number_format($order->total, 2) }}€</p>
        <p>Fecha: {{ $order->created_at->format('d/m/Y H:i') }}</p>

        <ul>
            @foreach($order->items as $item)
                <li>
                    {{ $item->product->name }} - Talla {{ $item->size->size }} -
                    Cantidad: {{ $item->quantity }} -
                    Subtotal: {{ number_format($item->subtotal, 2) }}€
                </li>
            @endforeach
        </ul>
    </div>
    @if($order->status === 'pending')
        <form action="{{ route('admin.orders.deliver', $order->id) }}" method="POST" style="display:inline-block; margin-top:10px;">
            @csrf
            <button type="submit" class="btn btn-primary">Entregado</button>
        </form>

        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" style="display:inline-block; margin-left:10px;">
            @csrf
            <button type="submit" class="btn btn-danger">Cancelar</button>
        </form>
    @endif

@empty
    <p>No hay pedidos registrados.</p>
@endforelse
@endsection

