<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\MenuImage;
use Illuminate\Validation\Rule;
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

use Illuminate\Support\Facades\Log;
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
        return view('berita', compact('beritas', 'berandas', 'kontaks'));
    }


    public function kritiksaran()
    {

        $berandas = Beranda::first();                 // Semua peraturan
        $beritas = Berita::latest()->paginate(4); // Pagination, 4 berita per halaman
        $kontaks = Kontak::first();
        return view('kritik-saran', compact('beritas', 'berandas', 'kontaks'));
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
        return view('profil-pengajar', compact('pengajar', 'berandas', 'kontaks'));
    }


    public function strukturuser()
    {

        $berandas = Beranda::first();                 // Semua peraturan
        $strk = Struktur::all();
        $kontaks = Kontak::first();
        return view('struktur', compact('strk', 'berandas', 'kontaks'));
    }


    public function galleryuser()
    {
        $kontaks = Kontak::first();
        $berandas = Beranda::first();                 // Semua peraturan
        $data = Gallery::paginate(6); // Menampilkan 6 gambar per halaman
        return view('gallery', compact('data', 'berandas', 'kontaks'));
    }

    public function agendauser()
    {
        $kontaks = Kontak::first();
        $berandas = Beranda::first();                 // Semua peraturan
        $agendas = Agenda::all();
        return view('agenda', compact('berandas', 'agendas', 'kontaks'));
    }

    public function programuser()
    {
        $kontaks = Kontak::first();
        $berandas = Beranda::first();                 // Semua peraturan
        $program = Program::all();
        $ekstra = Ekstra::all();
        return view('program', compact('berandas', 'program', 'kontaks', 'ekstra'));
    }


    public function beritashow($id)
    {
        $berita = Berita::findOrFail($id);
        $berandas = Beranda::first();                 // Semua peraturan
        $kontaks = Kontak::first();
        // Kirim data berita ke view
        return view('berita-show', compact('berandas', 'berita', 'kontaks'));
    }

    public function agendashow($id)
    {
        $agenda = Agenda::findOrFail($id);
        $berandas = Beranda::first();                 // Semua peraturan
        $kontaks = Kontak::first();
        // Kirim data agenda ke view
        return view('agenda-show', compact('berandas', 'agenda', 'kontaks'));
    }

    /*
|--------------------------------------------------------------------------
| End User
|--------------------------------------------------------------------------
*/

    public function dashboard()
    {


        $balitaCount = User::where('level', 'balita')->count();
        $remajaCount = User::where('level', 'remaja')->count();
        $dewasaCount = User::where('level', 'dewasa')->count();
        $ibuhamilCount = User::where('level', 'ibu hamil')->count();
        $lansiaCount = User::where('level', 'lansia')->count();

        $aktifCount = User::where('status', 1)->where('level', '!=', 'admin')->count();
        $tidakAktifCount = User::where('status', 0)->where('level', '!=', 'admin')->count();
        // $totalPasien = $balitaCount + $remajaCount + $dewasaCount + $ibuhamilCount + $lansiaCount;

        $totalPasien = User::where('level', '!=', 'admin')->count();

        // $data = User::paginate(10);

        $data = User::where('level', '!=', 'admin')->paginate(10);


        return view('admin-page.dashboard', compact(
            'balitaCount',
            'remajaCount',
            'dewasaCount',
            'ibuhamilCount',
            'lansiaCount',
            'totalPasien',
            'tidakAktifCount',
            'aktifCount',
            'data'
        ));
    }

    public function tambahanggota()
    {
        return view('actions-admin.tambah-anggota');
    }
    public function simpananggota(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'type' => 'required|string', // tambahkan validasi type
        ]);

        $data = [
            'name' => $request->name,
            'level' => $request->level,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->has('status') ? 1 : 0,
            'type' => $request->type, // tambahkan ini
        ];

        User::create($data);

        return redirect()->route('dashboard')->with('success', 'Role berhasil ditambahkan!');
    }

    public function editanggota($id)
    {
        $data = User::findorfail($id);
        return view('actions-admin.edit-anggota', compact('data'));
        // return view('admin.edit', ['No' => $data]);
    }


    public function updateanggota(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'type' => 'required|string', // tambahkan validasi type
            'password' => 'nullable|min:6', // password opsional
        ]);

        $data = [
            'name' => $request->name,
            'level' => $request->level,
            'email' => $request->email,
            'status' => $request->has('status') ? 1 : 0,
            'type' => $request->type, // tambahkan ini
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        User::where('id', $id)->update($data);

        return redirect()->route('dashboard')->with('success', 'Role berhasil diupdate!');
    }

    public function destroy($id)
    {
        $data = User::find($id);
        if (!$data) {
            return back()->with('error', 'Data tidak ditemukan');
        }

        $name = $data->name; // Ambil nama sebelum data dihapus
        $data->delete();

        return back()->with('success', "Akun dengan nama $name berhasil dihapus");
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
            // Hapus gambar lama jika ada
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
        return redirect()->route('admin-kritik')->with('success', 'Kritik & Saran berhasil dihapus.');
    }

    public function searchAnggota(Request $request)
    {
        $q = $request->q;
        $data = User::where('name', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->get();
        return response()->json($data);
    }


    public function kaderHome()
    {

        // Data konten
        // $balitaCount = User::where('level', 'balita')->where('status', 1)->count();
        // $remajaCount = User::where('level', 'remaja')->where('status', 1)->count();
        // $dewasaCount = User::where('level', 'dewasa')->where('status', 1)->count();
        // $ibuhamilCount = User::where('level', 'ibu hamil')->where('status', 1)->count();
        // $lansiaCount = User::where('level', 'lansia')->where('status', 1)->count();

        // $totalPasien = $balitaCount + $remajaCount + $dewasaCount + $ibuhamilCount + $lansiaCount;

        $balitaCount = User::where('level', 'balita')->count();
        $remajaCount = User::where('level', 'remaja')->count();
        $dewasaCount = User::where('level', 'dewasa')->count();
        $ibuhamilCount = User::where('level', 'ibu hamil')->count();
        $lansiaCount = User::where('level', 'lansia')->count();

        $aktifCount = User::where('status', 1)->where('level', '!=', 'admin')->count();
        $tidakAktifCount = User::where('status', 0)->where('level', '!=', 'admin')->count();
        // $totalPasien = $balitaCount + $remajaCount + $dewasaCount + $ibuhamilCount + $lansiaCount;

        // $totalPasien = User::where('level', '!=', 'admin')->count();
        $totalPasien = $balitaCount + $remajaCount + $dewasaCount + $ibuhamilCount + $lansiaCount;

        // Hanya tampilkan user yang level-nya BUKAN admin dan kader
        $data = User::whereNotIn('level', ['admin', 'kader'])->paginate(10);

        return view('admin-page.kader.dashboard', compact(
            'balitaCount',
            'remajaCount',
            'dewasaCount',
            'ibuhamilCount',
            'lansiaCount',
            'totalPasien',
            'aktifCount',
            'tidakAktifCount',
            'data'
        ));
    }
    public function tambahpasien()
    {
        return view('admin-page.kader.tambah-pasien');
    }
    public function simpanpasien(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'type' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'level' => $request->level,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->has('status') ? 1 : 0,
            'type' => $request->type,
        ];

        User::create($data);

        return redirect()->route('kader-home')->with('success', 'Akun berhasil dibuat!');
    }



    public function editpasien($id)
    {
        $data = User::findorfail($id);
        return view('admin-page.kader.edit-pasien', compact('data'));
        // return view('admin.edit', ['No' => $data]);
    }

    public function updatepasien(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'type' => 'required|string',
        ]);

        $data = [
            'name' => $request->name,
            'level' => $request->level,
            'email' => $request->email,
            'status' => $request->has('status') ? 1 : 0,
            'type' => $request->type,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        User::where('id', $id)->update($data);

        return redirect()->route('kader-home')->with('success', 'Akun berhasil diupdate!');
    }




    public function formLengkapiData($id)
    {
        $user = User::findOrFail($id);
        return view('admin-page.kader.formdata', compact('user'));
    }


    // app/Http/Controllers/MenuController.php





    public function simpanLengkapiData(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'nik' => [
                    'required',
                    'digits:16',
                    'numeric',
                    Rule::unique('users', 'nik')->ignore($id)
                ],
                'tanggal_lahir' => 'required|date|before_or_equal:today',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'alamat' => 'required|string',
                'no_hp' => 'required|string|max:15',
                // ... field lainnya tetap sama
            ], [
                'nik.required' => 'NIK wajib diisi.',
                'nik.digits' => 'NIK harus terdiri dari 16 digit.',
                'nik.numeric' => 'NIK hanya boleh berisi angka.',
                'nik.unique' => 'NIK sudah terdaftar, gunakan NIK lain.',
                'nama.required' => 'Nama wajib diisi.',
                'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
                'alamat.required' => 'Alamat wajib diisi.',
                'no_hp.required' => 'No HP wajib diisi.',
            ]);

            $user = User::findOrFail($id);
            $tanggal_lahir = Carbon::parse($request->tanggal_lahir);
            $sekarang = Carbon::now();

            $diff = $tanggal_lahir->diff($sekarang);
            $umur_string = $diff->y . ' tahun ' . $diff->m . ' bulan ' . $diff->d . ' hari';

            $userData = [
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'dusun' => $request->dusun,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'kecamatan' => $request->kecamatan,
                'wilayah' => $request->wilayah,
                'berat_badan_lahir' => $request->berat_badan_lahir,
                'panjang_badan_lahir' => $request->panjang_badan_lahir,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'status_perkawinan' => $request->status_perkawinan,
                'pekerjaan' => $request->pekerjaan,
                'riwayat_keluarga' => is_array($request->riwayat_keluarga) ? implode(',', $request->riwayat_keluarga) : $request->riwayat_keluarga,
                'riwayat_diri' => is_array($request->riwayat_diri) ? implode(',', $request->riwayat_diri) : $request->riwayat_diri,
                'perilaku_beresiko' => is_array($request->perilaku_beresiko) ? implode(',', $request->perilaku_beresiko) : $request->perilaku_beresiko,
                'jarak_kehamilan_tahun' => $request->jarak_kehamilan_tahun,
                'jarak_kehamilan_bulan' => $request->jarak_kehamilan_bulan,
                'berat_badan_ibu' => $request->berat_badan_ibu,
                'hamil_ke' => $request->hamil_ke,
                'tinggi_badan_ibu' => $request->tinggi_badan_ibu,
                'data_lengkap' => true,
                'umur' => $umur_string,
            ];

            $user->update($userData);

            // ✅ SUCCESS - REDIRECT KE DASHBOARD DENGAN SUCCESS MESSAGE
            return redirect()->route('kader-home')->with('success', ' Data berhasil disimpan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ ERROR - REDIRECT KE DASHBOARD DENGAN ERROR MESSAGE
            $errorMessages = [];
            foreach ($e->errors() as $field => $errors) {
                foreach ($errors as $error) {
                    $errorMessages[] = $error;
                }
            }

            return redirect()->route('kader-home')->with('error', ' Gagal menyimpan data: ' . implode(', ', $errorMessages));
        }
    }


    // INPUT PEMERIKSAAN

    public function inputPemeriksaan()
    {
        // Tampilkan form pencarian NIK/nama saja dulu
        return view('admin-page.kader.input-pemeriksaan');
    }

    public function cariPasien(Request $request)
    {
        $q = $request->input('q');
        $user = User::where('nik', $q)->orWhere('nama', 'like', "%$q%")->first();

        if ($user) {
            // Render partial blade sesuai role user
            return view('admin-page.pemeriksaan-form-partial', compact('user'))->render();
        } else {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }
    }



    public function simpanPemeriksaan(Request $request, $id)
    {
        // Validasi dan simpan data pemeriksaan sesuai kebutuhan
        // Misal: Pemeriksaan::create([...]);
        return back()->with('success', 'Data pemeriksaan berhasil disimpan!');
    }
}
