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
    public function route(){
        $isadmin = Auth::user()->is_admin;
        if($isadmin == 1){
            return redirect()->route('admin.index');
        }else{
            return redirect()->route('karyawan.index');
        }
    }

    public function index(){

        $data['user'] = Auth::user();
        $userid = $data['user']->id;

        //presensi hari ini
        $data['todayabsensi'] = Absensi::where('user_id', $userid)
            ->whereDate('start_at', Carbon::today())
            ->first();

        // dd($data['todayabsensi']);
        return view('karyawan.index')->with($data);
    }

    public function hadir(Request $request){
        $jammasuk = date('Y-m-d 09:00:00');
        if ($request->time <= $jammasuk) {
            $absensi = new Absensi();

            $absensi->user_id           = Auth::id();
            $absensi->status            = 'hadir';
            $absensi->start_at          = date('Y-m-d H:i:s');
            $absensi->is_active         = 1;

            $absensi->save();
            return Redirect::back()->with('success', 'Sukses melakukan absensi.');
        } else {
            $absensi = new Absensi();

            $absensi->user_id           = Auth::id();
            $absensi->status            = 'alpha';
            $absensi->start_at          = date('Y-m-d H:i:s');
            $absensi->is_active         = 1;

            $absensi->save();
            return Redirect::back()->with('error', 'Anda telat melakukan absensi.');
        }
    }

    public function izin(Request $request){
        $tanggal = $request->tanggal . date(' H:i:s');
        $absensi = new Absensi();

        $absensi->user_id           = Auth::id();
        $absensi->status            = $request->status;
        // $absensi->start_at          = $request->tanggal;
        $absensi->start_at          = $tanggal;
        $absensi->keterangan        = $request->keterangan;
        $absensi->is_active         = 0;
        // dd($absensi);
        $absensi->save();

        return Redirect::back()->with('success', 'Izin Anda akan Kami tinjau.');
    }

    public function pulang(Request $request){
        $data['user'] = Auth::user();
        $userid = $data['user']->id;

        $jampulang = date('Y-m-d 17:00:00');
        if($request->time >= $jampulang){
            Absensi::where('user_id', $userid)->whereDate('start_at', Carbon::today())
                ->update([
                    'end_at' => $request->time
                ]);

            return Redirect::back()->with('success', 'Anda berhasil melakukan absensi pulang.');
        }else{
            return Redirect::back()->with('error', 'Anda belum boleh melakukan absensi pulang.');
        }
    }

    public function riwayat(){
        $data['riwayat'] = Absensi::where('user_id', Auth::id())
                                    ->orderBy('start_at', 'desc')
                                    ->get();
        // dd($data); 
        return view('karyawan.riwayat')->with($data);
    }

    public function indexadmin(Request $request){
        $data['karyawan'] = Absensi::join('users', 'absensi.user_id', '=', 'users.id')
                                    ->when($request->user_id != null, function ($q) use ($request){
                                        return $q->where('users.id', $request->user_id);
                                    })
                                    ->when($request->month != null, function ($q) use ($request){
                                        return $q->whereMonth('absensi.start_at', $request->month);
                                    })
                                    ->get();
        $data['user'] = User::orderBy('name')->get();
        // dd($data['karyawan']);
        return view('admin.index')->with($data);
    }

    public function pengajuan(Request $request){
        $data['karyawan'] = Absensi::select('absensi.id as absensiid', 'absensi.*', 'users.*')
                                    ->join('users', 'absensi.user_id', '=', 'users.id')
                                    ->where('absensi.is_active', 0)
                                    ->get();
        // dd(date('Y-m-d H:i:s'));
        return view('admin.pengajuan')->with($data);
    }

    public function izinkan(Request $request){

        Absensi::where('id', $request->id)
            ->update([
                'is_active' => 1,
                'end_at' => date('Y-m-d H:i:s')
            ]);

        return Redirect::back()->with('success', 'Anda telah mengizinkan.');
    }

    public function tolak(Request $request){

        Absensi::where('id', $request->id)
            ->update([
                'status' => 'alpha',
                'is_active' => 1,
                'end_at' => date('Y-m-d H:i:s')
            ]);

        return Redirect::back()->with('success', 'Anda telah menolak.');
    }
}
