@extends('layouts.absensi')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Absensi</h3>

                <div class="card-tools">
                    <form role="form" action="{{ route('admin.index') }}" method="get">
                        <div class="input-group input-group-sm" style="width: 300px;">
                            <select name="user_id" class="form-control float-right">
                                <option value="" disabled selected>-- Karyawan --</option>
                                <option value="" >Semua Karyawan</option>
                                <option value="" disabled>----</option>
                                @foreach ($user as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <select name="month" class="form-control float-right">
                                <option value="" selected>-- Bulan --</option>
                                <option value="01" >Januari</option>
                                <option value="02" >Februari</option>
                                <option value="03" >Maret</option>
                                <option value="04" >April</option>
                                <option value="05" >Mei</option>
                                <option value="06" >Juni</option>
                                <option value="07" >Juli</option>
                                <option value="08" >Agustus</option>
                                <option value="09" >September</option>
                                <option value="10" >Oktober</option>
                                <option value="11" >November</option>
                                <option value="12" >Desember</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nomor</th>
                            <th>Nama</th>
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
                        @foreach ($karyawan as $karyawan)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $karyawan->name }}</td>
                            <td>{{ date('d-m-Y', strtotime($karyawan->start_at)) }}</td>
                            <td>{{ date('H:i:s', strtotime($karyawan->start_at)) }}</td>
                            <td>{{ date('H:i:s', strtotime($karyawan->end_at)) }}</td>
                            <td>
                                @if ($karyawan->is_active == 1)
                                {{ $karyawan->status }}
                                @elseif ($karyawan->is_active == 0)
                                {{ $karyawan->status }} (sedang diajukan)
                                @endif
                            </td>
                            <td>{{ $karyawan->keterangan }}</td>
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