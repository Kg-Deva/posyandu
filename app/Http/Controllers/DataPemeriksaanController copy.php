<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\PemeriksaanBalita;
use App\Models\User;
use App\Models\PemeriksaanRemaja;
use App\Models\PemeriksaanIbuHamil;
use App\Models\PemeriksaanDewasa;
use App\Models\PemeriksaanLansia;
use App\Exports\DataPemeriksaanExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BalitaExport;
use App\Exports\RemajaExport;
use App\Exports\RekapBalitaExport;
use App\Exports\RekapRemajaExport;

class DataPemeriksaanController extends Controller
{
    public function index(Request $request)
    {
        return view('admin-page.kader.data-pemeriksaan');
    }

    public function getData(Request $request)
    {
        try {
            $role = $request->get('role', '');
            $allData = collect();

            // ✅ BALITA DATA - LOGIC SEDERHANA
            if (empty($role) || $role === 'balita') {
                $balitaQuery = PemeriksaanBalita::with(['user' => function ($q) {
                    $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
                }]);

                $this->applyFilters($balitaQuery, $request);

                $balitaData = $balitaQuery->get()->map(function ($item) {
                    $rujukanStatus = 'Normal';

                    // 1. Cek field rujuk_puskesmas
                    // if (
                    //     isset($item->rujuk_puskesmas) &&
                    //     (str_contains($item->rujuk_puskesmas, 'RUJUK') || $item->rujuk_puskesmas === 'Perlu Rujukan')
                    // ) {
                    if (
                        isset($item->rujuk_puskesmas) &&
                        (strpos($item->rujuk_puskesmas, 'RUJUK') !== false || $item->rujuk_puskesmas === 'Perlu Rujukan')
                    ) {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    // 2. Cek TBC ≥ 2 gejala
                    $jumlahGejala = $item->jumlah_gejala_tbc ?? 0;
                    if (is_string($jumlahGejala) && preg_match('/(\d+)/', $jumlahGejala, $matches)) {
                        $jumlahGejala = (int)$matches[1];
                    }
                    if ($jumlahGejala >= 2) {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    // 3. Cek LILA hanya jika umur ≥ 6 bulan DAN < 11.5 cm
                    $umur = $item->umur ?? 0;
                    if ($umur >= 6 && $item->lila && floatval($item->lila) < 11.5) {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'lingkar_kepala' => $item->lingkar_kepala,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'rujuk_puskesmas' => $rujukanStatus,
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'balita',
                        'model_type' => 'balita'
                    ];
                });

                $allData = $allData->merge($balitaData);
            }

            // REMAJA DATA
            if (empty($role) || $role === 'remaja') {
                $remajaQuery = PemeriksaanRemaja::with(['user' => function ($q) {
                    $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
                }]);

                $this->applyFilters($remajaQuery, $request);

                $remajaData = $remajaQuery->get()->map(function ($item) {
                    $healthStatus = $this->calculateHealthStatus($item, $item->user);

                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'imt' => $item->imt,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'rujuk_puskesmas' => ($healthStatus['category'] === 'urgent' ||
                            $item->rujuk_puskesmas === 'Perlu Rujukan') ? 'Perlu Rujukan' : 'Normal',
                        // 'rujuk_puskesmas' => ($item->rujuk_puskesmas === 'Perlu Rujukan') ? 'Perlu Rujukan' : 'Normal',
                        'health_status' => $healthStatus,
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'remaja',
                        'model_type' => 'remaja'
                    ];
                });

                $allData = $allData->merge($remajaData);
            }

            // IBU HAMIL DATA
            if (empty($role) || $role === 'ibu-hamil') {
                $ibuHamilQuery = PemeriksaanIbuHamil::with(['user' => function ($q) {
                    $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
                }]);

                $this->applyFilters($ibuHamilQuery, $request);

                $ibuHamilData = $ibuHamilQuery->get()->map(function ($item) {
                    $healthStatus = $this->calculateHealthStatus($item, $item->user);

                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->nik,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'usia_kehamilan' => $item->usia_kehamilan,
                        'lila' => $item->lila,
                        'umur' => $item->umur,
                        'perlu_rujukan' => $item->perlu_rujukan,
                        // 'rujuk_puskesmas' => ($healthStatus['category'] === 'urgent' ||
                        //     $item->perlu_rujukan) ? 'Perlu Rujukan' : 'Normal',
                        'rujuk_puskesmas' => ($item->perlu_rujukan) ? 'Perlu Rujukan' : 'Normal',
                        'health_status' => $healthStatus,
                        'pemeriksa' => $item->pemeriksa,
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'ibu-hamil',
                        'model_type' => 'ibu-hamil'
                    ];
                });

                $allData = $allData->merge($ibuHamilData);
            }

            // DEWASA DATA
            if (empty($role) || $role === 'dewasa') {
                $dewasaQuery = PemeriksaanDewasa::with(['user' => function ($q) {
                    $q->select('id', 'nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
                }]);

                $this->applyFilters($dewasaQuery, $request);

                $dewasaData = $dewasaQuery->get()->map(function ($item) {
                    $rujukanStatus = 'Normal';
                    $umur = $item->user ? Carbon::parse($item->user->tanggal_lahir)->age : 0;

                    // 1. Cek status_tbc (hanya jika persis "Rujuk ke Puskesmas" atau "Perlu Rujukan")
                    if (!empty($item->status_tbc) && (
                        $item->status_tbc === 'Rujuk ke Puskesmas' ||
                        $item->status_tbc === 'Perlu Rujukan'
                    )) {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    // 2. Cek status_puma
                    if (!empty($item->status_puma) && (
                        $item->status_puma === 'Rujuk ke Puskesmas' ||
                        $item->status_puma === 'Perlu Rujukan'
                    )) {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    // 3. Cek kesimpulan_td
                    if (!empty($item->kesimpulan_td) && $item->kesimpulan_td === 'Hipertensi') {
                        $rujukanStatus = 'Perlu Rujukan';
                    }
                    // 4. Cek kesimpulan_gula_darah
                    if (!empty($item->kesimpulan_gula_darah) && $item->kesimpulan_gula_darah === 'Diabetes') {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->user->nik ?? null,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'sistole' => $item->sistole ?? null,
                        'diastole' => $item->diastole ?? null,
                        'gula_darah' => $item->gula_darah ?? null,
                        'imt' => $item->imt ?? null,
                        'umur' => $umur,
                        'rujuk_puskesmas' => $rujukanStatus,
                        'pemeriksa' => $item->created_by ?? 'N/A',
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'dewasa',
                        'model_type' => 'dewasa'
                    ];
                });

                $allData = $allData->merge($dewasaData);
            }

            // LANSIA DATA
            if (empty($role) || $role === 'lansia') {
                $lansiaQuery = PemeriksaanLansia::with(['user' => function ($q) {
                    $q->select('id', 'nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
                }]);

                $this->applyFilters($lansiaQuery, $request);


                $lansiaData = $lansiaQuery->get()->map(function ($item) {
                    $rujukanStatus = 'Normal';

                    // 1. Cek status_tbc
                    if (!empty($item->status_tbc) && (
                        $item->status_tbc === 'Rujuk ke Puskesmas' ||
                        $item->status_tbc === 'Perlu Rujukan'
                    )) {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    // 2. Cek status_puma
                    if (!empty($item->status_puma) && (
                        $item->status_puma === 'Rujuk ke Puskesmas' ||
                        $item->status_puma === 'Perlu Rujukan'
                    )) {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    // 3. Cek skor_puma (kalau diisi dan > 6, langsung rujuk)
                    if (!is_null($item->skor_puma) && is_numeric($item->skor_puma) && $item->skor_puma > 6) {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    // 4. Cek kesimpulan_td
                    if (!empty($item->kesimpulan_td) && $item->kesimpulan_td === 'Hipertensi') {
                        $rujukanStatus = 'Perlu Rujukan';
                    }
                    // 5. Cek kesimpulan_gula_darah
                    if (!empty($item->kesimpulan_gula_darah) && $item->kesimpulan_gula_darah === 'Diabetes') {
                        $rujukanStatus = 'Perlu Rujukan';
                    }

                    $umur = $item->user ? Carbon::parse($item->user->tanggal_lahir)->age : 0;

                    return [
                        'id' => $item->id,
                        'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                        'nik' => $item->user->nik ?? null,
                        'bb' => $item->bb,
                        'tb' => $item->tb,
                        'imt' => $item->imt ?? null,
                        'sistole' => $item->sistole ?? null,
                        'diastole' => $item->diastole ?? null,
                        'gula_darah' => $item->gula_darah ?? null,
                        'umur' => $umur,
                        'rujuk_puskesmas' => $rujukanStatus,
                        'pemeriksa' => $item->created_by ?? 'N/A',
                        'user' => $item->user,
                        'jenis_pemeriksaan' => 'lansia',
                        'model_type' => 'lansia'
                    ];
                });

                $allData = $allData->merge($lansiaData);
            }

            $allData = $allData->sortByDesc('tanggal_pemeriksaan')->values();

            $page = $request->get('page', 1);
            $perPage = 10;
            $total = $allData->count();
            $items = $allData->forPage($page, $perPage)->values();

            $pagination = [
                'current_page' => (int) $page,
                'last_page' => (int) ceil($total / $perPage),
                'per_page' => $perPage,
                'total' => $total,
                'from' => ($page - 1) * $perPage + 1,
                'to' => min($page * $perPage, $total)
            ];

            return response()->json([
                'success' => true,
                'data' => $items,
                'pagination' => $pagination,
                'stats' => $this->calculateUniversalStats($request)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }


    private function calculateHealthStatus($pemeriksaan, $user = null)
    {
        if (!$pemeriksaan) {
            return [
                'status' => 'Belum Ada Data',
                'category' => 'info',
                'score' => 0,
                'badge' => 'bg-secondary',
                'icon' => 'question-circle',
                'description' => 'Belum ada pemeriksaan'
            ];
        }

        $user = $user ?: $pemeriksaan->user;

        // ✅ FIX DETEKSI LEVEL - BERDASARKAN MODEL CLASS
        $modelClass = get_class($pemeriksaan);
        $level = 'umum';

        if (strpos($modelClass, 'PemeriksaanBalita') !== false) {
            $level = 'balita';
        } elseif (strpos($modelClass, 'PemeriksaanRemaja') !== false) {
            $level = 'remaja';
        } elseif (strpos($modelClass, 'PemeriksaanIbuHamil') !== false) {
            $level = 'ibu-hamil';
        } elseif (strpos($modelClass, 'PemeriksaanDewasa') !== false) {
            $level = 'dewasa';
        } elseif (strpos($modelClass, 'PemeriksaanLansia') !== false) {
            $level = 'lansia';
        }

        $umurBulan = $pemeriksaan->umur ?? 12;

        $score = 80;
        $criticalIssues = [];
        $warningIssues = [];

        if (isset($pemeriksaan->rujuk_puskesmas) && $pemeriksaan->rujuk_puskesmas === 'Perlu Rujukan') {
            $score -= 50;
            $criticalIssues[] = 'Perlu rujukan ke puskesmas';
        }

        $gejalaTBC = $pemeriksaan->jumlah_gejala_tbc ?? 0;
        if ($gejalaTBC >= 2) {
            $score -= 30;
            $criticalIssues[] = 'Risiko TBC tinggi';
        } elseif ($gejalaTBC >= 1) {
            $score -= 15;
            $warningIssues[] = 'Perlu pantau gejala TBC';
        }

        // ✅ PAKAI LEVEL YANG SUDAH DIDETEKSI
        switch ($level) {
            case 'balita':
                $score += $this->checkBalitaHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;

            case 'ibu-hamil':
                $score += $this->checkIbuHamilHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;

            case 'remaja':
                $score += $this->checkRemajaHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;

            case 'dewasa':
                $score += $this->checkDewasaHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;

            case 'lansia':
                $score += $this->checkLansiaHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;

            default:
                $score += $this->checkUmumHealth($pemeriksaan, $umurBulan, $criticalIssues, $warningIssues);
                break;
        }

        return $this->determineFinalStatus($score, $criticalIssues, $warningIssues);
    }

    private function checkBalitaHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 0;

        if ($umurBulan >= 2 && !$pemeriksaan->imunisasi) {
            $adjustment -= 25;
            $criticalIssues[] = 'Imunisasi belum lengkap';
        }

        if ($umurBulan <= 6 && !$pemeriksaan->asi_eksklusif) {
            $adjustment -= 15;
            $warningIssues[] = 'ASI eksklusif belum diberikan';
        }

        if ($umurBulan >= 6 && !$pemeriksaan->mp_asi) {
            $adjustment -= 15;
            $warningIssues[] = 'MPASI belum diberikan';
        }

        if ($pemeriksaan->lila && $pemeriksaan->lila < 11.5) {
            $adjustment -= 25;
            $criticalIssues[] = 'LILA di bawah normal';
        }

        return $adjustment;
    }

    private function checkIbuHamilHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 0;

        if ($pemeriksaan->lila) {
            $lilaValue = floatval($pemeriksaan->lila);
            if ($lilaValue < 23.5) {
                $adjustment -= 30;
                $criticalIssues[] = 'KEK (Kurang Energi Kronis)';
            }
        } else {
            $adjustment -= 15;
            $warningIssues[] = 'LILA belum diukur';
        }

        if (!$pemeriksaan->imunisasi) {
            $adjustment -= 10;
            $warningIssues[] = 'Imunisasi belum lengkap';
        }

        return $adjustment;
    }

    private function checkRemajaHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 0;

        if ($pemeriksaan->lila) {
            $lilaValue = floatval($pemeriksaan->lila);
            if ($lilaValue < 23.5) {
                $adjustment -= 60;
                $criticalIssues[] = 'LILA rendah - risiko KEK - URGENT rujuk!';
            }
        }

        if ($pemeriksaan->imt) {
            $imtValue = floatval($pemeriksaan->imt);
            if ($imtValue < 17) {
                $adjustment -= 60;
                $criticalIssues[] = 'IMT sangat rendah (<17) - malnutrisi berat';
            } elseif ($imtValue >= 30) {
                $adjustment -= 50;
                $criticalIssues[] = 'IMT sangat tinggi (≥30) - obesitas';
            } elseif ($imtValue < 18.5) {
                $adjustment -= 40;
                $criticalIssues[] = 'IMT rendah (<18.5) - kurus';
            } elseif ($imtValue >= 25) {
                $adjustment -= 30;
                $criticalIssues[] = 'IMT berlebih (≥25) - overweight';
            }
        }

        return $adjustment;
    }

    private function checkDewasaHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 0;

        if (isset($pemeriksaan->kesimpulan_td) && $pemeriksaan->kesimpulan_td === 'Hipertensi') {
            $adjustment -= 80;
            $criticalIssues[] = 'Hipertensi terdeteksi - URGENT rujuk!';
        }

        if (isset($pemeriksaan->kesimpulan_gula_darah) && $pemeriksaan->kesimpulan_gula_darah === 'Diabetes') {
            $adjustment -= 80;
            $criticalIssues[] = 'Diabetes terdeteksi - URGENT rujuk!';
        }

        if (isset($pemeriksaan->sistole)) {
            $sistole = floatval($pemeriksaan->sistole);
            if ($sistole >= 160) {
                $adjustment -= 80;
                $criticalIssues[] = 'Tekanan darah sistole sangat tinggi (≥160) - hipertensi berat';
            } elseif ($sistole >= 140) {
                $adjustment -= 70;
                $criticalIssues[] = 'Tekanan darah sistole tinggi (≥140) - hipertensi';
            } elseif ($sistole >= 130) {
                $adjustment -= 50;
                $criticalIssues[] = 'Tekanan darah sistole agak tinggi (≥130) - prehipertensi';
            }
        }

        if (isset($pemeriksaan->diastole)) {
            $diastole = floatval($pemeriksaan->diastole);
            if ($diastole >= 100) {
                $adjustment -= 80;
                $criticalIssues[] = 'Tekanan darah diastole sangat tinggi (≥100) - hipertensi berat';
            } elseif ($diastole >= 90) {
                $adjustment -= 70;
                $criticalIssues[] = 'Tekanan darah diastole tinggi (≥90) - hipertensi';
            } elseif ($diastole >= 80) {
                $adjustment -= 50;
                $criticalIssues[] = 'Tekanan darah diastole agak tinggi (≥80) - prehipertensi';
            }
        }

        if (isset($pemeriksaan->gula_darah)) {
            $gulaDarah = floatval($pemeriksaan->gula_darah);
            if ($gulaDarah >= 250) {
                $adjustment -= 80;
                $criticalIssues[] = 'Gula darah sangat tinggi (≥250) - diabetes berat';
            } elseif ($gulaDarah >= 200) {
                $adjustment -= 70;
                $criticalIssues[] = 'Gula darah tinggi (≥200) - diabetes';
            } elseif ($gulaDarah >= 140) {
                $adjustment -= 50;
                $criticalIssues[] = 'Gula darah agak tinggi (≥140) - prediabetes';
            }
        }

        return $adjustment;
    }

    private function checkLansiaHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 0;

        if (isset($pemeriksaan->kesimpulan_td) && $pemeriksaan->kesimpulan_td === 'Hipertensi') {
            $adjustment -= 90;
            $criticalIssues[] = 'Hipertensi pada lansia - URGENT rujuk!';
        }

        if (isset($pemeriksaan->kesimpulan_gula_darah) && $pemeriksaan->kesimpulan_gula_darah === 'Diabetes') {
            $adjustment -= 90;
            $criticalIssues[] = 'Diabetes pada lansia - URGENT rujuk!';
        }

        if (isset($pemeriksaan->sistole)) {
            $sistole = floatval($pemeriksaan->sistole);
            if ($sistole >= 150) {
                $adjustment -= 90;
                $criticalIssues[] = 'Tekanan darah sistole sangat tinggi (≥150) - hipertensi lansia berat';
            } elseif ($sistole >= 130) {
                $adjustment -= 80;
                $criticalIssues[] = 'Tekanan darah sistole tinggi (≥130) - hipertensi lansia';
            } elseif ($sistole >= 120) {
                $adjustment -= 60;
                $criticalIssues[] = 'Tekanan darah sistole agak tinggi (≥120) - prehipertensi lansia';
            }
        }

        if (isset($pemeriksaan->diastole)) {
            $diastole = floatval($pemeriksaan->diastole);
            if ($diastole >= 90) {
                $adjustment -= 90;
                $criticalIssues[] = 'Tekanan darah diastole sangat tinggi (≥90) - hipertensi lansia berat';
            } elseif ($diastole >= 80) {
                $adjustment -= 80;
                $criticalIssues[] = 'Tekanan darah diastole tinggi (≥80) - hipertensi lansia';
            } elseif ($diastole >= 70) {
                $adjustment -= 60;
                $criticalIssues[] = 'Tekanan darah diastole agak tinggi (≥70) - prehipertensi lansia';
            }
        }

        if (isset($pemeriksaan->gula_darah)) {
            $gulaDarah = floatval($pemeriksaan->gula_darah);
            if ($gulaDarah >= 200) {
                $adjustment -= 90;
                $criticalIssues[] = 'Gula darah sangat tinggi (≥200) - diabetes lansia berat';
            } elseif ($gulaDarah >= 180) {
                $adjustment -= 80;
                $criticalIssues[] = 'Gula darah tinggi (≥180) - diabetes lansia';
            } elseif ($gulaDarah >= 140) {
                $adjustment -= 60;
                $criticalIssues[] = 'Gula darah agak tinggi (≥140) - prediabetes lansia';
            }
        }

        return $adjustment;
    }

    private function checkUmumHealth($pemeriksaan, $umurBulan, &$criticalIssues, &$warningIssues)
    {
        $adjustment = 5;
        return $adjustment;
    }

    private function determineFinalStatus($score, $criticalIssues, $warningIssues)
    {
        if (count($criticalIssues) >= 1 || $score <= 40) {
            return [
                'status' => 'Perlu Perhatian Khusus',
                'category' => 'urgent',
                'score' => $score,
                'badge' => 'bg-danger',
                'icon' => 'exclamation-triangle-fill',
                'description' => 'Memerlukan penanganan segera',
                'issues' => $criticalIssues
            ];
        } elseif (count($criticalIssues) >= 1 || count($warningIssues) >= 2 || $score <= 60) {
            return [
                'status' => 'Perlu Pantau',
                'category' => 'warning',
                'score' => $score,
                'badge' => 'bg-warning',
                'icon' => 'exclamation-triangle',
                'description' => 'Perlu pemantauan lebih ketat',
                'issues' => array_merge($criticalIssues, $warningIssues)
            ];
        } else {
            return [
                'status' => 'Kondisi Baik',
                'category' => 'good',
                'score' => $score,
                'badge' => 'bg-success',
                'icon' => 'check-circle-fill',
                'description' => 'Kondisi kesehatan baik',
                'issues' => []
            ];
        }
    }

    private function applyFilters($query, $request)
    {
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_pemeriksaan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_pemeriksaan', $request->tahun);
        }

        if ($request->filled('rw')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('rw', $request->rw);
            });
        }

        if ($request->filled('rujukan')) {
            $modelClass = get_class($query->getModel());

            if ($request->rujukan === 'Perlu Rujukan') {
                if (strpos($modelClass, 'PemeriksaanIbuHamil') !== false) {
                    $query->where('perlu_rujukan', true);
                } elseif (strpos($modelClass, 'PemeriksaanDewasa') !== false || strpos($modelClass, 'PemeriksaanLansia') !== false) {
                    $query->where(function ($q) {
                        $q->where('kesimpulan_td', 'Hipertensi')
                            ->orWhere('kesimpulan_gula_darah', 'Diabetes')
                            ->orWhere('rujuk_puskesmas', 'Perlu Rujukan')
                            ->orWhere('sistole', '>=', 140)
                            ->orWhere('diastole', '>=', 90)
                            ->orWhere('gula_darah', '>=', 200);
                    });
                } else {
                    $query->where(function ($q) {
                        $q->where('rujuk_puskesmas', 'LIKE', '%RUJUK%')
                            ->orWhere('rujuk_puskesmas', 'Perlu Rujukan')
                            ->orWhere('jumlah_gejala_tbc', 'LIKE', '2 gejala%')
                            ->orWhere('jumlah_gejala_tbc', 'LIKE', '3 gejala%')
                            ->orWhere('jumlah_gejala_tbc', 'LIKE', '4 gejala%')
                            ->orWhere(function ($subQ) {
                                $subQ->where('lila', '<', 11.5)->whereNotNull('lila');
                            });
                    });
                }
            } elseif ($request->rujukan === 'Tidak Perlu Rujukan') {
                if (strpos($modelClass, 'PemeriksaanIbuHamil') !== false) {
                    $query->where(function ($q) {
                        $q->where('perlu_rujukan', false)->orWhereNull('perlu_rujukan');
                    });
                } elseif (strpos($modelClass, 'PemeriksaanDewasa') !== false || strpos($modelClass, 'PemeriksaanLansia') !== false) {
                    $query->where(function ($q) {
                        $q->where('kesimpulan_td', '!=', 'Hipertensi')
                            ->where('kesimpulan_gula_darah', '!=', 'Diabetes')
                            ->where(function ($subQ) {
                                $subQ->where('rujuk_puskesmas', '!=', 'Perlu Rujukan')
                                    ->orWhereNull('rujuk_puskesmas');
                            })
                            ->where(function ($subQ) {
                                $subQ->where('sistole', '<', 140)->orWhereNull('sistole');
                            })
                            ->where(function ($subQ) {
                                $subQ->where('diastole', '<', 90)->orWhereNull('diastole');
                            })
                            ->where(function ($subQ) {
                                $subQ->where('gula_darah', '<', 200)->orWhereNull('gula_darah');
                            });
                    });
                } else {
                    $query->where(function ($q) {
                        $q->where('rujuk_puskesmas', 'NOT LIKE', '%RUJUK%')
                            ->orWhereNull('rujuk_puskesmas')
                            ->orWhere('rujuk_puskesmas', 'Normal')
                            ->orWhere('rujuk_puskesmas', 'TIDAK RUJUK%');
                    })->where(function ($q) {
                        $q->where('jumlah_gejala_tbc', 'NOT LIKE', '2 gejala%')
                            ->where('jumlah_gejala_tbc', 'NOT LIKE', '3 gejala%')
                            ->where('jumlah_gejala_tbc', 'NOT LIKE', '4 gejala%')
                            ->orWhereNull('jumlah_gejala_tbc')
                            ->orWhere('jumlah_gejala_tbc', '')
                            ->orWhere('jumlah_gejala_tbc', 'LIKE', '0 gejala%')
                            ->orWhere('jumlah_gejala_tbc', 'LIKE', '1 gejala%');
                    })->where(function ($q) {
                        $q->where('lila', '>=', 11.5)->orWhereNull('lila');
                    });
                }
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('nama', 'LIKE', "%{$search}%");
                    });
            });
        }
    }

    private function calculateUniversalStats($request)
    {
        $stats = [
            'non_warga' => 0,
            'bulan_ini' => 0,
            'perlu_rujukan' => 0,
            'total_pemeriksaan' => 0
        ];

        $role = $request->get('role', '');
        $currentMonth = now()->month;
        $currentYear = now()->year;

        if (empty($role) || $role === 'balita') {
            $balitaCount = PemeriksaanBalita::count();
            $stats['total_pemeriksaan'] += $balitaCount;

            $balitaNonWarga = PemeriksaanBalita::whereDoesntHave('user', function ($q) {
                $q->whereNotNull('rw');
            })->count();
            $stats['non_warga'] += $balitaNonWarga;

            $balitaBulanIni = PemeriksaanBalita::whereMonth('tanggal_pemeriksaan', $currentMonth)
                ->whereYear('tanggal_pemeriksaan', $currentYear)->count();
            $stats['bulan_ini'] += $balitaBulanIni;

            $balitaRujukan = PemeriksaanBalita::where(function ($q) {
                $q->where('rujuk_puskesmas', 'LIKE', '%RUJUK%')
                    ->orWhere('rujuk_puskesmas', 'Perlu Rujukan')
                    ->orWhere('jumlah_gejala_tbc', 'LIKE', '2 gejala%')
                    ->orWhere('jumlah_gejala_tbc', 'LIKE', '3 gejala%')
                    ->orWhere('jumlah_gejala_tbc', 'LIKE', '4 gejala%')
                    ->orWhere(function ($subQ) {
                        $subQ->where('lila', '<', 11.5)->whereNotNull('lila');
                    });
            })->count();

            $stats['perlu_rujukan'] += $balitaRujukan;
        }

        if (empty($role) || $role === 'remaja') {
            $remajaCount = PemeriksaanRemaja::count();
            $stats['total_pemeriksaan'] += $remajaCount;

            $remajaNonWarga = PemeriksaanRemaja::whereDoesntHave('user', function ($q) {
                $q->whereNotNull('rw');
            })->count();
            $stats['non_warga'] += $remajaNonWarga;

            $remajaBulanIni = PemeriksaanRemaja::whereMonth('tanggal_pemeriksaan', $currentMonth)
                ->whereYear('tanggal_pemeriksaan', $currentYear)->count();
            $stats['bulan_ini'] += $remajaBulanIni;

            $remajaData = PemeriksaanRemaja::with('user')->get();
            foreach ($remajaData as $item) {
                $healthStatus = $this->calculateHealthStatus($item, $item->user);
                if ($healthStatus['category'] === 'urgent' || $item->rujuk_puskesmas === 'Perlu Rujukan') {
                    $stats['perlu_rujukan']++;
                }
            }
        }

        if (empty($role) || $role === 'ibu-hamil') {
            $ibuHamilCount = PemeriksaanIbuHamil::count();
            $stats['total_pemeriksaan'] += $ibuHamilCount;

            $ibuHamilNonWarga = PemeriksaanIbuHamil::whereDoesntHave('user', function ($q) {
                $q->whereNotNull('rw');
            })->count();
            $stats['non_warga'] += $ibuHamilNonWarga;

            $ibuHamilBulanIni = PemeriksaanIbuHamil::whereMonth('tanggal_pemeriksaan', $currentMonth)
                ->whereYear('tanggal_pemeriksaan', $currentYear)->count();
            $stats['bulan_ini'] += $ibuHamilBulanIni;

            $ibuHamilData = PemeriksaanIbuHamil::with('user')->get();
            foreach ($ibuHamilData as $item) {
                $healthStatus = $this->calculateHealthStatus($item, $item->user);
                if ($healthStatus['category'] === 'urgent' || $item->perlu_rujukan) {
                    $stats['perlu_rujukan']++;
                }
            }
        }

        if (empty($role) || $role === 'dewasa') {
            $dewasaCount = PemeriksaanDewasa::count();
            $stats['total_pemeriksaan'] += $dewasaCount;

            $dewasaNonWarga = PemeriksaanDewasa::whereDoesntHave('user', function ($q) {
                $q->whereNotNull('rw');
            })->count();
            $stats['non_warga'] += $dewasaNonWarga;

            $dewasaBulanIni = PemeriksaanDewasa::whereMonth('tanggal_pemeriksaan', $currentMonth)
                ->whereYear('tanggal_pemeriksaan', $currentYear)->count();
            $stats['bulan_ini'] += $dewasaBulanIni;

            $dewasaData = PemeriksaanDewasa::with('user')->get();
            foreach ($dewasaData as $item) {
                $healthStatus = $this->calculateHealthStatus($item, $item->user);
                if (
                    $healthStatus['category'] === 'urgent' ||
                    $item->kesimpulan_td === 'Hipertensi' ||
                    $item->kesimpulan_gula_darah === 'Diabetes' ||
                    (isset($item->rujuk_puskesmas) && $item->rujuk_puskesmas === 'Perlu Rujukan')
                ) {
                    $stats['perlu_rujukan']++;
                }
            }
        }

        if (empty($role) || $role === 'lansia') {
            $lansiaCount = PemeriksaanLansia::count();
            $stats['total_pemeriksaan'] += $lansiaCount;

            $lansiaNonWarga = PemeriksaanLansia::whereDoesntHave('user', function ($q) {
                $q->whereNotNull('rw');
            })->count();
            $stats['non_warga'] += $lansiaNonWarga;

            $lansiaBulanIni = PemeriksaanLansia::whereMonth('tanggal_pemeriksaan', $currentMonth)
                ->whereYear('tanggal_pemeriksaan', $currentYear)->count();
            $stats['bulan_ini'] += $lansiaBulanIni;

            $lansiaData = PemeriksaanLansia::with('user')->get();
            foreach ($lansiaData as $item) {
                $healthStatus = $this->calculateHealthStatus($item, $item->user);
                if (
                    $healthStatus['category'] === 'urgent' ||
                    $item->kesimpulan_td === 'Hipertensi' ||
                    $item->kesimpulan_gula_darah === 'Diabetes' ||
                    (isset($item->rujuk_puskesmas) && $item->rujuk_puskesmas === 'Perlu Rujukan')
                ) {
                    $stats['perlu_rujukan']++;
                }
            }
        }

        return $stats;
    }

    public function getFilterOptions()
    {
        try {
            $rwList = collect();

            if (PemeriksaanBalita::count() > 0) {
                $balitaRw = PemeriksaanBalita::with('user')->get()->pluck('user.rw')->filter();
                $rwList = $rwList->merge($balitaRw);
            }

            if (PemeriksaanRemaja::count() > 0) {
                $remajaRw = PemeriksaanRemaja::with('user')->get()->pluck('user.rw')->filter();
                $rwList = $rwList->merge($remajaRw);
            }

            if (PemeriksaanIbuHamil::count() > 0) {
                $ibuHamilRw = PemeriksaanIbuHamil::with('user')->get()->pluck('user.rw')->filter();
                $rwList = $rwList->merge($ibuHamilRw);
            }

            if (PemeriksaanDewasa::count() > 0) {
                $dewasaRw = PemeriksaanDewasa::with('user')->get()->pluck('user.rw')->filter();
                $rwList = $rwList->merge($dewasaRw);
            }

            if (PemeriksaanLansia::count() > 0) {
                $lansiaRw = PemeriksaanLansia::with('user')->get()->pluck('user.rw')->filter();
                $rwList = $rwList->merge($lansiaRw);
            }

            $rwList = $rwList->unique()->sort()->values();

            $roleList = collect();

            if (PemeriksaanBalita::count() > 0) {
                $roleList->push('balita');
            }

            if (PemeriksaanRemaja::count() > 0) {
                $roleList->push('remaja');
            }

            if (PemeriksaanIbuHamil::count() > 0) {
                $roleList->push('ibu-hamil');
            }

            if (PemeriksaanDewasa::count() > 0) {
                $roleList->push('dewasa');
            }

            if (PemeriksaanLansia::count() > 0) {
                $roleList->push('lansia');
            }

            $years = collect();

            if (PemeriksaanBalita::count() > 0) {
                $balitaYears = PemeriksaanBalita::selectRaw('YEAR(tanggal_pemeriksaan) as year')->distinct()->pluck('year');
                $years = $years->merge($balitaYears);
            }

            if (PemeriksaanRemaja::count() > 0) {
                $remajaYears = PemeriksaanRemaja::selectRaw('YEAR(tanggal_pemeriksaan) as year')->distinct()->pluck('year');
                $years = $years->merge($remajaYears);
            }

            if (PemeriksaanIbuHamil::count() > 0) {
                $ibuHamilYears = PemeriksaanIbuHamil::selectRaw('YEAR(tanggal_pemeriksaan) as year')->distinct()->pluck('year');
                $years = $years->merge($ibuHamilYears);
            }

            if (PemeriksaanDewasa::count() > 0) {
                $dewasaYears = PemeriksaanDewasa::selectRaw('YEAR(tanggal_pemeriksaan) as year')->distinct()->pluck('year');
                $years = $years->merge($dewasaYears);
            }

            if (PemeriksaanLansia::count() > 0) {
                $lansiaYears = PemeriksaanLansia::selectRaw('YEAR(tanggal_pemeriksaan) as year')->distinct()->pluck('year');
                $years = $years->merge($lansiaYears);
            }

            $years = $years->unique()->sort()->reverse()->values();

            return response()->json([
                'success' => true,
                'rw_list' => $rwList,
                'role_list' => $roleList,
                'years' => $years
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detail($id)
    {
        try {
            $models = [
                PemeriksaanBalita::class,
                PemeriksaanRemaja::class,
                PemeriksaanIbuHamil::class,
                PemeriksaanDewasa::class,
                PemeriksaanLansia::class
            ];

            foreach ($models as $modelClass) {
                $pemeriksaan = $modelClass::with(['user:id,nik,nama,alamat,rw,level,tanggal_lahir,jenis_kelamin'])->find($id);
                if ($pemeriksaan) {
                    return response()->json([
                        'success' => true,
                        'data' => $pemeriksaan
                    ]);
                }
            }

            throw new \Exception('Data pemeriksaan tidak ditemukan');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemeriksaan tidak ditemukan'
            ], 404);
        }
    }

    public function getLastExamination($nik)
    {
        $currentDate = request()->get('current_date', date('Y-m-d'));

        $baselineExam = PemeriksaanBalita::where('nik', $nik)
            ->where('tanggal_pemeriksaan', '<', $currentDate)
            ->orderBy('tanggal_pemeriksaan', 'desc')
            ->first();

        return response()->json($baselineExam);
    }

    public function exportExcel(Request $request)
    {
        try {
            $role = $request->get('role', '');

            switch ($role) {
                case 'balita':
                    return Excel::download(new BalitaExport($request->all()), 'data-balita.xlsx');
                case 'remaja':
                    return Excel::download(new RemajaExport($request->all()), 'data-remaja.xlsx');
                default:
                    $allData = $this->getExportData($request);
                    return Excel::download(new DataPemeriksaanExport($allData, $request->all()), 'data-pemeriksaan.xlsx');
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error exporting data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function exportRekap(Request $request)
    {
        $role = $request->get('role', '');

        switch ($role) {
            case 'balita':
                return Excel::download(
                    new RekapBalitaExport($request->all()),
                    'rekap-balita.xlsx'
                );
            case 'remaja':
                return Excel::download(
                    new RekapRemajaExport($request->all()),
                    'rekap-remaja.xlsx'
                );
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak valid untuk rekap.'
                ], 400);
        }
    }

    private function getExportData(Request $request)
    {
        $role = $request->get('role', '');
        $allData = collect();

        if (empty($role) || $role === 'balita') {
            $balitaQuery = PemeriksaanBalita::with(['user' => function ($q) {
                $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
            }]);
            $this->applyFilters($balitaQuery, $request);
            $balitaData = $balitaQuery->get()->map(function ($item) {
                $rujukanStatus = 'Normal';

                // 1. Cek field rujuk_puskesmas
                if (
                    isset($item->rujuk_puskesmas) &&
                    (str_contains($item->rujuk_puskesmas, 'RUJUK') || $item->rujuk_puskesmas === 'Perlu Rujukan')
                ) {
                    $rujukanStatus = 'Perlu Rujukan';
                }

                // 2. Cek TBC ≥ 2 gejala
                $jumlahGejala = $item->jumlah_gejala_tbc ?? 0;
                if (is_string($jumlahGejala) && preg_match('/(\d+)/', $jumlahGejala, $matches)) {
                    $jumlahGejala = (int)$matches[1];
                }
                if ($jumlahGejala >= 2) {
                    $rujukanStatus = 'Perlu Rujukan';
                }

                // 3. Cek LILA hanya jika umur ≥ 6 bulan DAN < 11.5 cm
                $umur = $item->umur ?? 0;
                if ($umur >= 6 && $item->lila && floatval($item->lila) < 11.5) {
                    $rujukanStatus = 'Perlu Rujukan';
                }

                return [
                    'id' => $item->id,
                    'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                    'nik' => $item->nik,
                    'bb' => $item->bb,
                    'tb' => $item->tb,
                    'lingkar_kepala' => $item->lingkar_kepala,
                    'lila' => $item->lila,
                    'umur' => $item->umur,
                    'rujuk_puskesmas' => $rujukanStatus,
                    'pemeriksa' => $item->pemeriksa,
                    'user' => $item->user,
                    'jenis_pemeriksaan' => 'balita',
                    'model_type' => 'balita'
                ];
            });
            $allData = $allData->merge($balitaData);
        }

        if (empty($role) || $role === 'remaja') {
            $remajaQuery = PemeriksaanRemaja::with(['user' => function ($q) {
                $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
            }]);
            $this->applyFilters($remajaQuery, $request);
            $remajaData = $remajaQuery->get()->map(function ($item) {
                $healthStatus = $this->calculateHealthStatus($item, $item->user);

                return [
                    'id' => $item->id,
                    'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                    'nik' => $item->nik,
                    'bb' => $item->bb,
                    'tb' => $item->tb,
                    'imt' => $item->imt,
                    'lila' => $item->lila,
                    'umur' => $item->umur,
                    'rujuk_puskesmas' => ($healthStatus['category'] === 'urgent' ||
                        $item->rujuk_puskesmas === 'Perlu Rujukan') ? 'Perlu Rujukan' : 'Normal',
                    'health_status' => $healthStatus,
                    'pemeriksa' => $item->pemeriksa,
                    'user' => $item->user,
                    'jenis_pemeriksaan' => 'remaja',
                    'model_type' => 'remaja'
                ];
            });
            $allData = $allData->merge($remajaData);
        }

        if (empty($role) || $role === 'ibu-hamil') {
            $ibuHamilQuery = PemeriksaanIbuHamil::with(['user' => function ($q) {
                $q->select('nik', 'nama', 'rw', 'level', 'alamat', 'tanggal_lahir', 'jenis_kelamin');
            }]);
            $this->applyFilters($ibuHamilQuery, $request);
            $ibuHamilData = $ibuHamilQuery->get()->map(function ($item) {
                $healthStatus = $this->calculateHealthStatus($item, $item->user);
                return [
                    'id' => $item->id,
                    'tanggal_pemeriksaan' => $item->tanggal_pemeriksaan,
                    'nik' => $item->nik,
                    'bb' => $item->bb,
                    'tb' => $item->tb,
                    'usia_kehamilan' => $item->usia_kehamilan,
                    'lila' => $item->lila,
                    'umur' => $item->umur,
                    'perlu_rujukan' => $item->perlu_rujukan,
                    'rujuk_puskesmas' => ($healthStatus['category'] === 'urgent' ||
                        $item->perlu_rujukan) ? 'Perlu Rujukan' : 'Normal',
                    'pemeriksa' => $item->pemeriksa,
                    'user' => $item->user,
                    'jenis_pemeriksaan' => 'ibu-hamil',
                    'model_type' => 'ibu-hamil'
                ];
            });
            $allData = $allData->merge($ibuHamilData);
        }

        return $allData->sortByDesc('tanggal_pemeriksaan')->values()->toArray();
    }
}
