<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AbsensiController extends Controller
{
    //route jika admin atau bukan 
    public function route(){
        //check user tersbut admin atau bukan
        $isadmin = Auth::user()->is_admin;
        //jika user adalah admin
        if($isadmin == 1){
            return redirect()->route('admin.index');
        //jika user bukan admin atau karyawan
        }else{
            return redirect()->route('karyawan.index');
        }
    }

    ///Karyawan area
    //index atau halaman depan
    public function index(){
        //data user yang login
        $data['user'] = Auth::user();
        $userid = $data['user']->id;

        //presensi karyawan hari ini
        $data['todayabsensi'] = Absensi::where('user_id', $userid)
            ->whereDate('start_at', Carbon::today())
            ->first();

        return view('karyawan.index')->with($data);
    }

    //karyawan menekan tombol hadir
    public function hadir(Request $request){
        //jam masuk yang telah ditentukan
        $jammasuk = date('Y-m-d 09:00:00');
        //jika karyawan menekan sebelum jam yang telah ditentukan di atas
        if ($request->time <= $jammasuk) {
            $absensi = new Absensi();

            //maka statusnya hadir
            $absensi->user_id           = Auth::id();
            $absensi->status            = 'hadir';
            $absensi->start_at          = date('Y-m-d H:i:s');
            $absensi->is_active         = 1;

            $absensi->save();

            return Redirect::back()->with('success', 'Sukses melakukan absensi.');
        //jika karyawan menekan setelah jam yang telah ditentukan di atas
        } else {
            $absensi = new Absensi();

            //maka statusnya alpha karena telat
            $absensi->user_id           = Auth::id();
            $absensi->status            = 'alpha';
            $absensi->start_at          = date('Y-m-d H:i:s');
            $absensi->is_active         = 1;

            $absensi->save();
            return Redirect::back()->with('error', 'Anda telat melakukan absensi.');
        }
    }

    //karyawan mengajukan izin
    public function izin(Request $request){
        $tanggal = $request->tanggal . date(' H:i:s');
        $absensi = new Absensi();

        //data absensi dibuat dengan status cuti atau sakit namun masih 0 isactive untuk persetujuan admin 
        $absensi->user_id           = Auth::id();
        $absensi->status            = $request->status;
        $absensi->start_at          = $tanggal;
        $absensi->keterangan        = $request->keterangan;
        $absensi->is_active         = 0;
        
        $absensi->save();

        return Redirect::back()->with('success', 'Izin Anda akan Kami tinjau.');
    }

    //karyawan menekan tombol pulang
    public function pulang(Request $request){
        $data['user'] = Auth::user();
        $userid = $data['user']->id;

        //jam pulang yang telah ditentukan
        $jampulang = date('Y-m-d 17:00:00');
        //jika karyawan menekan tombol pulang setelah waktu yang ditentukan
        if($request->time >= $jampulang){
            Absensi::where('user_id', $userid)->whereDate('start_at', Carbon::today())
                ->update([
                    'end_at' => $request->time
                ]);

            return Redirect::back()->with('success', 'Anda berhasil melakukan absensi pulang.');
        //jika karyawan menekan tombol pulang sebelum waktu yang ditentukan
        }else{
            //error karena belum boleh pulang
            return Redirect::back()->with('error', 'Anda belum boleh melakukan absensi pulang.');
        }
    }

    //riwayat karyawan yang bisa dilihat oleh karyawan itu sendiri
    public function riwayat(){
        //data riwayat
        $data['riwayat'] = Absensi::where('user_id', Auth::id())
                                    ->orderBy('start_at', 'desc')
                                    ->get();

        return view('karyawan.riwayat')->with($data);
    }
    ///

    ///Admin area
    //index admin yang berisi riwayat karyawan
    public function indexadmin(Request $request){
        //data karyawan
        $data['karyawan'] = Absensi::join('users', 'absensi.user_id', '=', 'users.id')
                                    //jika admin memilih salah satu karyawan
                                    ->when($request->user_id != null, function ($q) use ($request){
                                        return $q->where('users.id', $request->user_id);
                                    })
                                    //jika admin memilih salah satu bulan
                                    ->when($request->month != null, function ($q) use ($request){
                                        return $q->whereMonth('absensi.start_at', $request->month);
                                    })
                                    ->get();
        //daftar karyawan untuk admin bisa memilih 
        $data['user'] = User::orderBy('name')->get();

        return view('admin.index')->with($data);
    }

    //berisi pengajuan izin dari karyawan
    public function pengajuan(){
        //data karyawan yang meminta izin
        $data['karyawan'] = Absensi::select('absensi.id as absensiid', 'absensi.*', 'users.*')
                                    ->join('users', 'absensi.user_id', '=', 'users.id')
                                    ->where('absensi.is_active', 0)
                                    ->get();
                                    
        return view('admin.pengajuan')->with($data);
    }

    //admin memberi izin pada karyawan
    public function izinkan(Request $request){
        //jika diizinkan, maka data absensi karyawan akan diupdate untuk menjadi aktif
        Absensi::where('id', $request->id)
            ->update([
                'is_active' => 1,
                'end_at' => date('Y-m-d H:i:s')
            ]);

        return Redirect::back()->with('success', 'Anda telah mengizinkan.');
    }

    //admin menolak izin pada karyawan
    public function tolak(Request $request){
        //jika ditolak, maka status absensi karyawan akan diupdate untuk menjadi alpha
        Absensi::where('id', $request->id)
            ->update([
                'status' => 'alpha',
                'is_active' => 1,
                'end_at' => date('Y-m-d H:i:s')
            ]);

        return Redirect::back()->with('success', 'Anda telah menolak.');
    }
}
