<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Lokasi;
use App\Models\Jtengah;
use App\Models\ApiTengah;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ApiTengahController extends Controller
{
    public function index()
    {
        //$kecamatans = Jtengah::all();
        $kecamatans = Lokasi::where('provinsi', 'Jawa Tengah')->get();


        $kabupatens = $kecamatans->groupBy('kabupaten');
        
       
        //dd($data);

        return view('jateng.detail', compact('kecamatans', 'kabupatens'));
    }

    public function showData(Request $request)
    {
        if ($request->ajax()) {

            $tanggal_penarikan = $request->input('tanggal');
            $kecamatan_location = $request->input('kecamatan');

            $data = ApiTengah::with('jawa_tengah', 'weather');

            // Jika tanggal panrikan telah dipilih, tambahkan filter berdasarkan tanggal
            if ($tanggal_penarikan) {
                $data->whereDate('created_at', Carbon::parse($tanggal_penarikan)->format('Y-m-d'));
            }else{
                // Jika tanggal tidak dipilih, tampilkan data dengan tanggal hari ini
                $data->whereDate('created_at', Carbon::today()->format('Y-m-d'));
            }

            // Filter berdasarkan kecamatan jika ada yang dipilih
            if ($kecamatan_location) {
                $data->whereHas('jawa_tengah', function ($query) use ($kecamatan_location) {
                    $query->where('location', $kecamatan_location);
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function (ApiTengah $apiTengah) {
                    return $apiTengah->created_at_formatted;
                })
                ->addColumn('timestamp', function (ApiTengah $apiTengah) {
                    return $apiTengah->timestamp_formatted;
                })
                ->make(true);
        }

        return abort(404);
    }
}
