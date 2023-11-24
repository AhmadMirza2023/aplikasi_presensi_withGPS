<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class PresensiController extends Controller
{
    public function create()
    {
        $hariIni = date('Y-m-d');
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariIni)->where('nik', $nik)->count();
        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date('Y-m-d');
        $jam = date('H:i:s');

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();
        if ($cek > 0) {
            $ket = "out";
        } else {
            $ket = "in";
        };

        $lokasi = $request->lokasi;
        $latitudeKantor = -7.2891713;
        $longitudeKantor = 112.7989571;
        $lokasiUser = explode(',', $lokasi);
        $latitudeUser = $lokasiUser[0];
        $longitudeUser = $lokasiUser[1];
        $jarak = $this->distance($latitudeKantor, $longitudeKantor, $latitudeUser, $longitudeUser);
        $radius = round($jarak['meters']);

        $image = $request->image;
        $folderPath = 'public/uploads/absensi/';
        $formatName = $nik . '-' . $tgl_presensi . '-' . $ket;
        $image_parts = explode(';base64', $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . '.png';
        $file = $folderPath . $fileName;

        if ($radius > 20) {
            echo 'error|Maaf anda berada di luar radius, jarak anda dengan radius kantor adalah ' . $radius . ' m';
        } else {
            if ($cek > 0) {
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi,
                ];
                $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                if ($update) {
                    echo 'success|Hati-hati di jalan!|out';
                    Storage::put($file, $image_base64);
                } else {
                    echo 'error|Oops..gagal!|out';
                }
            } else {
                $data = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi,
                ];
                $simpan = DB::table('presensi')->insert($data);
                if ($simpan) {
                    echo 'success|Semangat bekerja!|in';
                    Storage::put($file, $image_base64);
                } else {
                    echo 'error|Oops..gagal!|in';
                }
            }
        }
    }

    // Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editProfile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('presensi.editProfile', compact('karyawan'));
    }

    public function updateProfile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $foto = $request->foto;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();

        if ($request->hasFile('foto')) {
            $foto = $nik . '.' . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        if (empty($password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto,
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto,
            ];
        }

        $update = DB::table('karyawan')->where('nik', $nik)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = 'public/uploads/karyawan';
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data berhasil di update']);
        } else {
            return Redirect::back()->with(['error'=> 'Data tidak berhasil di update']);
        }
    }

    public function histori()
    {
        $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        return view('presensi.histori', compact('namaBulan'));
    }

    public function getHistori(Request $request) {
        $nik = Auth::guard('karyawan')->user()->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $histori = DB::table('presensi')
        ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
        ->where('nik', $nik)
        ->orderBy('tgl_presensi')
        ->get();
        
        return view('presensi.getHistori', compact('histori'));
    }

    public function izin()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $dataIzin = DB::table('perizinan')->where('nik', $nik)->get();
        return view('presensi.izin', compact('dataIzin'));
    }

    public function buatIzin()
    {
        return view('presensi.buatIzin');
    }

    public function storeIzin(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin'=> $tgl_izin,
            'status'=> $status,
            'keterangan'=> $keterangan
        ];

        $simpan = DB::table('perizinan')->insert($data);

        if ($simpan) {
            return redirect('/izin')->with(['success' => 'Data berhasil disimpan']);
        } else {
            return redirect('/izin')->with(['error' => 'Data tidak berhasil disimpan']);
        }
    }
}
