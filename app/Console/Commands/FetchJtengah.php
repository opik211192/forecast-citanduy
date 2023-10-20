<?php

namespace App\Console\Commands;

use App\Models\Hujan;
use App\Models\Lokasi;
use League\Csv\Reader;
use App\Models\Jtengah;
use App\Models\ApiTengah;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class FetchJtengah extends Command
{
    protected $signature = 'fetch-jtengah';

    protected $description = 'Penarikan data cuaca Jawa Tengah';

    public function handle()
    {
        $maxRetries = 3; // Jumlah maksimum percobaan
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            try {
                $this->doImport(); // Fungsi yang melakukan impor data

                Log::channel('single')->info('Proses import data Jawa Tengah berhasil.');
                break; // Keluar dari loop jika berhasil
            } catch (\Exception $e) {
                $retryCount++;
                Log::channel('single')->error('Proses import data Jawa Tengah error: ' . $e->getMessage());

                if ($retryCount < $maxRetries) {
                    //$delay = pow(2, $retryCount); // Exponential backoff
                    $delay = 120;
                    Log::channel('single')->info("Menjadwalkan ulang eksekusi dalam {$delay} detik...");
                    sleep($delay);

                    // Jalankan kembali perintah
                    Artisan::call('fetch-jtengah');
                } else {
                    Log::channel('single')->error('Percobaan maksimum telah dicapai. Menghentikan eksekusi.');
                }
            }
        }
    }

    protected function doImport()
    {
         try {
            
            $csvFile =  "https://data.bmkg.go.id/DataMKG/MEWS/DigitalForecast/CSV/kecamatanforecast-jawatengah.csv";
            Log::channel('single')->info('Memulai proses import data Jawa Tengah.');
            $csvContent = file_get_contents($csvFile);
            
            //$tempFile = tempnam(sys_get_temp_dir(), 'csv');
            $tempFile = storage_path('app/temp') . '/' . uniqid('csv_', true) . '.csv';
            file_put_contents($tempFile, $csvContent);
            
            $csv = Reader::createFromPath($tempFile, 'r');
            $csv->setDelimiter(';');
            
            $data = $csv->getRecords();
            
            //$dataJawaTengah = Jtengah::pluck('location')->toArray();
            $dataJawaTengah = Lokasi::where('provinsi', 'Jawa Tengah')->pluck('location')->toArray();

            
            $hasValidWeatherData = false;
            $hasLoggedHujan = false;
            
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

        } catch (\Exception $e) {
            // Tangani error dan log pesan error
            Log::channel('single')->error('Proses impor data Jawa Tengah error: ' . $e->getMessage());

            // Menjadwalkan ulang perintah untuk dieksekusi dalam 30 menit (1800 detik)
            //$this->retry(1800);

            // Menambahkan log ketika retry sudah berhasil
            Log::channel('single')->info('Retry berhasil dilakukan.');
        }
    }
}
