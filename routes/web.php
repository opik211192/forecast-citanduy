<?php

use App\Models\Hujan;
use App\Models\Lokasi;
use App\Models\ApiBarat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\HujanController;
use App\Http\Controllers\ApiBaratController;
use App\Http\Controllers\ApiTengahController;
use App\Http\Controllers\DashboardBaratController;
use App\Http\Controllers\DashboardTengahController;

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

Route::get('/map', [MapController::class, 'index'])->name('map.index');
Route::get('/data/jabar', [MapController::class, 'mapJabar'])->name('map.jabar');
Route::get('/data/jateng', [MapController::class, 'mapJateng'])->name('map.jateng');


Route::get('/coba-lagi', function(){
    // $coba = ApiBarat::with(['jBarat'])->whereDate('created_at', '2023-10-12')->get();
    // return response()->json($coba);
    // $lokasi = Lokasi::all();
    // $apiBarats = ApiBarat::whereIn('location', $lokasi->pluck('location'))->whereDate('created_at', '2023-10-12')->get();
    // $lastUpdate = ApiBarat::orderBy('updated_at', 'desc')->first();
    // dd($lastUpdate);
     $response = Http::get("https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/CSV/kecamatanforecast-jawatengah.csv");

    //dd($response);
    //dd($response->header('last-modified'));
    $lastModifiedBMKG = Carbon::parse($response->header('last-modified'))->setTimezone('Asia/Jakarta')->toDateString();
    //$lastModifiedBMKG = '2024-01-12';


    $lastModifiedDatabase = Carbon::parse(ApiBarat::max('last_modified'))->toDateString();

    //dd($lastModifiedBMKG);
    //dd($lastModifiedDatabase);
    if ($lastModifiedDatabase >= $lastModifiedBMKG) {
        echo "tidak disimpan";
    }else {
        echo "simpan data baru";
        //maka simpan data di database dan update view di halaman cuaca nya
    }
    
});

Route::get('/jabar', [ApiBaratController::class, 'index'])->name('jabar.index');
Route::get('/jabar-data', [ApiBaratController::class, 'showData'])->name('jabar-data');

Route::get('/jateng', [ApiTengahController::class, 'index'])->name('jateng.index');
Route::get('/jateng-data', [ApiTengahController::class, 'showData'])->name('jateng-data');

Route::get('log-viewers', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/coba', [CobaController::class, 'index'])->name('coba-data');

Route::get('/dashboard/jabar', [DashboardBaratController::class, 'index'])->name('dashboard-jabar');
Route::get('/dashboard/jabar/cari', [DashboardBaratController::class, 'filter'])->name('jabar.filter');

Route::get('/dashboard/jateng', [DashboardTengahController::class, 'index'])->name('dashboard-jateng');
Route::get('/dashboard/jateng/cari', [DashboardTengahController::class, 'filter'])->name('jateng.filter');


Route::get('/hujan/jabar', [HujanController::class,'hujanJabar'])->name('hujan.jabar');
Route::get('/hujan/jabar/cari', [HujanController::class, 'filterJabar'])->name('hujan.filterJabar');

Route::get('/hujan/jateng', [HujanController::class,'hujanJateng'])->name('hujan.jateng');
Route::get('/hujan/jateng/cari', [HujanController::class, 'filterJateng'])->name('hujan.filterJateng');


Route::get('/data-hujan', [HujanController::class, 'showData'])->name('hujan-data');



