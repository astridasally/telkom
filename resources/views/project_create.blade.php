<x-app-layout>
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

     
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
            <div class="form-column">

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
                    </select>
            </div>

            <div class="form-group">
                <label>STO</label>
                <input type="text" name="sto" required>
            </div>

            <div class="form-group">
                <label for="assign_to">Assign To</label>
                <select name="assign_to" id="assign_to" class="form-control">
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
            
            <div class="form-group">
                <label>Catuan ID</label>
                <input type="text" name="catuan_id" required />
            </div>
            </div>

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
                <label style="color: #0d6efd; font-weight: 650;"></label>
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;"><label>Plan Lainnya</label>
                        <input type="date" name="plan_survey" placeholder="Plan" class="form-control">
                    </div>
                    <div style="flex: 1;"><label>Realisasi Lainnya</label>
                        <input type="date" name="realisasi_survey" placeholder="Realisasi" class="form-control">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label style="color: #0d6efd; font-weight: 650;"></label>
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;"><label>Plan MOS</label>
                        <input type="date" name="plan_delivery" placeholder="Plan" class="form-control">
                    </div>
                    <div style="flex: 1;"><label>Realisasi MOS</label>
                        <input type="date" name="realisasi_delivery" placeholder="Realisasi" class="form-control">
                    </div>
                </div>  
            </div>

            <div class="form-group">
                <label style="color: #0d6efd; font-weight: 650;"></label>
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;"><label>Plan Instalasi</label>
                        <input type="date" name="plan_instalasi" placeholder="Plan" class="form-control">
                    </div>
                    <div style="flex: 1;"><label>Plan Instalasi</label>
                        <input type="date" name="realisasi_instalasi" placeholder="Realisasi" class="form-control">
                    </div>
                </div>
            </div>            

            <div class="form-group">
                <label style="color: #0d6efd; font-weight: 700;"></label>
                <div style="display: flex; gap: 40px;">
                    <div style="flex: 1;"><label>Plan Integrasi</label>
                        <input type="date" name="plan_integrasi" placeholder="Plan" class="form-control">
                    </div>
                    <div style="flex: 1;"><label>Realiasi Integrasi</label>
                        <input type="date" name="realisasi_integrasi" placeholder="Realisasi" class="form-control">
                    </div>
                </div>
            </div>   

            <div class="form-group">
                <label>Drop</label>
                <select name="drop_data" required>
                    <option value="">-- Pilih --</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                    <option value="Relokasi">Relokasi</option>
                </select>
            </div>

            <div id="relokasi-fields" style="display: none;">

                <div class="form-group">
                    <label>Regional (Relokasi)</label>
                    <select name="relok_regional" id="relok_regional" required>
                        <option value="">-- Pilih Regional --</option>
                        @foreach (App\Enums\Regional::cases() as $regional)
                        <option value="{{ $regional->value }}">{{ $regional->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Witel (Relokasi)</label>
                    <select name="relok_witel" id="relok_witel" required>
                        <option value="">-- Pilih Witel --</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>STO (Relokasi)</label>
                    <input type="text" name="relok_sto" required>
                </div>

                <div class="form-group">
                    <label>Site (Relokasi)</label>
                    <input type="text" name="relok_site" required>
                </div>
                
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
            </div>

            <div id="bukti-drop-group" class="form-group" style="display: none;">
                <label>Bukti Drop</label>
                <input type="file" name="bukti_drop" accept="application/pdf,image/*" required>
                <span class="error text-danger" id="error_bukti_drop"></span>
            </div>
            </div>
        </div>


        <div style="margin-top: 20px; text-align: right">
            <button type="submit" class="update-btn">Simpan</button>
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


    <script>
    document.addEventListener('DOMContentLoaded', function () {
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
                }
                else if (this.value === 'Relokasi') {
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
    document.querySelector('[name="regional"]').addEventListener('change', function() {
        let regional = this.value;
        let witelSelect = document.querySelector('[name="witel"]');
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

    
</div>
</x-app-layout>