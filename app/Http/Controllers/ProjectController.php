<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

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
        // 1. Inisialisasi baseQuery
        $baseQuery = Project::query();

        // 2. Dapatkan nilai filter dari Request
        // Default ke null jika tidak ada di URL
        $selectedMitra = $request->input('mitra');
        $selectedRegional = $request->input('regional');
        $selectedWitel = $request->input('witel');

        // 3. Terapkan filter berdasarkan input dari Request ke baseQuery
        // Filter Mitra
        if ($selectedMitra && $selectedMitra !== 'All Mitra') {
            $baseQuery->where('mitra', $selectedMitra);
        }

        // Filter Regional
        if ($selectedRegional && $selectedRegional !== 'All Regional') {
            $baseQuery->where('regional', $selectedRegional);
        }

        // Filter Witel
        if ($selectedWitel && $selectedWitel !== 'All Witel') {
            $baseQuery->where('witel', $selectedWitel);
        }

        // 4. Ambil data proyek setelah semua filter diterapkan
        // Penting: Lakukan ->get() hanya sekali setelah semua where diterapkan
        $projects = $baseQuery->get();

        // 5. Hitung semua metrik dari koleksi $projects yang sudah difilter
        $projectCount = $projects->count();

        // SURVEY
        $planSurveyCount = $projects->whereNotNull('plan_survey')->count();
        $realSurveyCount = $projects->whereNotNull('realisasi_survey')->count();

        // DELIVERY
        $planDeliveryCount = $projects->whereNotNull('plan_delivery')->count();
        $realDeliveryCount = $projects->whereNotNull('realisasi_delivery')->count();

        // INSTALASI
        $planInstalasiCount = $projects->whereNotNull('plan_instalasi')->count();
        $realInstalasiCount = $projects->whereNotNull('realisasi_instalasi')->count();

        // INTEGRASI
        $planIntegrasiCount = $projects->whereNotNull('plan_integrasi')->count();
        $realIntegrasiCount = $projects->whereNotNull('realisasi_integrasi')->count();

        // DROP
        // Perhitungan drop juga akan dilakukan pada koleksi $projects yang sudah difilter
        $dropYesCount = $projects->where('drop_data', 'yes')->count();
        $dropNoCount = $projects->where('drop_data', 'no')->count();
        $dropRelokasiCount = $projects->where('drop_data', 'relokasi')->count();


        // Mengirimkan semua variabel yang sudah dihitung ke view
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
            'selectedMitra',    // Kirim nilai filter yang dipilih ke view
            'selectedRegional', // Kirim nilai filter yang dipilih ke view
            'selectedWitel'     // Kirim nilai filter yang dipilih ke view
        ));
    }
}