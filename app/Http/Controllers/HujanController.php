<?php

namespace App\Http\Controllers;

use App\Models\Hujan;
use App\Models\ApiBarat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HujanController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
         $yesterday = Carbon::yesterday();

        $data = DB::table('api_barats as a')
                ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                ->whereDay('a.created_at', '=', $today)
                ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan')
                ->get();

        // Join dengan tabel weather
        $data2 = $data->map(function ($item) {
            $weatherInfo = DB::table('weather')
                ->select('name')
                ->where('weather_code', $item->weather_code_tertinggi)
                ->first();
            
            $item->nama_cuaca = $weatherInfo ? $weatherInfo->name : null;

            return $item;
        });

        // $outputData = new Collection($data2);
        $groupedData = $data2->groupBy(['kecamatan', 'tanggal']);
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


//-------------------------------------Jawa Tengah -----------------------------------//
    
        $dataJtengah = DB::table('api_tengahs as a')
                ->select(DB::raw('DATE(a.timestamp) AS tanggal'), 'a.location', 'b.kecamatan', DB::raw('MAX(a.weather_code) AS weather_code_tertinggi'))
                ->whereDay('a.created_at', '=', $today)
                ->leftJoin('lokasis as b', 'a.location', '=', 'b.location')
                ->groupBy(DB::raw('DATE(a.timestamp)'), 'a.location', 'b.kecamatan')
                ->get();

        $dataJtengah2 = $dataJtengah->map(function ($item) {
            $weatherInfo = DB::table('weather')
                ->select('name')
                ->where('weather_code', $item->weather_code_tertinggi)
                ->first();

            $item->nama_cuaca = $weatherInfo ? $weatherInfo->name : null;

            return $item;
         });
        
        $groupedData2 = $dataJtengah2->groupBy(['kecamatan', 'tanggal']);
        $groupedData2 = $groupedData2->map(function ($items) {
            return $items->map(function ($item) {
                $weatherCodeTertinggi = $item->pluck('weather_code_tertinggi')->first();
                $nama_cuaca = $item->pluck('nama_cuaca')->first();

                return [
                    'weather_code_tertinggi' => $weatherCodeTertinggi,
                    'nama_cuaca' => $nama_cuaca,
                ];
            });
        });
        
        $dates2 = $dataJtengah->pluck('tanggal')->unique()->values();


        
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

        return view('hujan', [
            'groupedData' => $groupedData,
            'groupedData2' => $groupedData2,
            'dates' => $dates,
            'dates2' => $dates2,
            'weatherIcons' => $weatherIcons
        ]);
        
    }
    
    public function showData(Request $request)
    {
       
     
    }
}
