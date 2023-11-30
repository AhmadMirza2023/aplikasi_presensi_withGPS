<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function proseslogin(Request $request)
    {
        if (Auth::guard('karyawan')->attempt(['nik' => $request->nik, 'password' => $request->password])) {
            return redirect('/panel2/dashboard');
        } else {
            return redirect('/panel2')->with(['warning' => 'Nik / password salah']);
        }
    }
    public function proseslogout(Request $request)
    {
        if (Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
            return redirect('/panel2');
        }
    }

    public function proseslogoutadmin(Request $request)
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect('/panel1');
        } 
    }
    public function prosesloginadmin(Request $request)
    {
        if (Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect('/panel1/dashboardAdmin');
        } else {
            return redirect('/panel1')->with(['warning' => 'Email / Password salah']);
        }
    }
}
