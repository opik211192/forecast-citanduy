<?php

namespace App\Http\Controllers;

use App\Models\ApiBarat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardBaratController extends Controller
{
    public function index()
    {
        $currentHour = Carbon::now()->hour;
        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        $dataToday = ApiBarat::with(['jawa_barat', 'weather'])->whereDate('created_at', $today)->get();

        // if ($dataToday->isEmpty()) {
        //     $dataToday = ApiBarat::with(['jawa_barat', 'weather'])->whereDate('created_at', $yesterday)->get();
            
        // }
        
        if ($currentHour < 13) {
            $dataToday = $dataToday->isEmpty() ? ApiBarat::with(['jawa_barat', 'weather'])
                ->whereDate('created_at', $yesterday)
                ->get()
            : $dataToday;
        }

        $groupedData = [];
        $uniqueDates = [];
        $hoursInDay = range(0, 21, 3);

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

        foreach ($dataToday as $item) {
            $location = $item->jawa_barat->kecamatan;
            //$locationName =$item->jawa_barat->kecamatan; 
            $date = Carbon::parse($item->timestamp); // Menggunakan Carbon untuk tanggal
            $hour = Carbon::parse($item->timestamp)->hour; // Menggunakan Carbon untuk jam
            $weatherCode = $item->weather->weather_code;
            $weatherIcon = $weatherIcons[$weatherCode] ?? '';

            //dd($item);

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
                'weather_code' => $weatherCode,
                'weather_icon' => $weatherIcon,
            ];
        }

            //dd($locationName)
     
        return view('jabar.index', [
            'groupedData' => $groupedData,
            'uniqueDates' => $uniqueDates,
            'hoursInDay' => $hoursInDay,
        ]);
    }
}
