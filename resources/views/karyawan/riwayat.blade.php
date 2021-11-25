@extends('layouts.absensi')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Riwayat Absensi</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($riwayat as $riwayat)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ date('d-m-Y', strtotime($riwayat->start_at)) }}</td>
                            <td>{{ date('H:i:s', strtotime($riwayat->start_at)) }}</td>
                            <td>{{ date('H:i:s', strtotime($riwayat->end_at)) }}</td>
                            <td>
                                @if ($riwayat->is_active == 1)
                                    {{ $riwayat->status }}
                                @elseif ($riwayat->is_active == 0)
                                    {{ $riwayat->status }} (sedang diajukan)
                                @endif    
                            </td>
                            <td>{{ $riwayat->keterangan }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection