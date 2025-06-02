<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Offer;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->rol == 'admin') {
                $query = Product::with(['brand', 'offer']);

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('description')) {
                $query->where('description', 'like', '%' . $request->description . '%');
            }

            if ($request->filled('price_min')) {
                $query->where('price', '>=', $request->price_min);
            }

            if ($request->filled('price_max')) {
                $query->where('price', '<=', $request->price_max);
            }

            if ($request->filled('brand_id')) {
                $query->where('brand_id', $request->brand_id);
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->has('has_offer')) {
                if ($request->has_offer == '1') {
                    $query->whereNotNull('offer_id');
                } elseif ($request->has_offer == '0') {
                    $query->whereNull('offer_id');
                }
            }

            $products = $query->orderBy('created_at', 'desc')->simplePaginate(10)->withQueryString();

            // Para los select
            $brands = Brand::all();
            $categories = Product::select('category')->distinct()->pluck('category');

            return view('products.index', compact('products', 'brands', 'categories'));
        } else {
            $query = Product::with(['brand', 'offer'])->where('is_active', true);

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('description')) {
                $query->where('description', 'like', '%' . $request->description . '%');
            }

            if ($request->filled('price_min')) {
                $query->where('price', '>=', $request->price_min);
            }

            if ($request->filled('price_max')) {
                $query->where('price', '<=', $request->price_max);
            }

            if ($request->filled('brand_id')) {
                $query->where('brand_id', $request->brand_id);
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->has('has_offer')) {
                if ($request->has_offer == '1') {
                    $query->whereNotNull('offer_id');
                } elseif ($request->has_offer == '0') {
                    $query->whereNull('offer_id');
                }
            }

            $products = $query->orderBy('created_at', 'desc')->simplePaginate(10)->withQueryString();

            // Para los select
            $brands = Brand::all();
            $categories = Product::select('category')->distinct()->pluck('category');

            return view('products.index', compact('products', 'brands', 'categories'));
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $brands = Brand::all();
        $categories = Product::select('category')->distinct()->pluck('category');

        return view('products.create', compact('brands', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $validated = $request->validated();

        if ($request->hasFile('image_url') && $request->file('image_url')->isValid()) {
                $extension = $request->file('image_url')->getClientOriginalExtension();
                $imageName = $request->name . '.' . $extension;
                $request->file('image_url')->move(public_path('Images/Products'), $imageName);
                $validated['image_url'] = $imageName;
            }

        // Por si no llega el checkbox, marcar activo = false
        $validated['is_active'] = $request->has('is_active');

        Product::create($validated);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('brand', 'offer', 'sizes', 'reviews.user');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Product $product)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $brands = Brand::all();
        $offers = Offer::all();
        return view('products.edit', compact('product', 'brands', 'offers'));
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $validated = $request->validated();
        $oldName = $product->name;
        $oldImage = $product->image_url;

        // Si hay una nueva imagen
        if ($request->hasFile('image')) {
            // Eliminar imagen antigua si existe
            if ($oldImage && file_exists(public_path('Images/Products/' . $oldImage))) {
                unlink(public_path('Images/Products/' . $oldImage));
            }

            // Obtener extensión
            $extension = $request->file('image')->getClientOriginalExtension();
            $imageName = $validated['name'] . '.' . $extension;

            // Mover imagen nueva
            $request->file('image')->move(public_path('Images/Products'), $imageName);

            // Asignar nueva imagen
            $product->image_url = $imageName;
        } else {
            // Si el nombre cambió pero no hay nueva imagen
            if ($oldName !== $validated['name'] && $oldImage) {
                $oldPath = public_path('Images/Products/' . $oldImage);
                $extension = pathinfo($oldImage, PATHINFO_EXTENSION);
                $newImageName = $validated['name'] . '.' . $extension;
                $newPath = public_path('Images/Products/' . $newImageName);

                if (file_exists($oldPath)) {
                    rename($oldPath, $newPath);
                    $product->image_url = $newImageName;
                }
            }
        }

        // Actualizar el resto de campos
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'category' => $validated['category'],
            'brand_id' => $validated['brand_id'],
            'offer_id' => $validated['offer_id'],
            'image_url' => $product->image_url, // asegura que la imagen se actualice si cambió
        ]);

        return redirect()->route('products.index');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        if ($product->image_url) {
            $imagePath = public_path('Images/Products/' . $product->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
    }


    public function toggleActive(Product $product)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $product->is_active = !$product->is_active;
        $product->save();

        return back()->with('success', 'Estado del producto actualizado correctamente.');
    }

    public function removeFromOffer(Product $product)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $product->update([
            'offer_id' => null,
        ]);

        return back()->with('success', 'Producto eliminado de la oferta.');
    }
}
