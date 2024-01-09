<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\ApiBarat;
use App\Models\ApiTengah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiLokasiController extends Controller
{
    public function index()
    {
       try {
            $now = Carbon::now();
            $today = $now->format('Y-m-d');
            $yesterday = Carbon::now()->subDay()->format('Y-m-d');

            // Mendapatkan jam saat ini dan membulatkannya ke jam terdekat yang merupakan kelipatan 3
            $currentHour = $now->hour;
            $currentMinute = $now->minute;
            $roundedHour = floor($currentHour / 3) * 3;

            if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
                $apiBarat = ApiBarat::with(['jawa_barat', 'weather'])
                    ->whereDate('created_at', $yesterday)
                    ->whereDate('timestamp', $today)
                    ->where('timestamp', '=', $today . ' ' . sprintf('%02d', $roundedHour) . ':00:00')
                    ->get();
                
                $apiTengah = ApiTengah::with(['jawa_tengah', 'weather'])
                    ->whereDate('created_at', $yesterday)
                    ->whereDate('timestamp', $today)
                    ->where('timestamp', '=', $today . ' ' . sprintf('%02d', $roundedHour) . ':00:00')
                    ->get();

            }else{                
                $apiBarat = ApiBarat::with(['jawa_barat', 'weather'])
                        ->whereDate('created_at', $today)
                        ->whereDate('timestamp', $today)
                        ->where('timestamp', '=', $today . ' ' . sprintf('%02d', $roundedHour) . ':00:00')
                        ->get();

                $apiTengah = ApiTengah::with(['jawa_tengah', 'weather'])
                        ->whereDate('created_at', $today)
                        ->whereDate('timestamp', $today)
                        ->where('timestamp', '=', $today . ' ' . sprintf('%02d', $roundedHour) . ':00:00')
                        ->get();
            }
            

            //Menentukan ikon cuaca berdasarkan kode cuaca
            $weatherIcons = [
                '0' => ['icon' => '100_cerah.svg', 'title' => 'Cerah'],
                '100' => ['icon' => '100_cerah.svg', 'title' => 'Cerah'],
                '1' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
                '101' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
                '2' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
                '102' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
                '3' => ['icon' => '103_berawan.svg', 'title' => 'Berawan'],
                '103' => ['icon' => '103_berawan.svg', 'title' => 'Berawan'],
                '4' => ['icon' => '104_berawan_tebal.svg', 'title' => 'Berawan Tebal'],
                '104' => ['icon' => '104_berawan_tebal.svg', 'title' => 'Berawan Tebal'],
                '5' => ['icon' => '5_udara_kabur.svg', 'title' => 'Udara Kabur'],
                '10' => ['icon' => '10_asap.svg', 'title' => 'Asap'],
                '45' => ['icon' => '45_kabut.svg', 'title' => 'Kabut'],
                '60' => ['icon' => '60_hujan_ringan.svg', 'title' => 'Hujan Ringan'],
                '61' => ['icon' => '61_hujan.svg', 'title' => 'Hujan Sedang'],
                '63' => ['icon' => '63_hujan_lebat.svg', 'title' => 'Hujan Lebat'],
                '95' => ['icon' => '95_97_hujan_petir.svg', 'title' => 'Hujan Petir'],
                '97' => ['icon' => '95_97_hujan_petir.svg', 'title' => 'Hujan Petir'],
            ];

            $markers = [];

            // Tambahkan data ApiBarat ke array jika ada
            if ($apiBarat->isNotEmpty()) {
                foreach ($apiBarat as $data) {
                    $location = $data->location;
                    $temperature = $data->temperature;
                    $humidity = $data->humidity;
                    $wind_speed = $data->wind_speed;
                    $wind_direction = $data->wind_direction;
                    $weatherCode = $data->weather_code;
                    $weatherIcon = $weatherIcons[$weatherCode]['icon'];
                    $provinsi = $data->jawa_barat->provinsi;
                    $kabupaten = $data->jawa_barat->kabupaten;
                    $kecamatan = $data->jawa_barat->kecamatan;
                    $latitude = $data->jawa_barat->latitude;
                    $longitude = $data->jawa_barat->longitude;
                    $weather_name = $data->weather->name;

                    $markers[] = [
                            'location' => $location,
                            'temperature' => $temperature,
                            'humidity' => $humidity,
                            'wind_speed' => $wind_speed,
                            'wind_direction' => $wind_direction,
                            'weather_code' => $weatherCode,
                            'weather_icon' => $weatherIcon,
                            'provinsi' => $provinsi,
                            'kabupaten' => $kabupaten,
                            'kecamatan' => $kecamatan,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'weather_name' => $weather_name,
                    ];
                }
            }

            // Tambahkan data ApiTengah ke array jika ada
            if ($apiTengah->isNotEmpty()) {
                foreach ($apiTengah as $data) {
                    $location = $data->location;
                    $temperature = $data->temperature;
                    $humidity = $data->humidity;
                    $wind_speed = $data->wind_speed;
                    $wind_direction = $data->wind_direction;
                    $weatherCode = $data->weather_code;
                    $weatherIcon = $weatherIcons[$weatherCode]['icon'];
                    $provinsi = $data->jawa_tengah->provinsi;
                    $kabupaten = $data->jawa_tengah->kabupaten;
                    $kecamatan = $data->jawa_tengah->kecamatan;
                    $latitude = $data->jawa_tengah->latitude;
                    $longitude = $data->jawa_tengah->longitude;
                    $weather_name = $data->weather->name;

                    // Tambahkan data ApiTengah ke dalam array yang sudah ada
                    $markers[] = [
                            'location' => $location,
                            'temperature' => $temperature,
                            'humidity' => $humidity,
                            'wind_speed' => $wind_speed,
                            'wind_direction' => $wind_direction,
                            'weather_code' => $weatherCode,
                            'weather_icon' => $weatherIcon,
                            'provinsi' => $provinsi,
                            'kabupaten' => $kabupaten,
                            'kecamatan' => $kecamatan,
                            'latitude' => $latitude,
                            'longitude' => $longitude,
                            'weather_name' => $weather_name,

                    ];
                }
            }

            // Kembalikan array data marker
            return response()->json([
                'status' => 200,
                'data' => $markers
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return ['error' => 'Terjadi kesalahan. Silakan ulangi halaman ini.'];
        }
        
    }

   
    //untuk menampilkan di drawer
    public function getByLocation($location)
    {
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');
        $groupedData = [];
        $uniqueDates = [];
        $hoursInDay = range(0, 21, 3);

        $weatherIcons = [
            '0' => ['icon' => '100_cerah.svg', 'title' => 'Cerah'],
            '100' => ['icon' => '100_cerah.svg', 'title' => 'Cerah'],
            '1' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '101' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '2' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '102' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '3' => ['icon' => '103_berawan.svg', 'title' => 'Berawan'],
            '103' => ['icon' => '103_berawan.svg', 'title' => 'Berawan'],
            '4' => ['icon' => '104_berawan_tebal.svg', 'title' => 'Berawan Tebal'],
            '104' => ['icon' => '104_berawan_tebal.svg', 'title' => 'Berawan Tebal'],
            '5' => ['icon' => '5_udara_kabur.svg', 'title' => 'Udara Kabur'],
            '10' => ['icon' => '10_asap.svg', 'title' => 'Asap'],
            '45' => ['icon' => '45_kabut.svg', 'title' => 'Kabut'],
            '60' => ['icon' => '60_hujan_ringan.svg', 'title' => 'Hujan Ringan'],
            '61' => ['icon' => '61_hujan.svg', 'title' => 'Hujan Sedang'],
            '63' => ['icon' => '63_hujan_lebat.svg', 'title' => 'Hujan Lebat'],
            '95' => ['icon' => '95_97_hujan_petir.svg', 'title' => 'Hujan Petir'],
            '97' => ['icon' => '95_97_hujan_petir.svg', 'title' => 'Hujan Petir'],
        ];
        
        
        
        // Mencari data di ApiBarat
        $apiBarat = ApiBarat::with(['jawa_barat', 'weather'])
        ->whereDate('created_at', $today)
        ->where('location', $location)
        ->get();
        
        // Jika data kosong, ambil data dengan timestamp sebelumnya
        if ($apiBarat->isEmpty()) {
            $apiBarat = ApiBarat::with(['jawa_barat', 'weather'])
                ->whereDate('created_at', $yesterday)
                ->where('location', $location)
                ->get();
        }

        // Jika data ditemukan di ApiBarat, kembalikan respons dengan data tersebut
        if (!$apiBarat->isEmpty()) {
            $lokasi = Lokasi::where('location', $location)->get();

            foreach ($apiBarat as $item) {
                $date = Carbon::parse($item->timestamp); // Menggunakan Carbon untuk tanggal
                $hour = Carbon::parse($item->timestamp)->hour; // Menggunakan Carbon untuk jam
                $weatherCode = $item->weather->weather_code;
                $weatherIcon = $weatherIcons[$weatherCode] ?? '';
                $provinsi = $item->jawa_barat->provinsi;
                $kabupaten = $item->jawa_barat->kabupaten;
                $kecamatan = $item->jawa_barat->kecamatan;
                $latitude = $item->jawa_barat->latitude;
                $longitude = $item->jawa_barat->longitude;

                $humidity = $item->humidity;
                $temperature =  $item->temperature;
                $wind_direction = $item->wind_direction;
                $wind_speed = $item->wind_speed;

                if (!in_array($date->format('d M Y'), $uniqueDates)) {
                    $uniqueDates[] = $date->format('d M Y');
                }

                if (!isset($groupedData[$location])) {
                    $groupedData[$location] = [];
                }

                if (!isset($groupedData[$location][$date->format('d M Y')])) {
                    $groupedData[$location][$date->format('d M Y')] = [];
                }


                $groupedData[$location][$date->format('d M Y')][$hour] = [
                    'humidity' => $humidity,
                    'temperature' => $temperature,
                    'wind_direction' => $wind_direction,
                    'wind_speed' => $wind_speed,
                    'weather_code' => $weatherCode,
                    'weather_icon' => $weatherIcon,
                    'provinsi' => $provinsi,
                    'kabupaten' => $kabupaten,
                    'kecamatan' => $kecamatan,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'current_hour' => date('H'),
                ];
            }

            return response()->json([
                'groupedData' => $groupedData,
                'hoursInDay' => $hoursInDay,
                'uniqueDates' => $uniqueDates,
                'lokasi' => $lokasi
            ]);

        } else {
            // Jika tidak ditemukan di ApiBarat, coba cari di ApiTengah
            $apiTengah = ApiTengah::with(['jawa_tengah', 'weather'])
                ->whereDate('created_at', $today)
                ->where('location', $location)
                ->get();

             // Jika data kosong, ambil data dengan timestamp sebelumnya
            if ($apiTengah->isEmpty()) {
                $apiTengah = ApiTengah::with(['jawa_tengah', 'weather'])
                    ->whereDate('created_at', $yesterday)
                    ->where('location', $location)
                    ->get();
            }

            // Jika data ditemukan di ApiTengah, kembalikan respons dengan data tersebut
            if (!$apiTengah->isEmpty()) {
                $lokasi = Lokasi::where('location', $location)->get();
                
                foreach ($apiTengah as $item) {
                    $date = Carbon::parse($item->timestamp); // Menggunakan Carbon untuk tanggal
                    $hour = Carbon::parse($item->timestamp)->hour; // Menggunakan Carbon untuk jam
                    $weatherCode = $item->weather->weather_code;
                    $weatherIcon = $weatherIcons[$weatherCode] ?? '';
                    $provinsi = $item->jawa_tengah->provinsi;
                    $kabupaten = $item->jawa_tengah->kabupaten;
                    $kecamatan = $item->jawa_tengah->kecamatan;
                    $latitude = $item->jawa_tengah->latitude;
                    $longitude = $item->jawa_tengah->longitude;

                    $humidity = $item->humidity;
                    $temperature =  $item->temperature;
                    $wind_direction = $item->wind_direction;
                    $wind_speed = $item->wind_speed;


                    if (!in_array($date->format('d M Y'), $uniqueDates)) {
                        $uniqueDates[] = $date->format('d M Y');
                    }

                    if (!isset($groupedData[$location])) {
                        $groupedData[$location] = [];
                    }

                    if (!isset($groupedData[$location][$date->format('d M Y')])) {
                        $groupedData[$location][$date->format('d M Y')] = [];
                    }

                    $groupedData[$location][$date->format('d M Y')][$hour] = [
                        'humidity' => $humidity,
                        'temperature' => $temperature,
                        'wind_direction' => $wind_direction,
                        'wind_speed' => $wind_speed,
                        'weather_code' => $weatherCode,
                        'weather_icon' => $weatherIcon,
                        'provinsi' => $provinsi,
                        'kabupaten' => $kabupaten,
                        'kecamatan' => $kecamatan,
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'current_hour' => date('H'),
                    ];
                }

                return response()->json([
                    'groupedData' => $groupedData,
                    'hoursInDay' => $hoursInDay,
                    'uniqueDates' => $uniqueDates,
                    'lokasi' => $lokasi

                ]);
            }else {
                // Jika tidak ditemukan di ApiTengah juga, berikan respons JSON dengan status 404
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
        }
    }

    //untuk filter per kabupaten
    public function filterJabar(Request $request)
    {
        // Dapatkan nilai kabupaten dari permintaan
        $kabupaten = $request->input('kabupaten');
        $currentHour = Carbon::now()->hour;
        $currentMinute = Carbon::now()->minute;

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
            $dataToday = ApiBarat::with(['jawa_barat', 'weather'])
                ->whereDate('created_at', $yesterday);
        }else {
            $dataToday = ApiBarat::with(['jawa_barat', 'weather'])
                ->whereDate('created_at', $today);
        }

        if (!empty($kabupaten)) {
             if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
                 $dataToday = ApiBarat::with(['jawa_barat', 'weather'])
                ->leftJoin('lokasis as b', 'api_barats.location', '=', 'b.location')
                ->whereDate('api_barats.created_at', $yesterday)
                ->where('b.kabupaten', $kabupaten);

             }else{
                $dataToday = ApiBarat::with(['jawa_barat', 'weather'])
                ->leftJoin('lokasis as b', 'api_barats.location', '=', 'b.location')
                ->whereDate('api_barats.created_at', $today)
                ->where('b.kabupaten', $kabupaten);
             }
            
        }

        $dataToday = $dataToday->get();

       // Query untuk menghitung jumlah lokasi berdasarkan kondisi cuaca dan kabupaten
        $weatherCounts = ApiBarat::select(
            'b.kabupaten',
            DB::raw('SUM(CASE WHEN weather_code = 0 THEN 1 ELSE 0 END) AS locations_0'),
            DB::raw('SUM(CASE WHEN weather_code = 1 THEN 1 ELSE 0 END) AS locations_1'),
            DB::raw('SUM(CASE WHEN weather_code = 3 THEN 1 ELSE 0 END) AS locations_3'),
            DB::raw('SUM(CASE WHEN weather_code = 4 THEN 1 ELSE 0 END) AS locations_4'),
            DB::raw('SUM(CASE WHEN weather_code = 5 THEN 1 ELSE 0 END) AS locations_5'),
            DB::raw('SUM(CASE WHEN weather_code = 10 THEN 1 ELSE 0 END) AS locations_10'),
            DB::raw('SUM(CASE WHEN weather_code = 45 THEN 1 ELSE 0 END) AS locations_45'),
            DB::raw('SUM(CASE WHEN weather_code = 60 THEN 1 ELSE 0 END) AS locations_60'),
            DB::raw('SUM(CASE WHEN weather_code = 61 THEN 1 ELSE 0 END) AS locations_61'),
            DB::raw('SUM(CASE WHEN weather_code = 63 THEN 1 ELSE 0 END) AS locations_63'),
            DB::raw('SUM(CASE WHEN weather_code = 95 THEN 1 ELSE 0 END) AS locations_95')
        )
        ->leftJoin('lokasis as b', 'api_barats.location', '=', 'b.location')
        ->whereIn('api_barats.weather_code', [0, 1, 3, 4, 60, 61, 63, 95, 97]);

        // Penyesuaian kondisi waktu
        if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
            $weatherCounts->whereDate('api_barats.created_at', $yesterday);
        } else {
            $weatherCounts->whereDate('api_barats.created_at', $today);
        }

        // Penyesuaian kabupaten
        if (!empty($kabupaten)) {
            $weatherCounts->where('b.kabupaten', $kabupaten);
        }

        $weatherCounts = $weatherCounts->groupBy('b.kabupaten')->first();
        
        $groupedData = [];
        $uniqueDates = [];
        $hoursInDay = range(0, 21, 3);

        $weatherIcons = [
            '0' => ['icon' => '100_cerah.svg', 'title' => 'Cerah'],
            '100' => ['icon' => '100_cerah.svg', 'title' => 'Cerah'],
            '1' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '101' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '2' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '102' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '3' => ['icon' => '103_berawan.svg', 'title' => 'Berawan'],
            '103' => ['icon' => '103_berawan.svg', 'title' => 'Berawan'],
            '4' => ['icon' => '104_berawan_tebal.svg', 'title' => 'Berawan Tebal'],
            '104' => ['icon' => '104_berawan_tebal.svg', 'title' => 'Berawan Tebal'],
            '5' => ['icon' => '5_udara_kabur.svg', 'title' => 'Udara Kabur'],
            '10' => ['icon' => '10_asap.svg', 'title' => 'Asap'],
            '45' => ['icon' => '45_kabut.svg', 'title' => 'Kabut'],
            '60' => ['icon' => '60_hujan_ringan.svg', 'title' => 'Hujan Ringan'],
            '61' => ['icon' => '61_hujan.svg', 'title' => 'Hujan Sedang'],
            '63' => ['icon' => '63_hujan_lebat.svg', 'title' => 'Hujan Lebat'],
            '95' => ['icon' => '95_97_hujan_petir.svg', 'title' => 'Hujan Petir'],
            '97' => ['icon' => '95_97_hujan_petir.svg', 'title' => 'Hujan Petir'],

        ];

        foreach ($dataToday as $item) {
            $location = $item->jawa_barat->kecamatan;
            $idLocation = $item->jawa_barat->location;
            $date = Carbon::parse($item->timestamp);
            $hour = Carbon::parse($item->timestamp)->hour;
            $weatherCode = $item->weather->weather_code;
            $weatherIcon = $weatherIcons[$weatherCode] ?? '';

            if (!in_array($date->format('d M Y'), $uniqueDates)) {
                $uniqueDates[] = $date->format('d M Y');
            }

            if (!isset($groupedData[$location])) {
                $groupedData[$location] = [];
            }

            if (!isset($groupedData[$location][$date->format('d M Y')])) {
                $groupedData[$location][$date->format('d M Y')] = [];
            }

            $groupedData[$location][$date->format('d M Y')][$hour] = [
                'idLocation' => $idLocation,
                'weather_code' => $weatherCode,
                'weather_icon' => $weatherIcon,
                ];
            }

            $kabupatenList = Lokasi::where('provinsi', 'Jawa Barat')
                ->distinct()
                ->pluck('kabupaten');
                
        return response()->json([
            'status' => 'success',
            'groupedData' => $groupedData,
            'uniqueDates' => $uniqueDates,
            'hoursInDay' => $hoursInDay,
            'kabupatenList' => $kabupatenList,
            'weatherCounts' => $weatherCounts,
        ]);
    }

    public function filterJateng(Request $request)
    {
       // Dapatkan nilai kabupaten dari permintaan
        $kabupaten = $request->input('kabupaten');
        $currentHour = Carbon::now()->hour;
        $currentMinute = Carbon::now()->minute;

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
            $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
                ->whereDate('created_at', $yesterday);
        }else {
            $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
                ->whereDate('created_at', $today);
        }

        if (!empty($kabupaten)) {
             if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
                 $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
                ->leftJoin('lokasis as b', 'api_tengahs.location', '=', 'b.location')
                ->whereDate('api_tengahs.created_at', $yesterday)
                ->where('b.kabupaten', $kabupaten);

             }else{
                $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
                ->leftJoin('lokasis as b', 'api_tengahs.location', '=', 'b.location')
                ->whereDate('api_tengahs.created_at', $today)
                ->where('b.kabupaten', $kabupaten);
             }
            
        }

        $dataToday = $dataToday->get();

        // Query untuk menghitung jumlah lokasi berdasarkan kondisi cuaca dan kabupaten
        $weatherCounts = ApiTengah::select(
            'b.kabupaten',
            DB::raw('SUM(CASE WHEN weather_code = 0 THEN 1 ELSE 0 END) AS locations_0'),
            DB::raw('SUM(CASE WHEN weather_code = 1 THEN 1 ELSE 0 END) AS locations_1'),
            DB::raw('SUM(CASE WHEN weather_code = 3 THEN 1 ELSE 0 END) AS locations_3'),
            DB::raw('SUM(CASE WHEN weather_code = 4 THEN 1 ELSE 0 END) AS locations_4'),
            DB::raw('SUM(CASE WHEN weather_code = 5 THEN 1 ELSE 0 END) AS locations_5'),
            DB::raw('SUM(CASE WHEN weather_code = 10 THEN 1 ELSE 0 END) AS locations_10'),
            DB::raw('SUM(CASE WHEN weather_code = 45 THEN 1 ELSE 0 END) AS locations_45'),
            DB::raw('SUM(CASE WHEN weather_code = 60 THEN 1 ELSE 0 END) AS locations_60'),
            DB::raw('SUM(CASE WHEN weather_code = 61 THEN 1 ELSE 0 END) AS locations_61'),
            DB::raw('SUM(CASE WHEN weather_code = 63 THEN 1 ELSE 0 END) AS locations_63'),
            DB::raw('SUM(CASE WHEN weather_code = 95 THEN 1 ELSE 0 END) AS locations_95')
        )
        ->leftJoin('lokasis as b', 'api_tengahs.location', '=', 'b.location')
        ->whereIn('api_tengahs.weather_code', [0, 1, 3, 4, 60, 61, 63, 95, 97])
        ->whereDate('api_tengahs.created_at', $today)
        ->where('b.kabupaten', $kabupaten)
        ->groupBy('b.kabupaten')
        ->first();
        
        $groupedData = [];
        $uniqueDates = [];
        $hoursInDay = range(0, 21, 3);

        $weatherIcons = [
            '0' => ['icon' => '100_cerah.svg', 'title' => 'Cerah'],
            '100' => ['icon' => '100_cerah.svg', 'title' => 'Cerah'],
            '1' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '101' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '2' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '102' => ['icon' => '101_102_cerah_berawan.svg', 'title' => 'Cerah Berawan'],
            '3' => ['icon' => '103_berawan.svg', 'title' => 'Berawan'],
            '103' => ['icon' => '103_berawan.svg', 'title' => 'Berawan'],
            '4' => ['icon' => '104_berawan_tebal.svg', 'title' => 'Berawan Tebal'],
            '104' => ['icon' => '104_berawan_tebal.svg', 'title' => 'Berawan Tebal'],
            '5' => ['icon' => '5_udara_kabur.svg', 'title' => 'Udara Kabur'],
            '10' => ['icon' => '10_asap.svg', 'title' => 'Asap'],
            '45' => ['icon' => '45_kabut.svg', 'title' => 'Kabut'],
            '60' => ['icon' => '60_hujan_ringan.svg', 'title' => 'Hujan Ringan'],
            '61' => ['icon' => '61_hujan.svg', 'title' => 'Hujan Sedang'],
            '63' => ['icon' => '63_hujan_lebat.svg', 'title' => 'Hujan Lebat'],
            '95' => ['icon' => '95_97_hujan_petir.svg', 'title' => 'Hujan Petir'],
            '97' => ['icon' => '95_97_hujan_petir.svg', 'title' => 'Hujan Petir'],

        ];

        foreach ($dataToday as $item) {
            $location = $item->jawa_tengah->kecamatan;
            $idLocation = $item->jawa_tengah->location;
            $date = Carbon::parse($item->timestamp);
            $hour = Carbon::parse($item->timestamp)->hour;
            $weatherCode = $item->weather->weather_code;
            $weatherIcon = $weatherIcons[$weatherCode] ?? '';

            if (!in_array($date->format('d M Y'), $uniqueDates)) {
                $uniqueDates[] = $date->format('d M Y');
            }

            if (!isset($groupedData[$location])) {
                $groupedData[$location] = [];
            }

            if (!isset($groupedData[$location][$date->format('d M Y')])) {
                $groupedData[$location][$date->format('d M Y')] = [];
            }

            $groupedData[$location][$date->format('d M Y')][$hour] = [
                'idLocation' => $idLocation,
                'weather_code' => $weatherCode,
                'weather_icon' => $weatherIcon,
                ];
            }

            $kabupatenList = Lokasi::where('provinsi', 'Jawa Tengah')
                ->distinct()
                ->pluck('kabupaten');
                
        return response()->json([
            'status' => 'success',
            'groupedData' => $groupedData,
            'uniqueDates' => $uniqueDates,
            'hoursInDay' => $hoursInDay,
            'kabupatenList' => $kabupatenList,
            'weatherCounts' => $weatherCounts,
        ]);
    }

    public function weatherCountJabar($location)
    {
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        // Query untuk menghitung jumlah lokasi berdasarkan kondisi cuaca dan kabupaten
        $weatherCounts = ApiBarat::select(
            'b.kecamatan',
            DB::raw('SUM(CASE WHEN weather_code = 0 THEN 1 ELSE 0 END) AS locations_0'),
            DB::raw('SUM(CASE WHEN weather_code = 1 THEN 1 ELSE 0 END) AS locations_1'),
            DB::raw('SUM(CASE WHEN weather_code = 3 THEN 1 ELSE 0 END) AS locations_3'),
            DB::raw('SUM(CASE WHEN weather_code = 4 THEN 1 ELSE 0 END) AS locations_4'),
            DB::raw('SUM(CASE WHEN weather_code = 5 THEN 1 ELSE 0 END) AS locations_5'),
            DB::raw('SUM(CASE WHEN weather_code = 10 THEN 1 ELSE 0 END) AS locations_10'),
            DB::raw('SUM(CASE WHEN weather_code = 45 THEN 1 ELSE 0 END) AS locations_45'),
            DB::raw('SUM(CASE WHEN weather_code = 60 THEN 1 ELSE 0 END) AS locations_60'),
            DB::raw('SUM(CASE WHEN weather_code = 61 THEN 1 ELSE 0 END) AS locations_61'),
            DB::raw('SUM(CASE WHEN weather_code = 63 THEN 1 ELSE 0 END) AS locations_63'),
            DB::raw('SUM(CASE WHEN weather_code = 95 THEN 1 ELSE 0 END) AS locations_95')
        )
        ->leftJoin('lokasis as b', 'api_barats.location', '=', 'b.location')
        ->whereIn('api_barats.weather_code', [0, 1, 3, 4, 60, 61, 63, 95, 97]);

        // Penyesuaian kondisi waktu
        if (Carbon::now()->hour < 12) {
            $weatherCounts->whereDate('api_barats.created_at', $yesterday);
        } else {
            $weatherCounts->whereDate('api_barats.created_at', $today);
        }

        // Penyesuaian lokasi
        $weatherCounts->where('api_barats.location', $location);

        // Group by kabupaten dan ambil hasil pertama
        $weatherCounts = $weatherCounts->groupBy('b.kecamatan')->first();

        return response()->json([
            'weatherCounts' => $weatherCounts
        ]);;
    }

    public function weatherCountJateng($location)
    {
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        // Query untuk menghitung jumlah lokasi berdasarkan kondisi cuaca dan kabupaten
        $weatherCounts = ApiTengah::select(
            'b.kecamatan',
            DB::raw('SUM(CASE WHEN weather_code = 0 THEN 1 ELSE 0 END) AS locations_0'),
            DB::raw('SUM(CASE WHEN weather_code = 1 THEN 1 ELSE 0 END) AS locations_1'),
            DB::raw('SUM(CASE WHEN weather_code = 3 THEN 1 ELSE 0 END) AS locations_3'),
            DB::raw('SUM(CASE WHEN weather_code = 4 THEN 1 ELSE 0 END) AS locations_4'),
            DB::raw('SUM(CASE WHEN weather_code = 5 THEN 1 ELSE 0 END) AS locations_5'),
            DB::raw('SUM(CASE WHEN weather_code = 10 THEN 1 ELSE 0 END) AS locations_10'),
            DB::raw('SUM(CASE WHEN weather_code = 45 THEN 1 ELSE 0 END) AS locations_45'),
            DB::raw('SUM(CASE WHEN weather_code = 60 THEN 1 ELSE 0 END) AS locations_60'),
            DB::raw('SUM(CASE WHEN weather_code = 61 THEN 1 ELSE 0 END) AS locations_61'),
            DB::raw('SUM(CASE WHEN weather_code = 63 THEN 1 ELSE 0 END) AS locations_63'),
            DB::raw('SUM(CASE WHEN weather_code = 95 THEN 1 ELSE 0 END) AS locations_95')
        )
        ->leftJoin('lokasis as b', 'api_tengahs.location', '=', 'b.location')
        ->whereIn('api_tengahs.weather_code', [0, 1, 3, 4, 60, 61, 63, 95, 97]);

        // Penyesuaian kondisi waktu
        if (Carbon::now()->hour < 12) {
            $weatherCounts->whereDate('api_tengahs.created_at', $yesterday);
        } else {
            $weatherCounts->whereDate('api_tengahs.created_at', $today);
        }

        // Penyesuaian lokasi
        $weatherCounts->where('api_tengahs.location', $location);

        // Group by kabupaten dan ambil hasil pertama
        $weatherCounts = $weatherCounts->groupBy('b.kecamatan')->first();

        return response()->json([
            'weatherCounts' => $weatherCounts
        ]);;
    }

}
