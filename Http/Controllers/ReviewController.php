<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReviewRequest;


class ReviewController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request) // Asegúrate que el use sea correcto
    {
        $validated = $request->validated(); // Obtiene los datos validados

        Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
            'is_approved' => true, // Pendiente de aprobación
        ]);

        return back();
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
    public function edit(Review $review)
    {
        if (Auth::id() !== $review->user_id) {
            return redirect()->back()->with('error', 'No tienes permiso para editar esta reseña.');
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $review->comment = $request->input('comment');
        $review->save();

        return redirect()->route('products.show', $review->product_id);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        if (Auth::id() !== $review->user_id) {
            return back();
        }

        $review->delete();

        return back();
    }

    public function toggle(Review $review)
    {
        if (Auth::user()->rol !== 'admin') {
            return back();
        }

        $review->is_approved = !$review->is_approved;
        $review->save();

        return back();
    }


}
