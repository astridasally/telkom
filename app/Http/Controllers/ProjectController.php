<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User; // Pastikan ini ada
use Illuminate\Http\Request;
use App\Enums\Regional; // Pastikan ini ada
use App\Enums\Witel; // Pastikan ini ada
use Carbon\Carbon; // Pastikan ini ada
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

    public function projectForm()
    {
        return view('project_create');
    }

    public function store_projectForm(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'regional' => 'required',
            'witel' => 'required',
            'sto' => 'required',
            'site' => 'required',
            'priority' => 'nullable',
            'catuan_id' => 'nullable',
            'ihld' => 'nullable',
            'plan_survey' => 'nullable|date',
            'realisasi_survey' => 'nullable|date|after_or_equal:plan_survey',
            'plan_delivery' => 'nullable|date|after_or_equal:realisasi_survey',
            'realisasi_delivery' => 'nullable|date|after_or_equal:plan_delivery',
            'plan_instalasi' => 'nullable|date|after_or_equal:realisasi_delivery',
            'realisasi_instalasi' => 'nullable|date|after_or_equal:plan_instalasi',
            'plan_integrasi' => 'nullable|date|after_or_equal:realisasi_instalasi',
            'realisasi_integrasi' => 'nullable|date|after_or_equal:plan_integrasi',

            //'drop_data' => 'required|in:Yes,No,Relokasi',
            'bukti_drop' => 'required_if:drop_data,Yes|nullable|mimes:pdf,jpg,jpeg,png|max:5200', //5mb
            'relok_regional' => 'required_if:drop_data,Relokasi',
            'relok_witel'    => 'required_if:drop_data,Relokasi',
            'relok_sto'      => 'required_if:drop_data,Relokasi',
            'relok_site'     => 'required_if:drop_data,Relokasi',

            'remark' => 'nullable',
            'drop_data' => 'nullable',

        ]);


        // Simpan bukti drop (jika ada)
        if ($request->hasFile('bukti_drop')) {
            $validated['bukti_drop'] = $request->file('bukti_drop')->store('bukti_drop', 'public');
        }


        $validated['user_id'] = auth()->id(); // relasi ke user login

        $project = Project::create($validated);

        if ($project) {
            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan project.');
        }
    }

    public function report(Request $request)
    {
        $user = Auth::user();
        $query = Project::query(); // Mulai query builder

        // Logika filter berdasarkan peran pengguna (mitra hanya melihat proyeknya sendiri)
        // Ini harus diterapkan pada query builder yang sama
        if ($user->role === 'mitra') {
            $query->where('user_id', $user->id);
        }

        // --- TAMBAHAN UNTUK FILTER DROPDOWN ---
        $selectedRegionalFilter = $request->input('filter_regional');
        $selectedWitelFilter = $request->input('filter_witel');
        $selectedStoFilter = $request->input('filter_sto');

        if ($selectedRegionalFilter && $selectedRegionalFilter !== 'all') {
            $query->where('regional', $selectedRegionalFilter);
        }

        if ($selectedWitelFilter && $selectedWitelFilter !== 'all') {
            $query->where('witel', $selectedWitelFilter);
        }

        if ($selectedStoFilter && $selectedStoFilter !== 'all') {
            $query->where('sto', $selectedStoFilter);
        }
        // --- AKHIR TAMBAHAN UNTUK FILTER DROPDOWN ---


        // Logika pencarian utama (tetap dipertahankan dan digabungkan)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = strtolower($request->search);

            $query->where(function($q) use ($searchTerm, $user) {
                $q->whereRaw('LOWER(regional) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(witel) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(sto) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(site) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(ihld) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(catuan_id) LIKE ?', ['%' . $searchTerm . '%']);

                if ($user->role === 'mitra' || $user->role === 'admin') {
                    $q->orWhereRaw('LOWER(priority) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(remark) LIKE ?', ['%' . $searchTerm . '%']);
                }

                if ($user->role === 'vendor' || $user->role === 'admin') {
                    $q->orWhereRaw('LOWER(priority_ta) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(dependensi) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(ftth_csf) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(ftth_port) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(golive_csf) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(golive_port) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(status_osp) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(scenario_uplink) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(status_uplink) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(remark_ta) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(relok_regional) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(relok_witel) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(relok_sto) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(relok_site) LIKE ?', ['%' . $searchTerm . '%']);
                }

                if ($user->role === 'admin' || $user->role === 'vendor') {
                    $q->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
                    });
                }
            });
        }

        $query->orderBy('created_at', 'desc');

        $projects = $query->paginate(18)->appends($request->query());

        // --- PASTIKAN BAGIAN INI ADA DAN BENAR ---
        // Untuk mengisi dropdown di view, kita perlu mendapatkan daftar unik Regional, Witel, STO
        // Ambil dari database, pastikan model Project terhubung dengan kolom-kolom ini
        $allRegionals = Project::distinct('regional')->pluck('regional')->sort()->toArray();
        $allWitels = Project::distinct('witel')->pluck('witel')->sort()->toArray();
        $allStos = Project::distinct('sto')->pluck('sto')->sort()->toArray();
        // --- AKHIR PASTIKAN BAGIAN INI ---

        return view('project_report', compact(
            'projects',
            'allRegionals', // Pastikan ini ada
            'allWitels',    // Pastikan ini ada
            'allStos',      // Pastikan ini ada
            'selectedRegionalFilter',
            'selectedWitelFilter',
            'selectedStoFilter'
        ));

        // Logika pencarian utama
        if ($request->has('search') && $request->search != '') {
            $searchTerm = strtolower($request->search); // Konversi searchTerm ke huruf kecil

            // Gunakan where(function) untuk mengelompokkan semua kondisi OR
            // ini sangat penting agar filter peran (user_id) tetap berfungsi sebagai AND
            // dan pencarian OR berlaku di dalam grup ini.
            $query->where(function($q) use ($searchTerm, $user) {
                // Kolom-kolom umum yang selalu dicari (gunakan LOWER() untuk case-insensitivity)
                $q->whereRaw('LOWER(regional) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(witel) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(sto) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(site) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(ihld) LIKE ?', ['%' . $searchTerm . '%'])
                  ->orWhereRaw('LOWER(catuan_id) LIKE ?', ['%' . $searchTerm . '%']);

                // Kolom spesifik untuk peran 'mitra' atau 'admin'
                if ($user->role === 'mitra' || $user->role === 'admin') {
                    $q->orWhereRaw('LOWER(priority) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(remark) LIKE ?', ['%' . $searchTerm . '%']);
                }

                // Kolom spesifik untuk peran 'vendor' atau 'admin'
                if ($user->role === 'vendor' || $user->role === 'admin') {
                    $q->orWhereRaw('LOWER(priority_ta) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(dependensi) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(ftth_csf) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(ftth_port) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(golive_csf) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(golive_port) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(status_osp) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(scenario_uplink) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(status_uplink) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(remark_ta) LIKE ?', ['%' . $searchTerm . '%'])
                      // Kolom relokasi
                      ->orWhereRaw('LOWER(relok_regional) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(relok_witel) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(relok_sto) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(relok_site) LIKE ?', ['%' . $searchTerm . '%']);
                }

                // Jika user adalah admin atau vendor, tambahkan pencarian berdasarkan nama mitra
                if ($user->role === 'admin' || $user->role === 'vendor') {
                    $q->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->whereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%']);
                    });
                }
            });
        }

        // Mengurutkan proyek (opsional: agar hasilnya konsisten)
        $query->orderBy('created_at', 'desc');

        // Ambil proyek dengan paginasi
        // Pagination limit diseragamkan menjadi 10 (seperti yang ada di logika pencarian)
        $projects = $query->paginate(18)->appends($request->query()); // appends() untuk mempertahankan parameter search

        return view('project_report', compact('projects'));
    }


//punya TA (form dan report)
    public function projectFormTA()
    {
        return view('project_create_ta');
    }

    public function store_projectFormTA(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'regional' => 'required',
            'witel' => 'required',
            'sto' => 'required',
            'site' => 'required',
            'ihld' => 'required',
            'catuan_id' => 'required',
            'priority_ta' => 'nullable',
            'status_osp' => 'nullable',
            'dependensi' => 'required',
            'scenario_uplink' => 'nullable',
            'status_uplink' => 'nullable',
            'remark_ta' => 'nullable|string',
            'ftth_csf' => 'nullable',
            'ftth_port' => 'nullable',
            'golive_csf' => 'nullable',
            'golive_port' => 'nullable',

        ]);
            
        $validated['user_id'] = auth()->id();
        
        Project::create($validated);

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }


    public function show_project($id)
    {
        $project = Project::findOrFail($id);
        return view('project_update_admin', compact('project'));
    }

    public function store_project_update(Request $request, $id)
    {
        $project = Project::findOrFail($id);


        $validated = $request->validate([
            'regional' => 'required',
            'witel' => 'required',
            'sto' => 'required',
            'site' => 'required',
            'priority' => 'nullable',
            'catuan_id' => 'required',
            'ihld' => 'required',
            'plan_survey' => 'nullable|date',
            'realisasi_survey' => 'nullable|date|after_or_equal:plan_survey',
            'plan_delivery' => 'nullable|date|after_or_equal:realisasi_survey',
            'realisasi_delivery' => 'nullable|date|after_or_equal:plan_delivery',
            'plan_instalasi' => 'nullable|date|after_or_equal:realisasi_delivery',
            'realisasi_instalasi' => 'nullable|date|after_or_equal:plan_instalasi',
            'plan_integrasi' => 'nullable|date|after_or_equal:realisasi_instalasi',
            'realisasi_integrasi' => 'nullable|date|after_or_equal:plan_integrasi',
            'remark' => 'nullable',
            'drop_data' => 'nullable',

            //'drop_data' => 'required|in:Yes,No,Relokasi',
            'bukti_drop' => 'required_if:drop_data,Yes|nullable|mimes:pdf,jpg,jpeg,png|max:5200',
            'relok_regional' => 'required_if:drop_data,Relokasi',
            'relok_witel'    => 'required_if:drop_data,Relokasi',
            'relok_sto'      => 'required_if:drop_data,Relokasi',
            'relok_site'     => 'required_if:drop_data,Relokasi',

            //validate form edit TA 
            'priority_ta' => 'nullable',
            'dependensi' => 'nullable',
            'ftth_csf' => 'nullable',
            'ftth_port' => 'nullable',
            'golive_csf' => 'nullable',
            'golive_port' => 'nullable',
            'status_osp' => 'nullable',
            'scenario_uplink' => 'nullable',
            'status_uplink' => 'nullable',
            'remark_ta' => 'nullable',
        ]);



        // Simpan bukti drop (jika ada)
        if ($request->hasFile('bukti_drop')) {
            $validated['bukti_drop'] = $request->file('bukti_drop')->store('bukti_drop', 'public');
        }

        $validated['user_id'] = auth()->id(); // relasi ke user login

        // Perbaikan di sini: update bukan create
        $project->update($validated);

        return redirect()->route('project_report')->with('success', 'Project berhasil diperbarui.');
    }
    
    public function store_project_update_admin(Request $request, $id)
    {
        $project = Project::findOrFail($id);


        $validated = $request->validate([
            'regional' => 'required',
            'witel' => 'required',
            'sto' => 'required',
            'site' => 'required',
            'priority' => 'nullable',
            'catuan_id' => 'required',
            'ihld' => 'required',
            'plan_survey' => 'nullable|date',
            'realisasi_survey' => 'nullable|date|after_or_equal:plan_survey',
            'plan_delivery' => 'nullable|date|after_or_equal:realisasi_survey',
            'realisasi_delivery' => 'nullable|date|after_or_equal:plan_delivery',
            'plan_instalasi' => 'nullable|date|after_or_equal:realisasi_delivery',
            'realisasi_instalasi' => 'nullable|date|after_or_equal:plan_instalasi',
            'plan_integrasi' => 'nullable|date|after_or_equal:realisasi_instalasi',
            'realisasi_integrasi' => 'nullable|date|after_or_equal:plan_integrasi',
            
            'remark' => 'nullable',
            'drop_data' => 'nullable',


            //'drop_data' => 'required|in:Yes,No,Relokasi',
            'bukti_drop' => 'required_if:drop_data,Yes|nullable|mimes:pdf,jpg,jpeg,png|max:5200',
            'relok_regional' => 'required_if:drop_data,Relokasi',
            'relok_witel'    => 'required_if:drop_data,Relokasi',
            'relok_sto'      => 'required_if:drop_data,Relokasi',
            'relok_site'     => 'required_if:drop_data,Relokasi',

            'priority_ta' => 'nullable',
            'dependensi' => 'nullable',
            'ftth_csf' => 'nullable',
            'ftth_port' => 'nullable',
            'golive_csf' => 'nullable',
            'golive_port' => 'nullable',
            'status_osp' => 'nullable',
            'scenario_uplink' => 'nullable',
            'status_uplink' => 'nullable',
            'remark_ta' => 'nullable',
        ]);



        // Simpan bukti drop (jika ada)
        if ($request->hasFile('bukti_drop')) {
            $validated['bukti_drop'] = $request->file('bukti_drop')->store('bukti_drop', 'public');
        }

        $validated['user_id'] = $project->user_id; // relasi ke user login

        // Perbaikan di sini: update bukan create
        $project->update($validated);

        return redirect()->route('project_report')->with('success', 'Data berhasil diperbarui.');
    }

   public function dashboard(Request $request)
    {
        // =======================================================
        // BAGIAN 1: LOGIKA DASHBOARD UTAMA (FILTER & COUNT)
        // =======================================================

        // 1. Inisialisasi baseQuery untuk dashboard utama
        $baseQuery = Project::query();
        $today = Carbon::today(); 


        // 2. Dapatkan nilai filter dari Request
        $selectedMitra    = $request->input('mitra');
        $selectedRegional = $request->input('regional');
        $selectedWitel    = $request->input('witel');

        // 3. Terapkan filter berdasarkan input dari Request ke baseQuery

        // FILTER MITRA (Menggunakan relasi user)
        if ($selectedMitra && $selectedMitra !== 'All Mitra') {
            $baseQuery->whereHas('user', function ($query) use ($selectedMitra) {
                $query->where('name', $selectedMitra); // Filter di kolom 'name' tabel users
            });
        }

        // FILTER REGIONAL
        if ($selectedRegional && $selectedRegional !== 'All Regional') {
            $baseQuery->where('regional', $selectedRegional);
        }

        // FILTER WITEL
        if ($selectedWitel && $selectedWitel !== 'All Witel') {
            $baseQuery->where('witel', $selectedWitel);
        }

        // 4. Ambil data proyek UTAMA setelah semua filter dasar diterapkan
        $projects = $baseQuery->get(); // Ini adalah data yang akan ditampilkan di tabel utama dashboard

        // 5. Hitung semua metrik untuk dashboard utama
        $projectCount = $baseQuery->clone()->count();

        // SURVEY
        $planSurveyCount = $baseQuery->clone()->whereNotNull('plan_survey')->count();
        $realSurveyCount = $baseQuery->clone()->whereNotNull('realisasi_survey')->count();

        // DELIVERY (untuk dashboard utama)
        $planDeliveryCount = $baseQuery->clone()->whereNotNull('plan_delivery')->count();
        $realDeliveryCount = $baseQuery->clone()->whereNotNull('realisasi_delivery')->count();

        // INSTALASI (untuk dashboard utama)
        $planInstalasiCount = $baseQuery->clone()->whereNotNull('plan_instalasi')->count();
        $realInstalasiCount = $baseQuery->clone()->whereNotNull('realisasi_instalasi')->count();

        // INTEGRASI (untuk dashboard utama)
        $planIntegrasiCount = $baseQuery->clone()->whereNotNull('plan_integrasi')->count();
        $realIntegrasiCount = $baseQuery->clone()->whereNotNull('realisasi_integrasi')->count();

        // DROP
        $dropYesCount      = $baseQuery->clone()->where('drop_data', 'yes')->count();
        $dropNoCount       = $baseQuery->clone()->where('drop_data', 'no')->count();
        $dropRelokasiCount = $baseQuery->clone()->where('drop_data', 'relokasi')->count();


        // =======================================================
        // BAGIAN 2: LOGIKA FUNNELING OLT (BERADA DI DASHBOARD)
        // =======================================================
        $funnelingData = [];
        $totalFunnelingCounts = $this->initializeFunnelingMetrics(); // Menggunakan nama variabel berbeda agar tidak bentrok

        $regions = Regional::cases(); // Dapatkan semua Regional dari Enum

        foreach ($regions as $regionalEnum) {
            $regionName = $regionalEnum->value;

            // Base query untuk funneling OLT per regional (tanpa filter dashboard utama)
            // ATAU, jika Anda ingin filter dashboard utama juga mempengaruhi tabel funneling OLT:
            // $funnelingQuery = $baseQuery->clone()->where('regional', $regionName); // Gunakan ini jika filter dashboard memengaruhi funneling

            // Jika funneling OLT adalah tabel independen tanpa dipengaruhi filter dashboard:
            $funnelingQuery = Project::where('regional', $regionName);

            $regionalFunnelingCounts = $this->initializeFunnelingMetrics();

            // PLAN CSF (hitung project yang punya catuan_id)
            $regionalFunnelingCounts['plan_csf'] = $funnelingQuery->clone()->whereNotNull('catuan_id')->count();

            // FTTH READY
            $regionalFunnelingCounts['ftth_ready_csf'] = $funnelingQuery->clone()->whereNotNull('ftth_csf')->count();
            $regionalFunnelingCounts['ftth_ready_port'] = $funnelingQuery->clone()->whereNotNull('ftth_port')->count();

            // DELIVERY
            $regionalFunnelingCounts['delivery_plan'] = $funnelingQuery->clone()->whereNotNull('plan_delivery')->count();
            $regionalFunnelingCounts['delivery_done'] = $funnelingQuery->clone()->whereNotNull('realisasi_delivery')->count();

            // INSTALASI
            $regionalFunnelingCounts['instalasi_plan'] = $funnelingQuery->clone()->whereNotNull('plan_instalasi')->count();
            $regionalFunnelingCounts['instalasi_done'] = $funnelingQuery->clone()->whereNotNull('realisasi_instalasi')->count();

            // INTEGRASI
            $regionalFunnelingCounts['integrasi_plan'] = $funnelingQuery->clone()->whereNotNull('plan_integrasi')->count();
            $regionalFunnelingCounts['integrasi_done'] = $funnelingQuery->clone()->whereNotNull('realisasi_integrasi')->count();

            // GO LIVE
            $regionalFunnelingCounts['golive_csf'] = $funnelingQuery->clone()->whereNotNull('golive_csf')->count();
            $regionalFunnelingCounts['golive_port'] = $funnelingQuery->clone()->whereNotNull('golive_port')->count();

            // UPLINK MINI OLT READINESS (Asumsi 'READY' dan 'NOT READY' adalah string yang ada di status_uplink)
            $regionalFunnelingCounts['uplink_ready'] = $funnelingQuery->clone()->where('status_uplink', 'READY')->count();
            $regionalFunnelingCounts['uplink_not_ready'] = $funnelingQuery->clone()->where('status_uplink', 'NOT READY')->count();

            // Simpan data untuk regional ini
            $funnelingData[$regionName] = $regionalFunnelingCounts;

            // Tambahkan ke total Funneling OLT
            foreach ($regionalFunnelingCounts as $key => $count) {
                $totalFunnelingCounts[$key] += $count;
            }
        }
        // =======================================================
        // BAGIAN 3: LOGIKA SKENARIO INTEGRASI (PER REGIONAL) - BARU
        // =======================================================
        $skenarioUplinkColumns = [
            'DIRECT',
            'SFP Bidi', // Perhatikan spasi, sesuaikan dengan nilai di database
            'Cascading',
            'L2S', // Di form Anda L2S, di gambar L2SW (pastikan konsisten dengan DB)
            'OTN',
            'ONT',
            'Re_engineering', // Di gambar RE-ENG, di form Re-engineering (pakai yang di DB)
            'lainnya', // Di gambar LAINNYA, di form lainnya (pakai yang di DB)
        ];

        $skenarioIntegrasiByRegional = [];
        $totalSkenarioIntegrasiPerColumn = array_fill_keys($skenarioUplinkColumns, 0); // Total per kolom
        $totalSkenarioIntegrasiOverall = 0; // Total keseluruhan

        // Ambil semua regional unik yang ada di data proyek,
        // atau gunakan enum Regional jika Anda ingin semua regional ditampilkan
        // bahkan jika tidak ada data proyek di dalamnya.
        // Jika ingin hanya regional yang punya data:
        // $allExistingRegionals = Project::distinct('regional')->pluck('regional')->sort()->toArray();
        // $regionsForSkenario = array_map(fn($r) => ['value' => $r], $allExistingRegionals);

        // Menggunakan Enum Regional agar semua regional pasti muncul
        $regionsForSkenario = Regional::cases(); // Sama seperti Funneling OLT

        foreach ($regionsForSkenario as $regionalEnum) {
            $regionName = $regionalEnum->value;
            $skenarioIntegrasiByRegional[$regionName] = []; // Inisialisasi untuk regional ini
            $totalSkenarioIntegrasiPerRegional = 0; // Total baris (per regional)

            // Base query untuk regional spesifik, dengan filter dashboard utama
            $regionalSkenarioQuery = $baseQuery->clone()->where('regional', $regionName);

            $selectStatements = [];
            foreach ($skenarioUplinkColumns as $columnName) {
                // Gunakan nama kolom di database ('scenario_uplink') untuk WHERE
                $selectStatements[] = "COUNT(CASE WHEN scenario_uplink = '{$columnName}' THEN 1 ELSE NULL END) as " . str_replace([' ', '-', '_'], '', $columnName) . "_count";
            }

            $rawSkenarioData = $regionalSkenarioQuery->selectRaw(implode(', ', $selectStatements))->first();

            foreach ($skenarioUplinkColumns as $columnName) {
                $alias = str_replace([' ', '-', '_'], '', $columnName) . '_count';
                $count = $rawSkenarioData->$alias ?? 0; // Ambil nilai atau 0 jika null

                $skenarioIntegrasiByRegional[$regionName][$columnName] = $count;
                $totalSkenarioIntegrasiPerRegional += $count;
                $totalSkenarioIntegrasiPerColumn[$columnName] += $count; // Akumulasi total per kolom
            }
            $skenarioIntegrasiByRegional[$regionName]['Total'] = $totalSkenarioIntegrasiPerRegional; // Tambah total baris
            $totalSkenarioIntegrasiOverall += $totalSkenarioIntegrasiPerRegional; // Akumulasi total keseluruhan
        }

        // =======================================================
        // BAGIAN 5: LOGIKA FAILED INTEGRASI - BARU
        // =======================================================
        $failedIntegrasiProjects = $baseQuery->clone()
                                             ->whereNotNull('plan_integrasi') // plan_integrasi sudah diisi
                                             ->whereNull('realisasi_integrasi')   // realisasi_integrasi masih kosong
                                             ->whereDate('plan_integrasi', '<=', $today) // plan_integrasi adalah hari ini atau di masa lalu
                                             ->with('user')
                                             ->select('regional', 'witel', 'sto', 'site', 'ihld', 'catuan_id', 'user_id')
                                             ->get();

        // =======================================================
        // BAGIAN 4: LOGIKA DAILY INTEGRASI - BARU
        // =======================================================
        $today = Carbon::today(); // Dapatkan tanggal hari ini

        $dailyIntegrasiProjects = $baseQuery->clone()
                                            ->whereDate('plan_integrasi', $today) // Filter berdasarkan tanggal hari ini
                                            ->with('user') // Eager load user untuk mendapatkan nama mitra
                                            ->select('regional', 'witel', 'sto', 'site', 'ihld', 'catuan_id', 'user_id') // Pilih kolom yang dibutuhkan
                                            ->get();
        // =======================================================
        // BAGIAN 4: LOGIKA GRAFIK S-CURVE (PLAN & REALISASI INTEGRASI)
        // =======================================================
        $sCurveLabels = [];
        $sCurvePlanData = [];
        $sCurveRealData = [];

        // Tentukan rentang tanggal untuk grafik
        // Start Date: Selalu 12 bulan yang lalu dari bulan ini
        $startDate = Carbon::now()->subMonths(11)->startOfMonth(); // Mengambil 12 bulan termasuk bulan ini
        // End Date: Akhir bulan saat ini atau bulan depan (sesuai kebutuhan Anda)
        $endDate = Carbon::now()->endOfMonth(); // Hingga akhir bulan ini

        // Atau jika Anda ingin sampai bulan depan:
        // $endDate = Carbon::now()->addMonth()->startOfMonth();

        $currentDate = $startDate->copy();

        while ($currentDate->lessThanOrEqualTo($endDate)) {
            $monthYear = $currentDate->format('M Y'); // Contoh: Jun 2025
            $sCurveLabels[] = $monthYear;

            // Clone base query untuk memastikan filter dashboard diterapkan jika diinginkan
            // Jika Anda ingin grafik ini TIDAK dipengaruhi filter dashboard, gunakan Project::query()
            $queryForGraph = $baseQuery->clone(); // Menggunakan filter dashboard

            // Hitung kumulatif PLAN INTEGRASI hingga akhir bulan ini
            $planIntegrasiCumulative = $queryForGraph->clone()
                                                    ->whereNotNull('plan_integrasi')
                                                    ->where('plan_integrasi', '<=', $currentDate->endOfMonth()->toDateString())
                                                    ->count();
            $sCurvePlanData[] = $planIntegrasiCumulative;

            // Hitung kumulatif REALISASI INTEGRASI hingga akhir bulan ini
            $realIntegrasiCumulative = $queryForGraph->clone()
                                                    ->whereNotNull('realisasi_integrasi')
                                                    ->where('realisasi_integrasi', '<=', $currentDate->endOfMonth()->toDateString())
                                                    ->count();
            $sCurveRealData[] = $realIntegrasiCumulative;

            $currentDate->addMonth(); // Maju ke bulan berikutnya
        }
        // =======================================================
        // BAGIAN 5: MENGIRIMKAN SEMUA VARIABEL KE VIEW DASHBOARD
        // =======================================================
        return view('dashboard', compact(
            'projects',
            'projectCount',
            'planSurveyCount',
            'planDeliveryCount',
            'planInstalasiCount',
            'planIntegrasiCount',
            'realSurveyCount',
            'realDeliveryCount',
            'realInstalasiCount',
            'realIntegrasiCount',
            'dropYesCount',
            'dropNoCount',
            'dropRelokasiCount',
            'selectedMitra',
            'selectedRegional',
            'selectedWitel',
            // Variabel untuk Funneling OLT yang akan digunakan di dashboard.blade.php
            'funnelingData',
            'totalFunnelingCounts',
            // Variabel untuk Skenario Integrasi (per Regional)
            'skenarioUplinkColumns', // Kirim juga daftar kolomnya untuk header tabel
            'skenarioIntegrasiByRegional',
            'totalSkenarioIntegrasiPerColumn',
            'totalSkenarioIntegrasiOverall',
            // Variabel untuk Daily Integrasi - BARU
            'dailyIntegrasiProjects',
            'regions', // Kirim juga list regionalnya untuk loop di view
            'failedIntegrasiProjects', // Kirim data ini ke view

             // Variabel untuk Grafik S-Curve
            'sCurveLabels',
            'sCurvePlanData',
            'sCurveRealData'
        ));
    }

    /**
     * Helper function to initialize all metric counts to zero for the Funneling OLT report.
     * Dipisahkan agar kode lebih rapi.
     */
    private function initializeFunnelingMetrics()
    {
        return [
            'plan_csf'         => 0,
            'ftth_ready_csf'   => 0,
            'ftth_ready_port'  => 0,
            'delivery_plan'    => 0,
            'delivery_done'    => 0,
            'instalasi_plan'   => 0,
            'instalasi_done'   => 0,
            'integrasi_plan'   => 0,
            'integrasi_done'   => 0,
            'golive_csf'       => 0,
            'golive_port'      => 0,
            'uplink_ready'     => 0,
            'uplink_not_ready' => 0,
        ];
    }

}