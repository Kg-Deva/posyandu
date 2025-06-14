<!-- filepath: c:\laragon\www\posyandu\resources\views\admin-page\pemeriksaan-form\balita.blade.php -->
<form>
    <div class="mb-3">
        <label for="bb" class="form-label">Berat Badan Balita (kg)</label>
        <input type="number" step="0.01" class="form-control" id="bb" name="bb" required>
    </div>
    <div class="mb-3">
        <label for="tb" class="form-label">Tinggi/Panjang Badan Balita (cm)</label>
        <input type="number" step="0.1" class="form-control" id="tb" name="tb" required>
    </div>
    <div class="mb-3">
        <label for="umur" class="form-label">Umur Balita (bulan)</label>
        <input type="number" class="form-control" id="umur" name="umur" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Kesimpulan Hasil Pengukuran BB/U (0-5 tahun)</label>
        <input type="text" class="form-control" id="kesimpulan_bbu" name="kesimpulan_bbu" readonly>
    </div>
    <div class="mb-3">
        <label class="form-label">Kesimpulan Hasil Pengukuran TB/U (0-5 tahun)</label>
        <input type="text" class="form-control" id="kesimpulan_tbuu" name="kesimpulan_tbuu" readonly>
    </div>
    <div class="mb-3">
        <label class="form-label">Kesimpulan Hasil Pengukuran BB/TB</label>
        <input type="text" class="form-control" id="kesimpulan_bbtb" name="kesimpulan_bbtb" readonly>
    </div>
</form>
