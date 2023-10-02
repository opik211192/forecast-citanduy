<?php

namespace App\Http\Controllers;

use App\Models\ApiBarat;
use App\Models\Jbarat;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class ApiBaratController extends Controller
{
    public function index()
    {
        //$kecamatans = Jbarat::all();
        $kecamatans = Lokasi::where('provinsi', 'Jawa Barat')->get();


        $kabupatens = $kecamatans->groupBy('kabupaten');

        return view('jabar.detail', compact('kecamatans', 'kabupatens'));
        //dd($data);
    }

    public function showData(Request $request)
    {
         if ($request->ajax()) {

            $tanggal_penarikan = $request->input('tanggal');
            $kecamatan_location = $request->input('kecamatan');

            $data = ApiBarat::with('jawa_barat', 'weather');

            // Jika tanggal panrikan telah dipilih, tambahkan filter berdasarkan tanggal
            if ($tanggal_penarikan) {
                $data->whereDate('created_at', Carbon::parse($tanggal_penarikan)->format('Y-m-d'));
            }else{
                // Jika tanggal tidak dipilih, tampilkan data dengan tanggal hari ini
                $data->whereDate('created_at', Carbon::today()->format('Y-m-d'));
            }

            // Filter berdasarkan kecamatan jika ada yang dipilih
            if ($kecamatan_location) {
                $data->whereHas('jawa_barat', function ($query) use ($kecamatan_location) {
                    $query->where('location', $kecamatan_location);
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function (ApiBarat $apiBarat) {
                    return $apiBarat->created_at_formatted;
                })
                ->addColumn('timestamp', function (ApiBarat $apiBarat) {
                    return $apiBarat->timestamp_formatted;
                })
                ->make(true);
        }

        return abort(404);
    }
}
