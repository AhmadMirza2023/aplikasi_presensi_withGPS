<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $nik = Auth::guard("karyawan")->user()->nik;
        $hariIni = date("Y-m-d");
        $bulanIni = date('m');
        $tahunIni = date('Y');
        $presensiHariIni = DB::table("presensi")->where('nik', $nik)->where('tgl_presensi', $hariIni)->first();
        $historiBulanIni = DB::table('presensi')
        ->where('nik', $nik)
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanIni . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunIni .'"')
        ->orderBy('tgl_presensi')
        ->get();

        $rekapPresensi = DB::table('presensi')
        ->selectRaw('COUNT(nik) as jmlHadir, SUM(IF(jam_in > "07:00",1,0)) as jmlTelat')
        ->where('nik', $nik)
        ->whereRaw('MONTH(tgl_presensi)="' . $bulanIni . '"')
        ->whereRaw('YEAR(tgl_presensi)="' . $tahunIni .'"')
        ->first();

        $leaderboard = DB::table('presensi')
        ->join('karyawan','presensi.nik','=','karyawan.nik')
        ->where('tgl_presensi', $hariIni)
        ->orderBy('jam_in')
        ->get();

        $namaBulanIni = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desmeber'];

        $rekapIzin = DB::table('perizinan')
        ->selectRaw('SUM(IF(status="i"&&status_approved=1,1,0)) as jmlIzin, SUM(IF(status="s"&&status_approved=1,1,0)) as jmlSakit')
        ->where('nik', $nik)
        ->whereRaw('MONTH(tgl_izin)="' . $bulanIni . '"')
        ->whereRaw('YEAR(tgl_izin)="' . $tahunIni .'"')
        ->first();

        return view('dashboard.dashboard', compact('presensiHariIni', 'historiBulanIni', 'namaBulanIni', 'bulanIni', 'tahunIni', 'rekapPresensi', 'leaderboard', 'rekapIzin'));
    }

    public function dashboardAdmin()
    {
        $hariIni = date('Y-m-d');
        $rekapPresensi = DB::table('presensi')
        ->selectRaw('COUNT(nik) as jmlHadir, SUM(IF(jam_in > "07:00",1,0)) as jmlTelat')
        ->where('tgl_presensi', $hariIni)
        ->first();

        $rekapIzin = DB::table('perizinan')
        ->selectRaw('SUM(IF(status="i",1,0)) as jmlIzin, SUM(IF(status="s",1,0)) as jmlSakit')
        ->where('tgl_izin', $hariIni)
        ->where('status_approved', 1)
        ->first();
        return view('dashboard.dashboardAdmin', compact('rekapPresensi', 'rekapIzin'));
    }
}
