<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\ApiTengah;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardTengahController extends Controller
{
    public function index(Request $request)
    {
        $kabupaten = $request->input('kabupaten');

        $currentHour = Carbon::now()->hour;
        $currentMinute = Carbon::now()->minute;

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');
        $twoDaysAgo = Carbon::now()->subDays(2)->format('Y-m-d');
        
        //  //ini perubahannya jadi jam 12.14
        // if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
        //     $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])->whereDate('created_at', $yesterday)->get();
        // } else {
        //     $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])->whereDate('created_at', $today)->get();
        // }

        //  // Jika data kemarin juga tidak ada, coba tampilkan data dari dua hari sebelumnya
        // if ($dataToday->isEmpty() && $currentHour < 12) {
        //     $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
        //         ->whereDate('created_at', $twoDaysAgo)
        //         ->get();
        // }
         $dataTodayQuery = ApiTengah::with(['jawa_tengah', 'weather'])
        ->whereDate('last_modified', $today);

        if (!empty($kabupaten)) {
            $dataTodayQuery->leftJoin('lokasis as b', 'api.tengahs.location', '=', 'b.location')
                ->whereDate('api.tengahs.last_modified', $today)
                ->where('b.kabupaten', $kabupaten);
        }

        $dataToday = $dataTodayQuery->get();

        if ($dataToday->isEmpty()) {
            $lastModifiedDates = ApiTengah::select('last_modified')
                ->orderBy('last_modified', 'desc')
                ->distinct()
                ->pluck('last_modified');

            $latestLastModified = $lastModifiedDates->first();

            $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
                ->whereDate('last_modified', $latestLastModified)
                ->get();
        }
        $kabupatenList = Lokasi::where('provinsi', 'Jawa Tengah')
                        ->distinct()
                        ->pluck('kabupaten');

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
     
        return view('jateng.index', [
            'groupedData' => $groupedData,
            'uniqueDates' => $uniqueDates,
            'hoursInDay' => $hoursInDay,
            'kabupatenList' => $kabupatenList,
            'selectedKabupaten' => $kabupaten,
        ]);
    }

    public function filter(Request $request)
    {
        $kabupaten = $request->input('kabupaten');
        $currentHour = Carbon::now()->hour;
        $currentMinute = Carbon::now()->minute;

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::now()->subDay()->format('Y-m-d');

        // if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
        //     $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
        //         ->whereDate('created_at', $yesterday);
        // }else {
        //     $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
        //         ->whereDate('created_at', $today);
        // }

        // if (!empty($kabupaten)) {
        //     if ($currentHour < 12 || ($currentHour == 12 && $currentMinute < 14)) {
        //          $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
        //         ->leftJoin('lokasis as b', 'api_tengahs.location', '=', 'b.location')
        //         ->whereDate('api_tengahs.created_at', $yesterday)
        //         ->where('b.kabupaten', $kabupaten);
        //     }else{
        //         $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
        //        ->leftJoin('lokasis as b', 'api_tengahs.location', '=', 'b.location')
        //        ->whereDate('api_tengahs.created_at', $today)
        //        ->where('b.kabupaten', $kabupaten);

        //     }
            
        // }

        // $dataToday = $dataToday->get();
        
        // Query untuk mencari data hari ini
        $dataTodayQuery = ApiTengah::with(['jawa_tengah', 'weather'])
            ->whereDate('last_modified', $today);

        // Jika ada kabupaten yang dipilih, tambahkan kondisi untuk kabupaten
        if (!empty($kabupaten)) {
            $dataTodayQuery->leftJoin('lokasis as b', 'api_tengahs.location', '=', 'b.location')
                ->where('b.kabupaten', $kabupaten);
        }

        // Ambil data hari ini berdasarkan query yang telah dibuat
        $dataToday = $dataTodayQuery->get();

        // Jika tidak ada data hari ini atau data untuk kabupaten tertentu, ambil data dengan last-modified terakhir
        if ($dataToday->isEmpty()) {
            // Ambil tanggal last-modified terbaru
            $latestLastModified = ApiTengah::select('last_modified')
                ->orderBy('last_modified', 'desc')
                ->distinct()
                ->first();

            // Ambil data dengan last-modified terbaru
            $dataToday = ApiTengah::with(['jawa_tengah', 'weather'])
                ->whereDate('last_modified', $latestLastModified->last_modified);

            // Jika ada kabupaten yang dipilih, tambahkan kondisi untuk kabupaten
            if (!empty($kabupaten)) {
                $dataToday->leftJoin('lokasis as b', 'api_tengahs.location', '=', 'b.location')
                    ->where('b.kabupaten', $kabupaten);
            }

            $dataToday = $dataToday->get();
        }

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
                'weather_code' => $weatherCode,
                'weather_icon' => $weatherIcon,
                ];
            }

            $kabupatenList = Lokasi::where('provinsi', 'Jawa Tengah')
                ->distinct()
                ->pluck('kabupaten');

            return view('jateng.index', [
                'groupedData' => $groupedData,
                'uniqueDates' => $uniqueDates,
                'hoursInDay' => $hoursInDay,
                'kabupatenList' => $kabupatenList,
                'selectedKabupaten' => $kabupaten,
            ]);
    }
    
}
