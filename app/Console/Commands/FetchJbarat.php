<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Hujan;
use App\Models\Lokasi;
use League\Csv\Reader;
use App\Models\ApiBarat;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FetchJbarat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-jbarat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'penarikan data cuaca jawa barat';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $maxRetries = 3; // Jumlah maksimum percobaan
        $retryDelay = 60; // Waktu penundaan antara percobaan (dalam detik)

        try {
            Log::channel('single')->info('Memulai proses import data Jawa Barat.');
            
            $response = Http::retry($maxRetries, $retryDelay)
                        ->timeout(20)
                        ->get("https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/CSV/kecamatanforecast-jawabarat.csv");

            if ($response->failed()) {
                throw new Exception('Gagal mengambil file CSV.');
            }

            $lastModifiedBMKG = Carbon::parse($response->header('last-modified'))->setTimezone('Asia/Jakarta')->toDateString();
            //$lastModifiedDatabase = Carbon::parse(ApiBarat::max('last_modified'))->toDateString();
            $lastModifiedDatabase = ApiBarat::max('last_modified');

            if (is_null($lastModifiedDatabase) || $lastModifiedDatabase < $lastModifiedBMKG) {
            // if (true) {
                $csvContent = $response->body();

                //$tempFile = tempnam(sys_get_temp_dir(), 'csv');
                $tempFile = storage_path('app/temp') . '/' . uniqid('csv_', true) . '.csv';
                file_put_contents($tempFile, $csvContent);

                $csv = Reader::createFromPath($tempFile, 'r');
                $csv->setDelimiter(';');

                $data = $csv->getRecords();
                
                //$dataJawaBarat = Jbarat::pluck('location')->toArray();
                $dataJawaBarat = Lokasi::where('provinsi', 'Jawa Barat')->pluck('location')->toArray();


                $hasValidWeatherData = false;
                $hasLoggedHujan = false; // Variabel untuk melacak apakah log sudah ditampilkan

                foreach ($data as $record) {
                    $location = $record[0];
                    $timestamp = $record[1];
                    $weateher_code = $record[8];

                    if (in_array('X', $record)) {
                        continue;
                    }

                    if (in_array($location, $dataJawaBarat)) {
                        $jbaratData = new ApiBarat([
                            'location' => $location,
                            'timestamp' => $record[1],
                            'temp_min' => $record[2] ?: null,
                            'temp_max' => $record[3] ?: null,
                            'humidity_min' => $record[4] ?: null,
                            'humidity_max' => $record[5] ?: null,
                            'humidity' => $record[6] ?: null,
                            'temperature' => $record[7],
                            'weather_code' => $record[8],
                            'wind_direction' => $record[9],
                            'wind_speed' => $record[10],
                            'provinsi' => 'Jawa Barat',
                            'last_modified' => $lastModifiedBMKG
                        ]);
            
                        $jbaratData->save();

                        //jika weather_code =  4, simpan ke tabel proses_barat
                        if (in_array($weateher_code, [60,61,63])) {
                            $weateher_code_Result = new Hujan([
                                'location' => $location,
                                'timestamp' => $timestamp,
                                'weather_code' => $weateher_code,
                                'provinsi' => 'Jawa Barat',
                            ]);

                        //  if ($weateher_code == 3) {
                        //     $weateher_code_Result = new Hujan([
                        //         'location' => $location,
                        //         'timestamp' => $timestamp,
                        //         'weather_code' => $weateher_code,
                        //         'provinsi' => 'Jawa Barat',
                        //     ]);
                            
                            $weateher_code_Result->save();

                            if (!$hasLoggedHujan) {
                                Log::channel('single')->info("Data Hujan Terdeteksi Wilayah Jawa Barat");
                                $hasLoggedHujan = true; // Set variabel untuk menandakan log sudah ditampilkan
                            }

                            $hasValidWeatherData = true;
                        }
                    }

                }

                if (!$hasValidWeatherData) {
                    Log::channel('single')->info("Tidak Ada Data Hujan Wilayah Jawa Barat");
                }


                // Hapus file temporer setelah selesai
                unlink($tempFile);

                Log::channel('single')->info('Proses Data impor Jawa Barat berhasil.');
           }else{
                Log::channel('single')->warning('Data Jawa Barat di BMKG belum diperbaharui');
                return;
           }
        } catch (\Exception $e) {
            // Tangani error dan log pesan error
            Log::channel('single')->error('Proses impor data Jawa Barat error: ' . $e->getMessage());

            Log::channel('single')->error('Maksimum percobaan telah tercapai. Tidak dapat mengimpor data.');
             // Menjadwalkan ulang perintah untuk dieksekusi dalam 30 menit (1800 detik)
            //$this->retry(1800);

            // Menambahkan log ketika retry sudah berhasil
            //Log::channel('single')->info('Retry berhasil dilakukan.');
            //coba lakukan notif wa jika data gagal di tarik dari BMKG
            // $apiKey = '3iueLF2v895BJJcAFiUTXb7qard7Av0PaVNWKGxqGYTLAaq98kvlk8SIunpdpgGS';
            // $response = Http::timeout(120)
            //     ->retry(3, 5000)
            //     ->get("https://jogja.wablas.com/api/send-message", [
            //             'phone' => '6281223171795',
            //             'message' => "Gagal Narik Data Jawa Barat",
            //             'token' => $apiKey,
            //             'isGroup' => 'false',
            // ]);

            //watzap api
            $apiKey = 'RTUFBIWTSPVDXQHV';
            $number_key = 'vebd5mseyZvRUEyx';
            $phone_no = '6281223171795';

             $response = Http::timeout(120)
            ->retry(3, 5000)
            ->post("https://api.watzap.id/v1/send_message", [
                    'api_key' => $apiKey,
                    'number_key' => $number_key,
                    'phone_no' => $phone_no,
                    'message' => 'Gagal Narik Data Jawa Barat',
                ]);
        }
    }
}
