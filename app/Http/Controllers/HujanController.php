<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HujanController extends Controller
{
    public function hujanJabar(Request $request)
    {
        $kabupaten = $request->input('kabupaten');
        $kabupatenList = Lokasi::where('provinsi', 'Jawa Barat')
                        ->distinct()
                        ->pluck('kabupaten');

        $today = Carbon::now();
        $yesterday = Carbon::yesterday();
        $currentTime = Carbon::now();

        //if ($currentTime->hour < 12 || ($currentTime->hour == 12 && $currentTime->minute < 15)) {
        if ($currentTime->hour < 12 || ($currentTime->hour == 12 && $currentTime->minute < 15)) {
                $data = DB::table('api_barats as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $yesterday)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan')
                    ->get();
        }else{
                // Jika saat ini setelah jam 12:15 PM, tampilkan data dari hari ini
                $data = DB::table('api_barats as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $today)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan')
                    ->get();
        }

        // Join dengan tabel weather
        $dataJbarat = $data->map(function ($item) {
            $weatherInfo = DB::table('weather')
                ->select('name')
                ->where('weather_code', $item->weather_code_tertinggi)
                ->first();
            
            $item->nama_cuaca = $weatherInfo ? $weatherInfo->name : null;

            return $item;
        });

        // $outputData = new Collection($dataJbarat);
        $groupedData = $dataJbarat->groupBy(['kecamatan', 'tanggal']);
        $groupedData = $groupedData->map(function ($items) {
            return $items->map(function ($item) {
                $weatherCodeTertinggi = $item->pluck('weather_code_tertinggi')->first();
                $nama_cuaca = $item->pluck('nama_cuaca')->first();

                return [
                    'weather_code_tertinggi' => $weatherCodeTertinggi,
                    'nama_cuaca' => $nama_cuaca,
                ];
            });
        });
        $dates = $data->pluck('tanggal')->unique()->values();

        
        $weatherIcons = [
            '0' => ['icon' => '100_cerah.png', 'title' => 'Cerah'],
            '100' => ['icon' => '100_cerah.png', 'title' => 'Cerah'],
            '1' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '101' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '2' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '102' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '3' => ['icon' => '103_berawan.png', 'title' => 'Berawan'],
            '103' => ['icon' => '103_berawan.png', 'title' => 'Berawan'],
            '4' => ['icon' => '104_berawan_tebal.png', 'title' => 'Berawan Tebal'],
            '104' => ['icon' => '104_berawan_tebal.png', 'title' => 'Berawan Tebal'],
            '5' => ['icon' => '5_udara_kabur.png', 'title' => 'Udara Kabur'],
            '10' => ['icon' => '10_asap.png', 'title' => 'Asap'],
            '45' => ['icon' => '45_kabut.png', 'title' => 'Kabut'],
            '60' => ['icon' => '60_hujan_ringan.png', 'title' => 'Hujan Ringan'],
            '61' => ['icon' => '61_hujan.png', 'title' => 'Hujan'],
            '63' => ['icon' => '63_hujan_lebat.png', 'title' => 'Hujan Lebat'],
            '95' => ['icon' => '95_97_hujan_petir.png', 'title' => 'Hujan Petir'],
            '97' => ['icon' => '95_97_hujan_petir.png', 'title' => 'Hujan Petir'],

        ];

        return view('Hujan.jabar', [
            'groupedData' => $groupedData,
            'dates' => $dates,
            'weatherIcons' => $weatherIcons,
            'kabupatenList' => $kabupatenList,
            'selectedKabupaten' => $kabupaten,
        ]);
        
    }

    public function filterJabar(Request $request)
    {
        $kabupaten = $request->input('kabupaten');

        $today = Carbon::now();
        $yesterday = Carbon::yesterday();
        $currentTime = Carbon::now();

        if ($currentTime->hour < 12 || ($currentTime->hour == 12 && $currentTime->minute < 15)) {
                $data = DB::table('api_barats as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $yesterday)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan');
                   
        }else{
                // Jika saat ini setelah jam 12:15 PM, tampilkan data dari hari ini
                $data = DB::table('api_barats as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $today)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan');
                    
        }

        if (!empty($kabupaten)) {
            if ($currentTime->hour < 12 || ($currentTime->hour == 12 && $currentTime->minute < 15)) {
                $data = DB::table('api_barats as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $yesterday)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->where('b.kabupaten', $kabupaten)
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan');
                    
            }else{
                    // Jika saat ini setelah jam 12:15 PM, tampilkan data dari hari ini
                    $data = DB::table('api_barats as a')
                        ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                        ->whereDay('a.created_at', '=', $today)
                        ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                        ->where('b.kabupaten', $kabupaten)
                        ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan');
            }
        }

        $data = $data->get();
        
         // Join dengan tabel weather
        $dataJbarat = $data->map(function ($item) {
            $weatherInfo = DB::table('weather')
                ->select('name')
                ->where('weather_code', $item->weather_code_tertinggi)
                ->first();
            
            $item->nama_cuaca = $weatherInfo ? $weatherInfo->name : null;

            return $item;
        });

        // $outputData = new Collection($dataJbarat);
        $groupedData = $dataJbarat->groupBy(['kecamatan', 'tanggal']);
        $groupedData = $groupedData->map(function ($items) {
            return $items->map(function ($item) {
                $weatherCodeTertinggi = $item->pluck('weather_code_tertinggi')->first();
                $nama_cuaca = $item->pluck('nama_cuaca')->first();

                return [
                    'weather_code_tertinggi' => $weatherCodeTertinggi,
                    'nama_cuaca' => $nama_cuaca,
                ];
            });
        });
        $dates = $data->pluck('tanggal')->unique()->values();

        
        $weatherIcons = [
            '0' => ['icon' => '100_cerah.png', 'title' => 'Cerah'],
            '100' => ['icon' => '100_cerah.png', 'title' => 'Cerah'],
            '1' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '101' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '2' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '102' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '3' => ['icon' => '103_berawan.png', 'title' => 'Berawan'],
            '103' => ['icon' => '103_berawan.png', 'title' => 'Berawan'],
            '4' => ['icon' => '104_berawan_tebal.png', 'title' => 'Berawan Tebal'],
            '104' => ['icon' => '104_berawan_tebal.png', 'title' => 'Berawan Tebal'],
            '5' => ['icon' => '5_udara_kabur.png', 'title' => 'Udara Kabur'],
            '10' => ['icon' => '10_asap.png', 'title' => 'Asap'],
            '45' => ['icon' => '45_kabut.png', 'title' => 'Kabut'],
            '60' => ['icon' => '60_hujan_ringan.png', 'title' => 'Hujan Ringan'],
            '61' => ['icon' => '61_hujan.png', 'title' => 'Hujan'],
            '63' => ['icon' => '63_hujan_lebat.png', 'title' => 'Hujan Lebat'],
            '95' => ['icon' => '95_97_hujan_petir.png', 'title' => 'Hujan Petir'],
            '97' => ['icon' => '95_97_hujan_petir.png', 'title' => 'Hujan Petir'],

        ];

        $kabupatenList = Lokasi::where('provinsi', 'Jawa Barat')
                ->distinct()
                ->pluck('kabupaten');

        return view('Hujan.jabar', [
            'groupedData' => $groupedData,
            'dates' => $dates,
            'weatherIcons' => $weatherIcons,
            'kabupatenList' => $kabupatenList,
            'selectedKabupaten' => $kabupaten,
        ]);
    }
    
    public function hujanJateng(Request $request) 
    {
        $kabupaten = $request->input('kabupaten');
        $kabupatenList = Lokasi::where('provinsi', 'Jawa Tengah')
                        ->distinct()
                        ->pluck('kabupaten');

        $today = Carbon::now();
        $yesterday = Carbon::yesterday();
        $currentTime = Carbon::now();
        
        //-------------------------------------Jawa Tengah -----------------------------------//
        
        if ($currentTime->hour < 12 || ($currentTime->hour == 12 && $currentTime->minute < 15)) {
            $dataJtengah = DB::table('api_tengahs as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $yesterday)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan')
                    ->get();
        }else{
            $dataJtengah = DB::table('api_tengahs as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $today)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan')
                    ->get();
        }


        $dataJtengah = $dataJtengah->map(function ($item) {
            $weatherInfo = DB::table('weather')
                ->select('name')
                ->where('weather_code', $item->weather_code_tertinggi)
                ->first();

            $item->nama_cuaca = $weatherInfo ? $weatherInfo->name : null;

            return $item;
         });
        
        $groupedData = $dataJtengah->groupBy(['kecamatan', 'tanggal']);
        $groupedData = $groupedData->map(function ($items) {
            return $items->map(function ($item) {
                $weatherCodeTertinggi = $item->pluck('weather_code_tertinggi')->first();
                $nama_cuaca = $item->pluck('nama_cuaca')->first();

                return [
                    'weather_code_tertinggi' => $weatherCodeTertinggi,
                    'nama_cuaca' => $nama_cuaca,
                ];
            });
        });
        
        $dates = $dataJtengah->pluck('tanggal')->unique()->values();


        
        $weatherIcons = [
            '0' => ['icon' => '100_cerah.png', 'title' => 'Cerah'],
            '100' => ['icon' => '100_cerah.png', 'title' => 'Cerah'],
            '1' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '101' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '2' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '102' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '3' => ['icon' => '103_berawan.png', 'title' => 'Berawan'],
            '103' => ['icon' => '103_berawan.png', 'title' => 'Berawan'],
            '4' => ['icon' => '104_berawan_tebal.png', 'title' => 'Berawan Tebal'],
            '104' => ['icon' => '104_berawan_tebal.png', 'title' => 'Berawan Tebal'],
            '5' => ['icon' => '5_udara_kabur.png', 'title' => 'Udara Kabur'],
            '10' => ['icon' => '10_asap.png', 'title' => 'Asap'],
            '45' => ['icon' => '45_kabut.png', 'title' => 'Kabut'],
            '60' => ['icon' => '60_hujan_ringan.png', 'title' => 'Hujan Ringan'],
            '61' => ['icon' => '61_hujan.png', 'title' => 'Hujan'],
            '63' => ['icon' => '63_hujan_lebat.png', 'title' => 'Hujan Lebat'],
            '95' => ['icon' => '95_97_hujan_petir.png', 'title' => 'Hujan Petir'],
            '97' => ['icon' => '95_97_hujan_petir.png', 'title' => 'Hujan Petir'],

        ];

        return view('Hujan.jateng', [
            'groupedData' => $groupedData,
            'dates' => $dates,
            'weatherIcons' => $weatherIcons,
            'kabupatenList' => $kabupatenList,
            'selectedKabupaten' => $kabupaten,
        ]);
    }

    public function filterJateng(Request $request)
    {
        $kabupaten = $request->input('kabupaten');

        $today = Carbon::now();
        $yesterday = Carbon::yesterday();
        $currentTime = Carbon::now();

        if ($currentTime->hour < 12 || ($currentTime->hour == 12 && $currentTime->minute < 15)) {
            $dataJtengah = DB::table('api_tengahs as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $yesterday)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan');
        }else{
            $dataJtengah = DB::table('api_tengahs as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $today)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan');
        }

        if (!empty($kabupaten)) {
            if ($currentTime->hour < 12 || ($currentTime->hour == 12 && $currentTime->minute < 15)) {
                $dataJtengah = DB::table('api_tengahs as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $yesterday)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->where('b.kabupaten', $kabupaten) 
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan');
                    
            }else{
                    // Jika saat ini setelah jam 12:15 PM, tampilkan data dari hari ini
                    $dataJtengah = DB::table('api_tengahs as a')
                    ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                    ->whereDay('a.created_at', '=', $today)
                    ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                    ->where('b.kabupaten', $kabupaten) 
                    ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan');
            }
        }

        $dataJtengah = $dataJtengah->get();

         $dataJtengah = $dataJtengah->map(function ($item) {
            $weatherInfo = DB::table('weather')
                ->select('name')
                ->where('weather_code', $item->weather_code_tertinggi)
                ->first();

            $item->nama_cuaca = $weatherInfo ? $weatherInfo->name : null;

            return $item;
         });
        
        $groupedData = $dataJtengah->groupBy(['kecamatan', 'tanggal']);
        $groupedData = $groupedData->map(function ($items) {
            return $items->map(function ($item) {
                $weatherCodeTertinggi = $item->pluck('weather_code_tertinggi')->first();
                $nama_cuaca = $item->pluck('nama_cuaca')->first();

                return [
                    'weather_code_tertinggi' => $weatherCodeTertinggi,
                    'nama_cuaca' => $nama_cuaca,
                ];
            });
        });
        
        $dates = $dataJtengah->pluck('tanggal')->unique()->values();


        
        $weatherIcons = [
            '0' => ['icon' => '100_cerah.png', 'title' => 'Cerah'],
            '100' => ['icon' => '100_cerah.png', 'title' => 'Cerah'],
            '1' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '101' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '2' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '102' => ['icon' => '101_102_cerah_berawan.png', 'title' => 'Cerah Berawan'],
            '3' => ['icon' => '103_berawan.png', 'title' => 'Berawan'],
            '103' => ['icon' => '103_berawan.png', 'title' => 'Berawan'],
            '4' => ['icon' => '104_berawan_tebal.png', 'title' => 'Berawan Tebal'],
            '104' => ['icon' => '104_berawan_tebal.png', 'title' => 'Berawan Tebal'],
            '5' => ['icon' => '5_udara_kabur.png', 'title' => 'Udara Kabur'],
            '10' => ['icon' => '10_asap.png', 'title' => 'Asap'],
            '45' => ['icon' => '45_kabut.png', 'title' => 'Kabut'],
            '60' => ['icon' => '60_hujan_ringan.png', 'title' => 'Hujan Ringan'],
            '61' => ['icon' => '61_hujan.png', 'title' => 'Hujan'],
            '63' => ['icon' => '63_hujan_lebat.png', 'title' => 'Hujan Lebat'],
            '95' => ['icon' => '95_97_hujan_petir.png', 'title' => 'Hujan Petir'],
            '97' => ['icon' => '95_97_hujan_petir.png', 'title' => 'Hujan Petir'],

        ];

         $kabupatenList = Lokasi::where('provinsi', 'Jawa Tengah')
                ->distinct()
                ->pluck('kabupaten');

        return view('Hujan.jateng', [
            'groupedData' => $groupedData,
            'dates' => $dates,
            'weatherIcons' => $weatherIcons,
            'kabupatenList' => $kabupatenList,
            'selectedKabupaten' => $kabupaten,
        ]);
    }
}
