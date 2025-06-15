class BalitaHandler {
  constructor() {
    this.initializeZScoreData();
    this.lastBBData = null; // Store last BB data
  }

  // ... existing zScore data ...

  initializeEventListeners() {
    console.log('=== BALITA HANDLER INITIALIZATION ===');

    const formBalita = document.getElementById('form-balita');

    if (!formBalita) {
      console.log('❌ Form balita not found');
      return;
    }

    console.log('✅ Form balita found, setting up handlers...');

    // ✅ GET LAST EXAMINATION DATA FOR COMPARISON
    this.fetchLastExaminationData();

    // ✅ 1. CALCULATION EVENT LISTENERS
    document.querySelectorAll('#bb, #tb, #umur').forEach((input) => {
      console.log('📊 Adding calculation listener to:', input.id);
      input.addEventListener('input', () => {
        this.calculateAndUpdateResults();
      });
    });

    console.log('✅ Balita handlers attached successfully');
  }

  // ✅ FETCH LAST EXAMINATION DATA
  fetchLastExaminationData() {
    const nikInput = document.getElementById('nik_balita');

    if (!nikInput || !nikInput.value) {
      console.log('📊 No NIK found, skipping last examination fetch');
      return;
    }

    const nik = nikInput.value;
    console.log('📊 Fetching last examination for NIK:', nik);

    fetch(`/get-last-examination/${nik}`)
      .then((response) => response.json())
      .then((data) => {
        console.log('📊 Last examination data:', data);

        if (data && data.bb) {
          // Store last BB data
          this.lastBBData = {
            bb: parseFloat(data.bb),
            tanggal: data.tanggal_pemeriksaan,
          };
          console.log('✅ Last BB data stored:', this.lastBBData);
        } else {
          console.log('📊 No previous examination found');
          this.lastBBData = null;
        }
      })
      .catch((error) => {
        console.log('📊 Error fetching last examination:', error);
        this.lastBBData = null;
      });
  }

  // ✅ CALCULATE AND UPDATE RESULTS (EXISTING + NEW BB COMPARISON)
  calculateAndUpdateResults() {
    console.log('=== CALCULATING RESULTS ===');

    const bb = parseFloat(document.getElementById('bb').value);
    const tb = parseFloat(document.getElementById('tb').value);
    const umur = parseInt(document.getElementById('umur').value);

    console.log('Values - BB:', bb, 'TB:', tb, 'Umur:', umur);

    // ✅ EXISTING CALCULATIONS
    const kesimpulanBBU = !isNaN(bb) && !isNaN(umur) ? this.getKesimpulanBBU(bb, umur) : '';
    const kesimpulanTBU = !isNaN(tb) && !isNaN(umur) ? this.getKesimpulanTBU(tb, umur) : '';
    const kesimpulanBBTB = !isNaN(bb) && !isNaN(tb) ? this.getKesimpulanBBTB(bb, tb) : '';

    // ✅ NEW BB COMPARISON CALCULATION
    const statusPerubahanBB = !isNaN(bb) ? this.getStatusPerubahanBB(bb) : '';

    // ✅ UPDATE FORM FIELDS
    document.getElementById('kesimpulan_bbu').value = kesimpulanBBU;
    document.getElementById('kesimpulan_tbuu').value = kesimpulanTBU;
    document.getElementById('kesimpulan_bbtb').value = kesimpulanBBTB;
    document.getElementById('status_perubahan_bb').value = statusPerubahanBB;

    console.log('✅ Results updated with BB comparison');
  }

  // ✅ NEW METHOD: GET STATUS PERUBAHAN BB
  getStatusPerubahanBB(currentBB) {
    console.log('📊 Calculating BB status change:', currentBB, 'vs', this.lastBBData);

    if (!this.lastBBData) {
      return 'Pemeriksaan pertama - Data baseline';
    }

    const lastBB = this.lastBBData.bb;
    const difference = currentBB - lastBB;
    const percentageChange = (difference / lastBB) * 100;

    console.log('📊 BB Difference:', difference, 'Percentage:', percentageChange);

    if (difference > 0) {
      // BERAT NAIK
      if (percentageChange >= 10) {
        return `Berat badan naik signifikan (+${difference.toFixed(
          2
        )} kg, +${percentageChange.toFixed(1)}%)`;
      } else if (percentageChange >= 5) {
        return `Berat badan naik baik (+${difference.toFixed(2)} kg, +${percentageChange.toFixed(
          1
        )}%)`;
      } else {
        return `Berat badan naik (+${difference.toFixed(2)} kg, +${percentageChange.toFixed(1)}%)`;
      }
    } else if (difference < 0) {
      // BERAT TURUN
      const absPercentage = Math.abs(percentageChange);
      if (absPercentage >= 10) {
        return `Berat badan turun signifikan (${difference.toFixed(
          2
        )} kg, ${percentageChange.toFixed(1)}%) - Perlu evaluasi`;
      } else if (absPercentage >= 5) {
        return `Berat badan turun cukup banyak (${difference.toFixed(
          2
        )} kg, ${percentageChange.toFixed(1)}%) - Perlu perhatian`;
      } else {
        return `Berat badan turun sedikit (${difference.toFixed(2)} kg, ${percentageChange.toFixed(
          1
        )}%)`;
      }
    } else {
      // SAMA
      return `Berat badan stabil (tidak ada perubahan dari ${lastBB} kg)`;
    }
  }

  // ... keep existing methods (getKesimpulanBBU, getKesimpulanTBU, getKesimpulanBBTB) ...
}

// ✅ UPDATE GLOBAL INITIALIZATION
const balitaHandler = new BalitaHandler();

function initializeBalitaHandler() {
  console.log('🔄 Initializing Balita Handler from external call...');
  balitaHandler.initializeEventListeners();
}

window.initializeBalitaHandler = initializeBalitaHandler;

// ✅ OBSERVER FOR DYNAMIC FORM DETECTION (KEEP EXISTING)
document.addEventListener('DOMContentLoaded', function () {
  console.log('🔧 Balita handler DOM ready, setting up observer...');

  const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
      mutation.addedNodes.forEach(function (node) {
        if (node.nodeType === 1) {
          if (node.id === 'form-balita' || node.querySelector('#form-balita')) {
            console.log('🔄 Balita form detected in DOM, auto-initializing...');
            setTimeout(() => {
              initializeBalitaHandler();
            }, 100);
          }
        }
      });
    });
  });

  const formContainer = document.getElementById('form-pemeriksaan');
  if (formContainer) {
    observer.observe(formContainer, {
      childList: true,
      subtree: true,
    });
    console.log('✅ Form observer attached to #form-pemeriksaan');
  }
});
