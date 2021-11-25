@extends('layouts.absensi')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Pengajuan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama</th>
                            <th>Tanggal Izin</th>
                            <th>Tanggal pengajuan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th colspan="2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($karyawan as $karyawan)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $karyawan->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($karyawan->start_at)) }}</td>
                            <td>{{ date('d-m-Y H:i:s', strtotime($karyawan->created_at)) }}</td>
                            <td>{{ $karyawan->status }}</td>
                            <td>{{ $karyawan->keterangan }}</td>
                            <td>
                                <form action="{{ route('admin.izinkan') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $karyawan->absensiid }}">
                                    <button type="submit" class="btn btn-warning">Izinkan</button>
                                </form>
                            </td>
                            <td>
                                <form action="{{ route('admin.tolak') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $karyawan->absensiid }}">
                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                </form>
                            </td>
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