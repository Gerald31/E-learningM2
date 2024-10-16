<?php

use App\Http\Controllers\TestStripeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

############### Route de test ###################
Route::get('stripe-form', [TestStripeController::class, 'form'])->name('stripeForm');
Route::post('stripe-form/submit', [TestStripeController::class, 'submit'])->name('stripeSubmit');
Route::get('stripe-response/{id}', [TestStripeController::class, 'response'])->name('stripeResponse');
