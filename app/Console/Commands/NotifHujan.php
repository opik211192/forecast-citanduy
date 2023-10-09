<?php

namespace App\Console\Commands;

use App\Models\Hujan;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class NotifHujan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif-hujan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $phone = env('WABLAS_PHONE');
        $apiKey = env('WABLAS_API_KEY');
        $today = Carbon::now();
        $futureDate = $today->copy()->addDays(6);

        $todayFormat = $today->format('d/m/Y');
        $futureDateFormat = $futureDate->format('d/m/Y');

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
        
        $message = "*INFO CUACA WS. CiTANDUY*\n";
        $message .= "Tgl: $todayFormat s.d. $futureDateFormat\n";

    if ($jawaBarat->count_60 > 0 || $jawaBarat->count_61 > 0 || $jawaBarat->count_63 > 0) {
        $message .= "\n*Jawa Barat* :\n";
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
        $message.= "\n*Wilayah Jawa Barat Tidak Ada Hujan*";
    }

    if ($jawaTengah->count_60 > 0 || $jawaTengah->count_61 > 0 || $jawaTengah->count_63 > 0) {
        $message .= "*Jawa Tengah* :\n";
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
        $message .= "\n*Wilayah Jawa Tengah Tidak Ada Hujan*";
    }

        $message .= "\n\nInfo lengkap: http://infocuaca.bbwscitanduy.id/\n";
        $message .= "Sumber data: https://data.bmkg.go.id/csv/";


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

            // Menjadwalkan ulang perintah untuk dieksekusi dalam 30 menit (1800 detik)
            // $this->retry(1800);

            // // Menambahkan log ketika retry sudah berhasil
            // Log::channel('single')->info('Retry berhasil dilakukan.');
        }
    }
}
