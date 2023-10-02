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
        $phone = env('WABLAS_PHONE');
        $apiKey = env('WABLAS_API_KEY');
        $today = Carbon::now()->format('d/m/Y');
        //dd($today);

        $jawaBarat = DB::table('api_barats')
        ->whereIn('weather_code', [60, 61, 63])
        ->whereDay('created_at', now()->day)
        ->select(
            DB::raw('SUM(CASE WHEN weather_code = 60 THEN 1 ELSE 0 END) AS count_60'),
            DB::raw('SUM(CASE WHEN weather_code = 61 THEN 1 ELSE 0 END) AS count_61'),
            DB::raw('SUM(CASE WHEN weather_code = 63 THEN 1 ELSE 0 END) AS count_63')
        )
        ->first();

        $jawaTengah = DB::table('api_tengahs')
        ->whereIn('weather_code', [60, 61, 63])
        ->whereDay('created_at', now()->day)
        ->select(
            DB::raw('SUM(CASE WHEN weather_code = 60 THEN 1 ELSE 0 END) AS count_60'),
            DB::raw('SUM(CASE WHEN weather_code = 61 THEN 1 ELSE 0 END) AS count_61'),
            DB::raw('SUM(CASE WHEN weather_code = 63 THEN 1 ELSE 0 END) AS count_63')
        )
        ->first();
        
        $message = "Data Hujan di WS. Citanduy\n";
        $message .= "Tgl: $today";

    if ($jawaBarat->count_60 > 0 || $jawaBarat->count_61 > 0 || $jawaBarat->count_63 > 0) {
        $message .= "\nJawa Barat :\n";
        if ($jawaBarat->count_60 > 0) {
            $message .= "Hujan Ringan di {$jawaBarat->count_60} lokasi\n";
        }
        if ($jawaBarat->count_61 > 0) {
            $message .= "Hujan Sedang di {$jawaBarat->count_61} Lokasi\n";
        }
        if ($jawaBarat->count_63 > 0) {
            $message .= "Hujan Lebat di {$jawaBarat->count_63} Lokasi\n";
        }
    }else{
        $message.= "\nWilayah Jawa Barat Tidak Ada Hujan";
    }

    if ($jawaTengah->count_60 > 0 || $jawaTengah->count_61 > 0 || $jawaTengah->count_63 > 0) {
        $message .= "Jawa Tengah :\n";
        if ($jawaTengah->count_60 > 0) {
            $message .= "Hujan Ringan di {$jawaTengah->count_60} lokasi\n";
        }
        if ($jawaTengah->count_61 > 0) {
            $message .= "Hujan Sedang di {$jawaTengah->count_61} Lokasi\n";
        }
        if ($jawaTengah->count_63 > 0) {
            $message .= "Hujan Lebat di {$jawaTengah->count_63} Lokasi\n";
        }
    }else{
        $message .= "\nWilayah Jawa Tengah Tidak Ada Hujan";
    }

    $message .= "\n\nInfo lengkap silahkan kunjungi: https://www.google.com";

    if (empty($message)) {
        $message = "Tidak ada hujan.";
    }


        $response = Http::get("https://jogja.wablas.com/api/send-message", [
            'phone' => $phone,
            'message' => $message,
            'token' => $apiKey,
            'isGroup' => 'true',
        ]);


        if ($response->successful()) {
            Log::channel('single')->info("Notifikasi Ke Whatsapp Grup Terkirim");
        }else {
            Log::channel('errorlog')->info("Gagal mengirim notifikasi ke Whatsapp Grup");
        }
    }
}
