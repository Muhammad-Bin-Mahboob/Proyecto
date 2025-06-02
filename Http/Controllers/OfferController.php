<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Offer;
use App\Http\Requests\OfferRequest;
use App\Http\Requests\addProductsToOfferRequest;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Verificar si está autenticado y si NO es admin
        $products = Product::with(['brand', 'offer'])
            ->whereHas('offer', function ($query) {
                $query->where('is_active', true);
            })
            ->inRandomOrder()
            ->limit(12)
            ->get();

        $brands = Brand::all();

        return view('index', compact('products', 'brands'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        return view('offers.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(OfferRequest $request)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }

        Offer::create([
            'name' => $request->name,
            'description' => $request->description,
            'discount' => $request->discount,
            'is_active' => $request->has('is_active'),
        ]);
        return redirect()->route('offers.show')->with('success', 'Oferta creada con éxito.');
    }


    /**
     * Display the specified resource.
     */
    public function show()
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }

        $offers = Offer::all();
        return view('offers.show', compact('offers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        return view('offers.edit', compact('offer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OfferRequest $request, Offer $offer)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        // Solo actualizamos lo necesario (sin tocar is_active)
        $offer->update($request->only(['name', 'description', 'discount']));
        return redirect()->route('offers.show');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $offer->delete();
        return redirect()->route('offers.show');
    }


    public function toggleStatus($id)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $offer = Offer::findOrFail($id);
        $offer->is_active = !$offer->is_active;
        $offer->save();

        return redirect()->back();
    }

   public function product(Offer $offer)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        // Traemos todos los productos, sin filtro
        $products = $offer->products()->get();
        return view('offers.product', compact('offer', 'products'));
    }

    public function addProductsForm(Request $request, Offer $offer)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $query = Product::whereNull('offer_id');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $availableProducts = $query->get();
        return view('offers.add-products', compact('offer', 'availableProducts'));
    }

    public function addProducts(AddProductsToOfferRequest $request, Offer $offer)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        Product::whereIn('id', $request->product_ids)->update(['offer_id' => $offer->id]);
        return redirect()->route('offers.product', $offer);
    }

}
