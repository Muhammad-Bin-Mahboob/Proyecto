@extends('layout')

@section('content')
<div>
    <h1>{{ $product->name }}</h1>
    @php
        $imagePath = public_path('Images/Products/' . $product->image_url);
        $imageUrl = file_exists($imagePath) && $product->image_url
            ? asset('Images/Products/' . $product->image_url)
            : asset('Images/Products/extra.jpg');
    @endphp

    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" width="300">

    <p><strong>Descripción:</strong> {{ $product->description }}</p>
    <p><strong>Marca:</strong> {{ $product->brand->name }}</p>
    <p><strong>Categoría:</strong> {{ ucfirst($product->category) }}</p>

    @if ($product->offer)
        @php
            $discountedPrice = $product->price * (1 - ($product->offer->discount / 100));
        @endphp
        <p><strong>Oferta:</strong> {{ $product->offer->name }}</p>
        <p><strong>Descuento:</strong> {{ $product->offer->discount }}%</p>
        <p>
            <strong>Precio original:</strong> <del>{{ number_format($product->price, 2) }}€</del><br>
            <strong>Precio con descuento:</strong> {{ number_format($discountedPrice, 2) }}€
        </p>
    @else
        <p><strong>Precio:</strong> {{ number_format($product->price, 2) }}€</p>
    @endif

    <h3>Tallas disponibles</h3>
    @if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if ($product->sizes->isEmpty())
    <p>No hay tallas disponibles.</p>
    @else
        <ul>
            @foreach ($product->sizes as $size)
                <li>
                    Talla: {{ $size->size }} — Stock: {{ $size->stock }}

                    {{-- Solo el usuario logeado puede ver el botón de añadir a la cesta --}}
                    @auth
                        <button onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ $imageUrl }}', '{{ $size->size }}', {{ $product->offer ? $discountedPrice : $product->price }})">
                            Añadir a cesta
                        </button>
                    @endauth

                    {{-- Solo admin puede editar/eliminar --}}
                    @auth
                        @if(auth()->user()->rol === 'admin')
                            <a href="{{ route('sizes.edit', $size) }}">Editar</a>
                            <form action="{{ route('sizes.destroy', $size) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Eliminar talla?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar</button>
                            </form>
                        @endif
                    @endauth
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Solo admin puede ver el formulario de crear una talla --}}
    @auth
        @if(auth()->user()->rol === 'admin')
            <h3>Crear una Talla</h3>
            <form method="POST" action="{{ route('sizes.store') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <label for="size">Talla:</label>
                <input type="number" name="size" min="30" max="60" step="1" required>

                <label for="stock">Stock:</label>
                <input type="number" name="stock" min="0" step="1" required>

                <button type="submit">Añadir Talla</button>
            </form>
        @endif
    @endauth

    <h3>Reseñas del producto</h3>

    @if ($product->reviews->isEmpty())
        <p>No hay reseñas aún.</p>
    @else
        <ul>
            @foreach ($product->reviews as $review)
                @if ($review->is_approved || (auth()->check() && auth()->user()->rol === 'admin'))
                    <li>
                        <strong>{{ $review->user->name }}</strong>:
                        {{ $review->comment }}
                        @if (!$review->is_approved)
                            <em>(Pendiente de aprobación)</em>
                        @endif

                        {{-- Solo admins pueden aprobar o desactivar --}}
                        @if (auth()->check() && auth()->user()->rol === 'admin')
                            <form action="{{ route('reviews.toggle', $review->id) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit">
                                    {{ $review->is_approved ? 'Ocultar' : 'Aprobar' }}
                                </button>
                            </form>
                        @endif
                    </li>

                    {{-- Solo el autor puede editar/eliminar --}}
                    @if (auth()->check() && auth()->id() === $review->user_id)
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta reseña?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                            <a href="{{ route('reviews.edit', $review->id) }}" class="btn btn-primary">Editar</a>
                        </form>
                    @endif
                @endif
            @endforeach
        </ul>
    @endif



    <h3>Agregar una reseña</h3>

    @if(auth()->check())
        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <label for="comment">Tu reseña:</label><br>
            <textarea name="comment" id="comment" rows="4" required></textarea><br>

            <button type="submit">Enviar reseña</button>
        </form>
    @else
        <p>Debes <a href="{{ route('login') }}">iniciar sesión</a> para dejar una reseña.</p>
    @endif

    <a href="{{ route('products.index') }}">← Volver al listado</a>
</div>

<script>
    const userId = "{{ auth()->check() ? auth()->user()->id : 'guest' }}";
    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        const expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + encodeURIComponent(cvalue) + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        const name = cname + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(name) === 0) {
                return decodeURIComponent(c.substring(name.length, c.length));
            }
        }
        return "";
    }

    function addToCart(productId, name, image, size, price) {
        const cartKey = 'cart_' + userId;
        let cart = [];

        const cartCookie = getCookie(cartKey);
        if (cartCookie) {
            try {
                cart = JSON.parse(cartCookie);
            } catch (e) {
                cart = [];
            }
        }

        const existing = cart.find(item => item.productId === productId && item.size === size);
        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                productId,
                name,
                image,
                size,
                price,
                quantity: 1
            });
        }

        setCookie(cartKey, JSON.stringify(cart), 7);
        alert("Producto añadido a la cesta");
    }
</script>
@endsection

