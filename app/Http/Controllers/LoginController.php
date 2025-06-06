<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- Tambahkan ini
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        // Ambil pesan role_changed jika ada, lalu hapus agar tidak muncul terus
        $roleChanged = session('role_changed');
        if ($roleChanged) {
            session()->forget('role_changed');
        }
        return view('login', compact('roleChanged'));
    }

    

    public function postlogin(Request $request) 
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return redirect()->back()->withInput()->with('error', 'Login Gagal, periksa kembali email dan password Anda.');
    }

    if ($user->status != 1) {
        return redirect()->back()->with('error', 'Maaf, akun anda tidak dapat login karena dinonaktifkan admin.');
    }

    Auth::login($user);

    // Redirect sesuai role
    switch ($user->level) {
        case 'admin':
            return redirect('/dashboard')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
        case 'kader':
          
            return redirect('/kader-home')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
        case 'balita':
            return redirect('/balita-home')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
        case 'remaja':
            return redirect('/remaja-home')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
        case 'dewasa':
            return redirect('/dewasa-home')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
        case 'ibu hamil':
            return redirect('/ibu-hamil-home')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
        case 'lansia':
            return redirect('/lansia-home')->with('success', 'Login Berhasil, Selamat datang ' . $user->name . '!');
        default:
            return redirect('/')->with('error', 'Role tidak dikenali.');
    }
}
    
    public function logout() {
        Auth::logout();
        return redirect('/');
    }

    public function authenticate(Request $request)
{
    $credentials = $request->only('email', 'password');

    // Cek user dengan status aktif
    // $user = \App\Models\User::where('email', $request->email)->first();
    
    $user = User::where('email', $request->email)->first();
    if (!$user || $user->status != 1) {
        return back()->withErrors(['email' => 'Akun tidak aktif atau tidak ditemukan.']);
    }

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}
}
