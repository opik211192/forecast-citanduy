<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\ApiBarat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MapController extends Controller
{
    public function index()
    {
        return view('leaflet.map');
    }

    public function mapJabar(Request $request)
    {
       $kabupaten = $request->input('kabupaten');

        //ini untuk data filter
        $kabupatenList = Lokasi::where('provinsi', 'Jawa Barat')
                        ->distinct()
                        ->pluck('kabupaten');

        return view('leaflet.jabar', [
            'kabupatenList' => $kabupatenList,
            'selectedKabupaten' => $kabupaten,
        ]);
    }

    public function mapJateng(Request $request)
    {
         $kabupaten = $request->input('kabupaten');

        //ini untuk data filter
        $kabupatenList = Lokasi::where('provinsi', 'Jawa Tengah')
                        ->distinct()
                        ->pluck('kabupaten');

        return view('leaflet.jateng', [
            'kabupatenList' => $kabupatenList,
            'selectedKabupaten' => $kabupaten,
        ]);
    }

}
