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

        $jawa_barat = DB::select("
            SELECT
                GROUP_CONCAT(DISTINCT CASE WHEN weather_code = 60 THEN location END) AS locations_60,
                GROUP_CONCAT(DISTINCT CASE WHEN weather_code = 61 THEN location END) AS locations_61,
                GROUP_CONCAT(DISTINCT CASE WHEN weather_code = 63 THEN location END) AS locations_63
            FROM api_barats
            WHERE weather_code IN (60, 61, 63)
            AND DATE(created_at) = CURDATE()");

        $jawa_tengah = DB::select("
            SELECT
                GROUP_CONCAT(DISTINCT CASE WHEN weather_code = 60 THEN location END) AS locations_60,
                GROUP_CONCAT(DISTINCT CASE WHEN weather_code = 61 THEN location END) AS locations_61,
                GROUP_CONCAT(DISTINCT CASE WHEN weather_code = 63 THEN location END) AS locations_63
            FROM api_tengahs
            WHERE weather_code IN (60, 61, 63)
            AND DATE(created_at) = CURDATE()");

        $message = "*INFO CUACA WS. CiTANDUY*\n";
        $message .= "Tgl: $todayFormat s.d. $futureDateFormat\n";
        $message .= "\n*Jawa Barat* :\n";

        if (!empty($jawa_barat[0]->locations_60)) {
            $locations_60 = explode(",", $jawa_barat[0]->locations_60);
            $count_60 = count($locations_60);
            $message .= "Hujan Ringan di " . $count_60 ." lokasi\n";
        }

        if (!empty($jawa_barat[0]->locations_61)) {
            $locations_61 = explode(",", $jawa_barat[0]->locations_61);
            $count_61 = count($locations_61);
            $message .= "Hujan Sedang di " . $count_61 ." lokasi\n";
        }

        if (!empty($jawa_barat[0]->locations_63)) {
            $locations_63 = explode(",", $jawa_barat[0]->locations_63);
            $count_63 = count($locations_63);
            $message .= "Hujan Lebat di " . $count_63 ." lokasi\n";
        }

        // Jika tidak ada data yang ditemukan di Jawa Barat, tambahkan pesan yang sesuai
        if (empty($jawa_barat[0]->locations_60) && empty($jawa_barat[0]->locations_61) && empty($jawa_barat[0]->locations_63)) {
            $message .= "Tidak ada data hujan untuk Jawa Barat hari ini.";
        }

        $message .= "\n*Jawa Tengah* :\n";

        if (!empty($jawa_tengah[0]->locations_60)) {
            $locations_60 = explode(",", $jawa_tengah[0]->locations_60);
            $count_60 = count($locations_60);
            $message .= "Hujan Ringan di " . $count_60 ." lokasi\n";
        }

        if (!empty($jawa_tengah[0]->locations_61)) {
            $locations_61 = explode(",", $jawa_tengah[0]->locations_61);
            $count_61 = count($locations_61);
            $message .= "Hujan Sedang di " . $count_61 ." lokasi\n";
        }

        if (!empty($jawa_tengah[0]->locations_63)) {
            $locations_63 = explode(",", $jawa_tengah[0]->locations_63);
            $count_63 = count($locations_63);
            $message .= "Hujan Lebat di " . $count_63 ." lokasi\n";
        }

        // Jika tidak ada data yang ditemukan di Jawa Tengah, tambahkan pesan yang sesuai
        if (empty($jawa_tengah[0]->locations_60) && empty($jawa_tengah[0]->locations_61) && empty($jawa_tengah[0]->locations_63)) {
            $message .= "Tidak ada data hujan untuk Jawa Tengah hari ini.";
        }

        $message .= "\n\nInfo lengkap: http://infocuaca.bbwscitanduy.id/dashboard/jabar\n";
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
        }
    }
}
