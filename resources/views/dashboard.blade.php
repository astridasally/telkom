<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    {{-- Asumsi <x-app-layout> sudah menyediakan navbar di bagian atas --}}

    {{-- Ini adalah wrapper baru untuk semua konten di bawah navbar --}}
    <div class="page-content-wrapper">

        {{-- Ini adalah blok tabel utama --}}
        {{-- Header "FUNNELING OLT" akan kita tambahkan di sini --}}
        <div class="main-table-container">
            <div class="header-info">
                <div class="title-left">FUNNELING OLT</div>
                <div class="date-right">Cut Off Data: </div>
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
                        <tr>
                            <td>REGIONAL 1</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>REGIONAL 2</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>REGIONAL 3</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>REGIONAL 4</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>REGIONAL 5</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><strong>TOTAL</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        {{-- Akhir blok tabel utama --}}

        {{-- Ini adalah dashboard-wrapper yang membungkus grafik dan main container --}}
        <div class="dashboard-wrapper">
            <div class="graph-placeholder">
                <div class="graph-header">
                    <h2>Graph Title Here</h2>
                </div>
                <div class="graph-content">
                    <p>Space for your chart/graph.</p>
                </div>
            </div>

            <div class="main-container">
                <div class="header-container-main">
                    <h1 class="title">Main Dashboard</h1>
                </div>

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
                <div class="content-grid">
                    <div class="stage-section">
                        <div class="card-header-stage">
                            <h2>Survey</h2>
                        </div>
                        <div class="card-content-only">
                            <div class="box-group">
                                <div class="box-wrapper">
                                    <span class="box-label">Plan</span>
                                    <div class="box">100</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Realisasi</span>
                                    <div class="box">75</div>
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
                                    <div class="box">80</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Realisasi</span>
                                    <div class="box">60</div>
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
                                    <div class="box">120</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Realisasi</span>
                                    <div class="box">90</div>
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
                                    <div class="box">95</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Realisasi</span>
                                    <div class="box">70</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="status-boxes-grid">
                    <div class="stage-section">
                        <div class="card-header-stage">
                            <h2>Drop</h2>
                        </div>
                        <div class="card-content-only">
                            <div class="box-group-three">
                                <div class="box-wrapper">
                                    <span class="box-label">Yes</span>
                                    <div class="box">25</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">No</span>
                                    <div class="box">5</div>
                                </div>
                                <div class="box-wrapper">
                                    <span class="box-label">Relokasi</span>
                                    <div class="box">10</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>