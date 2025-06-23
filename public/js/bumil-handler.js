/**
 * ✅ IBU HAMIL HANDLER - Auto-calculate BB, LILA, Tekanan Darah, Skrining TBC untuk Ibu Hamil
 * Referensi: Buku KIA Kemenkes, WHO 2011, Pedoman ANC 2020
 */
console.log('📦 IBU HAMIL: Handler loaded');

class IbuHamilHandler {
  constructor() {
    console.log('🚀 IBU HAMIL: IbuHamilHandler class initialized');
    this.formId = 'form-bumil';
    this.isInitialized = false;
  }

  /**
   * ✅ PUBLIC METHOD - Initialize ibu hamil calculations after form injection
   */
  initialize() {
    console.log('🔧 IBU HAMIL: Starting initialization...');

    const form = document.getElementById(this.formId);
    if (!form) {
      console.error('❌ IBU HAMIL: Form not found in DOM');
      return false;
    }

    console.log('✅ IBU HAMIL: Form found, setting up handlers...');
    this.setupEventListeners();
    this.setupTBCScreening(); // ✅ TAMBAH BARU
    this.calculateAll(); // Initial calculation
    this.isInitialized = true;

    console.log('🎉 IBU HAMIL: Initialization completed successfully!');
    return true;
  }

  /**
   * ✅ Setup event listeners for auto-calculation
   */
  setupEventListeners() {
    console.log('🔧 IBU HAMIL: Setting up event listeners...');

    // BB + Usia kehamilan untuk status BB sesuai KIA
    ['bb', 'usia_kehamilan_minggu'].forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('input', () => {
          console.log(`IBU HAMIL: ${id} changed to:`, input.value);
          this.calculateBBStatus();
          this.calculateRujukan();
        });
        console.log(`✅ IBU HAMIL: Listener attached to ${id}`);
      } else {
        console.warn(`⚠️ IBU HAMIL: Element #${id} not found`);
      }
    });

    // LILA
    const lilaInput = document.getElementById('lila');
    if (lilaInput) {
      lilaInput.addEventListener('input', () => {
        console.log('IBU HAMIL: LILA changed to:', lilaInput.value);
        this.calculateLILAStatus();
        this.calculateRujukan();
      });
      console.log('✅ IBU HAMIL: Listener attached to lila');
    }

    // Tekanan darah (Sistole + Diastole)
    ['sistole', 'diastole'].forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('input', () => {
          console.log(`IBU HAMIL: ${id} changed to:`, input.value);
          this.calculateTekananDarah();
          this.calculateRujukan();
        });
        console.log(`✅ IBU HAMIL: Listener attached to ${id}`);
      }
    });

    console.log('🎯 IBU HAMIL: All event listeners attached');
  }

  /**
   * ✅ Setup TBC screening calculation
   */
  setupTBCScreening() {
    console.log('🔧 IBU HAMIL: Setting up TBC screening...');

    const tbcCheckboxes = document.querySelectorAll('.skrining-tbc');
    tbcCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', () => {
        console.log('IBU HAMIL: TBC checkbox changed:', checkbox.id, checkbox.checked);
        this.calculateTBCScreening();
      });
    });

    console.log(`✅ IBU HAMIL: TBC screening attached to ${tbcCheckboxes.length} checkboxes`);
  }

  /**
   * ✅ Calculate BB status sesuai kurva buku KIA
   */
  calculateBBStatus() {
    const bbInput = document.getElementById('bb');
    const usiaInput = document.getElementById('usia_kehamilan_minggu');
    const statusOutput = document.getElementById('status_bb_kia');

    if (!bbInput || !usiaInput || !statusOutput) {
      console.warn('⚠️ IBU HAMIL: BB status elements not found');
      return;
    }

    const bb = parseFloat(bbInput.value) || 0;
    const usia = parseInt(usiaInput.value) || 0;

    console.log('IBU HAMIL: Calculating BB status - BB:', bb, 'Usia:', usia);

    if (bb > 0 && usia > 0) {
      // ✅ SIMPLIFIED LOGIC - Berdasarkan kurva buku KIA
      // Rata-rata kenaikan BB normal: 0.4-0.5 kg per minggu setelah trimester 1
      let status = '';
      let className = '';

      // Estimasi BB normal berdasarkan usia kehamilan (simplified)
      let expectedMinBB = 45; // Base weight
      let expectedMaxBB = 75; // Base weight

      if (usia >= 13) {
        // Trimester 2 dan 3
        const weeksSinceTrimester2 = usia - 12;
        expectedMinBB += weeksSinceTrimester2 * 0.3; // Minimal gain
        expectedMaxBB += weeksSinceTrimester2 * 0.6; // Maximal gain
      }

      if (bb >= expectedMinBB && bb <= expectedMaxBB) {
        status = 'Ya - Sesuai kurva buku KIA';
        className = 'form-control status-hijau';
      } else {
        status = 'Tidak - Perlu perhatian khusus';
        className = 'form-control status-merah';
      }

      statusOutput.value = status;
      statusOutput.className = className;

      console.log('✅ IBU HAMIL: BB status calculated -', status);
    } else {
      statusOutput.value = '';
      statusOutput.className = 'form-control';
      console.log('⚠️ IBU HAMIL: BB status calculation skipped - incomplete data');
    }
  }

  /**
   * ✅ Calculate LILA status
   */
  calculateLILAStatus() {
    const lilaInput = document.getElementById('lila');
    const statusOutput = document.getElementById('status_lila');

    if (!lilaInput || !statusOutput) {
      console.warn('⚠️ IBU HAMIL: LILA status elements not found');
      return;
    }

    const lila = parseFloat(lilaInput.value) || 0;

    console.log('IBU HAMIL: Calculating LILA status - LILA:', lila);

    if (lila > 0) {
      let status = '';
      let className = '';

      if (lila >= 23.5) {
        status = 'Ya - Normal (≥ 23.5 cm)';
        className = 'form-control status-hijau';
      } else {
        status = 'Tidak - Kurang Gizi (< 23.5 cm)';
        className = 'form-control status-merah';
      }

      statusOutput.value = status;
      statusOutput.className = className;

      console.log('✅ IBU HAMIL: LILA status calculated -', status);
    } else {
      statusOutput.value = '';
      statusOutput.className = 'form-control';
      console.log('⚠️ IBU HAMIL: LILA calculation skipped - incomplete data');
    }
  }

  /**
   * ✅ Calculate tekanan darah status sesuai buku KIA
   */
  /**
   * ✅ Calculate tekanan darah status sesuai buku KIA (3 kategori: Normal, Hipertensi, Hipotensi)
   */
  calculateTekananDarah() {
    const sistoleInput = document.getElementById('sistole');
    const diastoleInput = document.getElementById('diastole');
    const sistoleOutput = document.getElementById('kesimpulan_sistole');
    const diastoleOutput = document.getElementById('kesimpulan_diastole');
    const statusKIAOutput = document.getElementById('status_td_kia');

    if (!sistoleInput || !diastoleInput || !sistoleOutput || !diastoleOutput || !statusKIAOutput) {
      console.warn('⚠️ IBU HAMIL: Tekanan darah elements not found');
      return;
    }

    const sistole = parseInt(sistoleInput.value) || 0;
    const diastole = parseInt(diastoleInput.value) || 0;

    console.log('IBU HAMIL: Calculating tekanan darah - Sistole:', sistole, 'Diastole:', diastole);

    if (sistole > 0 && diastole > 0) {
      // Classify sistole
      let sistoleKategori = '';
      if (sistole < 90) {
        sistoleKategori = 'Rendah (<90)';
      } else if (sistole >= 90 && sistole < 140) {
        sistoleKategori = 'Normal (90-139)';
      } else {
        sistoleKategori = 'Tinggi (≥140)';
      }

      // Classify diastole
      let diastoleKategori = '';
      if (diastole < 60) {
        diastoleKategori = 'Rendah (<60)';
      } else if (diastole >= 60 && diastole < 90) {
        diastoleKategori = 'Normal (60-89)';
      } else {
        diastoleKategori = 'Tinggi (≥90)';
      }

      // Overall status sesuai buku KIA (3 kategori)
      let statusKIA = '';
      let className = '';

      if (sistole < 90 || diastole < 60) {
        statusKIA = 'Tidak - Hipotensi (<90/60 mmHg)';
        className = 'form-control status-merah';
      } else if (sistole >= 140 || diastole >= 90) {
        statusKIA = 'Tidak - Hipertensi (≥140/90 mmHg)';
        className = 'form-control status-merah';
      } else {
        statusKIA = 'Ya - Normal (90-139/60-89 mmHg)';
        className = 'form-control status-hijau';
      }

      sistoleOutput.value = sistoleKategori;
      diastoleOutput.value = diastoleKategori;
      statusKIAOutput.value = statusKIA;
      statusKIAOutput.className = className;

      console.log('✅ IBU HAMIL: Tekanan darah calculated -', statusKIA);
    } else {
      sistoleOutput.value = '';
      diastoleOutput.value = '';
      statusKIAOutput.value = '';
      statusKIAOutput.className = 'form-control';
      console.log('⚠️ IBU HAMIL: Tekanan darah calculation skipped - incomplete data');
    }
  }

  /**
   * ✅ Calculate TBC screening results (SAMA SEPERTI REMAJA)
   */
  calculateTBCScreening() {
    const checkboxes = document.querySelectorAll('.skrining-tbc:checked');
    const jumlahGejala = checkboxes.length;

    console.log('IBU HAMIL: TBC screening - checked:', jumlahGejala);

    // Collect checked symptoms
    const gejalaList = [];
    if (document.getElementById('batuk_terus_menerus')?.checked) {
      gejalaList.push('Batuk terus menerus');
    }
    if (document.getElementById('demam_2_minggu')?.checked) {
      gejalaList.push('Demam >2 minggu');
    }
    if (document.getElementById('bb_tidak_naik')?.checked) {
      gejalaList.push('BB tidak naik/turun');
    }
    if (document.getElementById('kontak_tbc')?.checked) {
      gejalaList.push('Kontak erat TBC');
    }

    // ✅ UPDATE FIELD JUMLAH GEJALA - KOSONG TOTAL KALAU 0
    const jumlahGejalaField = document.getElementById('jumlah_gejala_tbc');
    if (jumlahGejalaField) {
      if (jumlahGejala === 0) {
        jumlahGejalaField.value = ''; // KOSONG TOTAL
        jumlahGejalaField.className = 'form-control'; // PUTIH BERSIH
      } else {
        jumlahGejalaField.value = `${jumlahGejala} gejala: ${gejalaList.join(', ')}`;
        jumlahGejalaField.className = 'form-control'; // TETAP PUTIH
      }
    }

    // ✅ UPDATE FIELD RUJUKAN TBC - LANGSUNG BERUBAH WARNA SEJAK 1 GEJALA
    const rujukField = document.getElementById('rujuk_puskesmas_tbc');
    if (rujukField) {
      if (jumlahGejala >= 2) {
        // 2+ GEJALA - MERAH dengan tulisan putih
        rujukField.value = 'RUJUK - Perlu pemeriksaan lebih lanjut di Puskesmas';
        rujukField.className = 'form-control bg-danger text-white font-weight-bold';
      } else if (jumlahGejala === 1) {
        // 1 GEJALA - HIJAU dengan tulisan putih
        rujukField.value = 'TIDAK RUJUK - Gejala TBC tidak mencukupi';
        rujukField.className = 'form-control bg-success text-white';
      } else {
        // 0 GEJALA - KOSONG DAN PUTIH BERSIH
        rujukField.value = '';
        rujukField.className = 'form-control';
      }
    }

    console.log(`✅ IBU HAMIL: TBC screening - ${jumlahGejala} gejala`);
  }

  /**
   * ✅ Calculate rujukan status - Jika ada merah = rujuk
   */
  /**
   * ✅ Calculate rujukan status - Jika ada merah/kuning = rujuk
   */
  calculateRujukan() {
    const statusBBField = document.getElementById('status_bb_kia');
    const statusLILAField = document.getElementById('status_lila');
    const statusTDField = document.getElementById('status_td_kia');
    const rujukanOutput = document.getElementById('status_rujukan');

    if (!rujukanOutput) {
      console.warn('⚠️ IBU HAMIL: Rujukan field not found');
      return;
    }

    // Check if any field has "merah" atau "kuning" status (contains "Tidak")
    let hasAbnormalStatus = false;
    const abnormalConditions = [];

    if (statusBBField && statusBBField.value.includes('Tidak')) {
      hasAbnormalStatus = true;
      if (statusBBField.value.includes('kurva')) {
        abnormalConditions.push('BB tidak sesuai kurva');
      }
    }

    if (statusLILAField && statusLILAField.value.includes('Tidak')) {
      hasAbnormalStatus = true;
      abnormalConditions.push('LILA kurang');
    }

    if (statusTDField && statusTDField.value.includes('Tidak')) {
      hasAbnormalStatus = true;
      if (statusTDField.value.includes('Hipertensi')) {
        abnormalConditions.push('Hipertensi');
      } else if (statusTDField.value.includes('Hipotensi')) {
        abnormalConditions.push('Hipotensi');
      }
    }

    // Set rujukan status
    if (hasAbnormalStatus) {
      rujukanOutput.value = `RUJUK KE PUSKESMAS - ${abnormalConditions.join(', ')}`;
      rujukanOutput.className = 'form-control status-merah fw-bold';
    } else if (statusBBField?.value || statusLILAField?.value || statusTDField?.value) {
      rujukanOutput.value = 'TIDAK PERLU RUJUKAN - Semua parameter normal';
      rujukanOutput.className = 'form-control status-hijau';
    } else {
      rujukanOutput.value = '';
      rujukanOutput.className = 'form-control';
    }

    console.log(
      '✅ IBU HAMIL: Rujukan status calculated - Has abnormal:',
      hasAbnormalStatus,
      'Conditions:',
      abnormalConditions
    );
  }

  /**
   * ✅ Calculate all parameters at once
   */
  calculateAll() {
    console.log('🔄 IBU HAMIL: Calculating all parameters...');
    this.calculateBBStatus();
    this.calculateLILAStatus();
    this.calculateTekananDarah();
    this.calculateRujukan();
    // ✅ JANGAN PANGGIL calculateTBCScreening() di awal
    // Biarkan field TBC kosong sampai user centang checkbox
    console.log('✅ IBU HAMIL: All calculations completed');
  }
}

// ✅ Create global instance and expose initialization function
const ibuHamilHandler = new IbuHamilHandler();

/**
 * ✅ GLOBAL FUNCTION - Called from input-pemeriksaan.blade.php after AJAX inject
 */
function initializeIbuHamilHandler() {
  console.log('🎯 IBU HAMIL: initializeIbuHamilHandler called from external script');
  return ibuHamilHandler.initialize();
}

// ✅ Make function available globally
window.initializeIbuHamilHandler = initializeIbuHamilHandler;

console.log('✅ IBU HAMIL: Handler setup completed, waiting for initialization call');
