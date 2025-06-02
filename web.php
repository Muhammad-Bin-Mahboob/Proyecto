<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;


Route::get('/', [OfferController::class, 'index'])->name('index');

Route::get('/offers/show/all', [OfferController::class, 'show'])->name('offers.show')->middleware('auth');
Route::post('/offers/{id}/toggle', [OfferController::class, 'toggleStatus'])->name('offers.toggle')->middleware('auth');
Route::get('/offers/create', [OfferController::class, 'create'])->name('offers.create')->middleware('auth');
Route::post('/offers', [OfferController::class, 'store'])->name('offers.store')->middleware('auth');
Route::delete('/offers/{offer}', [OfferController::class, 'destroy'])->name('offers.destroy')->middleware('auth');
Route::get('/offers/{offer}/edit', [OfferController::class, 'edit'])->name('offers.edit')->middleware('auth');
Route::put('/offers/{offer}/update', [OfferController::class, 'update'])->name('offers.update')->middleware('auth');
Route::get('/offers/{offer}/product', [OfferController::class, 'product'])->name('offers.product')->middleware('auth');
Route::get('/offers/{offer}/add-products', [OfferController::class, 'addProductsForm'])->name('offers.addProductsForm')->middleware('auth');
Route::post('/offers/{offer}/add-products', [OfferController::class, 'addProducts'])->name('offers.addProducts')->middleware('auth');

Route::delete('/brands/destroy/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy')->middleware('auth');
Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create')->middleware('auth');
Route::post('/brands/store', [BrandController::class, 'store'])->name('brands.store')->middleware('auth');
Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit')->middleware('auth');
Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update')->middleware('auth');


Route::get('/products/index', [ProductController::class, 'index'])->name('products.index');
Route::post('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggleActive')->middleware('auth');
Route::post('/products/{product}/remove-offer', [ProductController::class, 'removeFromOffer'])->name('products.removeFromOffer')->middleware('auth');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create')->middleware('auth');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store')->middleware('auth');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('auth');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit')->middleware('auth');
Route::put('/products/update/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('auth');

Route::get('/products/show/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/sizes/create/{product}', [SizeController::class, 'create'])->name('sizes.create')->middleware('auth');
// aqui
Route::post('/sizes', [SizeController::class, 'store'])->name('sizes.store')->middleware('auth');
Route::get('/sizes/edit/{size}', [SizeController::class, 'edit'])->name('sizes.edit')->middleware('auth');
Route::put('/sizes/update/{size}', [SizeController::class, 'update'])->name('sizes.update')->middleware('auth');
Route::delete('/sizes/delete/{size}', [SizeController::class, 'destroy'])->name('sizes.destroy')->middleware('auth');

Route::get('/cuenta', [UserController::class, 'show'])->name('users.account')->middleware('auth');
Route::delete('/cuenta/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('auth');

Route::get('signup', [LoginController::class, 'signupForm'])->name('signupForm');
Route::post('signup', [LoginController::class, 'signup'])->name('signup');

Route::get('login', [LoginController::class, 'loginForm'])->name('loginForm');
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::get('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/cuenta/editar', [UserController::class, 'edit'])->name('users.edit')->middleware('auth');
Route::put('/cuenta/{user}', [UserController::class, 'update'])->name('users.update')->middleware('auth');

Route::get('/admin/users', [UserController::class, 'index'])->name('users.index')->middleware('auth');
//aqui
Route::put('/admin/users/{user}/role', [UserController::class, 'changeRole'])->name('users.changeRole')->middleware('auth');
Route::get('/admin/users', [UserController::class, 'adminIndex'])->name('users.index')->middleware('auth');
//aqui

Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->middleware('auth')->name('reviews.update');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->middleware('auth')->name('reviews.edit');
Route::patch('/reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('reviews.toggle')->middleware('auth');

Route::get('/cart', [CartController::class, 'show'])->name('carts.show')->middleware('auth');

Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store')->middleware('auth');
Route::get('/orders/index', [OrderController::class, 'index'])->middleware('auth')->name('orders.index');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->middleware('auth')->name('orders.cancel');

Route::get('/admin/orders', [OrderController::class, 'indexAdmin'])->name('orders.index_admin')->middleware('auth');
Route::post('/admin/orders/{order}/delivered', [OrderController::class, 'deliver'])->name('admin.orders.deliver')->middleware('auth');

