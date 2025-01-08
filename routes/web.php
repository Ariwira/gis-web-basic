<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MapDataController;
use App\Http\Controllers\PetaCRUDController;





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
Route::get('/latihan1', function () {
    return view('mapUp');
});

Route::get('/tugas1', function () {
    return view('tugas1Up');
});

Route::get('/latihan2', function () {
    return view('interactiveUp');
});

Route::get('/tugas2', function () {
    return view('tugas2Up');
});


Route::get('/', function () {
    return view('dashboard');
});

Route::get('/api/markers', [MapDataController::class, 'getMarkers']);
Route::get('/api/polygons', [MapDataController::class, 'getPolygons']);
Route::post('/api/markers', [MapDataController::class, 'storeMarker']);
Route::post('/api/polygons', [MapDataController::class, 'storePolygon']);
Route::delete('/api/markers/{id}', [MapDataController::class, 'deleteMarker']);
Route::delete('/api/polygons/{id}', [MapDataController::class, 'deletePolygon']);


Route::get('/interactiveUp', [MapDataController::class, 'index'])->name('map.index');
Route::post('/markers', [MapDataController::class, 'storeMarker'])->name('map.storeMarker');
Route::post('/polygons', [MapDataController::class, 'storePolygon'])->name('map.storePolygon');
Route::get('/data', [MapDataController::class, 'getData'])->name('map.getData');

Route::get('/handson3', [PetaCRUDController::class, 'index'])->name('handson3.index');
Route::get('/listDataMarker', [PetaCRUDController::class, 'getListMarker'])->name('handson3.getListMarker');
Route::get('/listDataPolygon', [PetaCRUDController::class, 'getListPolygon'])->name('handson3.getListPolygon');
Route::post('/storeMarker', [PetaCRUDController::class, 'storeMarker'])->name('handson3.storeMarker');
Route::post('/storePolygon', [PetaCRUDController::class, 'storePolygon'])->name('handson3.storePolygon');

/*HANDS-ON 4 : */
Route::get('/handson4/viewmaps/{id}', [PetaCRUDController::class, 'viewmaps'])->name('handson4.viewmaps');
Route::get('/handson4/viewleaflet/{id}', [PetaCRUDController::class, 'viewleaflet'])->name('handson4.viewleaflet');
Route::get('/handson4/{id}/edit', [PetaCRUDController::class, 'edit'])->name('handson4.edit');
Route::put('/updateMarker', [PetaCRUDController::class, 'updateMarker'])->name('handson4.updateMarker');
