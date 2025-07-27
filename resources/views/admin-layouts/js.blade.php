{{-- filepath: c:\laragon\www\posyandu\resources\views\admin-layouts\js.blade.php --}}
<script src="{{ asset('admin/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('admin/assets/js/app.js') }}"></script>

{{-- ✅ OPTIMIZE APEXCHARTS LOADING --}}
{{-- CONDITIONAL LOADING - HANYA LOAD DI HALAMAN YANG BUTUH CHART --}}
@if(in_array(request()->route()->getName(), ['dashboard', 'balita.home', 'remaja.home', 'dewasa.home', 'ibu-hamil.home', 'lansia.home', 'data-pemeriksaan']))
    {{-- ✅ PRELOAD APEXCHARTS --}}
    <link rel="preload" href="{{ asset('admin/assets/extensions/apexcharts/apexcharts.min.js') }}" as="script">
    
    {{-- ✅ LAZY LOAD APEXCHARTS --}}
    <script>
        // ✅ LOAD APEXCHARTS HANYA KETIKA DIBUTUHKAN
        function loadApexCharts() {
            return new Promise((resolve, reject) => {
                if (window.ApexCharts) {
                    resolve(window.ApexCharts);
                    return;
                }
                
                const script = document.createElement('script');
                script.src = '{{ asset("admin/assets/extensions/apexcharts/apexcharts.min.js") }}';
                script.async = true;
                script.defer = true;
                
                script.onload = () => {
                    console.log('✅ ApexCharts loaded successfully');
                    resolve(window.ApexCharts);
                };
                
                script.onerror = () => {
                    console.error('❌ Failed to load ApexCharts');
                    reject(new Error('ApexCharts failed to load'));
                };
                
                document.head.appendChild(script);
            });
        }

        // ✅ LAZY LOAD CHART DENGAN INTERSECTION OBSERVER
        function initLazyCharts() {
            const chartContainers = document.querySelectorAll('.chart-container, [id*="chart"], .apexcharts-container');
            
            if (chartContainers.length === 0) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        loadApexCharts().then(() => {
                            // ✅ LOAD DASHBOARD SCRIPT SETELAH APEXCHARTS READY
                            if (!document.querySelector('script[src*="dashboard.js"]')) {
                                const dashboardScript = document.createElement('script');
                                dashboardScript.src = '{{ asset("admin/assets/js/pages/dashboard.js") }}';
                                dashboardScript.async = true;
                                document.head.appendChild(dashboardScript);
                            }
                        }).catch(console.error);
                        
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                rootMargin: '50px' // Load 50px sebelum chart visible
            });

            chartContainers.forEach(container => {
                observer.observe(container);
            });
        }

        // ✅ INIT SETELAH DOM READY
        document.addEventListener('DOMContentLoaded', initLazyCharts);
    </script>
@else
    {{-- ✅ TIDAK LOAD APEXCHARTS DI HALAMAN YANG TIDAK BUTUH --}}
    <script>
        console.log('📊 ApexCharts not loaded - not needed on this page');
    </script>
@endif

{{-- ✅ QUILL.JS - CONDITIONAL LOADING --}}
@if(in_array(request()->route()->getName(), ['admin.artikel.create', 'admin.artikel.edit']))
    {{-- ✅ PRECONNECT KE CDN --}}
    <link rel="preconnect" href="https://cdn.quilljs.com">
    
    {{-- ✅ LAZY LOAD QUILL --}}
    <script>
        function loadQuill() {
            if (window.Quill) return Promise.resolve();
            
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = 'https://cdn.quilljs.com/1.3.6/quill.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        // ✅ INIT QUILL KETIKA DIBUTUHKAN
        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelector('.ql-editor, #editor')) {
                loadQuill().then(() => {
                    // ✅ LOAD LOCAL QUILL CONFIG
                    const localScript = document.createElement('script');
                    localScript.src = '{{ asset("quill.js") }}';
                    document.head.appendChild(localScript);
                });
            }
        });
    </script>
@endif

{{-- ✅ CORE SCRIPTS - SELALU LOAD --}}
<script>
    // ✅ THEME TOGGLE - OPTIMIZED
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggle = document.querySelector('#toggle-theme');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
                // ✅ SAVE PREFERENCE
                localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
            });
            
            // ✅ LOAD SAVED THEME
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.body.classList.add('dark-mode');
            }
        }
    });
</script>

{{-- ✅ QUILL SAVE - CONDITIONAL --}}
@if(in_array(request()->route()->getName(), ['admin.artikel.create', 'admin.artikel.edit']))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const saveButton = document.getElementById('saveButton');
        if (saveButton) {
            saveButton.addEventListener('click', function() {
                const editor = document.querySelector('.ql-editor');
                if (!editor) return;
                
                const content = editor.innerHTML;
                
                // ✅ SHOW LOADING
                saveButton.disabled = true;
                saveButton.textContent = 'Menyimpan...';

                fetch('/save-content', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ content: content })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('✅ Content saved:', data);
                        saveButton.textContent = 'Tersimpan!';
                        setTimeout(() => {
                            saveButton.disabled = false;
                            saveButton.textContent = 'Simpan';
                        }, 2000);
                    })
                    .catch(error => {
                        console.error('❌ Save failed:', error);
                        saveButton.disabled = false;
                        saveButton.textContent = 'Gagal! Coba lagi';
                        setTimeout(() => {
                            saveButton.textContent = 'Simpan';
                        }, 3000);
                    });
            });
        }
    });
</script>
@endif

{{-- ✅ PASSWORD TOGGLE - CONDITIONAL --}}
@if(request()->route()->getName() === 'login' || str_contains(request()->url(), 'password'))
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password-vertical');
        const passwordIcon = document.getElementById('password-icon');
        
        if (!passwordInput || !passwordIcon) return;

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
@endif

{{-- ✅ SWAL CONFIRMATION - UNIVERSAL --}}
<script>
    // ✅ LAZY LOAD SWEETALERT2
    function loadSweetAlert() {
        if (window.Swal) return Promise.resolve();
        
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    // ✅ CONFIRMATION FUNCTION
    function confirmation(event) {
        event.preventDefault();
        
        loadSweetAlert().then(() => {
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
                    window.location.href = event.target.closest('a').href;
                }
            });
        });
    }

    // ✅ LOGOUT CONFIRMATION
    function confirmLogout() {
        loadSweetAlert().then(() => {
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
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    }
</script>

{{-- ✅ PERFORMANCE MONITORING --}}
<script>
    // ✅ MONITOR LOADING PERFORMANCE
    window.addEventListener('load', () => {
        const loadTime = performance.now();
        console.log(`📊 Page loaded in ${Math.round(loadTime)}ms`);
        
        // ✅ REPORT SLOW LOADING
        if (loadTime > 3000) {
            console.warn('⚠️ Page loading is slow. Consider optimizing resources.');
        }
    });
</script>

{{-- ✅ DYNAMIC SCRIPTS STACK --}}
@stack('scripts')