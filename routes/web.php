<?php

use App\Models\Hujan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\ApiBaratController;
use App\Http\Controllers\ApiTengahController;
use App\Http\Controllers\DashboardBaratController;
use App\Http\Controllers\DashboardTengahController;
use App\Http\Controllers\HujanController;

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
    //return view('welcome');
//     $today = Carbon::now()->format('Y-m-d');
//     $dataHujan = Hujan::select(
//     'location',
//     'provinsi',
//     DB::raw('MIN(timestamp) AS min_timestamp'),
//     DB::raw('MAX(timestamp) AS max_timestamp'),
//     DB::raw('SUM(CASE WHEN weather_code = 60 THEN 1 ELSE 0 END) AS count_60'),
//     DB::raw('SUM(CASE WHEN weather_code = 61 THEN 1 ELSE 0 END) AS count_61'),
//     DB::raw('SUM(CASE WHEN weather_code = 63 THEN 1 ELSE 0 END) AS count_63')
// )
//     ->whereIn('weather_code', [60, 61, 63])
//     ->groupBy('location', 'provinsi')
//     ->get();

//     dd($dataHujan);
        return redirect()->route('dashboard-jabar');
});

Route::get('/jabar', [ApiBaratController::class, 'index'])->name('jabar.index');
Route::get('/jabar-data', [ApiBaratController::class, 'showData'])->name('jabar-data');

Route::get('/jateng', [ApiTengahController::class, 'index'])->name('jateng.index');
Route::get('/jateng-data', [ApiTengahController::class, 'showData'])->name('jateng-data');

Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/coba', [CobaController::class, 'index'])->name('coba-data');

Route::get('/dashboard/jabar', [DashboardBaratController::class, 'index'])->name('dashboard-jabar');
Route::get('/dashboard/jateng', [DashboardTengahController::class, 'index'])->name('dashboard-jateng');

Route::get('/hujan', [HujanController::class,'index'])->name('hujan.index');
Route::get('/data-hujan', [HujanController::class, 'showData'])->name('hujan-data');



