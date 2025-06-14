{{-- resources/views/admin-page/pemeriksaan-form-partial.blade.php --}}
@if($user->level == 'balita')
    @include('admin-page.pemeriksaan-form.balita', ['user' => $user])
@elseif($user->level == 'remaja')
    @include('admin-page.pemeriksaan-form.remaja', ['user' => $user])
@elseif($user->level == 'dewasa')
    @include('admin-page.pemeriksaan-form.dewasa', ['user' => $user])
@elseif($user->level == 'ibu hamil')
    @include('admin-page.pemeriksaan-form.ibu-hamil', ['user' => $user])
@elseif($user->level == 'lansia')
    @include('admin-page.pemeriksaan-form.lansia', ['user' => $user])
@else
    <div class="alert alert-warning">Role tidak dikenali atau belum ada form pemeriksaan.</div>
@endif