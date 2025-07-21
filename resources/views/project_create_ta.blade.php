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

    <form method="POST" action="{{ route('project_store_ta') }}" enctype="multipart/form-data">
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
                <input type="text" name="sto">
            </div>

            <div class="form-group">
                <label>Site</label>
                <input type="text" name="site">
            </div>

            <div class="form-group">
                <label>IHLD</label>
                <input type="text" name="ihld" required />
            </div>
            
            <div class="form-group">
                <label>Catuan ID</label>
                <input type="text" name="catuan_id" required />
            </div>

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
                <label>FTTH CSF</label>
                <input type="number" name="ftth_csf">
                <span class="error text-danger" id="error_golive_port_client"></span>
            </div>

            <div class="form-group">
                <label>FTTH Port</label>
                <input type="number" name="ftth_port">
            </div>

            <div class="form-group">
                <label>Go Live CSF</label>
                <input type="number" name="golive_csf">
            </div>

            <div class="form-group">
                <label>Go Live Port</label>
                <input type="number" name="golive_port">
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

            <div class="form-group">
                <label>Drop TA</label>
                <select name="drop_ta">
                    <option value="">-- Pilih --</option>
                    <option value="Yes">Not Ready</option>
                    <option value="No">Ready</option>
                </select>
            </div>

            <div class="form-group">
                <label>Remark TA</label>
                <input type="text" name="remark_ta">
            </div>

            <div style="margin-top: 20px;">
            <button type="submit" class="update-btn">Simpan</button>
        </div>

        </div>

</x-app-layout>