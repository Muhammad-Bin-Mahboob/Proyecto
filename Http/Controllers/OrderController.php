<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Size;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders;
        return view('pedidos.index', compact('orders'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $cartData = json_decode($request->input('cart_data'), true);

        if (!$cartData || empty($cartData)) {
            return redirect()->back()->with('error', 'Tu cesta está vacía.');
        }

        $user = Auth::user();

        $total = 0;
        $validItems = [];

        // Validar y filtrar items con stock suficiente
        foreach ($cartData as $item) {
            $size = Size::where('size', $item['size'])
                        ->where('product_id', $item['productId'])
                        ->first();

            if (!$size) {
                // Talla o producto no existe, ignorar
                continue;
            }

            if ($size->stock < $item['quantity']) {
                // Stock insuficiente para este producto, puedes hacer return con error
                return redirect()->back()->with('error', "Stock insuficiente para {$item['name']} talla {$item['size']}.");
            }

            $validItems[] = [
                'product_id' => $item['productId'],
                'size_id' => $size->id,
                'quantity' => $item['quantity'],
                'price_unit' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
                'name' => $item['name'], // Para mensaje si quieres
                'size' => $item['size'],
            ];

            $total += $item['price'] * $item['quantity'];
        }

        if (empty($validItems)) {
            return redirect()->back()->with('error', 'No hay productos válidos en la cesta para realizar el pedido.');
        }

        // Crear pedido
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Guardar items y actualizar stock
        foreach ($validItems as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'size_id' => $item['size_id'],
                'quantity' => $item['quantity'],
                'price_unit' => $item['price_unit'],
                'subtotal' => $item['subtotal'],
            ]);

            $size = Size::find($item['size_id']);
            $size->stock -= $item['quantity'];
            $size->save();
        }

        // Borrar cookie y redirigir con éxito
        $cookieName = 'cart_' . $user->id;
        return redirect()->back()->withCookie(cookie()->forget($cookieName))
            ->with('success', '¡Pedido realizado con éxito!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function cancel($id)
    {
        $order = Order::with('items')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status !== 'pending' && $order->status !== 'reserved') {
            return back()->with('error', 'No se puede cancelar este pedido.');
        }

        // Restaurar el stock
        foreach ($order->items as $item) {
            $item->size->stock += $item->quantity;
            $item->size->save();
        }

        // Cambiar estado
        $order->status = 'cancelled';
        $order->save();

        return back()->with('success', 'Pedido cancelado.');
    }

    public function indexAdmin()
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $name = request('name');
        $email = request('email');
        $status = request('status');

        $query = Order::with(['user', 'items.product', 'items.size']);

        if ($name) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$name%"));
        }

        if ($email) {
            $query->whereHas('user', fn($q) => $q->where('email', 'like', "%$email%"));
        }

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->get();

        return view('pedidos.index_admin', compact('orders'));
    }


    public function deliver($id)
    {
       if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $order = Order::findOrFail($id);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Solo se pueden reservar pedidos pendientes.');
        }

        $order->status = 'delivered';
        $order->save();

        return back()->with('success', 'Pedido entregado.');
    }

}
