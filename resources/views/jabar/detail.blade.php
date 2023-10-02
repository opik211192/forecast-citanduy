@extends('layouts.app')
@section('menuJabar', 'active')

@section('content')
<h2>Perkiraan Cuaca Wilayah Jawa Barat Sungai Citanduy </h2>

<div class="mt-4 mb-2">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="tanggal" class="font-weight-bold">Tanggal Penarikan:</label>
                <input type="date" class="form-control" id="tanggal" value="<?php echo date('Y-m-d') ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="kecamatan" class="font-weight-bold">Kecamatan:</label>
                <select name="kecamatan" id="kecamatan" class="form-control">
                    <option value="">-Pilih-</option>
                    @foreach ($kabupatens as $kabupaten => $kecamatans)
                    <optgroup label="{{ $kabupaten }}">
                        @foreach ($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->location }}">{{ $kecamatan->kecamatan }}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-sm table-info table-striped" id="dataTable">
        <thead>
            <tr>
                <th style="width: 20px">#</th>
                <th>Location</th>
                <th>Kecamatan</th>
                <th>Timestamp</th>
                <th>Weather</th>
                {{-- <th>Tanggal Penarikan</th> --}}
                <!-- Tambahkan kolom lain sesuai kebutuhan -->
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: "{{ route('jabar-data') }}",
                data: function (d) {
                    d.tanggal = $('#tanggal').val(); // Ambil nilai tanggal dari input
                    d.kecamatan = $('#kecamatan').val();
                }
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                { data: 'location', name: 'location' },
                { data: 'jawa_barat.kecamatan', name: 'jawa_barat.kecamatan' },
                { data: 'timestamp', name: 'timestamp' },
                { data: 'weather.name', name: 'weather.name' },
                // { data: 'created_at', name: 'created_at' },
            ],
            columnDefs: [
                {
                    searchable: false,
                    target: 4,
                    render: function (data, type, row) { 
                        return `${row.weather.name}`;
                     }
                }
            ],
            "lengthMenu": [ [10, 25, 40, 50, -1], [10, 25, 40, 50, "All"] ],
            "pageLength": 40,
        });

        // Event listener untuk input tanggal ketika nilainya berubah
        $('#tanggal').on('change', function () {
            table.ajax.reload(); // Reload tabel dengan parameter tanggal yang baru
        });

        // Event listener untuk selec option kecamatan ketika nilainya berubah
            $('#kecamatan').on('change', function () {
            table.ajax.reload(); // Reload tabel dengan parameter kecamatan yang baru
        });
    });
</script>
@endsection