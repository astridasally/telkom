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

            'drop_data' => 'required|in:Yes,No,Relokasi',
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

            'drop_data' => 'required|in:Yes,No,Relokasi',
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

            'drop_data' => 'required|in:Yes,No,Relokasi',
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

    public function dashboard()
    {
        if (auth()->user()->role === 'admin') {
            $projects = Project::all(); // Admin lihat semua
        } else {
            $projects = Project::where('user_id', auth()->id())->get(); // Mitra hanya miliknya
        }

        $projectCount = $projects->count();

        return view('project_dashboard', compact('projects', 'projectCount'));
    }
}
