/**
 * ✅ REMAJA HANDLER - Auto-calculate IMT, Tekanan Darah, Anemia, Lingkar Perut
 * Referensi: WHO 2007, AHA 2017, IDF 2007, Kemenkes
 */
console.log('📦 REMAJA: Handler loaded');

class RemajaHandler {
  constructor() {
    console.log('🚀 REMAJA: RemajaHandler class initialized');
    this.formId = 'form-remaja';
    this.isInitialized = false;
  }

  /**
   * ✅ PUBLIC METHOD - Initialize remaja calculations after form injection
   */
  initialize() {
    console.log('🔧 REMAJA: Starting initialization...');

    const form = document.getElementById(this.formId);
    if (!form) {
      console.error('❌ REMAJA: Form not found in DOM');
      return false;
    }

    console.log('✅ REMAJA: Form found, setting up handlers...');
    this.setupEventListeners();
    this.setupTBCScreening();
    this.calculateAll(); // Initial calculation
    this.isInitialized = true;

    console.log('🎉 REMAJA: Initialization completed successfully!');
    return true;
  }

  /**
   * ✅ Setup event listeners for auto-calculation
   */
  setupEventListeners() {
    console.log('🔧 REMAJA: Setting up event listeners...');

    // IMT calculation (BB + TB)
    ['bb', 'tb'].forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('input', () => {
          console.log(`REMAJA: ${id} changed to:`, input.value);
          this.calculateIMT();
        });
        console.log(`✅ REMAJA: Listener attached to ${id}`);
      } else {
        console.warn(`⚠️ REMAJA: Element #${id} not found`);
      }
    });

    // Lingkar perut
    const lpInput = document.getElementById('lingkar_perut');
    if (lpInput) {
      lpInput.addEventListener('input', () => {
        console.log('REMAJA: Lingkar perut changed to:', lpInput.value);
        this.calculateLingkarPerut();
      });
      console.log('✅ REMAJA: Listener attached to lingkar_perut');
    }

    // Tekanan darah (Sistole + Diastole)
    ['sistole', 'diastole'].forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('input', () => {
          console.log(`REMAJA: ${id} changed to:`, input.value);
          this.calculateTekananDarah();
        });
        console.log(`✅ REMAJA: Listener attached to ${id}`);
      }
    });

    // ✅ HEMOGLOBIN + JENIS KELAMIN (UPDATE INI)
    const hbInput = document.getElementById('hb');
    const jenisKelaminInput = document.getElementById('jenis_kelamin');

    if (hbInput) {
      hbInput.addEventListener('input', () => {
        console.log('REMAJA: HB changed to:', hbInput.value);
        this.calculateAnemia();
      });
      console.log('✅ REMAJA: Listener attached to hb');
    }

    // ✅ TAMBAH LISTENER UNTUK JENIS KELAMIN
    if (jenisKelaminInput) {
      jenisKelaminInput.addEventListener('change', () => {
        console.log('REMAJA: Jenis kelamin changed to:', jenisKelaminInput.value);
        this.calculateAnemia(); // Recalculate anemia dengan kriteria baru
      });
      console.log('✅ REMAJA: Listener attached to jenis_kelamin');
    } else {
      console.warn('⚠️ REMAJA: Element #jenis_kelamin not found');
    }

    console.log('🎯 REMAJA: All event listeners attached');
  }

  /**
   * ✅ Setup TBC screening calculation
   */
  setupTBCScreening() {
    console.log('🔧 REMAJA: Setting up TBC screening...');

    const tbcCheckboxes = document.querySelectorAll('.skrining-tbc');
    tbcCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', () => {
        console.log('REMAJA: TBC checkbox changed:', checkbox.id, checkbox.checked);
        this.calculateTBCScreening();
      });
    });

    console.log(`✅ REMAJA: TBC screening attached to ${tbcCheckboxes.length} checkboxes`);
  }

  /**
   * ✅ Calculate IMT and status (WHO 2007 Growth Reference)
   */
  calculateIMT() {
    const bbInput = document.getElementById('bb');
    const tbInput = document.getElementById('tb');
    const imtOutput = document.getElementById('nilai_imt');
    const kesimpulanOutput = document.getElementById('kesimpulan_imt');

    if (!bbInput || !tbInput || !imtOutput || !kesimpulanOutput) {
      console.warn('⚠️ REMAJA: IMT elements not found');
      return;
    }

    const bb = parseFloat(bbInput.value) || 0;
    const tb = parseFloat(tbInput.value) || 0;

    console.log('REMAJA: Calculating IMT - BB:', bb, 'TB:', tb);

    if (bb > 0 && tb > 0) {
      // Calculate IMT
      const imt = bb / Math.pow(tb / 100, 2);
      imtOutput.value = imt.toFixed(1);

      // Determine category (WHO 2007 for adolescents)
      let kategori = '';
      if (imt < 17) {
        kategori = 'Sangat Kurus';
      } else if (imt < 18.5) {
        kategori = 'Kurus';
      } else if (imt < 25) {
        kategori = 'Normal';
      } else if (imt < 30) {
        kategori = 'Gemuk';
      } else {
        kategori = 'Obesitas';
      }

      kesimpulanOutput.value = kategori;
      console.log(`✅ REMAJA: IMT calculated - ${imt.toFixed(1)} (${kategori})`);
    } else {
      imtOutput.value = '';
      kesimpulanOutput.value = '';
      console.log('🔄 REMAJA: IMT cleared (incomplete input)');
    }
  }

  /**
   * ✅ Calculate lingkar perut status (IDF 2007 criteria for Asian population)
   */
  calculateLingkarPerut() {
    const lpInput = document.getElementById('lingkar_perut');
    const statusOutput = document.getElementById('status_lingkar_perut');

    if (!lpInput || !statusOutput) {
      console.warn('⚠️ REMAJA: Lingkar perut elements not found');
      return;
    }

    const lp = parseFloat(lpInput.value) || 0;
    console.log('REMAJA: Calculating lingkar perut status - LP:', lp);

    if (lp > 0) {
      let status = '';
      // IDF 2007 criteria for Asian population (simplified)
      if (lp >= 90) {
        status = 'Risiko Tinggi'; // High risk for metabolic syndrome
      } else if (lp >= 80) {
        status = 'Risiko Sedang'; // Moderate risk
      } else {
        status = 'Normal';
      }

      statusOutput.value = status;
      console.log(`✅ REMAJA: Lingkar perut status - ${lp}cm (${status})`);
    } else {
      statusOutput.value = '';
      console.log('🔄 REMAJA: Lingkar perut status cleared');
    }
  }

  /**
   * ✅ Calculate tekanan darah category (AHA 2017 Guidelines - Simplified)
   */
  calculateTekananDarah() {
    const sistoleInput = document.getElementById('sistole');
    const diastoleInput = document.getElementById('diastole');
    const kategoriOutput = document.getElementById('kategori_tekanan_darah');
    const sistoleOutput = document.getElementById('kesimpulan_sistole');
    const diastoleOutput = document.getElementById('kesimpulan_diastole');

    if (!sistoleInput || !diastoleInput || !kategoriOutput) {
      console.warn('⚠️ REMAJA: Tekanan darah elements not found');
      return;
    }

    const sistole = parseInt(sistoleInput.value) || 0;
    const diastole = parseInt(diastoleInput.value) || 0;

    console.log('REMAJA: Calculating tekanan darah - Sistole:', sistole, 'Diastole:', diastole);

    if (sistole > 0 && diastole > 0) {
      let kategori = '';
      let sistoleKategori = '';
      let diastoleKategori = '';

      // ✅ SIMPLIFIED SISTOLE CATEGORIES (Normal, Tinggi, Rendah)
      if (sistole < 90) {
        sistoleKategori = 'Rendah';
      } else if (sistole <= 120) {
        sistoleKategori = 'Normal';
      } else {
        sistoleKategori = 'Tinggi';
      }

      // ✅ SIMPLIFIED DIASTOLE CATEGORIES (Normal, Tinggi, Rendah)
      if (diastole < 60) {
        diastoleKategori = 'Rendah';
      } else if (diastole <= 80) {
        diastoleKategori = 'Normal';
      } else {
        diastoleKategori = 'Tinggi';
      }

      // ✅ SIMPLIFIED OVERALL CATEGORY (3 kategori saja)
      if (sistole < 90 || diastole < 60) {
        kategori = 'Hipotensi';
      } else if (sistole > 120 || diastole > 80) {
        kategori = 'Hipertensi';
      } else {
        kategori = 'Normal';
      }

      kategoriOutput.value = kategori;
      if (sistoleOutput) sistoleOutput.value = sistoleKategori;
      if (diastoleOutput) diastoleOutput.value = diastoleKategori;

      console.log(`✅ REMAJA: Tekanan darah - ${sistole}/${diastole}`);
      console.log(`   - Sistole: ${sistoleKategori}, Diastole: ${diastoleKategori}`);
      console.log(`   - Overall: ${kategori}`);
    } else {
      kategoriOutput.value = '';
      if (sistoleOutput) sistoleOutput.value = '';
      if (diastoleOutput) diastoleOutput.value = '';
      console.log('🔄 REMAJA: Tekanan darah kategori cleared');
    }
  }

  /**
   * ✅ Calculate anemia status (WHO 2011 criteria - Gender specific)
   */
  calculateAnemia() {
    const hbInput = document.getElementById('hb');
    const statusOutput = document.getElementById('status_anemia');
    const jenisKelaminInput = document.getElementById('jenis_kelamin');

    if (!hbInput || !statusOutput || !jenisKelaminInput) {
      console.warn('⚠️ REMAJA: Anemia elements not found');
      return;
    }

    const hb = parseFloat(hbInput.value) || 0;
    const jenisKelamin = jenisKelaminInput.value;

    console.log('REMAJA: Calculating anemia status - HB:', hb, 'Jenis Kelamin:', jenisKelamin);

    if (hb > 0 && jenisKelamin) {
      let status = '';
      let batasNormal = 0;

      // ✅ WHO 2011 criteria berdasarkan jenis kelamin
      if (jenisKelamin === 'Laki-laki') {
        batasNormal = 13.0; // Laki-laki remaja: ≥13.0 g/dL
      } else if (jenisKelamin === 'Perempuan') {
        batasNormal = 12.0; // Perempuan remaja: ≥12.0 g/dL
      } else {
        // Fallback jika jenis kelamin tidak terdeteksi
        batasNormal = 12.0;
        console.warn('⚠️ REMAJA: Jenis kelamin tidak terdeteksi, menggunakan standar perempuan');
      }

      // ✅ SIMPLIFIED: Anemia = Ya/Tidak
      if (hb < batasNormal) {
        status = 'Ya'; // Anemia
      } else {
        status = 'Tidak'; // Tidak anemia
      }

      statusOutput.value = status;
      console.log(`✅ REMAJA: Anemia status - ${hb} g/dL (${jenisKelamin}) = ${status}`);
      console.log(`   Batas normal ${jenisKelamin}: ≥${batasNormal} g/dL`);
    } else {
      statusOutput.value = '';
      if (!jenisKelamin) {
        console.warn('⚠️ REMAJA: Jenis kelamin belum diisi, tidak bisa menghitung anemia');
      }
      console.log('🔄 REMAJA: Anemia status cleared');
    }
  }

  /**
   * ✅ Calculate TBC screening results
   */
  calculateTBCScreening() {
    console.log('🔍 REMAJA: Calculating TBC screening...');

    const batukTerusMenerus = document.getElementById('batuk_terus_menerus')?.checked || false;
    const demam2Minggu = document.getElementById('demam_2_minggu')?.checked || false;
    const bbTidakNaik = document.getElementById('bb_tidak_naik')?.checked || false;
    const kontakTBC = document.getElementById('kontak_tbc')?.checked || false;

    // ✅ HITUNG JUMLAH GEJALA
    let jumlahGejala = 0;
    const gejalaList = [];

    if (batukTerusMenerus) {
      jumlahGejala++;
      gejalaList.push('Batuk terus menerus');
    }
    if (demam2Minggu) {
      jumlahGejala++;
      gejalaList.push('Demam > 2 minggu');
    }
    if (bbTidakNaik) {
      jumlahGejala++;
      gejalaList.push('BB tidak naik/turun');
    }
    if (kontakTBC) {
      jumlahGejala++;
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

    // ✅ UPDATE FIELD RUJUKAN - LANGSUNG BERUBAH WARNA SEJAK 1 GEJALA
    const rujukField = document.getElementById('rujuk_puskesmas');
    if (rujukField) {
      if (jumlahGejala >= 2) {
        rujukField.value = 'RUJUK - Perlu pemeriksaan lebih lanjut di Puskesmas';
        rujukField.className = 'form-control bg-danger text-white font-weight-bold';
      } else if (jumlahGejala === 1) {
        rujukField.value = 'TIDAK RUJUK - Gejala TBC tidak mencukupi';
        rujukField.className = 'form-control bg-success text-white';
      } else {
        // 0 GEJALA - KOSONG DAN PUTIH BERSIH
        rujukField.value = '';
        rujukField.className = 'form-control';
      }
    }

    console.log(`✅ REMAJA: TBC screening - ${jumlahGejala} gejala`);
  }

  /**
   * ✅ Calculate all parameters at once
   */
  calculateAll() {
    console.log('🔄 REMAJA: Calculating all parameters...');
    this.calculateIMT();
    this.calculateLingkarPerut();
    this.calculateTekananDarah();
    this.calculateAnemia();
    // ✅ JANGAN PANGGIL calculateTBCScreening() di awal
    // Biarkan field TBC kosong sampai user centang checkbox
    console.log('✅ REMAJA: All calculations completed');
  }
}

// ✅ Create global instance and expose initialization function
const remajaHandler = new RemajaHandler();

/**
 * ✅ GLOBAL FUNCTION - Called from input-pemeriksaan.blade.php after AJAX inject
 */
function initializeRemajaHandler() {
  console.log('🎯 REMAJA: initializeRemajaHandler called from external script');
  return remajaHandler.initialize();
}

// ✅ Make function available globally
window.initializeRemajaHandler = initializeRemajaHandler;

console.log('✅ REMAJA: Handler setup completed, waiting for initialization call');
