<x-app-layout>
    {{-- Sesuaikan path CSS Anda --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <div class="page-content-wrapper">

        {{-- Ini adalah bagian tabel utama Funneling OLT --}}
        {{-- Karena Anda tidak meminta perubahan pada bagian ini, saya akan biarkan struktur dasarnya. --}}
        {{-- Data di tabel ini perlu diisi secara dinamis jika Anda ingin menampilkan ringkasan per Regional. --}}
        <div class="main-table-container">
            <div class="header-info">
                <div class="title-left">FUNNELING OLT</div>
                <div class="date-right">Cut Off Data: 21 Juli 2025</div> {{-- Bisa dinamis jika dari backend --}}
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">Regional</th>
                            <th colspan="2">PLAN CSF</th>
                            <th colspan="2">MOS</th>
                            <th colspan="2">INSTALASI</th>
                            <th colspan="2">INTEGRASI</th>
                            <th colspan="2">GO LIVE</th>
                            <th colspan="2">UPLINK MINI OLT READINESS</th>
                        </tr>
                        <tr>
                            <th>CSF</th>
                            <th>PORT</th>
                            <th>DONE</th>
                            <th>OGP</th>
                            <th>DONE</th>
                            <th>OGP</th>
                            <th>DONE</th>
                            <th>OGP</th>
                            <th>CSF</th>
                            <th>PORT</th>
                            <th>RFI</th>
                            <th>CHECK</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Contoh baris statis. Anda mungkin perlu @foreach di sini jika data regional diisi dari $projects --}}
                        <tr> <td>REGIONAL 1</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        <tr> <td>REGIONAL 2</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        <tr> <td>REGIONAL 3</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        <tr> <td>REGIONAL 4</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                        <tr> <td>REGIONAL 5</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>TOTAL</strong></td>
                            {{-- Anda bisa menampilkan total projectCount di sini jika ini adalah total keseluruhan yang Anda inginkan --}}
                            {{-- Atau Anda perlu menghitung total per kolom secara terpisah di controller --}}
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Bagian Graph Placeholder --}}
        <div class="dashboard-wrapper">
            <div class="graph-placeholder">
                <div class="graph-header">
                    <h2>Graph Title Here</h2>
                </div>
                <div class="graph-content">
                    <p>Space for your chart/graph.</p>
                </div>
            </div>

            {{-- Bagian Main Dashboard dengan kotak-kotak angka --}}
            <div class="main-container">
                <div class="header-container-main">
                    <h1 class="title">Main Dashboard</h1>
                </div>

                {{-- Filter (tetap seperti sebelumnya) --}}
                <div class="dashboard-filters-row">
                    <div class="filter-item">
                        <select id="Mitra">
                            <option value="All Mitra">All Mitra</option>
                            <option value="ZTE">ZTE</option>
                            <option value="FiberHome">FiberHome</option>
                            <option value="Huawei">Huawei</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <select id="regional" name="regional">
                            <option value="">Regional</option>
                            <option value="All Regional">All Regional</option>
                            <option value="REG 1">REG 1</option>
                            <option value="REG 2">REG 2</option>
                            <option value="REG 3">REG 3</option>
                            <option value="REG 4">REG 4</option>
                            <option value="REG 5">REG 5</option>
                        </select>
                    </div>
                    <div class="filter-item">
                        <select id="Witel" name="witel">
                            <option value="">Witel</option>
                            <option value="All Witel">All Witel</option>
                            <option value="BALIKPAPAN">BALIKPAPAN</option>
                            <option value="BANDUNG">BANDUNG</option>
                            <option value="DENPASAR">DENPASAR</option>
                            <option value="GORONTALO">GORONTALO</option>
                            <option value="JEMBER">JEMBER</option>
                            <option value="KALBAR">KALBAR</option>
                            <option value="KALTARA">KALTARA</option>
                            <option value="KALTENG">KALTENG</option>
                            <option value="KALSEL">KALSEL</option>
                            <option value="KEDIRI">KEDIRI</option>
                            <option value="KUDUS">KUDUS</option>
                            <option value="KUPANG">KUPANG</option>
                            <option value="MADURA">MADURA</option>
                            <option value="MAGELANG">MAGELANG</option>
                            <option value="MAKASSAR">MAKASSAR</option>
                            <option value="MALANG">MALANG</option>
                            <option value="MALUKU">MALUKU</option>
                            <option value="MADIUN">MADIUN</option>
                            <option value="MATARAM">MATARAM</option>
                            <option value="NTB">NTB</option>
                            <option value="NTT">NTT</option>
                            <option value="PASURUAN">PASURUAN</option>
                            <option value="PAPUA">PAPUA</option>
                            <option value="PAPUA BARAT">PAPUA BARAT</option>
                            <option value="PEKALONGAN">PEKALONGAN</option>
                            <option value="SAMARINDA">SAMARINDA</option>
                            <option value="SEMARANG">SEMARANG</option>
                            <option value="SIDOARJO">SIDOARJO</option>
                            <option value="SINGARAJA">SINGARAJA</option>
                            <option value="SOLO">SOLO</option>
                            <option value="SULSEL">SULSEL</option>
                            <option value="SULSELBAR">SULSELBAR</option>
                            <option value="SULTRA">SULTRA</option>
                            <option value="SULTENG">SULTENG</option>
                            <option value="SULUT MALUT">SULUT MALUT</option>
                            <option value="SURABAYA UTARA">SURABAYA UTARA</option>
                            <option value="SUKABUMI">SUKABUMI</option>
                            <option value="TARAKAN">TARAKAN</option>
                            <option value="YOGYAKARTA">YOGYAKARTA</option>
                        </select>
                    </div>
                    <button class="filter-apply-btn">Apply Filters</button>
                </div>

                {{-- Kotak-kotak Angka --}}
                <div class="content-grid">
                    <div class="stage-section">
                        <div class="card-header-stage">
                            <h2>Survey</h2>
                        </div>
                        <div class="card-content-only">
                            <div class="box-group">
                                <div class="box-wrapper">
                                    <span class="box-label">Plan</span>
                                    <div class="box">{{ $planSurveyCount ?? 0 }}</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Realisasi</span>
                                    <div class="box">{{ $realSurveyCount ?? 0 }} </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stage-section">
                        <div class="card-header-stage">
                            <h2>Delivery</h2>
                        </div>
                        <div class="card-content-only">
                            <div class="box-group">
                                <div class="box-wrapper">
                                    <span class="box-label">Plan</span>
                                    <div class="box">{{ $planDeliveryCount ?? 0 }}</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Realisasi</span>
                                    <div class="box">{{$realDeliveryCount ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stage-section">
                        <div class="card-header-stage">
                            <h2>Instalasi</h2>
                        </div>
                        <div class="card-content-only">
                            <div class="box-group">
                                <div class="box-wrapper">
                                    <span class="box-label">Plan</span>
                                    <div class="box">{{$planInstalasiCount?? 0 }} </div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Realisasi</span>
                                    <div class="box">{{$realInstalasiCount ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="stage-section">
                        <div class="card-header-stage">
                            <h2>Integrasi</h2>
                        </div>
                        <div class="card-content-only">
                            <div class="box-group">
                                <div class="box-wrapper">
                                    <span class="box-label">Plan</span>
                                    <div class="box">{{$planIntegrasiCount?? 0 }}</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Realisasi</span>
                                    <div class="box">{{$realIntegrasiCount?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kotak Drop --}}
                <div class="status-boxes-grid">
                    <div class="stage-section">
                        <div class="card-header-stage">
                            <h2>Drop</h2>
                        </div>
                        <div class="card-content-only">
                            <div class="box-group-three">
                                <div class="box-wrapper">
                                    <span class="box-label">Yes</span>
                                    <div class="box">{{ $dropYes ?? 0 }}</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">No</span>
                                    <div class="box">{{ $dropNo ?? 0 }}</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Relokasi</span>
                                    <div class="box">{{ $dropRelokasi ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>