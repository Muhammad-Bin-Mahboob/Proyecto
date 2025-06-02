@extends('layout')

@section('content')
@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="color: red;">{{ session('error') }}</div>
@endif
<h1>Tu Cesta</h1>
<div id="cart-container">
    <p>Cargando cesta...</p>
</div>

<h3>Total: <span id="total-price">0.00€</span></h3>

<form id="order-form" method="POST" action="{{ route('orders.store') }}">
    @csrf
    <input type="hidden" name="cart_data" id="cart-data">
    <button type="submit" class="btn btn-success">Realizar pedido</button>
</form>

<a href="{{ route('products.index') }}">← Seguir comprando</a>

<script>
    const userId = {{ auth()->id() }};
    const cookieName = 'cart_' + userId;
    document.getElementById('order-form').addEventListener('submit', function(event) {
        const cartCookie = getCookie(cookieName);
        document.getElementById('cart-data').value = cartCookie;
    });

function getCookie(cname) {
    const name = cname + "=";
    const decodedCookie = decodeURIComponent(document.cookie);
    const ca = decodedCookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(name) === 0) return c.substring(name.length, c.length);
    }
    return "";
}

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    const expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function renderCart() {
    let cart = [];
    const cartCookie = getCookie(cookieName);
    if (cartCookie) {
        try {
            cart = JSON.parse(cartCookie);
        } catch {
            cart = [];
        }
    }

    const container = document.getElementById('cart-container');
    container.innerHTML = '';

    if (cart.length === 0) {
        container.innerHTML = '<p>Tu cesta está vacía.</p>';
        document.getElementById('total-price').innerText = '0.00€';
        return;
    }

    let total = 0;

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        const div = document.createElement('div');
        div.style.border = '1px solid #ccc';
        div.style.marginBottom = '10px';
        div.style.padding = '10px';
        div.style.display = 'flex';
        div.style.alignItems = 'center';

        div.innerHTML = `
            <img src="${item.image}" alt="${item.name}" width="80" style="margin-right:10px;">
            <div style="flex-grow:1;">
                <h4>${item.name}</h4>
                <p>Talla: ${item.size}</p>
                <p>Precio unitario: ${item.price.toFixed(2)}€</p>
                <p>Cantidad:
                    <button onclick="updateQuantity(${index}, -1)">-</button>
                    <span id="qty-${index}">${item.quantity}</span>
                    <button onclick="updateQuantity(${index}, 1)">+</button>
                </p>
                <p>Subtotal: ${itemTotal.toFixed(2)}€</p>
            </div>
            <button onclick="removeItem(${index})" style="margin-left:10px; color:red;">Eliminar</button>
        `;

        container.appendChild(div);
    });

    document.getElementById('total-price').innerText = total.toFixed(2) + '€';
}

function updateQuantity(index, change) {
    let cart = JSON.parse(getCookie(cookieName) || '[]');
    if (!cart[index]) return;

    cart[index].quantity += change;
    if (cart[index].quantity < 1) {
        if (!confirm('¿Eliminar este producto de la cesta?')) {
            return;
        }
        cart.splice(index, 1);
    }
    setCookie(cookieName, JSON.stringify(cart), 7);
    renderCart();
}

function removeItem(index) {
    let cart = JSON.parse(getCookie(cookieName) || '[]');
    if (!cart[index]) return;

    if (confirm('¿Eliminar este producto de la cesta?')) {
        cart.splice(index, 1);
        setCookie(cookieName, JSON.stringify(cart), 7);
        renderCart();
    }
}

document.addEventListener('DOMContentLoaded', renderCart);
</script>
@endsection

