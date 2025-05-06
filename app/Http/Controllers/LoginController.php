<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    // public function postlogin(Request $request) {
    //     if(Auth::attempt($request->only('email','password'))) {
    //         return redirect('/dashboard')->with('success', 'Login Berhasil');
    //     }
    //     return redirect('/login')->with('error', 'Login Gagal');
    // }

    public function postlogin(Request $request) 
{
    // $user = Auth::User();
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // if (Auth::attempt($request->only('email', 'password'))) {
    //     // return redirect('/dashboard')->with('success', 'Login Berhasil');
    //     return redirect('/dashboard')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
    // }

    if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::User(); // Ambil data user yang berhasil login
        return redirect('/dashboard')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
    }

    return redirect()->back()->withInput()->with('error', 'Login Gagal, periksa kembali email dan password Anda.');
}

    
    public function logout() {
        Auth::logout();
        return redirect('/login-lpq');
    }
    

   
}
