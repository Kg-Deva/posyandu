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
    this.setupFormValidation(); // ✅ TAMBAH: Setup validasi form
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
          this.calculateBBStatus();
          this.calculateRujukan();
        });
        console.log(`✅ IBU HAMIL: Listener attached to ${id}`);
      } else {
        console.warn(`⚠️ IBU HAMIL: Element ${id} not found`);
      }
    });

    // LILA
    const lilaInput = document.getElementById('lila');
    if (lilaInput) {
      lilaInput.addEventListener('input', () => {
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
          this.calculateTekananDarah();
          this.calculateRujukan();
        });
        console.log(`✅ IBU HAMIL: Listener attached to ${id}`);
      }
    });

    // ✅ TAMBAH: Suplementasi dan Gizi Event Listeners
    const konsumsiTTD = document.getElementById('konsumsi_tablet_fe');
    const konsumsiMT = document.getElementById('konsumsi_mt_bumilkek');

    if (konsumsiTTD) {
      konsumsiTTD.addEventListener('change', () => {
        this.updateEdukasiValidation();
      });
      console.log('✅ IBU HAMIL: Listener attached to konsumsi_tablet_fe');
    }

    if (konsumsiMT) {
      konsumsiMT.addEventListener('change', () => {
        this.updateEdukasiValidation();
      });
      console.log('✅ IBU HAMIL: Listener attached to konsumsi_mt_bumilkek');
    }

    // ✅ TAMBAH: Jumlah Porsi MT Event Listener
    const jumlahPorsiMT = document.getElementById('jumlah_porsi_mt');
    if (jumlahPorsiMT) {
      jumlahPorsiMT.addEventListener('input', () => {
        this.updateKomposisiMTValidation();
      });
      console.log('✅ IBU HAMIL: Listener attached to jumlah_porsi_mt');
    }

    // ✅ UPDATE: Kelas Ibu Event Listeners (TANPA frekuensi)
    const mengikutiKelas = document.getElementById('mengikuti_kelas_ibu');

    if (mengikutiKelas) {
      mengikutiKelas.addEventListener('change', () => {
        this.updateEdukasiValidation();
      });
      console.log('✅ IBU HAMIL: Listener attached to mengikuti_kelas_ibu');
    }

    console.log('🎯 IBU HAMIL: All event listeners attached');
  }

  /**
   * ✅ TAMBAH: Setup form validation
   */
  setupFormValidation() {
    console.log('🔧 IBU HAMIL: Setting up form validation...');

    const form = document.getElementById(this.formId);
    if (form) {
      form.addEventListener('submit', (e) => {
        if (!this.validateForm()) {
          e.preventDefault();
          console.log('❌ IBU HAMIL: Form submission blocked due to validation errors');
        }
      });
      console.log('✅ IBU HAMIL: Form validation listener attached');
    }
  }

  /**
   * ✅ UPDATE: Update edukasi field validation based on konsumsi + kelas ibu (TANPA frekuensi)
   */
  updateEdukasiValidation() {
    const konsumsiTTD = document.getElementById('konsumsi_tablet_fe');
    const konsumsiMT = document.getElementById('konsumsi_mt_bumilkek');
    const mengikutiKelas = document.getElementById('mengikuti_kelas_ibu');
    const edukasiField = document.getElementById('edukasi');
    const edukasiLabel = document.querySelector('label[for="edukasi"]');

    if (!konsumsiTTD || !konsumsiMT || !mengikutiKelas || !edukasiField || !edukasiLabel) {
      console.warn('⚠️ IBU HAMIL: Edukasi validation elements not found');
      return;
    }

    const ttdValue = konsumsiTTD.value;
    const mtValue = konsumsiMT.value;
    const kelasValue = mengikutiKelas.value;

    let needsEducation = false;
    let reasons = [];

    // ✅ EVALUASI TTD
    if (ttdValue === 'Tidak setiap hari') {
      needsEducation = true;
      reasons.push('konsumsi TTD tidak setiap hari');
    }

    // ✅ EVALUASI MT BUMIL KEK
    if (mtValue === 'Tidak setiap hari') {
      needsEducation = true;
      reasons.push('konsumsi MT tidak setiap hari');
    }

    // ✅ EVALUASI KELAS IBU
    if (kelasValue === 'Tidak') {
      needsEducation = true;
      reasons.push('tidak mengikuti kelas ibu');
    }

    console.log(
      'IBU HAMIL: Updating edukasi validation - Needs education:',
      needsEducation,
      'Reasons:',
      reasons
    );

    if (needsEducation) {
      // ✅ WAJIB: Set required dan update label
      edukasiField.setAttribute('required', 'required');
      edukasiLabel.innerHTML = 'Edukasi / Konseling <span class="text-danger">*</span>';

      // ✅ Update help text
      let helpText = edukasiField.parentNode.querySelector('.form-text');
      if (!helpText) {
        helpText = document.createElement('div');
        helpText.className = 'form-text text-muted';
        edukasiField.parentNode.appendChild(helpText);
      } else {
        helpText.className = 'form-text text-warning';
      }
      helpText.innerHTML = `<i class="bi bi-exclamation-triangle"></i> Field ini wajib diisi karena: ${reasons.join(
        ', '
      )}`;
    } else {
      // ✅ OPSIONAL: Remove required dan restore label
      edukasiField.removeAttribute('required');
      edukasiLabel.innerHTML = 'Edukasi / Konseling (Opsional)';

      // ✅ Update help text
      let helpText = edukasiField.parentNode.querySelector('.form-text');
      if (!helpText) {
        helpText = document.createElement('div');
        helpText.className = 'form-text text-muted';
        edukasiField.parentNode.appendChild(helpText);
      } else {
        helpText.className = 'form-text text-muted';
      }
      helpText.innerHTML =
        '<i class="bi bi-info-circle"></i> Field ini akan menjadi wajib jika konsumsi TTD/MT tidak setiap hari atau tidak mengikuti kelas ibu';
    }

    // ✅ Clear previous validation state
    edukasiField.classList.remove('is-invalid');
    const invalidFeedback = edukasiField.parentNode.querySelector('.invalid-feedback');
    if (invalidFeedback) {
      invalidFeedback.remove();
    }
  }

  /**
   * ✅ TAMBAH: Update komposisi MT validation based on jumlah porsi
   */
  updateKomposisiMTValidation() {
    const jumlahPorsiMT = document.getElementById('jumlah_porsi_mt');
    const komposisiMT = document.getElementById('komposisi_mt_bumilkek');
    const komposisiLabel = document.querySelector('label[for="komposisi_mt_bumilkek"]');

    if (!jumlahPorsiMT || !komposisiMT || !komposisiLabel) {
      console.warn('⚠️ IBU HAMIL: Komposisi MT validation elements not found');
      return;
    }

    const jumlahPorsi = parseInt(jumlahPorsiMT.value) || 0;
    const needsKomposisi = jumlahPorsi >= 1;

    console.log(
      'IBU HAMIL: Updating komposisi MT validation - Jumlah porsi:',
      jumlahPorsi,
      'Needs komposisi:',
      needsKomposisi
    );

    if (needsKomposisi) {
      // ✅ WAJIB: Enable field dan set required
      komposisiMT.removeAttribute('disabled');
      komposisiMT.setAttribute('required', 'required');
      komposisiMT.classList.remove('bg-light');
      komposisiLabel.innerHTML = 'Komposisi MT Bumil KEK <span class="text-danger">*</span>';

      // ✅ Update help text
      let helpText = komposisiMT.parentNode.querySelector('.form-text');
      if (helpText) {
        helpText.className = 'form-text text-warning';
        helpText.innerHTML =
          '<i class="bi bi-exclamation-triangle"></i> Field ini wajib diisi karena jumlah porsi ≥1';
      }
    } else {
      // ✅ OPSIONAL: Disable field dan remove required
      komposisiMT.setAttribute('disabled', 'disabled');
      komposisiMT.removeAttribute('required');
      komposisiMT.classList.add('bg-light');
      komposisiMT.value = ''; // Clear value
      komposisiLabel.innerHTML = 'Komposisi MT Bumil KEK';

      // ✅ Update help text
      let helpText = komposisiMT.parentNode.querySelector('.form-text');
      if (helpText) {
        helpText.className = 'form-text text-muted';
        helpText.innerHTML = 'Sebutkan jenis makanan tambahan yang diberikan (aktif jika porsi ≥1)';
      }
    }

    // ✅ Clear previous validation state
    komposisiMT.classList.remove('is-invalid');
    const invalidFeedback = komposisiMT.parentNode.querySelector('.invalid-feedback');
    if (invalidFeedback) {
      invalidFeedback.remove();
    }
  }

  /**
   * ✅ UPDATE: Validate form before submission (TANPA frekuensi)
   */
  validateForm() {
    console.log('🔍 IBU HAMIL: Validating form...');

    const konsumsiTTD = document.getElementById('konsumsi_tablet_fe');
    const konsumsiMT = document.getElementById('konsumsi_mt_bumilkek');
    const mengikutiKelas = document.getElementById('mengikuti_kelas_ibu');
    const edukasiField = document.getElementById('edukasi');
    const jumlahPorsiMT = document.getElementById('jumlah_porsi_mt');
    const komposisiMT = document.getElementById('komposisi_mt_bumilkek');

    if (
      !konsumsiTTD ||
      !konsumsiMT ||
      !mengikutiKelas ||
      !edukasiField ||
      !jumlahPorsiMT ||
      !komposisiMT
    ) {
      console.warn('⚠️ IBU HAMIL: Form validation elements not found');
      return true; // Allow submission if elements not found
    }

    const ttdValue = konsumsiTTD.value;
    const mtValue = konsumsiMT.value;
    const kelasValue = mengikutiKelas.value;
    const edukasiValue = edukasiField.value.trim();
    const jumlahPorsi = parseInt(jumlahPorsiMT.value) || 0;
    const komposisiValue = komposisiMT.value.trim();

    const needsEducation =
      ttdValue === 'Tidak setiap hari' || mtValue === 'Tidak setiap hari' || kelasValue === 'Tidak';
    const needsKomposisi = jumlahPorsi >= 1;

    let validationErrors = [];

    // ✅ Validasi edukasi
    if (needsEducation && !edukasiValue) {
      edukasiField.classList.add('is-invalid');
      edukasiField.focus();

      const existingFeedback = edukasiField.parentNode.querySelector('.invalid-feedback');
      if (existingFeedback) {
        existingFeedback.remove();
      }

      const invalidFeedback = document.createElement('div');
      invalidFeedback.className = 'invalid-feedback';
      invalidFeedback.innerHTML =
        'Field edukasi/konseling wajib diisi berdasarkan kondisi yang dipilih.';
      edukasiField.parentNode.appendChild(invalidFeedback);

      validationErrors.push(
        'Field "Edukasi / Konseling" wajib diisi karena ada kondisi yang memerlukan perhatian khusus.'
      );
    }

    // ✅ Validasi komposisi MT
    if (needsKomposisi && !komposisiValue) {
      komposisiMT.classList.add('is-invalid');
      if (validationErrors.length === 0) {
        komposisiMT.focus();
      }

      const existingFeedback = komposisiMT.parentNode.querySelector('.invalid-feedback');
      if (existingFeedback) {
        existingFeedback.remove();
      }

      const invalidFeedback = document.createElement('div');
      invalidFeedback.className = 'invalid-feedback';
      invalidFeedback.innerHTML = 'Komposisi MT wajib diisi karena ada pemberian MT (≥1 porsi).';
      komposisiMT.parentNode.appendChild(invalidFeedback);

      validationErrors.push('Field "Komposisi MT Bumil KEK" wajib diisi karena jumlah porsi ≥1.');
    }

    // ✅ Show validation errors
    if (validationErrors.length > 0) {
      alert('Validasi Form:\n\n' + validationErrors.join('\n'));
      console.log('❌ IBU HAMIL: Form validation failed');
      return false; // Block form submission
    }

    // ✅ Remove validation errors if all valid
    edukasiField.classList.remove('is-invalid');
    komposisiMT.classList.remove('is-invalid');

    [edukasiField, komposisiMT].forEach((field) => {
      const invalidFeedback = field.parentNode.querySelector('.invalid-feedback');
      if (invalidFeedback) {
        invalidFeedback.remove();
      }
    });

    console.log('✅ IBU HAMIL: Form validation passed');
    return true; // Allow form submission
  }

  /**
   * ✅ Setup TBC screening calculation
   */
  setupTBCScreening() {
    console.log('🔧 IBU HAMIL: Setting up TBC screening...');

    const tbcCheckboxes = document.querySelectorAll('.skrining-tbc');
    tbcCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', () => {
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
      let status, cssClass;

      // ✅ LOGIKA SEDERHANA: BB normal untuk ibu hamil
      if (usia <= 12) {
        // Trimester 1 (1-12 minggu): kenaikan minimal
        if (bb >= 45 && bb <= 90) {
          status = 'Ya - BB sesuai kurva KIA (Trimester 1)';
          cssClass = 'status-hijau';
        } else {
          status = 'Tidak - BB tidak sesuai kurva KIA (Trimester 1)';
          cssClass = 'status-merah';
        }
      } else if (usia <= 28) {
        // Trimester 2 (13-28 minggu): kenaikan progresif
        if (bb >= 50 && bb <= 95) {
          status = 'Ya - BB sesuai kurva KIA (Trimester 2)';
          cssClass = 'status-hijau';
        } else {
          status = 'Tidak - BB tidak sesuai kurva KIA (Trimester 2)';
          cssClass = 'status-merah';
        }
      } else {
        // Trimester 3 (29-42 minggu): kenaikan maksimal
        if (bb >= 55 && bb <= 100) {
          status = 'Ya - BB sesuai kurva KIA (Trimester 3)';
          cssClass = 'status-hijau';
        } else {
          status = 'Tidak - BB tidak sesuai kurva KIA (Trimester 3)';
          cssClass = 'status-merah';
        }
      }

      statusOutput.value = status;
      statusOutput.className = `form-control ${cssClass}`;
    } else {
      statusOutput.value = '';
      statusOutput.className = 'form-control';
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
      let status, cssClass;

      if (lila >= 23.5) {
        status = 'Ya - LILA Normal (≥ 23.5 cm)';
        cssClass = 'status-hijau';
      } else {
        status = 'Tidak - LILA Kurang Gizi (< 23.5 cm)';
        cssClass = 'status-merah';
      }

      statusOutput.value = status;
      statusOutput.className = `form-control ${cssClass}`;
    } else {
      statusOutput.value = '';
      statusOutput.className = 'form-control';
    }
  }

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
      // ✅ EVALUASI SISTOLE - 3 Kategori dengan istilah medis
      let sistoleStatus, sistoleCss;
      if (sistole < 90) {
        sistoleStatus = 'Rendah';
        sistoleCss = 'status-merah';
      } else if (sistole >= 90 && sistole < 140) {
        sistoleStatus = 'Normal';
        sistoleCss = 'status-hijau';
      } else {
        sistoleStatus = 'Tinggi';
        sistoleCss = 'status-merah';
      }

      // ✅ EVALUASI DIASTOLE - 3 Kategori dengan istilah medis
      let diastoleStatus, diastoleCss;
      if (diastole < 60) {
        diastoleStatus = 'Rendah';
        diastoleCss = 'status-merah';
      } else if (diastole >= 60 && diastole < 90) {
        diastoleStatus = 'Normal';
        diastoleCss = 'status-hijau';
      } else {
        diastoleStatus = 'Tinggi';
        diastoleCss = 'status-merah';
      }

      // ✅ STATUS KESELURUHAN - Spesifik dengan kondisi yang jelas
      let overallStatus, overallCss;

      if (sistoleStatus === 'Normal' && diastoleStatus === 'Normal') {
        // Kedua normal
        overallStatus = 'Ya - Tekanan darah normal sesuai KIA';
        overallCss = 'status-hijau';
      } else if (sistoleStatus === 'Tinggi' || diastoleStatus === 'Tinggi') {
        // Salah satu atau kedua Tinggi - prioritas tinggi
        overallStatus = 'Tidak - Hipertensi (perlu rujukan segera)';
        overallCss = 'status-merah';
      } else if (sistoleStatus === 'Rendah' || diastoleStatus === 'Rendah') {
        // Salah satu atau kedua hipotensi
        overallStatus = 'Tidak - Hipotensi (perlu rujukan)';
        overallCss = 'status-merah';
      } else {
        // Mix status yang tidak umum (fallback, seharusnya tidak terjadi)
        overallStatus = 'Tidak - Tekanan darah tidak normal (perlu rujukan)';
        overallCss = 'status-merah';
      }

      // ✅ UPDATE OUTPUT FIELDS
      sistoleOutput.value = sistoleStatus;
      sistoleOutput.className = `form-control ${sistoleCss}`;

      diastoleOutput.value = diastoleStatus;
      diastoleOutput.className = `form-control ${diastoleCss}`;

      statusKIAOutput.value = overallStatus;
      statusKIAOutput.className = `form-control ${overallCss}`;
    } else {
      // ✅ CLEAR JIKA BELUM ADA INPUT
      sistoleOutput.value = '';
      sistoleOutput.className = 'form-control';

      diastoleOutput.value = '';
      diastoleOutput.className = 'form-control';

      statusKIAOutput.value = '';
      statusKIAOutput.className = 'form-control';
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
        jumlahGejalaField.value = '';
        jumlahGejalaField.className = 'form-control';
      } else if (jumlahGejala === 1) {
        // ✅ 1 GEJALA = HIJAU (AMAN)
        jumlahGejalaField.value = `${jumlahGejala} gejala: ${gejalaList.join(', ')}`;
        jumlahGejalaField.className = 'form-control status-hijau';
      } else {
        jumlahGejalaField.value = `${jumlahGejala} gejala: ${gejalaList.join(', ')}`;
        jumlahGejalaField.className = 'form-control status-merah';
      }
    }

    // ✅ UPDATE FIELD RUJUKAN TBC - LANGSUNG BERUBAH WARNA SEJAK 1 GEJALA
    const rujukField = document.getElementById('rujuk_puskesmas_tbc');
    if (rujukField) {
      if (jumlahGejala === 0) {
        rujukField.value = '';
        rujukField.className = 'form-control';
      } else if (jumlahGejala >= 2) {
        rujukField.value = 'RUJUK - Perlu pemeriksaan lebih lanjut di Puskesmas';
        rujukField.className = 'form-control status-merah';
      } else {
        rujukField.value = 'TIDAK RUJUK - Gejala TBC tidak mencukupi';
        rujukField.className = 'form-control status-hijau';
      }
    }

    console.log(`✅ IBU HAMIL: TBC screening - ${jumlahGejala} gejala`);
  }

  /**
   * ✅ Calculate rujukan status - Jika ada merah/kuning = rujuk
   */
  calculateRujukan() {
    const statusBBField = document.getElementById('status_bb_kia');
    const statusLILAField = document.getElementById('status_lila');
    const statusTDField = document.getElementById('status_td_kia');
    const rujukanOutput = document.getElementById('status_rujukan');

    if (!rujukanOutput) {
      console.warn('⚠️ IBU HAMIL: Rujukan output field not found');
      return;
    }

    // Check if any field has "merah" atau "kuning" status (contains "Tidak")
    let hasAbnormalStatus = false;
    const abnormalConditions = [];

    if (statusBBField && statusBBField.value.includes('Tidak')) {
      hasAbnormalStatus = true;
      abnormalConditions.push('BB tidak sesuai kurva');
    }

    if (statusLILAField && statusLILAField.value.includes('Tidak')) {
      hasAbnormalStatus = true;
      abnormalConditions.push('LILA kurang normal');
    }

    if (statusTDField && statusTDField.value.includes('Tidak')) {
      hasAbnormalStatus = true;
      abnormalConditions.push('Tekanan darah tidak normal');
    }

    // Set rujukan status
    if (hasAbnormalStatus) {
      rujukanOutput.value = `Rujuk ke Puskesmas - ${abnormalConditions.join(', ')}`;
      rujukanOutput.className = 'form-control status-merah';
    } else if (statusBBField.value || statusLILAField.value || statusTDField.value) {
      rujukanOutput.value = 'Tidak Rujuk - Semua parameter normal';
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
    // ✅ TAMBAH: Initial calculation untuk validasi
    this.updateEdukasiValidation(); // ✅ Update validasi edukasi
    this.updateKomposisiMTValidation(); // ✅ Update validasi komposisi MT
    // TBC tetap kosong di awal
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
