<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Http\Requests\SizeRequest;
use Illuminate\Support\Facades\Auth;

class SizeController extends Controller
{
    public function store(SizeRequest $request)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        // Buscar si ya existe la talla para ese producto
        $size = Size::where('product_id', $request->product_id)
                    ->where('size', $request->size)
                    ->first();

        if ($size) {
            // Si existe, aumentamos el stock sumando el que se envía
            $size->stock += $request->stock;
            $size->save();
        } else {
            // Si no existe, creamos una nueva talla
            Size::create($request->all());
        }

        return redirect()->route('products.show', $request->product_id)
            ->with('success', 'Talla añadida correctamente.');
    }


    public function edit(Size $size)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $product = $size->product;
        return view('sizes.edit', compact('size', 'product'));
    }

    public function update(SizeRequest $request, Size $size)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $size->update($request->all());

        return redirect()->route('products.show', $size->product_id)
            ->with('success', 'Talla actualizada correctamente.');
    }

    public function destroy(Size $size)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $productId = $size->product_id;
        $size->delete();

        return redirect()->route('products.show', $productId)
            ->with('success', 'Talla eliminada correctamente.');
    }
}


