<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuImage;
use App\Models\User;
use App\Models\Beranda;
use App\Models\Profil;
use App\Models\ProfilPengajar;
use App\Models\Gallery;
use App\Models\Struktur;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\Kontak;
use App\Models\Program;
use App\Models\Ekstra;
use App\Models\kritiksaran;


use Symfony\Contracts\Service\Attribute\Required;


class MenuController extends Controller
{

    /*
|--------------------------------------------------------------------------
| Start User
|--------------------------------------------------------------------------
*/
    public function home()
    {
        
        $galleries = Gallery::latest()->take(6)->get(); // Ambil 6 galeri terbaru
        // $beritas = Berita::latest()->take(4)->get();       // Berita terbaru
        $agendas = Agenda::orderBy('tanggal', 'desc')->take(4)->get(); // Urutkan berdasarkan tanggal terbaru
        $kontaks = Kontak::first();  
        $beritas = Berita::orderBy('tanggal', 'desc')->take(4)->get(); // Urutkan berdasarkan tanggal terbaru
        $berandas = Beranda::first();                 // Semua peraturan
    
        return view('home', compact('galleries', 'beritas', 'berandas', 'agendas', 'kontaks'));
    }

    public function beritauser()
    {
        
        $berandas = Beranda::first();                 // Semua peraturan
        $beritas = Berita::latest()->paginate(4); // Pagination, 4 berita per halaman
        $kontaks = Kontak::first();  
        return view('berita', compact( 'beritas','berandas','kontaks'));
    }


    public function kritiksaran()
    {
        
        $berandas = Beranda::first();                 // Semua peraturan
        $beritas = Berita::latest()->paginate(4); // Pagination, 4 berita per halaman
        $kontaks = Kontak::first();  
        return view('kritik-saran', compact( 'beritas','berandas','kontaks'));
    }


    public function lpquser()
    {
        $berandas = Beranda::first();     
        $lpq = Profil::first();  // Ambil hanya satu data profil
        $kontaks = Kontak::first();  
        return view('profil-lpq', compact('lpq', 'berandas', 'kontaks'));
    }
    


    public function pengajaruser()
    {
        $kontaks = Kontak::first();  
        $berandas = Beranda::first();                 // Semua peraturan
        $pengajar = ProfilPengajar::paginate(6); // Menampilkan 6 data per halaman
        return view('profil-pengajar', compact( 'pengajar','berandas','kontaks'));
    }


    public function strukturuser()
    {
        
        $berandas = Beranda::first();                 // Semua peraturan
        $strk = Struktur::all();
        $kontaks = Kontak::first();  
        return view('struktur', compact( 'strk','berandas', 'kontaks'));
    }


    public function galleryuser()
    {
        $kontaks = Kontak::first();  
        $berandas = Beranda::first();                 // Semua peraturan
        $data = Gallery::paginate(6); // Menampilkan 6 gambar per halaman
        return view('gallery', compact( 'data','berandas', 'kontaks'));
    }

    public function agendauser()
    {
        $kontaks = Kontak::first();  
        $berandas = Beranda::first();                 // Semua peraturan
        $agendas = Agenda::all();     
        return view('agenda', compact( 'berandas','agendas', 'kontaks'));
    }

    public function programuser()
    {
        $kontaks = Kontak::first();  
        $berandas = Beranda::first();                 // Semua peraturan
        $program = Program::all();   
        $ekstra = Ekstra::all();    
        return view('program', compact( 'berandas','program','kontaks','ekstra'));
    }


    public function beritashow($id)
    {
        $berita = Berita::findOrFail($id);
        $berandas = Beranda::first();                 // Semua peraturan
        $kontaks = Kontak::first();  
        // Kirim data berita ke view
        return view('berita-show', compact('berandas','berita', 'kontaks'));
    }

    public function agendashow($id)
    {
        $agenda = Agenda::findOrFail($id);
        $berandas = Beranda::first();                 // Semua peraturan
        $kontaks = Kontak::first();  
        // Kirim data agenda ke view
        return view('agenda-show', compact('berandas','agenda', 'kontaks'));
    }

    /*
|--------------------------------------------------------------------------
| End User
|--------------------------------------------------------------------------
*/

    public function dashboard()
    {
        // return view('admin-page.dashboard');

        $data = User::all();

        // Menghitung jumlah pengguna
    // $anggotaCount = $data->count();

    // Data lainnya
    $beritaCount = Berita::count();
    $agendaCount = Agenda::count();
    $galleryCount = Gallery::count();
    $kritikCount = kritiksaran::count();

        return view('admin-page.dashboard', compact('data', 'galleryCount', 'kritikCount', 'agendaCount', 'beritaCount'));
    }

    public function tambahanggota()
    {
        return view('actions-admin.tambah-anggota');
    }
    public function simpananggota(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'level' => 'adminkonten'
        ]);

        return redirect()->route('dashboard')->with('success', 'Anggota berhasil ditambahkan!');
        // return redirect()->back()->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function editanggota($id)
    {
        $data = User::findorfail($id);
        return view('actions-admin.edit-anggota', compact('data'));
        // return view('admin.edit', ['No' => $data]);
    }

    public function updateanggota(Request $request, $id)
    {
        $data = User::findOrFail($id);

        // Validasi data yang diterima dari form, tanpa password
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Mengabaikan email yang sama untuk ID saat ini
        ]);

        // Jangan update password, cukup validasi dan update data lainnya
        // Pastikan level tetap sama dengan data sebelumnya
        $validateData['level'] = $data->level;

        // Update data anggota tanpa mengubah password
        $data->update($validateData);

        // Redirect ke halaman profil atau halaman lain dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = User::find($id);
        if (!$data) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        $name = $data->name; // Ambil nama sebelum data dihapus
        $data->delete();

        return back()->with('success', "Data dengan nama $name berhasil dihapus");
    }



    /*
|--------------------------------------------------------------------------
| Beranda
|--------------------------------------------------------------------------
*/



    public function beranda()
    {
        $data = Beranda::all();
        return view('admin-page.beranda', compact('data'));
    }
    public function tambahberanda()
    {
        return view('actions-admin.tambah-beranda');
    }

    // public function simpanBeranda(Request $request)
    // {
    //     // dd($request->all());

    //     // Validasi input dari form
    //     $request->validate([
    //         'tagline' => 'required|max:255',
    //         'deskripsi' => 'required',
    //         'gambar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
    //         'maps' => 'nullable'  // Menjadikan maps opsional
    //     ]);

    //     // Menyimpan gambar
    //     $gambarPath = $request->file('gambar')->store('images', 'public');

    //     // Menyimpan data ke database
    //     Beranda::create([
    //         'tagline' => $request->tagline,
    //         'deskripsi' => $request->deskripsi,
    //         'gambar' => $gambarPath,
    //         'maps' => $request->maps ?? '',  // Menetapkan nilai default jika maps kosong
    //     ]);

    //     // Redirect dengan pesan sukses
    //     return redirect()->route('beranda')->with('success', 'Beranda berhasil ditambahkan');
    // }

    public function simpanBeranda(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'tagline' => 'required|max:255',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'maps' => 'nullable'
        ]);
    
        // Hapus semua data lama sebelum menambah yang baru
        $oldData = Beranda::all();
        foreach ($oldData as $data) {
            // Hapus gambar dari storage
            if (file_exists(public_path('storage/' . $data->gambar))) {
                unlink(public_path('storage/' . $data->gambar));
            }
            // Hapus data dari database
            $data->delete();
        }
    
        // Parsing link embed Google Maps
        $mapsLink = $this->extractIframeSrc($request->maps);
    
        // Simpan gambar baru
        $gambarPath = $request->file('gambar')->store('images', 'public');
    
        // Simpan data baru
        Beranda::create([
            'tagline' => $request->tagline,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
            'maps' => $mapsLink,
        ]);
    
        return redirect()->route('beranda')->with('success', 'Beranda berhasil diperbarui dengan data baru');
    }
    

    public function editberanda($id)
    {
        $data = Beranda::findorfail($id);
        return view('actions-admin.edit-beranda', compact('data'));
        // return view('admin.edit', ['No' => $data]);
    }

    // public function updateberanda(Request $request, $id)
    // {
    //     // Validasi input dari form
    //     $request->validate([
    //         'tagline' => 'required|max:255',
    //         'deskripsi' => 'required',
    //         'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',  // Gambar opsional
    //         'maps' => 'nullable', // Validasi URL untuk maps
    //     ]);

    //     // Ambil data beranda berdasarkan ID
    //     $beranda = Beranda::findOrFail($id);

    //     // Menangani upload gambar jika ada
    //     if ($request->hasFile('gambar')) {
    //         // Hapus gambar lama jika ada
    //         if (file_exists(public_path('storage/' . $beranda->gambar))) {
    //             unlink(public_path('storage/' . $beranda->gambar));
    //         }

    //         // Simpan gambar baru
    //         $gambarPath = $request->file('gambar')->store('images', 'public');
    //         $beranda->gambar = $gambarPath;
    //     }

    //     // Perbarui data beranda
    //     $beranda->tagline = $request->tagline;
    //     $beranda->deskripsi = $request->deskripsi;
    //     $beranda->maps = $request->maps ?? '';  // Menetapkan nilai default jika maps kosong

    //     // Simpan perubahan
    //     $beranda->save();

    //     // Redirect dengan pesan sukses
    //     return redirect()->route('beranda')->with('success', 'Beranda berhasil diperbarui');
    // }

    public function updateberanda(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'tagline' => 'required|max:255',
            'deskripsi' => 'required',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'maps' => 'nullable',
        ]);
    
        // Ambil data beranda berdasarkan ID
        $beranda = Beranda::findOrFail($id);
    
        // Menangani upload gambar jika ada
        if ($request->hasFile('gambar')) {
            if (file_exists(public_path('storage/' . $beranda->gambar))) {
                unlink(public_path('storage/' . $beranda->gambar));
            }
    
            $gambarPath = $request->file('gambar')->store('images', 'public');
            $beranda->gambar = $gambarPath;
        }
    
        // Parsing link embed Google Maps
        $mapsLink = $this->extractIframeSrc($request->maps);
    
        // Perbarui data beranda
        $beranda->tagline = $request->tagline;
        $beranda->deskripsi = $request->deskripsi;
        $beranda->maps = $mapsLink;
    
        // Simpan perubahan
        $beranda->save();
    
        return redirect()->route('beranda')->with('success', 'Beranda berhasil diperbarui');
    }
    

    public function destroyberanda($id)
    {

        $beranda = Beranda::findOrFail($id);

        // Hapus gambar yang terkait jika ada
        if (file_exists(public_path('storage/' . $beranda->gambar))) {
            unlink(public_path('storage/' . $beranda->gambar));
        }

        // Hapus data beranda
        $beranda->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('beranda')->with('success', 'Data Beranda berhasil dihapus');
    }

    /**
 * Fungsi untuk ekstrak src dari embed iframe Google Maps
 */
private function extractIframeSrc($iframe)
{
    // Cek apakah input mengandung tag iframe
    if (strpos($iframe, '<iframe') !== false) {
        preg_match('/src="([^"]+)"/', $iframe, $matches);
        return $matches[1] ?? $iframe;
    }

    // Jika user langsung paste link src tanpa iframe
    return $iframe;
}


    /*
|--------------------------------------------------------------------------
|   Profil LPQ
|--------------------------------------------------------------------------
*/

    public function profil()
    {
        $data = Profil::all();
        return view('admin-page.profil', compact('data'));
    }


    public function tambahprofil()
    {
        return view('actions-admin.tambah-profil');
    }

    public function simpanprofil(Request $request)
{
    // Validasi input dari form
    $request->validate([
        'tujuan' => 'required|string|max:2000',
        'visi' => 'required|string|max:2000',
        'misi' => 'required|string|max:2000',
        'gambar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    // Cek apakah sudah ada data profil
    $profil = Profil::first();

    // Menyimpan gambar baru
    $gambarPath = null;
    if ($request->hasFile('gambar')) {
        $gambarPath = $request->file('gambar')->store('images', 'public');

        // Hapus gambar lama jika ada
        if ($profil && $profil->gambar && file_exists(public_path('storage/' . $profil->gambar))) {
            unlink(public_path('storage/' . $profil->gambar));
        }
    }

    if ($profil) {
        // Update data profil yang sudah ada
        $profil->update([
            'tujuan' => $request->tujuan,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'gambar' => $gambarPath ?? $profil->gambar, // Tetap pakai gambar lama kalau gak upload baru
        ]);
    } else {
        // Buat data baru kalau belum ada
        Profil::create([
            'tujuan' => $request->tujuan,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'gambar' => $gambarPath,
        ]);
    }

    // Redirect dengan pesan sukses
    return redirect()->route('profil')->with('success', 'Profil berhasil disimpan!');
}


    public function editprofil($id)
    {
        $data = Profil::findOrFail($id);
        return view('actions-admin.edit-Profil', compact('data'));
    }

    public function updateprofil(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'tujuan' => 'required',  // tujuan wajib diisi
            'visi' => 'required|max:1000', // Visi wajib diisi, maksimal 255 karakter
            'misi' => 'required',    // Misi wajib diisi
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',  // Gambar opsional
        ]);

        // Ambil data profil berdasarkan ID
        $profil = Profil::findOrFail($id);

        // Menangani upload gambar jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if (file_exists(public_path('storage/' . $profil->gambar))) {
                unlink(public_path('storage/' . $profil->gambar));
            }

            // Simpan gambar baru
            $gambarPath = $request->file('gambar')->store('images', 'public');
            $profil->gambar = $gambarPath;
        }

        // Perbarui data profil
        $profil->tujuan = $request->tujuan;
        $profil->visi = $request->visi;
        $profil->misi = $request->misi;

        // Simpan perubahan
        $profil->save();

        // Redirect dengan pesan sukses
        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui');
    }

    public function destroyprofil($id)
    {
        $profil = Profil::findOrFail($id);

        // Hapus gambar yang terkait jika ada
        if (file_exists(public_path('storage/' . $profil->gambar))) {
            unlink(public_path('storage/' . $profil->gambar));
        }

        // Hapus data profil
        $profil->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('profil')->with('success', 'Data Profil berhasil dihapus');
    }



    /*
|--------------------------------------------------------------------------
|   Profil Pengajar
|--------------------------------------------------------------------------
*/


    public function pengajar()
    {
        $data = ProfilPengajar::all();
        return view('admin-page.profil-guru', compact('data'));
    }

    public function tambahpengajar()
    {
        return view('actions-admin.tambah-pengajar');
    }




    public function simpanprofilpengajar(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'pengajar' => 'required|string|max:255', // Nama pengajar wajib diisi
            'jabatan' => 'required|string|max:255',       // Jabatan wajib diisi
            'gambar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Gambar opsional
        ]);

        // Menyimpan gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('images/profil-pengajar', 'public');
        }

        // Menyimpan data ke database
        ProfilPengajar::create([
            'pengajar' => $request->pengajar,
            'jabatan' => $request->jabatan,
            'gambar' => $gambarPath,
        ]);

        // Redirect dengan pesan sukses

        return redirect()->route('pengajar')->with('success', 'Profil pengajar berhasil ditambahkan');
    }

    public function editprofilpengajar($id)
    {
        $data = ProfilPengajar::findOrFail($id);
        return view('actions-admin.edit-pengajar', compact('data'));
    }

    public function updateprofilpengajar(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'pengajar' => 'required|string|max:255', // Nama pengajar wajib diisi
            'jabatan' => 'required|string|max:255',       // Jabatan wajib diisi
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Gambar opsional
        ]);

        // Ambil data pengajar berdasarkan ID
        $profilPengajar = ProfilPengajar::findOrFail($id);

        // Menangani upload gambar jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if (file_exists(public_path('storage/' . $profilPengajar->gambar))) {
                unlink(public_path('storage/' . $profilPengajar->gambar));
            }

            // Simpan gambar baru
            $gambarPath = $request->file('gambar')->store('images/profil-pengajar', 'public');
            $profilPengajar->gambar = $gambarPath;
        }

        // Perbarui data pengajar
        $profilPengajar->pengajar = $request->pengajar;
        $profilPengajar->jabatan = $request->jabatan;

        // Simpan perubahan
        $profilPengajar->save();

        // Redirect dengan pesan sukses
        return redirect()->route('pengajar')->with('success', 'Profil pengajar berhasil diperbarui');
    }

    
    // public function updateprofilpengajar(Request $request, $id)
    // {
    //     dd($request->all()); // Debugging untuk cek data yang dikirim
    // }    


    public function destroyprofilpengajar($id)
    {
        $profilPengajar = ProfilPengajar::findOrFail($id);

        // Hapus gambar yang terkait jika ada
        if (file_exists(public_path('storage/' . $profilPengajar->gambar))) {
            unlink(public_path('storage/' . $profilPengajar->gambar));
        }

        // Hapus data pengajar
        $profilPengajar->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('pengajar')->with('success', 'Data profil pengajar berhasil dihapus');
    }





    /*
|--------------------------------------------------------------------------
|   Gallery
|--------------------------------------------------------------------------
*/

    public function galleryitem()
    {
        $data = Gallery::all();
        return view('admin-page.gallery', compact('data'));
    }

    public function tambahgallery()
    {
        return view('actions-admin.tambah-gallery');
    }

    public function simpangallery(Request $request)
    {
        // dd($request->all()); // Cek data yang dikirim dari form

        // Validasi input dari form
        $request->validate([
            'images' => 'required|array', // Harus berupa array gambar
            'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048', // Format dan ukuran gambar
        ]);
    
        // Periksa apakah file ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Simpan gambar ke storage
                $imagePath = $image->store('images/gallery', 'public');
    
                // Simpan path gambar ke database
                Gallery::create([
                    'gallery_item' => $imagePath,
                ]);
            }
    
            return redirect()->route('gallery-item')->with('success', 'Gambar berhasil ditambahkan!');
        }
    
        return redirect()->back()->with('error', 'Tidak ada gambar yang diunggah.');
    }
    

    // Menampilkan form edit gallery
    public function editgallery($id)
    {
        $data = Gallery::findOrFail($id);
        return view('actions-admin.edit-gallery', compact('data'));
    }


    
    // Memperbarui gallery
    public function updategallery(Request $request, $id)
    {
        // Cari data gallery berdasarkan ID
        $gallery = Gallery::findOrFail($id);

        // Cek apakah ada gambar baru yang diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama dari storage jika ada
            if (file_exists(public_path('storage/' . $gallery->gallery_item))) {
                unlink(public_path('storage/' . $gallery->gallery_item));
            }

            // Simpan gambar baru ke dalam storage
            $imagePath = $request->file('image')->store('galleries', 'public');
            $gallery->gallery_item = $imagePath; // Update path gambar di database
        }

        // Simpan perubahan data gallery ke database
        $gallery->save();

        // Redirect dengan pesan sukses
        return redirect()->route('gallery-item')->with('success', 'Gambar berhasil diupdate!');
    }

    // Menghapus gallery
    public function destroygallery($id)
    {
        // Cari data gallery berdasarkan ID
        $gallery = Gallery::findOrFail($id);

        // Cek apakah ada gambar yang terkait dengan gallery tersebut
        if ($gallery->gallery_item && file_exists(public_path('storage/' . $gallery->gallery_item))) {
            // Hapus gambar yang terkait
            unlink(public_path('storage/' . $gallery->gallery_item));
        }

        // Hapus data gallery
        $gallery->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('gallery-item')->with('success', 'Gambar berhasil dihapus');
    }







    /*
|--------------------------------------------------------------------------
|  Struktur Organisasi
|--------------------------------------------------------------------------
*/

public function struktur()
{
    $data = Struktur::all();
    return view('admin-page.struktur', compact('data'));
}

public function tambahstruktur()
{
    return view('actions-admin.tambah-struktur');
}

public function simpanstruktur(Request $request)
{
    $request->validate([
        'file_peraturan' => 'required|mimes:pdf|max:2048',  // Hanya menerima file PDF dengan ukuran maksimal 2MB
    ]);

    // Cek apakah sudah ada data sebelumnya
    $existingData = Struktur::first();
    
    if ($existingData) {
        // Hapus file lama jika ada
        if ($existingData->file_path && file_exists(storage_path('app/public/peraturan/' . $existingData->file_path))) {
            unlink(storage_path('app/public/peraturan/' . $existingData->file_path));
        }

        // Hapus data lama dari database
        $existingData->delete();
    }

    // Upload file baru
    $file = $request->file('file_peraturan');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $file->storeAs('public/peraturan', $filename);

    // Simpan data baru ke database
    Struktur::create([
        'file_path' => $filename,
    ]);

    return redirect()->route('struktur')->with('success', 'Struktur Organisasi berhasil diperbarui');
}

public function editstruktur($id)
{
    $data = Struktur::findOrFail($id);
    return view('actions-admin.edit-struktur', compact('data'));
}

public function updatestruktur(Request $request, $id)
{
    $request->validate([
        'file_peraturan' => 'required|mimes:pdf|max:2048',  // Hanya menerima file PDF dengan ukuran maksimal 2MB
    ]);

    $peraturan = Struktur::findOrFail($id);

    // Menghapus file lama jika ada
    if ($peraturan->file_path && file_exists(storage_path('app/public/peraturan/' . $peraturan->file_path))) {
        unlink(storage_path('app/public/peraturan/' . $peraturan->file_path));
    }

    // Mengupload file PDF baru
    $file = $request->file('file_peraturan');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $file->storeAs('public/peraturan', $filename);

    $peraturan->update([
        'file_path' => $filename,  // Update nama file di database
    ]);

    return redirect()->route('struktur')->with('success', 'Struktur Organisasi berhasil diperbarui');
}

public function destroystruktur($id)
{
    $peraturan = Struktur::findOrFail($id);

    // Menghapus file PDF dari storage
    if ($peraturan->file_path && file_exists(storage_path('app/public/peraturan/' . $peraturan->file_path))) {
        unlink(storage_path('app/public/peraturan/' . $peraturan->file_path));
    }

    $peraturan->delete();

    return redirect()->route('struktur')->with('success', 'Struktur Organisasi berhasil dihapus');
}


/*
|--------------------------------------------------------------------------
|   Berita
|--------------------------------------------------------------------------
*/

    public function berita()
    {
        $data = Berita::all();
        return view('admin-page.berita', compact('data'));
    }
    public function tambahberita()
    {
        return view('actions-admin.tambah-berita');
    }

    public function simpanberita(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string', // Ganti 'text' dengan 'string'
            'tanggal' => 'required|date',
            'penulis' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images'), $imageName);
        } else {
            $imageName = null;
        }
    
        // Simpan berita
        Berita::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'penulis' => $request->penulis,
            'gambar' => $imageName,
        ]);
    
        return redirect()->route('berita')->with('success', 'Berita berhasil ditambahkan!');
    }
    

    // Menampilkan form edit berita
    public function editberita($id)
    {
        $data = Berita::findOrFail($id);
        return view('actions-admin.edit-berita', compact('data'));
    }

    // Mengupdate berita
    public function updateberita(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',  // Ganti 'text' dengan 'string'
            'tanggal' => 'required|date',
            'penulis' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Temukan berita berdasarkan ID
        $berita = Berita::findOrFail($id);
    
        // Update gambar jika ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($berita->gambar) {
                $oldImagePath = public_path('images/' . $berita->gambar);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Menghapus gambar lama
                }
            }
    
            // Simpan gambar baru
            $imageName = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('images'), $imageName);
    
            // Update nama gambar pada database
            $berita->gambar = $imageName;
        }
    
        // Update berita
        $berita->judul = $request->judul;
        $berita->deskripsi = $request->deskripsi;
        $berita->tanggal = $request->tanggal;
        $berita->penulis = $request->penulis;
        $berita->save();
    
        // Redirect dengan pesan sukses
        return redirect()->route('berita')->with('success', 'Berita berhasil diupdate!');
    }
    

    // Menghapus berita
    public function destroyberita($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();
        
        return redirect()->route('berita')->with('success', 'Berita berhasil dihapus!');
    }

   /*
|--------------------------------------------------------------------------
|   Agenda
|--------------------------------------------------------------------------
*/

public function agenda()
{
    $data = Agenda::all();
    return view('admin-page.agenda', compact('data'));
}
public function tambahagenda()
{
    return view('actions-admin.tambah-agenda');
}

public function simpanagenda(Request $request)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string', // Ganti 'text' dengan 'string'
        'tanggal' => 'required|date',
        'penulis' => 'required|string|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
    ]);

    // Upload gambar jika ada
    if ($request->hasFile('gambar')) {
        $imageName = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('images'), $imageName);
    } else {
        $imageName = null;
    }

    // Simpan agenda
    Agenda::create([
        'judul' => $request->judul,
        'deskripsi' => $request->deskripsi,
        'tanggal' => $request->tanggal,
        'penulis' => $request->penulis,
        'gambar' => $imageName,
    ]);

    return redirect()->route('agenda')->with('success', 'Agenda berhasil ditambahkan!');
}


// Menampilkan form edit berita
public function editagenda($id)
{
    $data = Agenda::findOrFail($id);
    return view('actions-admin.edit-agenda', compact('data'));
}

// Mengupdate agenda
public function updateagenda(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',  // Ganti 'text' dengan 'string'
        'tanggal' => 'required|date',
        'penulis' => 'required|string|max:255',
        'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
    ]);

    // Temukan agenda berdasarkan ID
    $agenda = Agenda::findOrFail($id);

    // Update gambar jika ada
    if ($request->hasFile('gambar')) {
        // Hapus gambar lama jika ada
        if ($agenda->gambar) {
            $oldImagePath = public_path('images/' . $agenda->gambar);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath); // Menghapus gambar lama
            }
        }

        // Simpan gambar baru
        $imageName = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('images'), $imageName);

        // Update nama gambar pada database
        $agenda->gambar = $imageName;
    }

    // Update agenda
    $agenda->judul = $request->judul;
    $agenda->deskripsi = $request->deskripsi;
    $agenda->tanggal = $request->tanggal;
    $agenda->penulis = $request->penulis;
    $agenda->save();

    // Redirect dengan pesan sukses
    return redirect()->route('agenda')->with('success', 'Agenda berhasil diupdate!');
}


// Menghapus berita
public function destroyagenda($id)
{
    $berita = Agenda::findOrFail($id);
    $berita->delete();
    
    return redirect()->route('agenda')->with('success', 'Agenda berhasil dihapus!');
}






   /*
|--------------------------------------------------------------------------
|   Kontak
|--------------------------------------------------------------------------
*/

public function kontak()
{
    $data = Kontak::all();
    return view('admin-page.kontak', compact('data'));
    // return view('admin-page.kontak');

}

public function tambahkontak()
{
    return view('actions-admin.tambah-kontak');
}


public function simpankontak(Request $request)
{
    $request->validate([
        'whatsapp' => 'required',
        'email' => 'required|email',
        'no_telp' => 'required',
        'alamat' => 'required',
    ]);

    // Cek apakah sudah ada data kontak
    $kontak = Kontak::first();

    if ($kontak) {
        // Update data yang sudah ada
        $kontak->update($request->all());
    } else {
        // Buat data baru jika belum ada
        Kontak::create($request->all());
    }

    return redirect()->route('kontak')->with('success', 'Kontak berhasil diperbarui dengan data baru!');
}


    // Menampilkan form edit kontak
    public function editkontak($id)
    {
        $data = Kontak::findOrFail($id);
        return view('actions-admin.edit-kontak', compact('data'));
    }

    // Memperbarui kontak
    public function updatekontak(Request $request, $id)
    {
        $request->validate([
            'whatsapp' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
            'alamat' => 'required',
        ]);

        $kontak = Kontak::findOrFail($id);
        $kontak->update($request->all());

        return redirect()->route('kontak')->with('success', 'Kontak berhasil diperbarui!');
    }

    // Menghapus kontak
    public function destroykontak($id)
    {
        $kontak = Kontak::findOrFail($id);
        $kontak->delete();

        return redirect()->route('kontak')->with('success', 'Kontak berhasil dihapus!');
    }




/*
|--------------------------------------------------------------------------
|   Program
|--------------------------------------------------------------------------
*/

public function program()
{
    $data = Program::all();
    return view('admin-page.program-pendidikan', compact('data'));
    // return view('admin-page.kontak');

}

public function tambahprogram()
{
    return view('actions-admin.tambah-program');
}

public function simpanprogram(Request $request)
    {
        $request->validate([
            'materi' => 'required',
            'deskripsi' => 'required',
        ]);

        Program::create([
            'materi' => $request->materi,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('program')->with('success', 'Program Pendidikan berhasil ditambahkan');
    }

    public function editprogram($id)
    {
        $data = Program::findOrFail($id);
        return view('actions-admin.edit-program', compact('data'));
    }

    public function updateprogram(Request $request, $id)
    {
        $request->validate([
            'materi' => 'required',
            'deskripsi' => 'required',
        ]);

        $program = Program::findOrFail($id);
        $program->update([
            'materi' => $request->materi,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('program')->with('success', 'Program Pendidikan berhasil diperbarui');
    }

    public function destroyprogram($id)
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('program')->with('success', 'Program Pendidikan berhasil dihapus');
    }






/*
|--------------------------------------------------------------------------
|   
|--------------------------------------------------------------------------
*/

public function ekstra()
{
    $data = Ekstra::all();
    return view('admin-page.ekstrakurikuler', compact('data'));
    // return view('admin-page.kontak');

}

public function tambahekstra()
{
    return view('actions-admin.tambah-ekstra');
}

public function simpanekstra(Request $request)
    {
        $request->validate([
            'ekstra' => 'required',
            'deskripsi' => 'required',
        ]);

        Ekstra::create([
            'ekstra' => $request->ekstra,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('ekstra')->with('success', 'Ekstrakurikuler berhasil ditambahkan');
    }

    public function editekstra($id)
    {
        $data = Ekstra::findOrFail($id);
        return view('actions-admin.edit-ekstra', compact('data'));
    }

    public function updateekstra(Request $request, $id)
    {
        $request->validate([
            'ekstra' => 'required',
            'deskripsi' => 'required',
        ]);

        $ekstra = Ekstra::findOrFail($id);
        $ekstra->update([
            'ekstra' => $request->ekstra,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('ekstra')->with('success', 'Ekstrakurikuler berhasil diperbarui');
    }

    public function destroyekstra($id)
    {
        $ekstra = Ekstra::findOrFail($id);
        $ekstra->delete();

        return redirect()->route('ekstra')->with('success', 'Ekstrakurikuler berhasil dihapus');
    }



/*
|--------------------------------------------------------------------------
|   Kritik dan Saran
|--------------------------------------------------------------------------
*/

public function simpankritiksaran(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subjek' => 'required',
            'pesan' => 'required',
            
        ]);


        KritikSaran::create([
            'name' => $request->name,
            'email' => $request->email,
            'subjek' => $request->subjek,
            'pesan' => $request->pesan,
            
        ]);
        // dd($request->all()); // Cek apakah data dikirim

        // return redirect()->back()->with('success', 'Kritik dan Saran berhasil dikirim!');
        return redirect('/kritik-saran')->with('success', 'Kritik dan Saran berhasil dikirim!');

    }

/// DIBAWAH INI
/// KRITIK DAN SARAN UNTUK TAMPIL
/// DI ADMIN

    public function kritikadmin()
    {
        $kritik = KritikSaran::orderBy('created_at', 'desc')->get();
        return view('admin-page.kritik', compact('kritik'));
    }

    public function deletekritik($id)
    {
        KritikSaran::where('id', $id)->delete();
        return redirect()->route('admin-kritik')->with('success', 'Kritik & Saran berhasil dihapus.');    }

}