@extends('layouts.absensi')

@section('content')

<div>
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @elseif ($message = Session::get('error'))
    <div class="alert alert-danger">
        <p>{{ $message }}</p>
    </div>
    @endif

    <h2>status Anda hari ini adalah
        @if (!empty($todayabsensi->status))
            @if ($todayabsensi->status == 'hadir')
                <span style="color:green">HADIR</span>
            @elseif ($todayabsensi->status == 'cuti')
                <span style="color:yellow">CUTI</span>
            @elseif ($todayabsensi->status == 'sakit')
                <span style="color:yellow">SAKIT</span>
            @elseif ($todayabsensi->status == 'alpha')
                <span style="color:red">ALPHA</span>
            @endif
        @else
            <span style="color:blue">Belum ada Status</span>
        @endif
    </h2>
    {{ date('Y-m-d H:i:s') }}

    @if (empty($todayabsensi->status))
    <h4><br> klik tombol di bawah untuk mulai presensi</h4>
    <form action="{{ route('karyawan.hadir') }}" method="post">
        @csrf
        <input type="hidden" name="time" value="{{ date('Y-m-d H:i:s') }}">
        <button type="submit" class="btn btn-success">Hadir</button>
    </form>
    @elseif ($todayabsensi->end_at)
        <h4><br> Anda sudah melakukan presensi hari ini</h4>
    @else
    <h4><br> klik tombol di bawah untuk akhiri presensi</h4>
    <form action="{{ route('karyawan.pulang') }}" method="post">
        @csrf
        <input type="hidden" name="time" value="{{ date('Y-m-d H:i:s') }}">
        <button type="submit" class="btn btn-primary">Pulang</button>
    </form>
    @endif

    <div class="card direct-chat mt-4">
        <div class="card-header">
            <h3 class="card-title">Isi form di bawah jika ingin mengajukan cuti/sakit</h3>
            <!-- card tools -->
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div class="card-body">
            <form role="form" action="{{ route('karyawan.izin') }}" method="post">
                @csrf
                <?php
                    $kemarin = new DateTime();
                    $min = $kemarin->modify("-3 days");
                    $besok = new DateTime();
                    $max = $besok->modify("1 day");
                ?>
                <div class="card-body ml-4 mr-4">
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" class="form-control">
                            <option value="" disabled selected>--- Pilih ---</option>
                            <option value="cuti">Cuti</option>
                            <option value="sakit">Sakit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Untuk Tangal:</label>
                        <input class="form-control" type="date" name="tanggal" min={{ $min->format('Y-m-d') }} max={{ $max->format('Y-m-d') }}>
                    </div>
                    <div class="form-group">
                        <label>Alasan</label>
                        <textarea class="form-control" rows="3" name="keterangan" placeholder="sebutkan alasanmu..."></textarea>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection