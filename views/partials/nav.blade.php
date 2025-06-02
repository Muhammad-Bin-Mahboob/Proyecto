<nav>
  <div class="nav-container">
    <a href="{{ route('index') }}" class="logo">
      <h1>Falcons</h1>
      <img src="/Images/Logo/logo.png" alt="Logo de Falcons" width="200"/>
    </a>

    <input type="checkbox" id="nav-toggle" class="nav-toggle" />
    <label for="nav-toggle" class="nav-toggle-label" aria-label="Abrir menú">
      <span></span>
      <span></span>
      <span></span>
    </label>
        <div id="nav-menu">
            <ul>
                <li><a href="{{ route('index') }}">Inicio</a></li>
                @auth
                    @if(auth()->user()->rol === 'admin')
                        <li><a href="{{ route('offers.show') }}">Crear Offers</a></li>
                    @endif
                @endauth

                <li><a href="{{ route('products.index') }}">Productos</a></li>
                @auth
                    @if(auth()->user()->rol === 'admin')
                        <li><a href="{{ route('products.create') }}">Crear Productos</a></li>
                    @endif
                @endauth

                @auth
                    <li><a href="{{ route('orders.index') }}">Pedidos</a></li>
                @endauth
                @auth
                    @if(auth()->user()->rol === 'admin')
                        <li><a href="{{ route('orders.index_admin') }}">Buscar Pedidos de Usuarios</a></li>
                    @endif
                @endauth

                @auth
                    <li><a href="{{ route('carts.show') }}">Carrito de Compra</a></li>
                @endauth
                <li><a href="{{ route('index') }}">Contacto</a></li>
                <li><a href="{{ route('index') }}">Donde Estamos</a></li>

                @guest
                <li><a href="{{ route('signupForm') }}">Register</a></li>
                <li><a href="{{ route('loginForm') }}">Login</a></li>
                @else
                @if(auth()->user()->rol === 'admin')
                <li><a href="{{ route('signupForm') }}">Registrar Usuarios</a></li>
                @endif
                <li><a href="{{ route('logout') }}">Logout</a></li>
                <li><a href="{{ route('users.account') }}">Cuenta</a></li>
                @endguest
                @auth
                    @if(auth()->user()->rol === 'admin')
                        <li><a href="{{ route('users.index') }}">Cuenta de todos los usuarios</a></li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>




