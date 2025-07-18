<x-app-layout>

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

    <form method="POST" action="{{ route('project_store') }}" enctype="multipart/form-data">
        @csrf

        <div>
            <div class="form-group">
                <label>Regional</label>
                <select name="regional" required>
                    <option value="">-- Pilih Regional --</option>
                    @foreach (App\Enums\Regional::cases() as $regional)
                    <option value="{{ $regional->value }}">{{ $regional->value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Witel</label>
                <select name="witel" required>
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


            @if(auth()->user()->role === 'mitra')
            <div class="form-group">
                <label>Priority</label>
                <select name="priority">
                    <option value="">-- Pilih --</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Plan Survey</label>
                <input type="date" name="plan_survey" />

            </div>
            <div class="form-group">
                <label>Realisasi Survey</label>
                <input type="date" name="realisasi_survey" />
            </div>

            <div class="form-group">
                <label>Plan Delivery</label>
                <input type="date" name="plan_delivery" />
            </div>
            <div class="form-group">
                <label>Realisasi Delivery</label>
                <input type="date" name="realisasi_delivery" />
            </div>

            <div class="form-group">
                <label>Plan Instalasi</label>
                <input type="date" name="plan_instalasi" />
            </div>

            <div class="form-group">
                <label>Realisasi Instalasi</label>
                <input type="date" name="realisasi_instalasi" />
            </div>

            <div class="form-group">
                <label>Plan Integrasi</label>
                <input type="date" name="plan_integrasi" />
            </div>


            <div class="form-group">
                <label>Realisasi Integrasi</label>
                <input type="date" name="realisasi_integrasi" />
            </div>

            

            <div class="form-group">
                <label>Drop</label>
                <select name="drop_data">
                    <option value="">-- Pilih --</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                    <option value="Relokasi">Relokasi</option>
                </select>
            </div>
            <div id="relokasi-fields" style="display: none;">

                <div class="form-group">
                    <label>Regional (Relokasi)</label>
                    <select name="relok_regional">
                        <option value="">-- Pilih Regional --</option>
                        @foreach (App\Enums\Regional::cases() as $regional)
                        <option value="{{ $regional->value }}">{{ $regional->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Witel (Relokasi)</label>
                    <select name="relok_witel">
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

            <div id="bukti-drop-group" class="form-group" style="display: none;">
                <label>Bukti Drop</label>
                <input type="file" name="bukti_drop" accept="application/pdf,image/*">
                <span class="error text-danger" id="error_bukti_drop"></span>
            </div>
            
            <div class="form-group">
                <label>Remark</label>
                <textarea name="remark" rows="3"></textarea>
            </div>
            @endif

        </div>

            @if (Auth::user()->role === 'vendor')
            <div class="form-group">
                <label>Priority TA</label>
                <select name="priority_ta">
                    <option value="">-- Pilih --</option>
                    <option value="P1">P1</option>
                    <option value="P2">P2</option>
                    <option value="P3">P3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Dependensi</label>
                <select name="dependensi">
                    <option value="">-- Pilih --</option>
                    <option value="Main">Main</option>
                    <option value="Dependence">Dependence</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status OSP</label>
                <select name="status_osp">
                    <option value="">-- Pilih --</option>
                    <option value="Finish_Instalasi">Finish Instalasi</option>
                    <option value="Proses_Instalasi">Proses Instalasi</option>
                    <option value="Persiapan">Persiapan</option>
                    <option value="Drop">Drop</option>
                </select>
            </div>

            <div class="form-group">
                <label>Skenario Uplink</label>
                <select name="skenario_uplink">
                    <option value="">-- Pilih --</option>
                    <option value="L2S">L2S</option>
                    <option value="OTN">OTN</option>
                    <option value="ONT">ONT</option>
                    <option value="Direct Core">Direct Core</option>
                    <option value="Re_engineering">Re-engineering</option>
                    <option value="SFP Bidi">Re-SFP Bidi</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status Uplink</label>
                <select name="status_uplink">
                    <option value="">-- Pilih --</option>
                    <option value="Not Ready">Not Ready</option>
                    <option value="Ready">Ready</option>
                </select>
            </div>
            @endif

        <div style="margin-top: 20px;">
            <button type="submit" class="update-btn">Simpan</button>
        </div>
    </form>

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

</x-app-layout>