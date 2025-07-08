class LansiaHandler {
  constructor() {
    this.formId = 'form-lansia';
  }

  initialize() {
    const form = document.getElementById(this.formId);
    if (!form) return false;

    // IMT calculation
    ['bb', 'tb'].forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('input', () => this.calculateIMT());
      }
    });

    // Sistole & Diastole
    ['sistole', 'diastole'].forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('input', () => {
          this.kesimpulanSistole();
          this.kesimpulanDiastole();
          this.kesimpulanTD();
        });
      }
    });

    // Gula darah
    const gulaInput = document.getElementById('gula_darah');
    if (gulaInput) {
      gulaInput.addEventListener('input', () => this.kesimpulanGula());
    }

    // PUMA
    [
      'puma_jk',
      'puma_usia',
      'puma_rokok',
      'puma_napas',
      'puma_dahak',
      'puma_batuk',
      'puma_spirometri',
    ].forEach((id) => {
      const input = document.getElementById(id);
      if (input) {
        input.addEventListener('change', () => this.hitungPUMA());
      }
    });

    // Skrining TBC
    ['tbc_batuk', 'tbc_demam', 'tbc_bb_turun', 'tbc_kontak'].forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('change', () => this.cekTBC());
    });
    this.cekTBC();

    // Edukasi wajib jika rujuk
    const statusTBC = document.getElementById('status_tbc');
    const edukasi = document.getElementById('edukasi');
    if (statusTBC && edukasi) {
      statusTBC.addEventListener('input', () => {
        edukasi.required = statusTBC.value.includes('Rujuk');
      });
    }

    return true;
  }

  calculateIMT() {
    const bb = parseFloat(document.getElementById('bb').value) || 0;
    const tb = parseFloat(document.getElementById('tb').value) || 0;
    const imtField = document.getElementById('imt');
    const kesimpulanField = document.getElementById('kesimpulan_imt');
    if (bb > 0 && tb > 0) {
      const tbMeter = tb / 100;
      const imt = bb / (tbMeter * tbMeter);
      imtField.value = imt.toFixed(1);
      let status = '';
      if (imt < 17) status = 'Sangat Kurus';
      else if (imt < 18.5) status = 'Kurus';
      else if (imt < 23) status = 'Normal';
      else if (imt < 25) status = 'Gemuk';
      else status = 'Obesitas';
      kesimpulanField.value = status;
    } else {
      imtField.value = '';
      kesimpulanField.value = '';
    }
  }

  kesimpulanSistole() {
    const sistole = parseInt(document.getElementById('sistole').value) || 0;
    const field = document.getElementById('kesimpulan_sistole');
    let status = '';
    if (sistole < 90) status = 'Rendah';
    else if (sistole > 140) status = 'Tinggi';
    else status = 'Normal';
    field.value = status;
  }

  kesimpulanDiastole() {
    const diastole = parseInt(document.getElementById('diastole').value) || 0;
    const field = document.getElementById('kesimpulan_diastole');
    let status = '';
    if (diastole < 60) status = 'Rendah';
    else if (diastole > 90) status = 'Tinggi';
    else status = 'Normal';
    field.value = status;
  }

  kesimpulanTD() {
    const sistole = parseInt(document.getElementById('sistole').value) || 0;
    const diastole = parseInt(document.getElementById('diastole').value) || 0;
    const field = document.getElementById('kesimpulan_td');
    let status = '';
    if (sistole < 90 || diastole < 60) status = 'Hipotensi';
    else if (sistole > 140 || diastole > 90) status = 'Hipertensi';
    else status = 'Normal';
    field.value = status;
  }

  kesimpulanGula() {
    const gula = parseFloat(document.getElementById('gula_darah').value) || 0;
    const field = document.getElementById('kesimpulan_gula_darah');
    let status = '';
    if (gula < 70) status = 'Rendah';
    else if (gula > 200) status = 'Tinggi';
    else status = 'Normal';
    field.value = status;
  }

  hitungPUMA() {
    const getVal = (id) => parseInt(document.getElementById(id)?.value) || 0;
    const skor =
      getVal('puma_jk') +
      getVal('puma_usia') +
      getVal('puma_rokok') +
      getVal('puma_napas') +
      getVal('puma_dahak') +
      getVal('puma_batuk') +
      getVal('puma_spirometri');
    document.getElementById('skor_puma').value = skor;
    const status = skor > 6 ? 'Rujuk ke Puskesmas' : 'Aman';
    document.getElementById('status_puma').value = status;
  }

  // cekTBC() {
  //   const gejala = [
  //     document.getElementById('tbc_batuk')?.checked,
  //     document.getElementById('tbc_demam')?.checked,
  //     document.getElementById('tbc_bb_turun')?.checked,
  //     document.getElementById('tbc_kontak')?.checked,
  //   ].filter(Boolean).length;
  //   const status = gejala >= 2 ? 'Rujuk ke Puskesmas' : 'Tidak Perlu Rujuk';
  //   const statusField = document.getElementById('status_tbc');
  //   if (statusField) statusField.value = status;

  //   // Edukasi wajib jika rujuk
  //   const edukasi = document.getElementById('edukasi');
  //   if (edukasi) edukasi.required = status === 'Rujuk ke Puskesmas';
  // }

  cekTBC() {
    // ✅ HITUNG GEJALA TBC YANG DI-CHECK
    const gejala = [
      document.getElementById('tbc_batuk')?.checked,
      document.getElementById('tbc_demam')?.checked,
      document.getElementById('tbc_bb_turun')?.checked,
      document.getElementById('tbc_kontak')?.checked,
    ].filter(Boolean).length;

    // ✅ COLLECT GEJALA YANG DIPILIH
    const gejalaList = [];
    if (document.getElementById('tbc_batuk')?.checked) {
      gejalaList.push('Batuk terus menerus');
    }
    if (document.getElementById('tbc_demam')?.checked) {
      gejalaList.push('Demam >2 minggu');
    }
    if (document.getElementById('tbc_bb_turun')?.checked) {
      gejalaList.push('BB tidak naik/turun');
    }
    if (document.getElementById('tbc_kontak')?.checked) {
      gejalaList.push('Kontak erat TBC');
    }

    console.log('LANSIA: TBC screening - checked:', gejala);

    // ✅ UPDATE FIELD JUMLAH GEJALA DENGAN WARNA
    const jumlahGejalaField = document.getElementById('jumlah_gejala_tbc');
    if (jumlahGejalaField) {
      if (gejala === 0) {
        jumlahGejalaField.value = '';
        jumlahGejalaField.className = 'form-control';
      } else if (gejala === 1) {
        // ✅ 1 GEJALA = HIJAU (AMAN)
        jumlahGejalaField.value = `${gejala} gejala: ${gejalaList.join(', ')}`;
        jumlahGejalaField.className = 'form-control status-hijau';
      } else {
        // ✅ 2+ GEJALA = MERAH (RUJUK)
        jumlahGejalaField.value = `${gejala} gejala: ${gejalaList.join(', ')}`;
        jumlahGejalaField.className = 'form-control status-merah';
      }
    }

    // ✅ UPDATE FIELD STATUS TBC DENGAN WARNA
    const status =
      gejala >= 2
        ? 'RUJUK - Perlu pemeriksaan lebih lanjut di Puskesmas'
        : 'TIDAK RUJUK - Gejala TBC tidak mencukupi';
    const statusField = document.getElementById('status_tbc');

    if (statusField) {
      if (gejala === 0) {
        // ✅ KOSONG KALAU BELUM PILIH GEJALA
        statusField.value = '';
        statusField.className = 'form-control';
      } else if (gejala >= 2) {
        // ✅ RUJUK = MERAH
        statusField.value = status;
        statusField.className = 'form-control status-merah';
      } else {
        // ✅ TIDAK RUJUK = HIJAU
        statusField.value = status;
        statusField.className = 'form-control status-hijau';
      }
    }

    // ✅ EDUKASI WAJIB JIKA RUJUK
    const edukasi = document.getElementById('edukasi');
    if (edukasi) {
      edukasi.required = gejala >= 2;

      // ✅ UPDATE LABEL EDUKASI
      const edukasiLabel = document.querySelector('label[for="edukasi"]');
      if (edukasiLabel) {
        // Hapus tanda * lama
        edukasiLabel.innerHTML = edukasiLabel.innerHTML.replace(
          ' <span class="text-danger">*</span>',
          ''
        );

        if (gejala >= 2) {
          edukasiLabel.innerHTML += ' <span class="text-danger">*</span>';
        }
      }
    }

    console.log(`✅ LANSIA: TBC screening - ${gejala} gejala`);
  }
}

const lansiaHandler = new LansiaHandler();
function initializeLansiaHandler() {
  return lansiaHandler.initialize();
}
window.initializeLansiaHandler = initializeLansiaHandler;

document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('form-pemeriksaan');
  if (!container) return;

  container.addEventListener('click', function (e) {
    if (e.target && e.target.id === 'btn-skrining-tahunan') {
      const userId = e.target.dataset.userId;
      fetch(`/skrining-tahunan-lansia/${userId}`)
        .then((res) => res.text())
        .then((html) => {
          const modalContent = document.getElementById('modal-skrining-tahunan-content');
          if (modalContent) {
            modalContent.innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('skriningTahunanModal'));
            modal.show();
          } else {
            console.error('❌ Element #modal-skrining-tahunan-content tidak ditemukan.');
          }
        })
        .catch((err) => {
          alert('❌ Gagal memuat form skrining: ' + err.message);
        });
    }
  });
});
