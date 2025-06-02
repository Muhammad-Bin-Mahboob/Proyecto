@extends('layout')

@section('content')
    <h1>Administrar Usuarios</h1>

    <form method="GET" action="{{ route('users.index') }}" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Buscar por nombre o email" value="{{ request('search') }}">
        <button type="submit">Buscar</button>
    </form>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <form action="{{ route('users.changeRole', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="rol" onchange="this.form.submit()">
                            <option value="client" {{ $user->rol == 'client' ? 'selected' : '' }}>Cliente</option>
                            <option value="admin" {{ $user->rol == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </form>
                </td>
                <td>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="color:red;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection

