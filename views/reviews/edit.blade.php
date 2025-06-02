@extends('layout')

@section('content')
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <h1>Editar reseña</h1>

    <form method="POST" action="{{ route('reviews.update', $review->id) }}">
    @csrf
    @method('PUT')

    <input type="hidden" name="product_id" value="{{ $review->product_id }}">

    <label for="comment">Tu reseña:</label><br>
    <textarea name="comment" id="comment" required>{{ old('comment', $review->comment) }}</textarea><br>

    <button type="submit">Actualizar reseña</button>
</form>


    <a href="{{ route('products.show', $review->product_id) }}">← Volver al producto</a>
@endsection

