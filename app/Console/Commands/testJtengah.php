<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Hujan;
use App\Models\Lokasi;
use League\Csv\Reader;
use App\Models\ApiTengah;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class testJtengah extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test-jtengah';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ambil data jawa tengah';

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
            Log::channel('single')->info('test import data Jawa tengah.');
            
            $response = Http::retry($maxRetries, $retryDelay)
                        ->timeout(20)
                        ->get("https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/CSV/kecamatanforecast-jawatengah.csv");

            if ($response->failed()) {
                throw new Exception('Gagal mengambil file CSV.');
            }

              $lastModifiedBMKG = Carbon::parse($response->header('last-modified'))->setTimezone('Asia/Jakarta')->toDateString();

             $csvContent = $response->body();

                //$tempFile = tempnam(sys_get_temp_dir(), 'csv');
                $tempFile = storage_path('app/temp') . '/' . uniqid('csv_', true) . '.csv';
                file_put_contents($tempFile, $csvContent);

                $csv = Reader::createFromPath($tempFile, 'r');
                $csv->setDelimiter(';');

                $data = $csv->getRecords();
                
                //$dataJawaBarat = Jbarat::pluck('location')->toArray();
                $dataJawaTengah = Lokasi::where('provinsi', 'Jawa Tengah')->pluck('location')->toArray();


                $hasValidWeatherData = false;
                $hasLoggedHujan = false; // Variabel untuk melacak apakah log sudah ditampilkan

                foreach ($data as $record) {
                    $location = $record[0];
                    $timestamp = $record[1];
                    $weateher_code = $record[8];

                    if (in_array('X', $record)) {
                        continue;
                    }

                    if (in_array($location, $dataJawaTengah)) {
                        $jtengahData = new ApiTengah([
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
                            'provinsi' => 'Jawa Tengah',
                            'last_modified' => $lastModifiedBMKG,
                        ]);
            
                        $jtengahData->save();

                        if (in_array($weateher_code, [60,61,63])) {
                            $weateher_code_Result = new Hujan([
                                'location' => $location,
                                'timestamp' => $timestamp,
                                'weather_code' => $weateher_code,
                                'provinsi' => 'Jawa Tengah',
                            ]);

                            $weateher_code_Result->save();

                            if (!$hasLoggedHujan) {
                                Log::channel('single')->info("Data Hujan Terdeteksi di Wilayah Jawa Tengah");
                                $hasLoggedHujan = true; // Set variabel untuk menandakan log sudah ditampilkan
                            }

                            $hasValidWeatherData = true;
                        }
                    }


                }

                if (!$hasValidWeatherData) {
                    Log::channel('single')->info("Tidak Ada Data Hujan Wilayah Jawa Tengah");
                }


                // Hapus file temporer setelah selesai
                unlink($tempFile);

                Log::channel('single')->info('Proses Data impor Jawa Tengah berhasil.');
        }catch(\Exception $e){
            Log::error("error test jawa tengah");
        }
    }
}
