<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/map', [MapController::class, 'index']);

Route::get('/tugas1', function () {
    return view('tugas1');
});

Route::get('/', function () {
    return view('welcome');
});
