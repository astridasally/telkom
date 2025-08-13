<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User; // Pastikan ini ada
use Illuminate\Http\Request;
use App\Enums\Regional; // Pastikan ini ada
use App\Enums\Witel; // Pastikan ini ada
use Carbon\Carbon; // Pastikan ini ada
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataExcel;
use App\Exports\FunnelingExport;



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
            'category' => 'required',
            'catuan_id' => 'nullable',
            'ihld' => 'nullable',
            'assign_to' => 'nullable',
            'project_type' =>'required',
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
            'drop_data' => 'required',

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

       // Logika filter berdasarkan peran pengguna (revisi)
        if ($user->role === 'mitra') {
            // Mitra hanya melihat proyek di mana 'assign_to' adalah nama mitra itu sendiri
            // PENTING: Pastikan Auth::user()->name untuk role 'mitra' sama dengan nilai di kolom 'assign_to'
            $query->where('assign_to', $user->name);
        }

        // --- TAMBAHAN UNTUK FILTER DROPDOWN ---
        $selectedRegionalFilter = $request->input('filter_regional');
        $selectedWitelFilter = $request->input('filter_witel');
        $selectedStoFilter = $request->input('filter_sto');
        $selectedMitraFilter = $request->input('filter_assign_to');
        $selectedType     = $request->input('project_type', 'Project TA'); // default Project TA


        if ($selectedRegionalFilter && $selectedRegionalFilter !== 'all') {
            $query->where('regional', $selectedRegionalFilter);
        }

        if ($selectedWitelFilter && $selectedWitelFilter !== 'all') {
            $query->where('witel', $selectedWitelFilter);
        }

        if ($selectedStoFilter && $selectedStoFilter !== 'all') {
            $query->where('sto', $selectedStoFilter);
        }

        if ($selectedMitraFilter && $selectedMitraFilter !== 'all') {
            $query->where('assign_to', $selectedMitraFilter);
        }
        // FILTER TYPE
        if ($selectedType && $selectedType !== 'All Project') {
            $query->where('project_type', $selectedType);
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
                    $q->orWhereRaw('LOWER(category) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(remark) LIKE ?', ['%' . $searchTerm . '%']);
                }

                if ($user->role === 'vendor' || $user->role === 'admin') {
                    $q->orWhereRaw('LOWER(priority_ta) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(dependensi) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(assign_to) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(golive_status) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(jumlah_port) LIKE ?', ['%' . $searchTerm . '%'])
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

        $projects = $query->paginate(100)->appends($request->query());

        // --- PASTIKAN BAGIAN INI ADA DAN BENAR ---
        // Untuk mengisi dropdown di view, kita perlu mendapatkan daftar unik Regional, Witel, STO
        // Ambil dari database, pastikan model Project terhubung dengan kolom-kolom ini
        $allRegionals = Project::distinct('regional')->pluck('regional')->sort()->toArray();
        $allWitels = Project::distinct('witel')->pluck('witel')->sort()->toArray();
        $allStos = Project::distinct('sto')->pluck('sto')->sort()->toArray();
        $allMitras = Project::distinct('assign_to')->pluck('assign_to')->sort()->toArray();
        $allProjectTypes = Project::whereNotNull('project_type')
            ->where('project_type', '!=', '')
            ->distinct()
            ->pluck('project_type')
            ->sort()
            ->toArray();

        // --- AKHIR PASTIKAN BAGIAN INI ---

        return view('project_report', compact(
            'projects',
            'allRegionals', // Pastikan ini ada
            'allWitels',    // Pastikan ini ada
            'allStos',      // Pastikan ini ada
            'allMitras',
            'allProjectTypes',
            'selectedRegionalFilter',
            'selectedWitelFilter',
            'selectedStoFilter',
            'selectedMitraFilter',
            'selectedType'
            

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
                    $q->orWhereRaw('LOWER(category) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(remark) LIKE ?', ['%' . $searchTerm . '%']);
                }

                // Kolom spesifik untuk peran 'vendor' atau 'admin'
                if ($user->role === 'vendor' || $user->role === 'admin') {
                    $q->orWhereRaw('LOWER(priority_ta) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(dependensi) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(assign_to) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(golive_status) LIKE ?', ['%' . $searchTerm . '%'])
                      ->orWhereRaw('LOWER(jumlah_port) LIKE ?', ['%' . $searchTerm . '%'])
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
        $projects = $query->paginate(100)->appends($request->query()); // appends() untuk mempertahankan parameter search

        return view('project_report', compact('projects'));
    }

//ini WITEL REGIONAL
    public function getWitels($regional)
{
    $regional = urldecode($regional);
    $mapping = \App\Enums\Regional::witels();
    return response()->json($mapping[$regional] ?? []);
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
            'category' => 'required',
            'status_osp' => 'nullable',
            'dependensi' => 'nullable',
            'assign_to' => 'nullable',
            'project_type' =>'required',
            'scenario_uplink' => 'nullable',
            'status_uplink' => 'nullable',
            'remark_ta' => 'nullable|string',
            'golive_status' => 'nullable',
            'jumlah_port' => 'nullable',

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

    // Definisikan aturan validasi secara terpisah
    $rules = [
        'regional' => 'required',
        'witel' => 'required',
        'sto' => 'required',
        'site' => 'required',
        'category' => 'nullable',
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
        'drop_data' => 'nullable|in:Yes,No,Relokasi',

        'relok_regional' => 'required_if:drop_data,Relokasi',
        'relok_witel' => 'required_if:drop_data,Relokasi',
        'relok_sto' => 'required_if:drop_data,Relokasi',
        'relok_site' => 'required_if:drop_data,Relokasi',

        //validate form edit TA
        'priority_ta' => 'nullable',
        'dependensi' => 'nullable',
        'assign_to' => 'nullable',
        'project_type' =>'required',
        'golive_status' => 'nullable',
        'jumlah_port' => 'nullable',
        'status_osp' => 'nullable',
        'scenario_uplink' => 'nullable',
        'status_uplink' => 'nullable',
        'remark_ta' => 'nullable',
    ];

    // Logika validasi kondisional untuk 'bukti_drop'
    // Validasi 'required' hanya akan diaktifkan jika drop_data = 'Yes' DAN belum ada file di database.
    if ($request->input('drop_data') === 'Yes' && is_null($project->bukti_drop)) {
        $rules['bukti_drop'] = 'required|mimes:pdf,jpg,jpeg,png|max:5200';
    } else {
        $rules['bukti_drop'] = 'nullable|mimes:pdf,jpg,jpeg,png|max:5200';
    }

    // Jalankan validasi
    $validated = $request->validate($rules);

    // --- Penanganan File ---
    if ($request->hasFile('bukti_drop')) {
        // Hapus file lama jika ada
        if ($project->bukti_drop) {
            Storage::disk('public')->delete($project->bukti_drop);
        }

        // Simpan file baru
        $path = $request->file('bukti_drop')->store('bukti_drop', 'public');
        $validated['bukti_drop'] = $path;
    } else {
        // Jika tidak ada file baru diupload, pertahankan file lama
        $validated['bukti_drop'] = $project->bukti_drop;
    }

    $validated['user_id'] = auth()->id();

    // Perbarui proyek
    $project->update($validated);

    return redirect()->route('project_report')->with('success', 'Project berhasil diperbarui.');
}
    
   public function store_project_update_admin(Request $request, $id)
{
    $project = Project::findOrFail($id);

    // Definisikan aturan validasi secara terpisah
    $rules = [
        'regional' => 'required',
        'witel' => 'required',
        'sto' => 'required',
        'site' => 'required',
        'category' => 'required',
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
        'drop_data' => 'nullable|in:Yes,No,Relokasi',
        'relok_regional' => 'required_if:drop_data,Relokasi',
        'relok_witel' => 'required_if:drop_data,Relokasi',
        'relok_sto' => 'required_if:drop_data,Relokasi',
        'relok_site' => 'required_if:drop_data,Relokasi',

        'priority_ta' => 'nullable',
        'dependensi' => 'nullable',
        'assign_to' => 'nullable',
        'project_type' =>'required',
        'golive_status' => 'nullable',
        'jumlah_port' => 'nullable',
        'status_osp' => 'nullable',
        'scenario_uplink' => 'nullable',
        'status_uplink' => 'nullable',
        'remark_ta' => 'nullable',
    ];

    // Logika validasi kondisional untuk 'bukti_drop'
    // Validasi 'required' hanya akan diaktifkan jika drop_data = 'Yes' DAN belum ada file di database.
    if ($request->input('drop_data') === 'Yes' && is_null($project->bukti_drop)) {
        $rules['bukti_drop'] = 'required|mimes:pdf,jpg,jpeg,png|max:5200';
    } else {
        $rules['bukti_drop'] = 'nullable|mimes:pdf,jpg,jpeg,png|max:5200';
    }

    // Jalankan validasi
    $validated = $request->validate($rules);

    // --- Penanganan File ---
    if ($request->hasFile('bukti_drop')) {
        // Hapus file lama jika ada
        if ($project->bukti_drop) {
            Storage::disk('public')->delete($project->bukti_drop);
        }

        // Simpan file baru
        $path = $request->file('bukti_drop')->store('bukti_drop', 'public');
        $validated['bukti_drop'] = $path;
    } else {
        // Jika tidak ada file baru diupload, pertahankan file lama
        $validated['bukti_drop'] = $project->bukti_drop;
    }

    $validated['user_id'] = $project->user_id; // relasi ke user yang membuat project

    // Perbarui proyek
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
        $selectedMitra    = $request->input('filter_assign_to');
        $selectedRegional = $request->input('regional');
        $selectedWitel    = $request->input('witel');
        $selectedType     = $request->input('project_type', 'Project TA'); // default Project TA

        // 3. Terapkan filter berdasarkan input dari Request

        // FILTER MITRA (Menggunakan relasi user)
        if ($selectedMitra && $selectedMitra !== 'all') {
            $baseQuery->where('assign_to', $selectedMitra);
        }

        // FILTER REGIONAL
        if ($selectedRegional && $selectedRegional !== 'All Regional') {
            $baseQuery->where('regional', $selectedRegional);
        }

        // FILTER WITEL
        if ($selectedWitel && $selectedWitel !== 'All Witel') {
            $baseQuery->where('witel', $selectedWitel);
        }

        // FILTER TYPE
        if ($selectedType && $selectedType !== 'All Project') {
            $baseQuery->where('project_type', $selectedType);
        }

        // Ambil semua opsi filter
        $allMitras       = Project::distinct()->pluck('assign_to')->sort()->toArray();
       $allProjectTypes = Project::whereNotNull('project_type')
        ->where('project_type', '!=', '')
        ->distinct()
        ->pluck('project_type')
        ->sort()
        ->toArray();


        // 4. Ambil data proyek UTAMA setelah semua filter dasar diterapkan
        $projects = $baseQuery->get(); // Ini adalah data yang akan ditampilkan di tabel utama dashboard

        // 5. Hitung semua metrik untuk dashboard utama
        $projectCount = $baseQuery->clone()->count();

        // SURVEY
        $planSurveyCount = $baseQuery->clone()->whereNotNull('plan_survey')->where('drop_data', 'No')->where('category', 'CSF')->count();
        $realSurveyCount = $baseQuery->clone()->whereNotNull('realisasi_survey')->where('drop_data', 'No')->where('category', 'CSF')->count();

        // DELIVERY (untuk dashboard utama)
        $planDeliveryCount = $baseQuery->clone()->whereNotNull('plan_delivery')->where('drop_data', 'No')->where('category', 'CSF')->count();
        $realDeliveryCount = $baseQuery->clone()->whereNotNull('realisasi_delivery')->where('drop_data', 'No')->where('category', 'CSF')->count();

        // INSTALASI (untuk dashboard utama)
        $planInstalasiCount = $baseQuery->clone()->whereNotNull('plan_instalasi')->where('drop_data', 'No')->where('category', 'CSF')->count();
        $realInstalasiCount = $baseQuery->clone()->whereNotNull('realisasi_instalasi')->where('drop_data', 'No')->where('category', 'CSF')->count();

        // INTEGRASI (untuk dashboard utama)
        $planIntegrasiCount = $baseQuery->clone()->whereNotNull('plan_integrasi')->where('drop_data', 'No')->where('category', 'CSF')->count();
        $realIntegrasiCount = $baseQuery->clone()->whereNotNull('realisasi_integrasi')->where('drop_data', 'No')->where('category', 'CSF')->count();

        // DROP
        $dropYesCount      = $baseQuery->clone()->where('drop_data', 'Yes')->count();
        $dropNoCount       = $baseQuery->clone()->where('drop_data', 'No')->count();
        $dropRelokasiCount = $baseQuery->clone()->where('drop_data', 'Relokasi')->count();


        // =======================================================
        // BAGIAN 2: LOGIKA FUNNELING OLT (BERADA DI DASHBOARD)
        // =======================================================
    $selectedType = $request->input('project_type', 'Project TA'); // null artinya semua project type
    $regions = Regional::cases();

    $tableData = [];
    $totalCounts = $this->initializeFunnelingMetrics();

    foreach ($regions as $regionalEnum) {
        $regionName = $regionalEnum->value;

        $query = Project::where('regional', $regionName)
            ->where(function($q) {
                $q->where('status_osp', '!=', 'Drop')
                  ->orWhereNull('status_osp');
            });

        // Filter project type jika dipilih
        if (!empty($selectedType)) {
            $query->where('project_type', $selectedType);
        }

        $counts = $this->initializeFunnelingMetrics();

        $counts['plan_csf']         = (clone $query)->where('category','CSF')->count();
        $counts['ftth_ready_csf']   = (clone $query)->where('priority_ta', 'P1')->where('category', 'CSF')->count();
        $counts['jumlah_port']      = (clone $query)->whereNotNull('jumlah_port')->where('category', 'CSF')->sum('jumlah_port');
        $counts['delivery_plan']    = (clone $query)->whereNotNull('plan_delivery')->where('category', 'CSF')->count();
        $counts['delivery_done']    = (clone $query)->whereNotNull('realisasi_delivery')->where('category', 'CSF')->count();
        $counts['instalasi_plan']   = (clone $query)->whereNotNull('plan_instalasi')->where('category', 'CSF')->count();
        $counts['instalasi_done']   = (clone $query)->whereNotNull('realisasi_instalasi')->where('category', 'CSF')->count();
        $counts['integrasi_plan']   = (clone $query)->whereNotNull('plan_integrasi')->where('category', 'CSF')->count();
        $counts['integrasi_done']   = (clone $query)->whereNotNull('realisasi_integrasi')->where('category', 'CSF')->count();
        $counts['golive_status']    = (clone $query)->whereNotNull('golive_status')->where('category', 'CSF')->count();
        $counts['uplink_ready']     = (clone $query)->where('status_uplink', 'Ready')->where('category', 'CSF')->count();
        $counts['uplink_not_ready'] = (clone $query)->where('status_uplink', 'Not Ready')->where('category', 'CSF')->count();

        $tableData[$regionName] = $counts;

        // Hitung total
        foreach ($counts as $key => $value) {
            $totalCounts[$key] += $value;
        }
    }




        // =======================================================
// BAGIAN 3: LOGIKA SKENARIO INTEGRASI (PER REGIONAL) - FIXED
// =======================================================

$skenarioUplinkColumns = [
    'Direct Core',
    'SFP Bidi',
    'Cascading',
    'L2S',
    'OTN',
    'ONT',
    'Re_engineering',
    'Lainnya',
];

$skenarioIntegrasiByRegional = [];
$totalSkenarioIntegrasiPerColumn = array_fill_keys($skenarioUplinkColumns, 0);
$totalSkenarioIntegrasiOverall = 0;

// ✅ INI PENTING! Ambil semua enum regional
$regionsForSkenario = Regional::cases();

foreach ($regionsForSkenario as $regionalEnum) {
    $regionName = $regionalEnum->value;
    $skenarioIntegrasiByRegional[$regionName] = [];
    $totalSkenarioIntegrasiPerRegional = 0;

    // ✅ Filter berdasarkan region, drop_data, dan category
    $regionalSkenarioQuery = $baseQuery->clone()
        ->where('regional', $regionName)
        ->where('drop_data', 'No')
        ->where('category', 'CSF');

    $selectStatements = [];
    foreach ($skenarioUplinkColumns as $columnName) {
        $alias = str_replace([' ', '-', '_'], '', $columnName) . '_count';
        $selectStatements[] = "COUNT(CASE WHEN scenario_uplink = '{$columnName}' THEN 1 ELSE NULL END) AS {$alias}";
    }

    $rawSkenarioData = $regionalSkenarioQuery
        ->selectRaw(implode(', ', $selectStatements))
        ->first();

    foreach ($skenarioUplinkColumns as $columnName) {
        $alias = str_replace([' ', '-', '_'], '', $columnName) . '_count';
        $count = $rawSkenarioData->$alias ?? 0;

        $skenarioIntegrasiByRegional[$regionName][$columnName] = $count;
        $totalSkenarioIntegrasiPerRegional += $count;
        $totalSkenarioIntegrasiPerColumn[$columnName] += $count;
    }

    $skenarioIntegrasiByRegional[$regionName]['Total'] = $totalSkenarioIntegrasiPerRegional;
    $totalSkenarioIntegrasiOverall += $totalSkenarioIntegrasiPerRegional;
}


        // =======================================================
        // BAGIAN 5: LOGIKA FAILED INTEGRASI - BARU
        // =======================================================
        $failedIntegrasiProjects = $baseQuery->clone()
                                             ->whereNotNull('plan_integrasi') // plan_integrasi sudah diisi
                                             ->where('drop_data', 'No')->where('category', 'CSF')
                                             ->whereNull('realisasi_integrasi')   // realisasi_integrasi masih kosong
                                             ->whereDate('plan_integrasi', '<=', $today) // plan_integrasi adalah hari ini atau di masa lalu
                                             ->with('user')
                                             ->select('regional', 'witel', 'sto', 'site', 'ihld', 'catuan_id', 'assign_to')
                                             ->get();

        // =======================================================
        // BAGIAN 4: LOGIKA DAILY INTEGRASI - BARU
        // =======================================================
        $today = Carbon::today(); // Dapatkan tanggal hari ini

        $dailyIntegrasiProjects = $baseQuery->clone()
                                            ->whereDate('plan_integrasi', $today) // Filter berdasarkan tanggal hari ini
                                            ->where('drop_data', 'No')->where('category', 'CSF')
                                            ->with('user') // Eager load user untuk mendapatkan nama mitra
                                            ->select('regional', 'witel', 'sto', 'site', 'ihld', 'catuan_id', 'assign_to') // Pilih kolom yang dibutuhkan
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
                                                    ->where('drop_data', 'No')->where('category', 'CSF')->count();
            $sCurvePlanData[] = $planIntegrasiCumulative;

            // Hitung kumulatif REALISASI INTEGRASI hingga akhir bulan ini
            $realIntegrasiCumulative = $queryForGraph->clone()
                                                    ->whereNotNull('realisasi_integrasi')
                                                    ->where('realisasi_integrasi', '<=', $currentDate->endOfMonth()->toDateString())
                                                    ->where('drop_data', 'No')->where('category', 'CSF')->count();
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
            'selectedType',
            'allMitras',
            'allProjectTypes',
            // Variabel untuk Funneling OLT yang akan digunakan di dashboard.blade.php
            'tableData', 'totalCounts', 'regions', 'selectedType',
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
            'delivery_plan'    => 0,
            'delivery_done'    => 0,
            'instalasi_plan'   => 0,
            'instalasi_done'   => 0,
            'integrasi_plan'   => 0,
            'integrasi_done'   => 0,
            'golive_status'    => 0,
            'jumlah_port'      => 0,
            'uplink_ready'     => 0,
            'uplink_not_ready' => 0,
        ];
    }
public function getPopupDetail(Request $request)
{
    $stage = $request->query('stage');

    $query = Project::query()
        ->where('drop_data', 'No')
        ->where('category', 'CSF');

    // 🔹 Filter Mitra
    if ($request->has('filter_assign_to') && $request->filter_assign_to !== 'all') {
        $query->where('assign_to', $request->filter_assign_to);
    }

    // 🔹 Filter Regional
    if ($request->has('regional') && $request->regional !== 'All Regional') {
        $query->where('regional', $request->regional);
    }

    // 🔹 Filter Witel
    if ($request->has('witel') && $request->witel !== 'All Witel') {
        $query->where('witel', $request->witel);
    }

    switch ($stage) {
        case 'lainnya':
            $query->where(function ($q) {
                $q->whereNotNull('plan_survey')
                  ->orWhereNotNull('realisasi_survey');
            });
            break;
        case 'mos':
            $query->where(function ($q) {
                $q->whereNotNull('plan_delivery')
                  ->orWhereNotNull('realisasi_delivery');
            });
            break;
        case 'instalasi':
            $query->where(function ($q) {
                $q->whereNotNull('plan_instalasi')
                  ->orWhereNotNull('realisasi_instalasi');
            });
            break;
        case 'integrasi':
            $query->where(function ($q) {
                $q->whereNotNull('plan_integrasi')
                  ->orWhereNotNull('realisasi_integrasi');
            });
            break;
        case 'drop':
            $query = Project::query(); 
            break;
    }

    $fields = match($stage) {
        'lainnya' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_survey', 'realisasi_survey'],
        'mos' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_delivery', 'realisasi_delivery'],
        'instalasi' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_instalasi', 'realisasi_instalasi'],
        'integrasi' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_integrasi', 'realisasi_integrasi'],
        'drop' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'drop_data'],
    };

    return response()->json($query->select($fields)->paginate(200));
}



    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv'
    ]);

    Excel::import(new DataExcel, $request->file('file'));

    return back()->with('success', 'Data project berhasil diimport!');
}

public function exportFunneling(Request $request)
{
    $data = json_decode($request->input('tableData'), true);
    $project = $request->input('selectedType', 'Unknown');

    return Excel::download(new FunnelingExport($data, $project), "funneling_{$project}.xlsx");
}


}

   