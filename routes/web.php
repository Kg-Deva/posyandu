<?php

use App\Http\Controllers\ContoController;
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



Route::get('/', [LoginController::class, 'index'])->name('login');
// Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/postlogin', [LoginController::class, 'postlogin'])->name('postlogin');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::resource('/conto', ContoController::class);


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/


// Route::group(['middleware' => ['auth', 'ceklevel:admin,kader', 'redirectrole:admin,kader']], function () {
//     Route::get('/tambah-anggota', [MenuController::class, 'tambahanggota'])->name('tambah-anggota');
//     Route::post('/simpan-anggota', [MenuController::class, 'simpananggota'])->name('simpan-anggota');
// });
Route::group(['middleware' => ['auth', 'ceklevel:admin', 'redirectrole:admin']], function () {

    Route::get('/dashboard', [MenuController::class, 'dashboard'])->name('dashboard');
    Route::get('/tambah-anggota', [MenuController::class, 'tambahanggota'])->name('tambah-anggota');
    Route::post('/simpan-anggota', [MenuController::class, 'simpananggota'])->name('simpan-anggota');
    Route::get('/edit-anggota/{id}', [MenuController::class, 'editanggota'])->name('edit-anggota');
    Route::post('/update-anggota/{id}', [MenuController::class, 'updateanggota'])->name('update-anggota');
    Route::get('/delete-anggota/{id}', [MenuController::class, 'destroy'])->name('delete-anggota');

    // Profil

    Route::get('/profil', [MenuController::class, 'profil'])->name('profil');
    Route::get('/tambah-profil', [MenuController::class, 'tambahprofil'])->name('tambah-profil');
    Route::post('/simpan-profil', [MenuController::class, 'simpanprofil'])->name('simpan-profil');
    Route::get('/edit-profil/{id}', [MenuController::class, 'editprofil'])->name('edit-profil');
    Route::post('/update-profil/{id}', [MenuController::class, 'updateprofil'])->name('update-profil');
    Route::get('/delete-profil/{id}', [MenuController::class, 'destroyprofil'])->name('delete-profil');


    // Profil Pengajar

    Route::get('/admin/profil-pengajar', [MenuController::class, 'pengajar'])->name('pengajar');
    Route::get('/tambah-profilpengajar', [MenuController::class, 'tambahpengajar'])->name('tambah-profilpengajar');
    Route::post('/simpan-profilpengajar', [MenuController::class, 'simpanprofilpengajar'])->name('simpan-profilpengajar');
    Route::get('/edit-profilpengajar/{id}', [MenuController::class, 'editprofilpengajar'])->name('edit-profilpengajar');
    Route::post('/update-profilpengajar/{id}', [MenuController::class, 'updateprofilpengajar'])->name('update-profilpengajar');
    Route::get('/delete-profilpengajar/{id}', [MenuController::class, 'destroyprofilpengajar'])->name('delete-profilpengajar');


    // Gallery

    Route::get('/gallery-item', [MenuController::class, 'galleryitem'])->name('gallery-item');
    Route::get('/tambah-gallery', [MenuController::class, 'tambahgallery'])->name('tambah-gallery');
    Route::post('/simpan-gallery', [MenuController::class, 'simpangallery'])->name('simpan-gallery');
    Route::get('/edit-gallery/{id}', [MenuController::class, 'editgallery'])->name('edit-gallery');
    Route::post('/update-gallery/{id}', [MenuController::class, 'updategallery'])->name('update-gallery');
    Route::get('/delete-gallery/{id}', [MenuController::class, 'destroygallery'])->name('delete-gallery');

    // Struktur Organisasi

    Route::get('/admin/struktur-organisasi', [MenuController::class, 'struktur'])->name('struktur');
    Route::get('/tambah-struktur', [MenuController::class, 'tambahstruktur'])->name('tambah-struktur');
    Route::post('/simpan-struktur', [MenuController::class, 'simpanstruktur'])->name('simpan-struktur');
    Route::get('/edit-struktur/{id}', [MenuController::class, 'editstruktur'])->name('edit-struktur');
    Route::post('/update-struktur/{id}', [MenuController::class, 'updatestruktur'])->name('update-struktur');
    Route::get('/delete-struktur/{id}', [MenuController::class, 'destroystruktur'])->name('delete-struktur');



    //Berita


    Route::get('/admin/berita', [MenuController::class, 'berita'])->name('berita');
    Route::get('/tambah-berita', [MenuController::class, 'tambahberita'])->name('tambah-berita');
    Route::post('/simpan-berita', [MenuController::class, 'simpanberita'])->name('simpan-berita');
    Route::get('/edit-berita/{id}', [MenuController::class, 'editberita'])->name('edit-berita');
    Route::post('/update-berita/{id}', [MenuController::class, 'updateberita'])->name('update-berita');
    Route::get('/delete-berita/{id}', [MenuController::class, 'destroyberita'])->name('delete-berita');



    //Berita


    Route::get('/admin/agenda', [MenuController::class, 'agenda'])->name('agenda');
    Route::get('/tambah-agenda', [MenuController::class, 'tambahagenda'])->name('tambah-agenda');
    Route::post('/simpan-agenda', [MenuController::class, 'simpanagenda'])->name('simpan-agenda');
    Route::get('/edit-agenda/{id}', [MenuController::class, 'editagenda'])->name('edit-agenda');
    Route::post('/update-agenda/{id}', [MenuController::class, 'updateagenda'])->name('update-agenda');
    Route::get('/delete-agenda/{id}', [MenuController::class, 'destroyagenda'])->name('delete-agenda');


    //Kontak
    Route::get('/kontak', [MenuController::class, 'kontak'])->name('kontak');
    Route::get('/tambah-kontak', [MenuController::class, 'tambahkontak'])->name('tambah-kontak');
    Route::post('/simpan-kontak', [MenuController::class, 'simpankontak'])->name('simpan-kontak');
    Route::get('/edit-kontak/{id}', [MenuController::class, 'editkontak'])->name('edit-kontak');
    Route::post('/update-kontak/{id}', [MenuController::class, 'updatekontak'])->name('update-kontak');
    Route::get('/delete-kontak/{id}', [MenuController::class, 'destroykontak'])->name('delete-kontak');



    //  Program
    Route::get('/program', [MenuController::class, 'program'])->name('program');
    Route::get('/tambah-program', [MenuController::class, 'tambahprogram'])->name('tambah-program');
    Route::post('/simpan-program', [MenuController::class, 'simpanprogram'])->name('simpan-program');
    Route::get('/edit-program/{id}', [MenuController::class, 'editprogram'])->name('edit-program');
    Route::post('/update-program/{id}', [MenuController::class, 'updateprogram'])->name('update-program');
    Route::get('/delete-program/{id}', [MenuController::class, 'destroyprogram'])->name('delete-program');



    //  ekstra
    Route::get('/ekstra', [MenuController::class, 'ekstra'])->name('ekstra');
    Route::get('/tambah-ekstra', [MenuController::class, 'tambahekstra'])->name('tambah-ekstra');
    Route::post('/simpan-ekstra', [MenuController::class, 'simpanekstra'])->name('simpan-ekstra');
    Route::get('/edit-ekstra/{id}', [MenuController::class, 'editekstra'])->name('edit-ekstra');
    Route::post('/update-ekstra/{id}', [MenuController::class, 'updateekstra'])->name('update-ekstra');
    Route::get('/delete-ekstra/{id}', [MenuController::class, 'destroyekstra'])->name('delete-ekstra');



    // kritik dan saran

    Route::get('/admin-kritik', [MenuController::class, 'kritikadmin'])->name('admin-kritik');
    Route::get('/delete-kritik/{id}', [MenuController::class, 'deletekritik'])->name('delete-kritik');
});

Route::get('/search-anggota', [MenuController::class, 'searchAnggota'])->name('search-anggota');

Route::group(['middleware' => ['auth', 'ceklevel:kader', 'redirectrole:kader']], function () {
    Route::get('/lengkapi-data/{id}', [MenuController::class, 'formLengkapiData']);
    Route::post('/lengkapi-data/{id}', [MenuController::class, 'simpanLengkapiData']);

    Route::get('/kader-home', [MenuController::class, 'kaderHome'])->name('kader-home');
    Route::get('/tambah-pasien', [MenuController::class, 'tambahpasien'])->name('tambah-pasien');
    Route::post('/simpan-pasien', [MenuController::class, 'simpanpasien'])->name('simpan-pasien');
    Route::get('/edit-pasien/{id}', [MenuController::class, 'editpasien'])->name('edit-pasien');
    Route::post('/update-pasien/{id}', [MenuController::class, 'updatepasien'])->name('update-pasien');
    Route::get('/delete-pasien/{id}', [MenuController::class, 'destroy'])->name('delete-pasien');

    // input pemeriksaan
    Route::get('/input-pemeriksaan', [MenuController::class, 'inputPemeriksaan'])->name('input-pemeriksaan');
    Route::post('/cari-pasien', [MenuController::class, 'cariPasien'])->name('cari-pasien');
    Route::post('/simpan-pemeriksaan/{id}', [MenuController::class, 'simpanPemeriksaan'])->name('simpan-pemeriksaan');
    // input pemeriksaan balita
    Route::post('/simpan-pemeriksaan-balita', [MenuController::class, 'simpanPemeriksaanBalita'])->name('simpan-pemeriksaan-balita');
    Route::post('/cek-bb-terakhir', [MenuController::class, 'cekBBTerakhir']);
    Route::get('/get-last-examination/{nik}', [MenuController::class, 'getLastExamination']);
});

Route::group(['middleware' => ['auth', 'ceklevel:balita', 'redirectrole:balita']], function () {
    Route::get('/balita-home', [MenuController::class, 'balitaHome'])->name('balita-home');
});

Route::group(['middleware' => ['auth', 'ceklevel:remaja', 'redirectrole:remaja']], function () {
    Route::get('/remaja-home', [MenuController::class, 'remajaHome'])->name('remaja-home');
});

Route::group(['middleware' => ['auth', 'ceklevel:dewasa', 'redirectrole:dewasa']], function () {
    Route::get('/dewasa-home', [MenuController::class, 'dewasaHome'])->name('dewasa-home');
});

Route::group(['middleware' => ['auth', 'ceklevel:ibu hamil', 'redirectrole:ibu hamil']], function () {
    Route::get('/ibu-hamil-home', [MenuController::class, 'ibuHamilHome'])->name('ibu-hamil-home');
});

Route::group(['middleware' => ['auth', 'ceklevel:lansia', 'redirectrole:lansia']], function () {
    Route::get('/lansia-home', [MenuController::class, 'lansiaHome'])->name('lansia-home');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
