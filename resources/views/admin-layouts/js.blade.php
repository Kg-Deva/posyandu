<script src="{{ asset('admin/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('admin/assets/js/app.js') }}"></script>

<!-- Need: Apexcharts -->
<script src="{{ asset('admin/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/pages/dashboard.js') }}"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ asset('quill.js') }}"></script>


<script>
    document.querySelector('#toggle-theme').addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
    });
</script>

@push('scripts')
    <script src="{{ asset('js/quill.js') }}"></script>
@endpush

<script>
    document.getElementById('saveButton').addEventListener('click', function() {
        const content = document.querySelector('.ql-editor').innerHTML;

        fetch('/save-content', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            });
    });
</script>
<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password-vertical');
        var passwordIcon = document.getElementById('password-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('bi-eye-slash');
            passwordIcon.classList.add('bi-eye');
        }
    }
</script>

<script>
    function confirmation(event) {
        event.preventDefault(); // Mencegah tautan langsung bekerja
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data ini akan dihapus secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika pengguna menekan "Ya, hapus!", arahkan ke URL tujuan
                window.location.href = event.target.closest('a').href;
            }
        });
    }
</script>

{{-- //Logout --}}
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Apakah Anda yakin ingin logout?',
            text: 'Anda akan keluar dari aplikasi!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, logout!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Lakukan logout jika dikonfirmasi
                document.getElementById('logoutForm').submit(); // Mengirimkan form logout
            }
        });
    }
</script>

