<x-app-layout>

    <h1>UPDATE</h1>
    <br>
    <form method="POST"
        action="{{ auth()->user()->role == 'admin' ? route('project_store_admin', $project->id) : 
        route('project_store', $project->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')


        <div>
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
                <select name="witel" required>
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
                <label>Priority</label>
                <select name="priority">
                    <option value="">-- Pilih --</option>
                    <option value="P1" {{ $project->priority == 'P1' ? 'selected' : '' }}>P1</option>
                    <option value="P2" {{ $project->priority == 'P2' ? 'selected' : '' }}>P2</option>
                    <option value="P3" {{ $project->priority == 'P3' ? 'selected' : '' }}>P3</option>
                </select>
            </div>

            <div class="form-group">
                <label>Plan Survey</label>
                <input type="date" name="plan_survey" value="{{ $project->plan_survey }}" />

            </div>
            <div class="form-group">
                <label>Realisasi Survey</label>
                <input type="date" name="realisasi_survey" value="{{ $project->realisasi_survey }}" />

            </div>

            <div class="form-group">
                <label>Plan Delivery</label>
                <input type="date" name="plan_delivery" value="{{ $project->plan_delivery }}" />
            </div>
            <div class="form-group">
                <label>Realisasi Delivery</label>
                <input type="date" name="realisasi_delivery" value="{{ $project->realisasi_delivery }}" />
            </div>

            <div class="form-group">
                <label>Plan Instalasi</label>
                <input type="date" name="plan_instalasi" value="{{ $project->plan_instalasi }}" />
            </div>

            <div class="form-group">
                <label>Realisasi Instalasi</label>
                <input type="date" name="realisasi_instalasi" value="{{ $project->realisasi_instalasi }}" />
            </div>

            <div class="form-group">
                <label>Plan Integrasi</label>
                <input type="date" name="plan_integrasi" value="{{ $project->plan_integrasi }}" />
            </div>


            <div class="form-group">
                <label>Realisasi Integrasi</label>
                <input type="date" name="realisasi_integrasi" value="{{ $project->realisasi_integrasi }}" />
            </div>

            <div class="form-group">
                <label>Remark</label>
                <textarea name="remark" rows="3">{{ $project->remark }}</textarea>
            </div>

            <div class="form-group">
                <label>Drop</label>
                <select name="drop_data">
                    <option value="">-- Pilih --</option>
                    <option value="Yes" {{ $project->drop_data == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $project->drop_data == 'No' ? 'selected' : '' }}>No</option>
                    <option value="Relokasi" {{ $project->drop_data == 'Relokasi' ? 'selected' : '' }}>Relokasi</option>
                </select>
            </div>
            <div id="relokasi-fields" style="display: none;">

                <div class="form-group">
                    <label>Regional (Relokasi)</label>
                    <select name="relok_regional">
                        <option value="">-- Pilih Regional --</option>
                        @foreach (App\Enums\Regional::cases() as $regional)
                        <option value="{{ $regional->value }}" {{ $project->relok_regional == $regional->value ? 'selected' : '' }}>{{ $regional->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Witel (Relokasi)</label>
                    <select name="relok_witel">
                        <option value="">-- Pilih Witel --</option>
                        @foreach (App\Enums\Witel::cases() as $witel)
                        <option value="{{ $witel->value }}" {{ $project->relok_witel == $witel->value ? 'selected' : '' }}>{{ $witel->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>STO (Relokasi)</label>
                    <input type="text" name="relok_sto" value="{{ $project->relok_sto }}">
                </div>

                <div class="form-group">
                    <label>Site (Relokasi)</label>
                    <input type="text" name="relok_site" value="{{ $project->relok_site }}">
                </div>

            </div>

            <div id="bukti-drop-group" class="form-group" style="display: none;">
                <label>Bukti Drop</label>
                <input type="file" name="bukti_drop" accept="application/pdf,image/*">
                @if ($project->bukti_drop)
                <p>File saat ini: <a href="{{ asset('storage/'.$project->bukti_drop) }}" target="_blank">Lihat File</a></p>
                @endif
            </div>


            <div class="form-group">
                <label>Catuan ID</label>
                <input type="text" name="catuan_id" value="{{ $project->catuan_id }}" required>
            </div>

            <div class="form-group">
                <label>IHLD</label>
                <input type="text" name="ihld" required value="{{ $project->ihld }}" />
            </div>
            @if (auth()->user()->role == 'vendor')
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
                <label>Dependensi</label>
                <select name="dependensi">
                    <option value="">-- Pilih --</option>
                    <option value="Main" {{ $project->dependensi == 'Main' ? 'selected' : '' }}>Main</option>
                    <option value="Dependence" {{ $project->dependensi == 'Dependence' ? 'selected' : '' }}>Dependence</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status OSP</label>
                <select name="status_osp">
                    <option value="">-- Pilih --</option>
                    <option value="Finish Instalasi" {{ $project->status_osp == 'Finish Instalasi' ? 'selected' : '' }}>Finish Instalasi</option>
                    <option value="Proses Instalasi" {{ $project->status_osp == 'Proses Instalasi' ? 'selected' : '' }}>Proses Instalasi</option>
                    <option value="Persiapan" {{ $project->status_osp == 'Persiapan' ? 'selected' : '' }}>Persiapan</option>
                    <option value="Drop" {{ $project->status_osp == 'Drop' ? 'selected' : '' }}>Drop</option>
                </select>
            </div>
            <div class="form-group">
                <label>Scenario Uplink </label>
                <select name="scenario_uplink">
                    <option value="">-- Pilih --</option>
                    <option value="L2S" {{ $project->scenario_uplink == 'L2S' ? 'selected' : '' }}>L2S</option>
                    <option value="OTN" {{ $project->scenario_uplink == 'OTN' ? 'selected' : '' }}>OTN</option>
                    <option value="ONT" {{ $project->scenario_uplink == 'ONT' ? 'selected' : '' }}>ONT</option>
                    <option value="Direct Core" {{ $project->scenario_uplink == 'Direct Core' ? 'selected' : '' }}>Direct Core</option>
                    <option value="Re-engineering" {{ $project->scenario_uplink == 'Re-engineering' ? 'selected' : '' }}>Re-engineering</option>
                    <option value="SFP Bidi" {{ $project->scenario_uplink == 'SFP Bidi' ? 'selected' : '' }}>SFP Bidi</option>
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
            @endif


        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="update-btn">Simpan</button>
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

</x-app-layout>