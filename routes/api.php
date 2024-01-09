<?php

use App\Http\Controllers\ApiLokasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('lokasi')->group(function(){
    Route::get('/', [ApiLokasiController::class, 'index'])->name('api.lokasi.index');
    Route::get('/location/{location}', [ApiLokasiController::class, 'getByLocation'])->name('api.lokasi.detail');
    Route::get('/jabar/count/{location}', [ApiLokasiController::class, 'weatherCountJabar'])->name('api.count.jabar');
    Route::get('/jateng/count/{location}', [ApiLokasiController::class, 'weatherCountJateng'])->name('api.count.jateng');


    Route::post('/jabar/cari', [ApiLokasiController::class, 'filterJabar'])->name('api.cari.jabar');
    Route::post('/jateng/cari', [ApiLokasiController::class, 'filterJateng'])->name('api.cari.jateng');

});