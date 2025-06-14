@auth
    <div class="logo">
        @if(auth()->user()->level == 'admin')
            <a href="/dashboard">
        @elseif(auth()->user()->level == 'kader')
            <a href="/kader-home">
        @elseif(auth()->user()->level == 'balita')
            <a href="/balita-home">
        @elseif(auth()->user()->level == 'remaja')
            <a href="/remaja-home">
        @elseif(auth()->user()->level == 'dewasa')
            <a href="/dewasa-home">
        @elseif(auth()->user()->level == 'ibu hamil')
            <a href="/ibu-hamil-home">
        @elseif(auth()->user()->level == 'lansia')
            <a href="/lansia-home">
        @else
            <a href="#">
        @endif
            <img src="{{ asset('landing-page/img/logoo.png') }}" alt="Logo" style="max-width: 70%; height: auto;">
        </a>
    </div>
    <!-- Elemen lain khusus admin -->
@endauth

<div class="theme-toggle d-flex gap-2  align-items-center mt-2">
  


{{-- YANG BARU --}}
    <div class="form-check form-switch fs-6 d-none">
        <input class="form-check-input me-0" type="checkbox" id="toggle-dark">
        <label class="form-check-label"></label>
    </div>
</div>