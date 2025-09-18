<x-app-layout>
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


<div class="form-wrapper">
    <div class="form-container">
        <h1>Update Project</h1>
    </div>
    
    <form method="POST"
        action="{{ auth()->user()->role == 'admin' || auth()->user()->role === 'mitra' || auth()->user()->role === 'vendor' ? route('project_store_admin', $project->id) : 
        route('project_store', $project->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        
    @if (Auth::user()->role === 'admin' || auth()->user()->role === 'mitra')
    <div class="form-grid">
        <div class="form-column">

            @if (Auth::user()->role === 'mitra')
            <div class="form-group">
                <label>Regional</label>
                {{-- Menggunakan input type="text" dengan atribut readonly --}}
                <input type="text" 
                       name="regional_display" 
                       value="{{ $project->regional ?? '' }}" 
                       readonly 
                       class="form-control-readonly"> 
                
                {{-- Jika Anda masih perlu mengirimkan nilai ini ke backend,
                     gunakan input hidden dengan nama yang sama dengan yang diharapkan backend
                     (misalnya 'regional') --}}
                <input type="hidden" name="regional" value="{{ $project->regional ?? '' }}">
            </div>

           {{-- Witel --}}
            <div class="form-group">
                <label>Witel</label>
                {{-- Untuk select, gunakan disabled dan pastikan option yang benar terpilih --}}
                <select name="witel" disabled class="form-control-readonly">
                    @foreach (App\Enums\Witel::cases() as $witel)
                    <option value="{{ $witel->value }}" {{ $project->witel == $witel->value ? 'selected' : '' }}>{{ $witel->value }}</option>
                    @endforeach
                </select>
                {{-- Penting: Gunakan input hidden untuk mengirimkan nilai Witel ke backend --}}
                <input type="hidden" name="witel" value="{{ $project->witel ?? '' }}">
            </div>

            {{-- STO --}}
            <div class="form-group">
                <label>STO</label>
                <input type="text" name="sto" value="{{ $project->sto }}" readonly class="form-control-readonly">
            </div>

            {{-- Site --}}
            <div class="form-group">
                <label>Site</label>
                <input type="text" name="site" value="{{ $project->site }}" readonly class="form-control-readonly">
            </div>

            {{-- IHLD --}}
            <div class="form-group">
                <label>IHLD</label>
                <input type="text" name="ihld" value="{{ $project->ihld }}" readonly class="form-control-readonly" />
            </div>

            {{-- Catuan ID --}}
            <div class="form-group">
                <label>Catuan ID</label>
                <input type="text" name="catuan_id" value="{{ $project->catuan_id }}" readonly class="form-control-readonly">
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">-- Pilih --</option>
                    <option value="CSF" {{ $project->category == 'CSF' ? 'selected' : '' }}>CSF</option>
                    <option value="NON CSF" {{ $project->category == 'NON CSF' ? 'selected' : '' }}>NON CSF</option>
                </select>
            </div>

            <div class="form-group">
                <label>Project Type</label>
                <select name="project_type" required>
                    <option value="">-- Pilih --</option>
                    <option value="Project TA" {{ $project->project_type == 'Project TA' ? 'selected' : '' }}>Project TA</option>
                    <option value="Project Mitratel" {{ $project->project_type == 'Project Mitratel' ? 'selected' : '' }}>Project Mitratel</option>
                </select>
            </div>
            @endif

            @if (Auth::user()->role === 'admin')
            <div class="form-group">
                <label>Regional</label>
                <select name="regional" required>
                    <option value="">-- Pilih Regional --</option>
                    @foreach (App\Enums\Regional::cases() as $regional)
                    <option value="{{ $regional->value }}" {{ $project->regional == $regional->value ? 'selected' : '' }}>{{ $regional->value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Witel</label>
                <select name="witel" id="witel" required>
                    <option value="">-- Pilih Witel --</option>
                    @foreach (App\Enums\Witel::cases() as $witel)
                    <option value="{{ $witel->value }}" {{ $project->witel == $witel->value ? 'selected' : '' }}>{{ $witel->value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>STO</label>
                <input type="text" name="sto" required value="{{ $project->sto }}">
            </div>

            <div class="form-group">
                <label>Site</label>
                <input type="text" name="site" required value="{{ $project->site }}">
            </div>

            <div class="form-group">
                <label>IHLD</label>
                <input type="text" name="ihld" value="{{ $project->ihld }}" />
            </div>
            
            <div class="form-group">
                <label>Catuan ID</label>
                <input type="text" name="catuan_id" value="{{ $project->catuan_id }}">
                </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">-- Pilih --</option>
                    <option value="CSF" {{ $project->category == 'CSF' ? 'selected' : '' }}>CSF</option>
                    <option value="NON CSF" {{ $project->category == 'NON CSF' ? 'selected' : '' }}>NON CSF</option>
                </select>
            </div>

            <div class="form-group">
                <label>Project Type</label>
                <select name="project_type" required>
                    <option value="">-- Pilih --</option>
                    <option value="Project TA" {{ $project->project_type == 'Project TA' ? 'selected' : '' }}>Project TA</option>
                    <option value="Project Mitratel" {{ $project->project_type == 'Project Mitratel' ? 'selected' : '' }}>Project Mitratel</option>
                </select>
            </div>
            @endif

            </div>

            <div class="form-column">
            
            @if (Auth::user()->role === 'mitra')
            <div class="form-group">
                <label>Assign To</label>
                <select name="assign_to" class="form-control">
                    <option value="">-- Pilih --</option>
                    <option value="ZTE">ZTE</option>
                    <option value="Huawei">Huawei</option>
                    <option value="FiberHome">FiberHome</option>
                </select>
            </div> 
            @endif

            <div class="form-group">
                <label style="color: #0d6efd; font-weight: 650;"></label>
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;"><label>Plan Lainnya</label>
                        <input type="date" name="plan_survey" placeholder="Plan" class="form-control" value="{{ $project->plan_survey }}">
                    </div>
                    <div style="flex: 1;"><label>Realisasi Lainnya</label>
                        <input type="date" name="realisasi_survey" placeholder="Realisasi" class="form-control" value="{{ $project->realisasi_survey }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label style="color: #0d6efd; font-weight: 650;"></label>
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;"><label>Plan MOS</label>
                        <input type="date" name="plan_delivery" placeholder="Plan" class="form-control" value="{{ $project->plan_delivery }}">
                    </div>
                    <div style="flex: 1;"><label>Realisasi MOS</label>
                        <input type="date" name="realisasi_delivery" placeholder="Realisasi" class="form-control" value="{{ $project->realisasi_delivery }}">
                    </div>
                </div>    
            </div>

            <div class="form-group">
                <label style="color: #0d6efd; font-weight: 650;"></label>
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;"><label>Plan Instalasi</label>
                        <input type="date" name="plan_instalasi" placeholder="Plan" class="form-control" value="{{ $project->plan_instalasi }}">
                    </div>
                    <div style="flex: 1;"><label>Realisasi Instalasi</label>
                        <input type="date" name="realisasi_instalasi" placeholder="Realisasi" class="form-control" value="{{ $project->realisasi_instalasi }}">
                    </div>
                </div> 
            </div>

            <div class="form-group">
                <label style="color: #0d6efd; font-weight: 650;"></label>
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;"><label>Plan Integrasi</label>
                        <input type="date" name="plan_integrasi" placeholder="Plan" class="form-control" value="{{ $project->plan_integrasi }}">
                    </div>
                    <div style="flex: 1;"><label>Realisasi Integrasi</label>
                        <input type="date" name="realisasi_integrasi" placeholder="Realisasi" class="form-control" value="{{ $project->realisasi_integrasi }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Remark</label>
                <textarea name="remark" rows="3">{{ $project->remark }}</textarea>
            </div>

            <div class="form-group">
                <label>Drop</label>
                <select name="drop_data" required>
                    <option value="">-- Pilih --</option>
                    <option value="Yes" {{ $project->drop_data == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $project->drop_data == 'No' ? 'selected' : '' }}>No</option>
                    <option value="Relokasi" {{ $project->drop_data == 'Relokasi' ? 'selected' : '' }}>Relokasi</option>
                </select>
            </div>

            <div id="relokasi-fields" style="display: none;">

                <div class="form-group">
                    <label style="color: #0d6efd; font-weight: 650;"></label>
                    <div style="display: flex; gap: 40px;">
                        <div style="flex: 1;"><label>Regional (Relokasi)</label>
                            <select name="relok_regional" id="relok_regional" class="form-control">
                                <option value="">-- Pilih Regional --</option>
                                @foreach (App\Enums\Regional::cases() as $regional)
                                <option value="{{ $regional->value }}" {{ $project->relok_regional == $regional->value ? 'selected' : '' }}>{{ $regional->value }}</option>
                                @endforeach
                            </select>                        
                        </div>
                        <div style="flex: 1;"><label>Witel (Relokasi)</label>
                            <select name="relok_witel" id="relok_witel">
                                <option value="">-- Pilih Witel --</option>
                                @foreach (App\Enums\Witel::cases() as $witel)
                                <option value="{{ $witel->value }}" {{ $project->relok_witel == $witel->value ? 'selected' : '' }}> {{ $witel->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label style="color: #0d6efd; font-weight: 650;"></label>
                    <div style="display: flex; gap: 40px;">
                        <div style="flex: 1;"><label>STO (Relokasi)</label>
                            <input type="text" name="relok_sto" class="form-control" value="{{ $project->relok_sto }}">
                        </div>
                        <div style="flex: 1;"><label>Site (Relokasi)</label>
                            <input type="text" name="relok_site" class="form-control" value="{{ $project->relok_site }}">
                        </div>
                    </div>
                </div>

            </div>

            <div id="bukti-drop-group" class="form-group" style="display: none;">
                <label>Bukti Drop</label>
                    <input type="file" name="bukti_drop" accept="application/pdf,image/*">
                        @if ($project->bukti_drop)
                            <p>File saat ini: <a href="{{ asset('storage/'.$project->bukti_drop) }}" target="_blank">Lihat File</a></p>
                        @endif
            </div>
            </div>

            </div>
            @endif


            @if (Auth::user()->role === 'admin')
            <div class="form-group">
                <label>Priority TA</label>
                <select name="priority_ta">
                    <option value="">-- Pilih --</option>
                    <option value="Go Live" {{ $project->priority_ta == 'Go Live' ? 'selected' : '' }}>Go Live</option>
                    <option value="P1" {{ $project->priority_ta == 'P1' ? 'selected' : '' }}>P1</option>
                    <option value="P2" {{ $project->priority_ta == 'P2' ? 'selected' : '' }}>P2</option>
                    <option value="P3" {{ $project->priority_ta == 'P3' ? 'selected' : '' }}>P3</option>
                </select>
            </div>

            <div class="form-column">
            <div style="display: flex; gap: 15px; width: 100%;">
                <div class="form-group" style="flex: 1;">
                    <label for="dependensi">Dependensi</label>
                    <select name="dependensi" id="dependensi" class="form-control">
                        <option value="">-- Pilih --</option>
                        {{-- Logika untuk mempertahankan pilihan Dependensi --}}
                        <option value="Main" {{ $project->dependensi == 'Main' ? 'selected' : '' }}>Main</option>
                        <option value="Dependence" {{ $project->dependensi == 'Dependence' ? 'selected' : '' }}>Dependence</option>
                    </select>
                </div>

                    <div class="form-group" style="flex: 1;">
                        <label for="assign_to">Assign To</label>
                        <select name="assign_to" id="assign_to" class="form-control">
                            <option value="">-- Pilih --</option>

                            {{-- Logika untuk mempertahankan pilihan Assign To --}}
                            <option value="ZTE" {{ $project->assign_to == 'ZTE' ? 'selected' : '' }}>ZTE</option>
                            <option value="Huawei" {{ $project->assign_to == 'Huawei' ? 'selected' : '' }}>Huawei</option>
                            <option value="FiberHome" {{ $project->assign_to == 'FiberHome' ? 'selected' : '' }}>FiberHome</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
            <div style="display: flex; gap: 15px; width: 100%;">
                <div class="form-group" style="flex: 1;"><label>Jumlah Port</label>
                        <input type="number" name="jumlah_port" class="form-control" value="{{ $project->jumlah_port }}">
                    </div>
                    <div class="form-group" style="flex: 1;"><label>Status Go Live</label>
                        <select name="golive_status">
                            <option value="">-- Pilih --</option>
                            <option value="Ready" {{ $project->golive_status == 'Ready' ? 'selected' : '' }}>Ready</option>
                            <option value="Not Yet" {{ $project->golive_status == 'Not Yet' ? 'selected' : '' }}>Not Yet</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Status OSP</label>
                <select name="status_osp">
                    <option value="">-- Pilih --</option>
                    <option value="Drop" {{ $project->status_osp == 'Drop' ? 'selected' : '' }}>Drop</option>
                    <option value="Persiapan" {{ $project->status_osp == 'Persiapan' ? 'selected' : '' }}>Persiapan</option>
                    <option value="Matdel" {{ $project->status_osp == 'Matdel' ? 'selected' : '' }}>Matdel</option>
                    <option value="OGP Instalasi" {{ $project->status_osp == 'OGP Instalasi' ? 'selected' : '' }}>OGP Instalasi</option>
                    <option value="Finish Instalasi" {{ $project->status_osp == 'Finish Instalasi' ? 'selected' : '' }}>Finish Instalasi</option>
                    <option value="Go Live" {{ $project->status_osp == 'Go_live' ? 'selected' : '' }}>Go Live</option>
                </select>
            </div>

            <div class="form-group">
                <label>Skenario Uplink </label>
                <select name="scenario_uplink">
                    <option value="">-- Pilih --</option>
                    <option value="L2S" {{ $project->scenario_uplink == 'L2S' ? 'selected' : '' }}>L2S</option>
                    <option value="OTN" {{ $project->scenario_uplink == 'OTN' ? 'selected' : '' }}>OTN</option>
                    <option value="ONT" {{ $project->scenario_uplink == 'ONT' ? 'selected' : '' }}>ONT</option>
                    <option value="Direct Core" {{ $project->scenario_uplink == 'Direct Core' ? 'selected' : '' }}>Direct Core</option>
                    <option value="Re-engineering" {{ $project->scenario_uplink == 'Re-engineering' ? 'selected' : '' }}>Re-engineering</option>
                    <option value="SFP Bidi" {{ $project->scenario_uplink == 'SFP Bidi' ? 'selected' : '' }}>SFP Bidi</option>
                    <option value="Cascading" {{ $project->scenario_uplink == 'Cascading' ? 'selected' : '' }}>Cascading</option>
                    <option value="Lainnya" {{ $project->scenario_uplink == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status Uplink </label>
                <select name="status_uplink">
                    <option value="">-- Pilih --</option>
                    <option value="Not Ready" {{ $project->status_uplink == 'Not Ready' ? 'selected' : '' }}>Not Ready</option>
                    <option value="Ready" {{ $project->status_uplink == 'Ready' ? 'selected' : '' }}>Ready</option>
                </select>
            </div>

            <div class="form-group">
                <label>Remark TA</label>
                <textarea name="remark_ta" rows="3">{{ $project->remark_ta }}</textarea>
            </div>
            @endif

            
            @if (Auth::user()->role === 'vendor')
            <div class="form-grid">
            <div class="form-column">

            <div class="form-group">
                <label>Regional</label>
                <select name="regional" required>
                    <option value="">-- Pilih Regional --</option>
                    @foreach (App\Enums\Regional::cases() as $regional)
                    <option value="{{ $regional->value }}" {{ $project->regional == $regional->value ? 'selected' : '' }}>{{ $regional->value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Witel</label>
                <select name="witel" id="witel" required>
                    <option value="">-- Pilih Witel --</option>
                    @foreach (App\Enums\Witel::cases() as $witel)
                    <option value="{{ $witel->value }}" {{ $project->witel == $witel->value ? 'selected' : '' }}>{{ $witel->value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>STO</label>
                <input type="text" name="sto" required value="{{ $project->sto }}">
            </div>

            <div class="form-group">
                <label>Site</label>
                <input type="text" name="site" required value="{{ $project->site }}">
            </div>

            <div class="form-group">
                <label>IHLD</label>
                <input type="text" name="ihld"  value="{{ $project->ihld }}" />
            </div>
            
            <div class="form-group">
                <label>Catuan ID</label>
                <input type="text" name="catuan_id" value="{{ $project->catuan_id }}" >
            </div>

            <div class="form-group">
                <label>Priority TA</label>
                <select name="priority_ta">
                    <option value="">-- Pilih --</option>
                    <option value="P1" {{ $project->priority_ta == 'P1' ? 'selected' : '' }}>P1</option>
                    <option value="P2" {{ $project->priority_ta == 'P2' ? 'selected' : '' }}>P2</option>
                    <option value="P3" {{ $project->priority_ta == 'P#' ? 'selected' : '' }}>P3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="">-- Pilih --</option>
                    <option value="CSF" {{ $project->category == 'CSF' ? 'selected' : '' }}>CSF</option>
                    <option value="NON CSF" {{ $project->category == 'NON CSF' ? 'selected' : '' }}>NON CSF</option>
                </select>
            </div>
            </div>

            <div class="form-column">

            <div class="form-column">
            <div class="form-group">
                <label>Project Type</label>
                <select name="project_type" required>
                    <option value="">-- Pilih --</option>
                    <option value="Project TA" {{ $project->project_type == 'Project TA' ? 'selected' : '' }}>Project TA</option>
                    <option value="Project Mitratel" {{ $project->project_type == 'Project Mitratel' ? 'selected' : '' }}>Project Mitratel</option>
                </select>
            </div>

            <div style="display: flex; gap: 15px; width: 100%;">
                <div class="form-group" style="flex: 1;">
                    <label for="dependensi">Dependensi</label>
                    <select name="dependensi" id="dependensi" class="form-control">
                        <option value="">-- Pilih --</option>
                        {{-- Logika untuk mempertahankan pilihan Dependensi --}}
                        <option value="Main" {{ $project->dependensi == 'Main' ? 'selected' : '' }}>Main</option>
                        <option value="Dependence" {{ $project->dependensi == 'Dependence' ? 'selected' : '' }}>Dependence</option>
                    </select>
                </div>
                </div>
            </div>

            <div class="form-group">
            <div style="display: flex; gap: 15px; width: 100%;">
                <div class="form-group" style="flex: 1;"><label>Jumlah Port</label>
                        <input type="number" name="jumlah_port" class="form-control" value="{{ $project->jumlah_port }}">
                    </div>
                    <div class="form-group" style="flex: 1;"><label>Status Go Live</label>
                        <select name="golive_status">
                            <option value="">-- Pilih --</option>
                            <option value="Ready" {{ $project->golive_status == 'Ready' ? 'selected' : '' }}>Ready</option>
                            <option value="Not Yet" {{ $project->golive_status == 'Not Yet' ? 'selected' : '' }}>Not Yet</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Status OSP</label>
                <select name="status_osp">
                    <option value="">-- Pilih --</option>
                    <option value="Drop" {{ $project->status_osp == 'Drop' ? 'selected' : '' }}>Drop</option>
                    <option value="Persiapan" {{ $project->status_osp == 'Persiapan' ? 'selected' : '' }}>Persiapan</option>
                    <option value="Matdel" {{ $project->status_osp == 'Matdel' ? 'selected' : '' }}>Matdel</option>
                    <option value="OGP Instalasi" {{ $project->status_osp == 'OGP Instalasi' ? 'selected' : '' }}>OGP Instalasi</option>
                    <option value="Finish Instalasi" {{ $project->status_osp == 'Finish Instalasi' ? 'selected' : '' }}>Finish Instalasi</option>
                    <option value="Go Live" {{ $project->status_osp == 'Go_live' ? 'selected' : '' }}>Go Live</option>
                </select>
            </div>

            <div class="form-group">
                <label>Skenario Uplink </label>
                <select name="scenario_uplink">
                    <option value="">-- Pilih --</option>
                    <option value="L2S" {{ $project->scenario_uplink == 'L2S' ? 'selected' : '' }}>L2S</option>
                    <option value="OTN" {{ $project->scenario_uplink == 'OTN' ? 'selected' : '' }}>OTN</option>
                    <option value="ONT" {{ $project->scenario_uplink == 'ONT' ? 'selected' : '' }}>ONT</option>
                    <option value="Direct Core" {{ $project->scenario_uplink == 'Direct Core' ? 'selected' : '' }}>Direct Core</option>
                    <option value="Re-engineering" {{ $project->scenario_uplink == 'Re-engineering' ? 'selected' : '' }}>Re-engineering</option>
                    <option value="SFP Bidi" {{ $project->scenario_uplink == 'SFP Bidi' ? 'selected' : '' }}>SFP Bidi</option>
                    <option value="Cascading" {{ $project->scenario_uplink == 'Cascading' ? 'selected' : '' }}>Cascading</option>
                    <option value="Lainnya" {{ $project->scenario_uplink == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status Uplink </label>
                <select name="status_uplink">
                    <option value="">-- Pilih --</option>
                    <option value="Not Ready" {{ $project->status_uplink == 'Not Ready' ? 'selected' : '' }}>Not Ready</option>
                    <option value="Ready" {{ $project->status_uplink == 'Ready' ? 'selected' : '' }}>Ready</option>
                </select>
            </div>

            <div class="form-group">
                <label>Remark TA</label>
                <textarea name="remark_ta" rows="3">{{ $project->remark_ta }}</textarea>
            </div>
            
            </div>
            </div>
            @endif

            <div style="margin-top: 20px; text-align: right">
                <button type="submit" class="update-btn">Simpan</button>
            </div>
        </div>

        </div>

    @if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 20px;">
        <strong>Periksa kembali input Anda:</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Notifikasi Success --}}
    @if(session('success'))
    <div class="notification-popup success">
        {{ session('success') }}
        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
    @endif

    {{-- Notifikasi Error --}}
    @if(session('error'))
    <div class="notification-popup error">
        {{ session('error') }}
        <button class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
    @endif

    <div class="form-container">
        {{-- ... (kode form Anda) ... --}}
    </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropSelect = document.querySelector('[name="drop_data"]');
            const buktiDropGroup = document.getElementById('bukti-drop-group');

            function toggleBuktiDrop() {
                if (dropSelect.value === 'Yes') {
                    buktiDropGroup.style.display = 'block';
                } else {
                    buktiDropGroup.style.display = 'none';
                    buktiDropGroup.querySelector('input').value = ''; // kosongkan isinya
                }
            }

            // Jalankan saat load dan saat ganti pilihan
            dropSelect.addEventListener('change', toggleBuktiDrop);
            toggleBuktiDrop(); // untuk pertama kali saat form load
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropSelect = document.querySelector('[name="drop_data"]');
            const relokasiFields = document.getElementById('relokasi-fields');

            dropSelect.addEventListener('change', function() {
                if (dropSelect.value === 'Yes') {
                    buktiDropGroup.style.display = 'block';
                } else if (this.value === 'Relokasi') {
                    relokasiFields.style.display = 'block';
                } else {
                    relokasiFields.style.display = 'none';

                    // Kosongkan field relokasi jika bukan relokasi
                    relokasiFields.querySelectorAll('input, select').forEach(el => el.value = '');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fields = [
                'plan_survey',
                'realisasi_survey',
                'plan_delivery',
                'realisasi_delivery',
                'plan_instalasi',
                'realisasi_instalasi',
                'plan_integrasi',
                'realisasi_integrasi',
            ];

            function getDate(name) {
                const val = document.querySelector(`[name="${name}"]`).value;
                return val ? new Date(val) : null;
            }

            function showError(field, message) {
                const el = document.getElementById(`error_${field}`);
                if (el) el.textContent = message;
                else alert(message);
            }

            function clearError(field) {
                const el = document.getElementById(`error_${field}`);
                if (el) el.textContent = '';
            }

            fields.forEach((field, index) => {
                const input = document.querySelector(`[name="${field}"]`);
                if (!input) return;

                input.addEventListener('change', () => {
                    clearError(field);
                    const currentDate = getDate(field);

                    for (let i = 0; i < index; i++) {
                        const prevField = fields[i];
                        const prevDate = getDate(prevField);

                        if (prevDate && currentDate && currentDate < prevDate) {
                            showError(field, `${formatLabel(field)} harus setelah ${formatLabel(prevField)}`);
                            input.value = ''; // Kosongkan jika salah
                            return;
                        }
                    }
                });
            });

            function formatLabel(field) {
                return field.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            }
        });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const regionalSelect = document.querySelector('[name="regional"]');
        const witelSelect = document.querySelector('[name="witel"]');
        const savedValue = "{{ $project->witel }}"; // Witel dari DB

        function fetchWitel() {
            const regional = regionalSelect.value;
            witelSelect.innerHTML = '<option value="">-- Pilih Witel --</option>';

            if (regional) {
                fetch(`/witels/${encodeURIComponent(regional)}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(witel => {
                            let option = document.createElement('option');
                            option.value = witel;
                            option.text = witel;
                            if (witel === savedValue) {
                                option.selected = true;
                            }
                            witelSelect.appendChild(option);
                        });
                    });
            }
        }

        // Jalankan saat halaman pertama kali load
        fetchWitel();

        // Jalankan setiap kali ganti regional
        regionalSelect.addEventListener('change', fetchWitel);
    });
    </script>


     <script>
    document.querySelector('[name="relok_regional"]').addEventListener('change', function() {
        let regional = this.value;
        let witelSelect = document.querySelector('[name="relok_witel"]');
        witelSelect.innerHTML = '<option value="">-- Pilih Witel --</option>';

    if (regional) {
        fetch(`/witels/${encodeURIComponent(regional)}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(witel => {
                    let option = document.createElement('option');
                    option.value = witel;
                    option.text = witel;
                    witelSelect.appendChild(option);
                });
            });
        }
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropSelect = document.querySelector('[name="drop_data"]');
        const relokasiFields = document.getElementById('relokasi-fields');

        function loadRelokasiWitel() {
            let regional = document.getElementById('relok_regional').value;
            let witelSelect = document.getElementById('relok_witel');
            witelSelect.innerHTML = '<option value="">-- Pilih Witel --</option>';

            if (regional) {
                fetch(`/witels/${encodeURIComponent(regional)}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(witel => {
                            let opt = document.createElement('option');
                            opt.value = witel;
                            opt.text = witel;
                            if (witel === "{{ $project->relok_witel }}") {
                                opt.selected = true;
                            }
                            witelSelect.appendChild(opt);
                        });
                    });
            }
        }

        // Saat ganti relok_regional
        document.getElementById('relok_regional').addEventListener('change', loadRelokasiWitel);

        // Saat halaman load & drop_data = Relokasi
        if (dropSelect.value === 'Relokasi') {
            relokasiFields.style.display = 'block';
            loadRelokasiWitel();
        }
    });
    </script>
    
    
</div>
</x-app-layout>