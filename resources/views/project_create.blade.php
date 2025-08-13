<x-app-layout>
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" crossorigin="anonymous" />

@if ($errors->any())
<div class="alert alert-danger">
    <strong>Periksa kembali input Anda:</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-wrapper">
    <div class="form-container">
        <h1>Form Update Site</h1>
    </div>
    
    <form method="POST" action="{{ route('project_store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid">
            {{-- Kolom Kiri --}}
            <div class="form-column">

                <div class="form-group">
                    <label>Project Type</label>
                    <select name="project_type" required>
                        <option value="">-- Pilih --</option>
                        <option value="Project TA">Project TA</option>
                        <option value="Project Mitratel">Project Mitratel</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Regional</label>
                    <select name="regional" id="regional" required>
                        <option value="">-- Pilih Regional --</option>
                        @foreach (App\Enums\Regional::cases() as $regional)
                        <option value="{{ $regional->value }}">{{ $regional->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Witel</label>
                    <select name="witel" id="witel" required>
                        <option value="">-- Pilih Witel --</option>
                        @foreach (App\Enums\Witel::cases() as $witel)
                        <option value="{{ $witel->value }}">{{ $witel->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>STO</label>
                    <input type="text" name="sto" required>
                </div>

                <div class="form-group">
                    <label>Assign To</label>
                    <select name="assign_to" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="ZTE">ZTE</option>
                        <option value="Huawei">Huawei</option>
                        <option value="FiberHome">FiberHome</option>
                    </select>
                </div>  

                <div class="form-group">
                    <label>Site</label>
                    <input type="text" name="site" required>
                </div>

                <div class="form-group">
                    <label>IHLD</label>
                    <input type="text" name="ihld" required />
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div class="form-column"> 
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="">-- Pilih --</option>
                        <option value="CSF">CSF</option>
                        <option value="NON CSF">NON CSF</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catuan ID</label>
                    <input type="text" name="catuan_id" required />
                </div>


                {{-- Input tanggal --}}
                @php
                    $dates = [
                        ['plan_survey', 'Plan Lainnya'],
                        ['realisasi_survey', 'Realisasi Lainnya'],
                        ['plan_delivery', 'Plan MOS'],
                        ['realisasi_delivery', 'Realisasi MOS'],
                        ['plan_instalasi', 'Plan Instalasi'],
                        ['realisasi_instalasi', 'Realisasi Instalasi'],
                        ['plan_integrasi', 'Plan Integrasi'],
                        ['realisasi_integrasi', 'Realisasi Integrasi'],
                    ];
                @endphp

                @foreach (array_chunk($dates, 2) as $pair)
                <div class="form-group">
                    <div style="display: flex; gap: 40px;">
                        @foreach ($pair as [$name, $label])
                        <div style="flex: 1;">
                            <label>{{ $label }}</label>
                            <input type="date" name="{{ $name }}" class="form-control">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach

                {{-- Drop --}}
                <div class="form-group">
                    <label>Drop</label>
                    <select name="drop_data" id="drop_data" required>
                        <option value="">-- Pilih --</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                        <option value="Relokasi">Relokasi</option>
                    </select>
                </div>

                {{-- Relokasi --}}
                <div id="relokasi-fields" style="display: none;">
                    <div class="form-group">
                        <label>Regional (Relokasi)</label>
                        <select name="relok_regional" id="relok_regional">
                            <option value="">-- Pilih Regional --</option>
                            @foreach (App\Enums\Regional::cases() as $regional)
                            <option value="{{ $regional->value }}">{{ $regional->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Witel (Relokasi)</label>
                        <select name="relok_witel" id="relok_witel">
                            <option value="">-- Pilih Witel --</option>
                            @foreach (App\Enums\Witel::cases() as $witel)
                            <option value="{{ $witel->value }}">{{ $witel->value }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>STO (Relokasi)</label>
                        <input type="text" name="relok_sto">
                    </div>

                    <div class="form-group">
                        <label>Site (Relokasi)</label>
                        <input type="text" name="relok_site">
                    </div>
                </div>

                {{-- Bukti Drop --}}
                <div id="bukti-drop-group" class="form-group" style="display: none;">
                    <label>Bukti Drop</label>
                    <input type="file" name="bukti_drop" accept="application/pdf,image/*">
                </div>
            </div>
        </div>

        <div style="margin-top: 20px; text-align: right">
            <button type="submit" class="update-btn">Simpan</button>
        </div>
    </form>
</div>

{{-- Script Drop & Relokasi --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropSelect = document.getElementById('drop_data');
    const buktiDropGroup = document.getElementById('bukti-drop-group');
    const relokasiFields = document.getElementById('relokasi-fields');

    function updateFields() {
        if (dropSelect.value === 'Yes') {
            buktiDropGroup.style.display = 'block';
            buktiDropGroup.querySelector('input').setAttribute('required', true);
            relokasiFields.style.display = 'none';
            relokasiFields.querySelectorAll('input, select').forEach(el => el.removeAttribute('required'));
        }
        else if (dropSelect.value === 'Relokasi') {
            relokasiFields.style.display = 'block';
            relokasiFields.querySelectorAll('input, select').forEach(el => el.setAttribute('required', true));
            buktiDropGroup.style.display = 'none';
            buktiDropGroup.querySelector('input').removeAttribute('required');
        }
        else {
            buktiDropGroup.style.display = 'none';
            buktiDropGroup.querySelector('input').removeAttribute('required');
            relokasiFields.style.display = 'none';
            relokasiFields.querySelectorAll('input, select').forEach(el => el.removeAttribute('required'));
        }
    }

    dropSelect.addEventListener('change', updateFields);
    updateFields();
});
</script>

{{-- Script Witel --}}
<script>
document.getElementById('regional').addEventListener('change', function() {
    let regional = this.value;
    let witelSelect = document.getElementById('witel');

    // Awal: isi default dari enum
    witelSelect.innerHTML = `<option value="">-- Pilih Witel --</option>
        @foreach (App\Enums\Witel::cases() as $witel)
        <option value="{{ $witel->value }}">{{ $witel->value }}</option>
        @endforeach`;

    if (!regional) return;

    fetch(`/witels/${encodeURIComponent(regional)}`)
        .then(res => {
            if (!res.ok) throw new Error('Fetch error');
            return res.json();
        })
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                witelSelect.innerHTML = '<option value="">-- Pilih Witel --</option>';
                data.forEach(witel => {
                    let opt = document.createElement('option');
                    opt.value = witel;
                    opt.text = witel;
                    witelSelect.appendChild(opt);
                });
            }
        })
        .catch(() => {
            // Biarkan fallback enum tetap
        });
});
</script>

<script>
document.getElementById('relok_regional').addEventListener('change', function() {
    let regional = this.value;
    let witelSelect = document.getElementById('relok_witel');

    // Awal: isi default dari enum
    witelSelect.innerHTML = `<option value="">-- Pilih Witel --</option>
        @foreach (App\Enums\Witel::cases() as $witel)
        <option value="{{ $witel->value }}">{{ $witel->value }}</option>
        @endforeach`;

    if (!regional) return;

    fetch(`/witels/${encodeURIComponent(regional)}`)
        .then(res => {
            if (!res.ok) throw new Error('Fetch error');
            return res.json();
        })
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                witelSelect.innerHTML = '<option value="">-- Pilih Witel --</option>';
                data.forEach(witel => {
                    let opt = document.createElement('option');
                    opt.value = witel;
                    opt.text = witel;
                    witelSelect.appendChild(opt);
                });
            }
        })
        .catch(() => {
            // Biarkan fallback enum tetap
        });
});
</script>
</x-app-layout>
