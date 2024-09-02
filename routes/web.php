<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/profil-tpq', function () {
    return view('profil-tpq');
});

Route::get('/profil-guru', function () {
    return view('profil-guru');
});

Route::get('/gallery', function () {
    return view('gallery');
});

Route::get('/form-pengaduan', function () {
    return view('pengaduan');
});

Route::get('/berita', function () {
    return view('berita');
});

Route::get('/agenda', function () {
    return view('agenda');
});

// ADMIN

// Route::get('/login-tpq', function () {
//     return view('login');
// });

Route::get('/login-lpq', [LoginController::class, 'index'])->name('login-lpq');
Route::get('/postlogin', [LoginController::class, 'postlogin'])->name('postlogin');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [MenuController::class, 'dashboard'])->name('dashboard');
Route::get('/beranda', [MenuController::class, 'beranda'])->name('beranda');

Route::get('/tambah-beranda', [MenuController::class, 'tambahberanda'])->name('tambah-beranda');


// Route::get('/dashboard', function () {
//     return view('admin-page.dashboard');
// });

