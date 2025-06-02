<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\BrandCreateRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\File;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandCreateRequest $request)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Obtener extensión original (como jpg, png)
            $extension = $image->getClientOriginalExtension();

            // Usar el nombre de la marca tal cual (sin modificaciones) + extensión
            $imageName = $request->name . '.' . $extension;

            // Guardar la imagen
            $image->move(public_path('Images/Brands'), $imageName);
        }

        // Crear la marca
        Brand::create([
            'name' => $request->name,
            'image_url' => $imageName
        ]);

        return redirect()->route('index');
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
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $brand = Brand::findOrFail($id); // Busca la marca por su ID
        return view('brands.edit', compact('brand')); // Pasa la variable a la vista
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $oldImageName = $brand->image_url;
        $newName = $request->name;
        $newImageName = $oldImageName; // Por defecto, mantenemos la misma imagen

        // Verificar si se subió una nueva imagen
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Obtener la extensión real de la nueva imagen subida
            $extension = $image->getClientOriginalExtension();

            // Construir el nuevo nombre de la imagen basado en el nuevo nombre de la marca y la extensión nueva
            $newImageName = $newName . '.' . $extension;

            // Eliminar la imagen anterior si existe
            if ($oldImageName && file_exists(public_path('Images/Brands/' . $oldImageName))) {
                unlink(public_path('Images/Brands/' . $oldImageName));
            }

            // Guardar la nueva imagen con el nuevo nombre
            $image->move(public_path('Images/Brands'), $newImageName);

        } elseif ($oldImageName && $brand->name !== $newName) {
            // Si NO hay nueva imagen, pero sí se cambió el nombre de la marca
            $oldPath = public_path('Images/Brands/' . $oldImageName);

            // Obtener la extensión de la imagen anterior para mantenerla
            $extension = pathinfo($oldImageName, PATHINFO_EXTENSION);


            // Nuevo nombre de la imagen con la extensión antigua
            $newImageName = $newName . '.' . $extension;
            $newPath = public_path('Images/Brands/' . $newImageName);

            // Renombrar el archivo en el sistema de archivos
            if (file_exists($oldPath)) {
                rename($oldPath, $newPath);
            }
        }

        // Actualizar datos en la base de datos
        $brand->update([
            'name' => $newName,
            'image_url' => $newImageName,
        ]);

        return redirect()->route('index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Auth::check() || Auth::user()->rol == 'client') {
            return redirect()->route('index');
        }
        $brand = Brand::findOrFail($id);

        // Ruta de la imagen
        if ($brand->image_url) {
            $imagePath = public_path('Images/Brands/' . $brand->image_url);

            // Eliminar imagen si existe
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        // Eliminar el brand (los productos se borran en cascada)
        $brand->delete();

        return redirect()->route('index');
    }
}
