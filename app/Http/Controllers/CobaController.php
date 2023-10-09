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
        $weatherCodes = [60, 61, 63];
        $yesterday = Carbon::yesterday();

        $result = ApiBarat::whereIn('weather_code', $weatherCodes)
        ->whereDay('created_at',  $yesterday)
        ->select('kecamatan')
        ->distinct()
        ->count();

        $today = Carbon::now();
        $futureDate = $today->copy()->addDays(6);

        $todayFormat = $today->format('d/m/Y');
        $futureDateFormat = $futureDate->format('d/m/Y');

        dd($todayFormat);
    }
}
