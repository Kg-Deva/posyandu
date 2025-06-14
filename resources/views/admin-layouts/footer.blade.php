{{-- filepath: resources/views/admin-layouts/footer.blade.php --}}
<footer>
    <div class="footer mb-0 text-muted text-center py-2" style="width:100%;">
        <span>
            &copy; <span id="tahun-footer"></span> Posyandu Gajahmungkur. All Rights Reserved.
        </span>
    </div>
    <script>
        document.getElementById('tahun-footer').textContent = new Date().getFullYear();
    </script>
</footer>
