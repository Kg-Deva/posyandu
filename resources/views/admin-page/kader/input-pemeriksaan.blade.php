{{-- filepath: resources/views/admin-page/input-pemeriksaan.blade.php --}}
@extends('admin-layouts.main')
@section('content')
<div class="container">
    <h4>Input Pemeriksaan</h4>
    <div class="mb-3">
        <input type="text" id="cari-nik" placeholder="Cari NIK atau Nama..." class="form-control">
    </div>
    <button id="btn-cari" class="btn btn-primary mb-3">Cari</button>
    <div id="hasil-cari"></div>
    <div id="form-pemeriksaan"></div>
</div>

{{-- Update bagian script di input-pemeriksaan.blade.php --}}
{{-- PINDAH SCRIPT KE ATAS --}}
<script src="{{ asset('js/balita-handler.js') }}"></script>

<script>
document.getElementById('btn-cari').onclick = function() {
    let q = document.getElementById('cari-nik').value.trim();
    fetch('/cari-pasien', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({q: q})
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Data tidak ditemukan');
        }
        return res.text();
    })
    .then(html => {
        console.log('Response HTML diterima');
        document.getElementById('form-pemeriksaan').innerHTML = html;
        
        console.log('HTML includes form balita?', html.includes('Berat Badan Balita'));
        
        if (html.includes('Berat Badan Balita')) {
            console.log('Memanggil initializeBalitaHandler()'); // ✅ GANTI NAMA FUNCTION
            // ✅ PANGGIL FUNCTION YANG BENAR
            initializeBalitaHandler();
        }
    })
    .catch(error => {
        document.getElementById('form-pemeriksaan').innerHTML = '<div class="alert alert-danger">Data tidak ditemukan</div>';
    });
}
</script>



@endsection