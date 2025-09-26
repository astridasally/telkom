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
use App\Exports\PopupExport;
use App\Exports\FunnelingDetailExport;
use App\Exports\SkenarioDetailExport;




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
            $file = $request->file('bukti_drop');
            $filename = time() . '_' . $file->getClientOriginalName();

            // simpan langsung ke public_html/storage/bukti_drop
            $file->move(public_path('storage/bukti_drop'), $filename);

            // simpan path relatif ke DB
            $validated['bukti_drop'] = 'bukti_drop/' . $filename;
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

    // --- FILTER PERAN ---
    if ($user->role === 'mitra') {
        $query->where(function ($q) use ($user) {
            $q->where('assign_to', $user->name)
              ->orWhereNull('assign_to')
              ->orWhere('assign_to', '');
        });
    }

    // --- TAMBAHAN UNTUK FILTER DROPDOWN ---
    $selectedRegionalFilter = $request->input('filter_regional');
    $selectedWitelFilter    = $request->input('filter_witel');
    $selectedStoFilter      = $request->input('filter_sto');

    // 🔹 Handle filter mitra (default beda sesuai role)
    if ($user->role === 'mitra') {
        $selectedMitraFilter = $request->input('filter_assign_to', $user->name);
    } else {
        $selectedMitraFilter = $request->input('filter_assign_to', 'all');
    }

    $selectedType = $request->input('project_type', 'Project TA'); // default Project TA

    if ($selectedRegionalFilter && $selectedRegionalFilter !== 'all') {
        $query->where('regional', $selectedRegionalFilter);
    }

    if ($selectedWitelFilter && $selectedWitelFilter !== 'all') {
        $query->where('witel', $selectedWitelFilter);
    }

    if ($selectedStoFilter && $selectedStoFilter !== 'all') {
        $query->where('sto', $selectedStoFilter);
    }

    // 🔹 Filter assign_to
    if ($selectedMitraFilter && $selectedMitraFilter !== 'all') {
        if ($selectedMitraFilter === 'unassigned') {
            $query->where(function($q) {
                $q->whereNull('assign_to')
                  ->orWhere('assign_to', '');
            });
        } else {
            $query->where('assign_to', $selectedMitraFilter);
        }
    }

    // 🔹 Filter project type
    if ($selectedType && $selectedType !== 'All Project') {
        $query->where('project_type', $selectedType);
    }
    // --- END FILTER DROPDOWN ---

    // --- Pencarian utama ---
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
    $projects = $query->paginate(200)->appends($request->query());

    // --- Data untuk dropdown ---
    $allRegionals = Project::distinct('regional')->pluck('regional')->sort()->toArray();
    $allWitels    = Project::distinct('witel')->pluck('witel')->sort()->toArray();
    $allStos      = Project::distinct('sto')->pluck('sto')->sort()->toArray();
    $allMitras    = Project::distinct('assign_to')->pluck('assign_to')->sort()->toArray();
    $allProjectTypes = Project::whereNotNull('project_type')
        ->where('project_type', '!=', '')
        ->distinct()
        ->pluck('project_type')
        ->sort()
        ->toArray();

    return view('project_report', compact(
        'projects',
        'allRegionals',
        'allWitels',
        'allStos',
        'allMitras',
        'allProjectTypes',
        'selectedRegionalFilter',
        'selectedWitelFilter',
        'selectedStoFilter',
        'selectedMitraFilter',
        'selectedType'
    ));
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
    $user = Auth::user();

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

        // validate form edit TA
        'priority_ta' => 'nullable',
        'dependensi' => 'nullable',
        'assign_to' => 'nullable',
        'project_type' =>'required',
        'jumlah_port' => 'nullable',
        'status_osp' => 'nullable',
        'scenario_uplink' => 'nullable',
        'status_uplink' => 'nullable',
        'remark_ta' => 'nullable',
    ];

    // Logika validasi kondisional untuk 'bukti_drop'
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

    // 🔹 Logika role mitra
    if ($user->role === 'mitra') {
        // Kalau kosong/null → boleh klaim
        if (empty($project->assign_to)) {
            $validated['assign_to'] = $user->name;
        }
        // Kalau sudah ada isinya dan bukan milik dia → tolak
        elseif ($project->assign_to !== $user->name) {
            abort(403, 'Project ini sudah dimiliki mitra lain.');
        }
    }

    // Update project
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
        'sto' => 'nullable',
        'site' => 'nullable',
        'category' => 'required',
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

public function destroy($id)
{
    $project = Project::findOrFail($id);
    $project->delete();

    return redirect()->route('project_report')->with('success', 'Project berhasil dihapus');
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
        $selectedWitelFilter = $request->input('filter_witel');
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

        if ($selectedWitelFilter && $selectedWitelFilter !== 'all') {
            $baseQuery->where('witel', $selectedWitelFilter);
        }

        // FILTER TYPE
        if ($selectedType && $selectedType !== 'All Project') {
            $baseQuery->where('project_type', $selectedType);
        }

        // Ambil semua opsi filter
        $allMitras       = Project::distinct()->pluck('assign_to')->sort()->toArray();
        $allWitels       = Project::distinct('witel')->pluck('witel')->sort()->toArray();

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
        $planSurveyCount = $baseQuery->clone()
            ->whereNotNull('plan_survey')
            ->where('category', 'CSF')
            ->where('drop_data','No')->count();

        $realSurveyCount = $baseQuery->clone()
            ->whereNotNull('realisasi_survey')
            ->where('category', 'CSF')
            ->where('drop_data','No')->count();

        // DELIVERY (untuk dashboard utama)
        $planDeliveryCount = $baseQuery->clone()
            ->whereNotNull('plan_delivery')
            ->where('category', 'CSF')
            ->where('drop_data','No')->count();

        $realDeliveryCount = $baseQuery->clone()
            ->whereNotNull('realisasi_delivery')
            ->where('category', 'CSF')
            ->where('drop_data','No')->count();

        // INSTALASI (untuk dashboard utama)
        $planInstalasiCount = $baseQuery->clone()
            ->whereNotNull('plan_instalasi')
            ->where('category', 'CSF')
            ->where('drop_data','No')->count();

        $realInstalasiCount = $baseQuery->clone()
            ->whereNotNull('realisasi_instalasi')
            ->where('category', 'CSF')
            ->where('drop_data','No')->count();

        // INTEGRASI (untuk dashboard utama)
        $planIntegrasiCount = $baseQuery->clone()
            ->whereNotNull('plan_integrasi')
            ->where('category', 'CSF')
            ->where('drop_data','No')->count();

        $realIntegrasiCount = $baseQuery->clone()
            ->whereNotNull('realisasi_integrasi')
            ->where('category', 'CSF')
            ->where('drop_data','No')->count();

        // DROP
        $dropYesCount = $baseQuery->clone()
            ->where('status_osp', 'Drop')
            ->count();

        $dropNoCount = $baseQuery->clone()
            ->where(function ($q) {
                $q->where('status_osp', '!=', 'Drop')
                ->orWhereNull('status_osp');
            })
            ->count();

        $dropRelokasiCount = $baseQuery->clone()
            ->where('drop_data', 'Relokasi')
            ->count();

        // =======================================================
        // BAGIAN 2: LOGIKA FUNNELING OLT (BERADA DI DASHBOARD)
        // =======================================================
    $selectedType = $request->input('project_type', 'Project TA'); // null artinya semua project type
    $regions = Regional::cases();

    $tableData = [];
    $totalCounts = $this->initializeFunnelingMetrics();

    foreach ($regions as $regionalEnum) {
        $regionName = $regionalEnum->value;

        $query = Project::where('regional', $regionName);


        // Filter project type jika dipilih
        if (!empty($selectedType)) {
            $query->where('project_type', $selectedType);
        }

        $counts = $this->initializeFunnelingMetrics();
        $counts['plan_csf']         = (clone $query)->where('category','CSF')->where(function($q) {$q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp'); })->count();
        $counts['ftth_ready_csf']   = (clone $query)->where('priority_ta', 'P1')->where('category', 'CSF')
            ->where(function($q) {$q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp'); })->count();
        $counts['jumlah_port']      = (clone $query)->whereNotNull('jumlah_port')->where('category', 'CSF')
            ->where(function($q) {$q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp');})->sum('jumlah_port');
        $counts['delivery_plan']    = (clone $query)->whereNotNull('plan_delivery')->where('category', 'CSF')->where('drop_data','No')->count();
        $counts['delivery_done']    = (clone $query)->whereNotNull('realisasi_delivery')->where('category', 'CSF')->where('drop_data','No')->count();
        $counts['instalasi_plan']   = (clone $query)->whereNotNull('plan_instalasi')->where('category', 'CSF')->where('drop_data','No')->count();
        $counts['instalasi_done']   = (clone $query)->whereNotNull('realisasi_instalasi')->where('category', 'CSF')->where('drop_data','No')->count();
        $counts['integrasi_plan']   = (clone $query)->whereNotNull('plan_integrasi')->where('category', 'CSF')->where('drop_data','No')->count();
        $counts['integrasi_done']   = (clone $query)->whereNotNull('realisasi_integrasi')->where('category', 'CSF')->where('drop_data','No')->count();
        $counts['golive_status']    = (clone $query)->where('status_osp', 'Go Live') ->where('category', 'CSF')->count();
        $counts['uplink_ready']     = (clone $query)->where('status_uplink', 'Ready')->where('category', 'CSF')
            ->where(function($q) {$q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp'); })->count();
        $counts['uplink_not_ready'] = (clone $query)->where('status_uplink', 'Not Ready')->where('category', 'CSF')
            ->where(function($q) {$q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp'); })->count();

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
    'Re-engineering',
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
        ->where('drop_data','No')
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
                                             ->where('drop_data','No')
                                                ->where('category', 'CSF')
                                             ->whereNull('realisasi_integrasi')   // realisasi_integrasi masih kosong
                                             ->whereDate('plan_integrasi', '<=', $today) // plan_integrasi adalah hari ini atau di masa lalu
                                             ->with('user')
                                             ->select('regional', 'witel', 'sto', 'site', 'ihld', 'catuan_id', 'assign_to','plan_integrasi')
                                             ->get();

        // =======================================================
        // BAGIAN 4: LOGIKA DAILY INTEGRASI - BARU
        // =======================================================
        $today = Carbon::today(); // Dapatkan tanggal hari ini

        $dailyIntegrasiProjects = $baseQuery->clone()
                                            ->whereDate('plan_integrasi', $today) // Filter berdasarkan tanggal hari ini
                                            ->where('drop_data','No')
                                            ->where('category', 'CSF')
                                            ->with('user') // Eager load user untuk mendapatkan nama mitra
                                            ->select('regional', 'witel', 'sto', 'site', 'ihld', 'catuan_id', 'assign_to') // Pilih kolom yang dibutuhkan
                                            ->get();
        // =======================================================
        // =======================================================
 // === BAGIAN 4: LOGIKA GRAFIK S-CURVE ===
    $sCurveLabels = [];
    $sCurvePlanData = [];
    $sCurveRealData = [];
// cari tanggal terakhir untuk plan & realisasi
$lastPlanDate = Project::whereNotNull('plan_integrasi')->max('plan_integrasi');
$lastRealDate = Project::whereNotNull('realisasi_integrasi')->max('realisasi_integrasi');

$lastPlanDate = $lastPlanDate ? Carbon::parse($lastPlanDate)->endOfMonth() : null;
$lastRealDate = $lastRealDate ? Carbon::parse($lastRealDate)->endOfMonth() : null;

// looping stop ikut plan (biar Oktober tetap ada kalau plan sudah masuk)
$lastDate = $lastPlanDate ?? $lastRealDate ?? now()->endOfMonth();

$startDate = $lastDate->copy()->subMonths(11)->startOfMonth();
$currentDate = $startDate->copy();

$sCurveLabels = [];
$sCurvePlanData = [];
$sCurveRealData = [];

while ($currentDate->lessThanOrEqualTo($lastDate)) {
    $monthYear = $currentDate->format('M Y');
    $sCurveLabels[] = $monthYear;

    $queryForGraph = $baseQuery->clone();

    // PLAN cumulative
    $planIntegrasiCumulative = $queryForGraph->clone()
        ->whereNotNull('plan_integrasi')
        ->where('plan_integrasi', '<=', $currentDate->copy()->endOfMonth()->toDateString())
        ->where('drop_data','No')
        ->where('category', 'CSF')
        ->count();
    $sCurvePlanData[] = $planIntegrasiCumulative;

    // REALISASI cumulative → hanya hitung sampai lastRealDate
    if ($lastRealDate && $currentDate->lessThanOrEqualTo($lastRealDate)) {
        $realIntegrasiCumulative = $queryForGraph->clone()
            ->whereNotNull('realisasi_integrasi')
            ->where('realisasi_integrasi', '<=', $currentDate->copy()->endOfMonth()->toDateString())
            ->where('drop_data','No')
            ->where('category', 'CSF')
            ->count();
    } else {
        $realIntegrasiCumulative = null; // ❌ tidak ada titik setelah bulan terakhir realisasi
    }

    $sCurveRealData[] = $realIntegrasiCumulative;

    $currentDate->addMonth();
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
            'selectedWitelFilter',
            'selectedType',
            'allMitras',
            'allWitels',
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
        ->where('category', 'CSF');

    // 🔹 Filter Project Type (TAMBAHAN WAJIB)
    if ($request->has('project_type') && $request->project_type !== 'All Project') {
        $query->where('project_type', $request->project_type);
    }

    // 🔹 Filter Mitra
    if ($request->has('filter_assign_to') && $request->filter_assign_to !== 'all') {
        $query->where('assign_to', $request->filter_assign_to);
    }

    // 🔹 Filter Regional
    if ($request->has('regional') && $request->regional !== 'All Regional') {
        $query->where('regional', $request->regional);
    }

    // 🔹 Filter Witel
    if ($request->has('filter_witel') && $request->filter_witel !== 'all') {
        $query->where('witel', $request->filter_witel);
    }
    
    // 🔹 Kondisi sesuai stage
    switch ($stage) {
        case 'lainnya':
            $query->where('drop_data', 'No')
            ->where(function ($q) {
                $q->whereNotNull('plan_survey')
                  ->orWhereNotNull('realisasi_survey');
            });
            break;

        case 'mos':
            $query->where('drop_data', 'No')
            ->where(function ($q) {
                $q->whereNotNull('plan_delivery')
                  ->orWhereNotNull('realisasi_delivery');
            });
            break;

        case 'instalasi':
            $query->where('drop_data', 'No')
            ->where(function ($q) {
                $q->whereNotNull('plan_instalasi')
                  ->orWhereNotNull('realisasi_instalasi');
            });
            break;

        case 'integrasi':
            $query->where('drop_data', 'No')
            ->where(function ($q) {
                $q->whereNotNull('plan_integrasi')
                  ->orWhereNotNull('realisasi_integrasi');
            });
            break;

        case 'drop':
            $query->where(function ($q) {
                $q->where('status_osp', 'Drop');
            });
            break;
    }

    $fields = match($stage) {
        'lainnya' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_survey', 'realisasi_survey'],
        'mos' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_delivery', 'realisasi_delivery'],
        'instalasi' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_instalasi', 'realisasi_instalasi'],
        'integrasi' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_integrasi', 'realisasi_integrasi'],
        'drop' => ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'status_osp'],
    };

    return response()->json($query->select($fields)->get());
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

public function exportPopup(Request $request)
{
    $data = json_decode($request->input('popupData'), true);
    $columns = json_decode($request->input('popupColumns'), true);
    $stage = $request->input('stage', 'popup');

    return Excel::download(new PopupExport($data, $columns), "popup_{$stage}.xlsx");
}
public function exportFunnelingDetail(Request $request)
{
    $stage = $request->get('stage');
    $projectType = $request->get('project_type', 'All Project');

    $query = Project::query()->where('category', 'CSF');
    if ($projectType !== 'All Project') {
        $query->where('project_type', $projectType);
    }

    // default columns
    $columns = ['Regional', 'Witel', 'STO', 'Site', 'IHLD', 'Catuan ID', 'Assign To'];
    $select  = ['regional', 'witel', 'sto', 'site', 'ihld', 'catuan_id', 'assign_to'];

    // mapping stage → filter + kolom
    switch ($stage) {
        case 'delivery_plan':
            $query->whereNotNull('plan_delivery')->where('drop_data','No');
            $columns[] = 'Plan Delivery';
            $select[] = 'plan_delivery';
            break;

        case 'delivery_done':
            $query->whereNotNull('realisasi_delivery')->where('drop_data','No');
            $columns[] = 'Realisasi Delivery';
            $select[] = 'realisasi_delivery';
            break;

        case 'instalasi_plan':
            $query->whereNotNull('plan_instalasi')->where('drop_data','No');
            $columns[] = 'Plan Instalasi';
            $select[] = 'plan_instalasi';
            break;

        case 'instalasi_done':
            $query->whereNotNull('realisasi_instalasi')->where('drop_data','No');
            $columns[] = 'Realisasi Instalasi';
            $select[] = 'realisasi_instalasi';
            break;

        case 'integrasi_plan':
            $query->whereNotNull('plan_integrasi')->where('drop_data','No');
            $columns[] = 'Plan Integrasi';
            $select[] = 'plan_integrasi';
            break;

        case 'integrasi_done':
            $query->whereNotNull('realisasi_integrasi')->where('drop_data','No');
            $columns[] = 'Realisasi Integrasi';
            $select[] = 'realisasi_integrasi';
            break;

        case 'jumlah_port':
            $query->whereNotNull('jumlah_port')->where(function($q) {$q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp'); });
            $columns[] = 'Jumlah Port';
            $select[] = 'jumlah_port';
            break;

        case 'golive_status':
            $query->where('status_osp', 'Go Live')->where(function($q) {$q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp'); });
            $columns[] = 'Status OSP';
            $select[] = 'status_osp';
            break;

        case 'uplink_ready':
        case 'uplink_not_ready':
            $query->where('status_uplink', $stage === 'uplink_ready' ? 'Ready' : 'Not Ready')->where(function($q) {$q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp'); });
            $columns[] = 'Status Uplink';
            $select[] = 'status_uplink';
            break;

        case 'plan_csf':
            $query->where(function($q) {
                $q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp');
            });
            $columns[] = 'Category';
            $select[] = 'category';
            break;

        case 'ftth_ready_csf':
            $query->where('priority_ta', 'P1')
                  ->where(function($q) {
                      $q->where('status_osp', '!=', 'Drop')->orWhereNull('status_osp');
                  });
            $columns[] = 'Priority TA';
            $select[] = 'priority_ta';
            break;
    }

    // ambil data sesuai select
    $projects = $query->get($select);

    $data = $projects->map(function($p) use ($select) {
        return collect($select)->map(fn($col) => $p->$col)->toArray();
    })->toArray();

    return Excel::download(new FunnelingDetailExport($data, $columns), "funneling_{$stage}.xlsx");
}

public function exportSkenarioIntegrasi(Request $request)
{
    $skenario = $request->get('skenario');
    $projectType = $request->get('project_type', 'All Project');

    $query = Project::query()->where('category', 'CSF')->where('drop_data', 'No');
    if ($projectType !== 'All Project') {
        $query->where('project_type', $projectType);
    }

    // Default kolom
    $columns = ['Regional', 'Witel', 'STO', 'Site', 'IHLD', 'Catuan ID', 'Assign To', 'Skenario Integrasi'];
    $select  = ['regional', 'witel', 'sto', 'site', 'ihld', 'catuan_id', 'assign_to', 'scenario_uplink'];

    // Filter berdasarkan skenario
    if ($skenario) {
        $query->where('scenario_uplink', $skenario);
    }

    // Ambil data
    $projects = $query->get($select);

    $data = $projects->map(function($p) use ($select) {
        return collect($select)->map(fn($col) => $p->$col)->toArray();
    })->toArray();

    return Excel::download(new FunnelingDetailExport($data, $columns), "skenario_{$skenario}.xlsx");
}





}

   