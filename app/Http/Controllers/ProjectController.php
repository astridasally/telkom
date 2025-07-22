<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User; // Pastikan ini ada
use Illuminate\Http\Request;
use App\Enums\Regional; // Pastikan ini ada
use App\Enums\Witel; // Pastikan ini ada
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


            'remark' => 'nullable|string',
        ]);


        // Simpan bukti drop (jika ada)
        if ($request->hasFile('bukti_drop')) {
            $validated['bukti_drop'] = $request->file('bukti_drop')->store('bukti_drop', 'public');
        }


        $validated['user_id'] = auth()->id(); // relasi ke user login

        $project = Project::create($validated);

        if ($project) {
            return redirect()->back()->with('success', 'Project berhasil disimpan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan project.');
        }
    }

    public function report()
    {
        if (auth()->user()->role === 'admin') {
            $projects = Project::all(); // Admin lihat semua
        } else {
            $projects = Project::where('user_id', auth()->id())->get(); // Mitra hanya miliknya
        }

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
            'priority' => 'required',
            'catuan_id' => 'nullable',
            'ihld' => 'nullable',
            'plan_survey' => 'required|date',
            'realisasi_survey' => 'nullable|date|after_or_equal:plan_survey',
            'plan_delivery' => 'nullable|date|after_or_equal:realisasi_survey',
            'realisasi_delivery' => 'nullable|date|after_or_equal:plan_delivery',
            'plan_instalasi' => 'nullable|date|after_or_equal:realisasi_delivery',
            'realisasi_instalasi' => 'nullable|date|after_or_equal:plan_instalasi',
            'plan_integrasi' => 'nullable|date|after_or_equal:realisasi_instalasi',
            'realisasi_integrasi' => 'nullable|date|after_or_equal:plan_integrasi',

            //'drop_data' => 'required|in:Yes,No,Relokasi',
            'bukti_drop' => 'required_if:drop_data,Yes|nullable|mimes:pdf,jpg,jpeg,png|max:5200',
            'relok_regional' => 'required_if:drop_data,Relokasi',
            'relok_witel'    => 'required_if:drop_data,Relokasi',
            'relok_sto'      => 'required_if:drop_data,Relokasi',
            'relok_site'     => 'required_if:drop_data,Relokasi',
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
            'priority' => 'required',
            'catuan_id' => 'nullable',
            'ihld' => 'nullable',
            'plan_survey' => 'required|date',
            'realisasi_survey' => 'nullable|date|after_or_equal:plan_survey',
            'plan_delivery' => 'nullable|date|after_or_equal:realisasi_survey',
            'realisasi_delivery' => 'nullable|date|after_or_equal:plan_delivery',
            'plan_instalasi' => 'nullable|date|after_or_equal:realisasi_delivery',
            'realisasi_instalasi' => 'nullable|date|after_or_equal:plan_instalasi',
            'plan_integrasi' => 'nullable|date|after_or_equal:realisasi_instalasi',
            'realisasi_integrasi' => 'nullable|date|after_or_equal:plan_integrasi',

            //'drop_data' => 'required|in:Yes,No,Relokasi',
            'bukti_drop' => 'required_if:drop_data,Yes|nullable|mimes:pdf,jpg,jpeg,png|max:5200',
            'relok_regional' => 'required_if:drop_data,Relokasi',
            'relok_witel'    => 'required_if:drop_data,Relokasi',
            'relok_sto'      => 'required_if:drop_data,Relokasi',
            'relok_site'     => 'required_if:drop_data,Relokasi',

            'remark' => 'nullable|string',
            'priority_ta' => 'required',
            'status_osp' => 'required',
            'dependensi' => 'required',
            'scenario_uplink' => 'required',
            'status_uplink' => 'required',
        ]);



        // Simpan bukti drop (jika ada)
        if ($request->hasFile('bukti_drop')) {
            $validated['bukti_drop'] = $request->file('bukti_drop')->store('bukti_drop', 'public');
        }

        $validated['user_id'] = $project->user_id; // relasi ke user login

        // Perbaikan di sini: update bukan create
        $project->update($validated);

        return redirect()->route('project_report')->with('success', 'Project berhasil diperbarui.');
    }

   public function dashboard(Request $request)
    {
        // =======================================================
        // BAGIAN 1: LOGIKA DASHBOARD UTAMA (FILTER & COUNT)
        // =======================================================

        // 1. Inisialisasi baseQuery untuk dashboard utama
        $baseQuery = Project::query();

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
        // BAGIAN 3: MENGIRIMKAN SEMUA VARIABEL KE VIEW DASHBOARD
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
            'totalFunnelingCounts'
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