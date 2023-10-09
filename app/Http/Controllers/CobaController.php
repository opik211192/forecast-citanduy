<?php

namespace App\Http\Controllers;

use App\Models\ApiBarat;
use App\Models\ApiTengah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class CobaController extends Controller
{
    public function index(Request $request)
    {
        $yesterday = Carbon::yesterday();

        $jawaBaratCount = DB::table('api_barats as a')
            ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
            ->whereDate('a.created_at', '=', $yesterday)
            ->whereIn('a.weather_code', [60, 61, 63])
            ->distinct()
            ->count(DB::raw('CONCAT(a.location, b.kecamatan)')); // Menggunakan CONCAT untuk membuat nilai unik

        $jawaTengahCount = DB::table('api_tengahs as a')
            ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
            ->whereDate('a.created_at', '=', $yesterday)
            ->whereIn('a.weather_code', [60, 61, 63])
            ->distinct()
            ->count(DB::raw('CONCAT(a.location, b.kecamatan)'));


        dd($jawaBaratCount);
    }
}
