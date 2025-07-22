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
                    <div class="date-right">Cut Off Data: {{ date('d F Y') }}</div>
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
                    <h2>Grafik Integrasi</h2>
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
                <form action="{{ route('dashboard') }}" method="GET">
                <div class="dashboard-filters-row">
                    <div class="filter-item">
                        <label for="mitra_filter" class="sr-only">Pilih Mitra</label>
                        <select id="mitra_filter" name="mitra">
                            <option value="All Mitra" {{ request('mitra') == 'All Mitra' ? 'selected' : '' }}>All Mitra</option>
                            <option value="ZTE" {{ request('mitra') == 'ZTE' ? 'selected' : '' }}>ZTE</option>
                            <option value="FiberHome" {{ request('mitra') == 'FiberHome' ? 'selected' : '' }}>FiberHome</option>
                            <option value="Huawei" {{ request('mitra') == 'Huawei' ? 'selected' : '' }}>Huawei</option>
                            {{-- Contoh: Jika Anda punya daftar mitra dari database, Anda bisa iterasi di sini --}}
                            {{-- @foreach ($mitrasFromDb as $mitraOption)
                                <option value="{{ $mitraOption->name }}" {{ request('mitra') == $mitraOption->name ? 'selected' : '' }}>{{ $mitraOption->name }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="filter-item">
                        <label for="regional_filter" class="sr-only">Pilih Regional</label>
                        <select id="regional_filter" name="regional">
                            {{-- Opsi default jika tidak ada regional yang dipilih --}}
                            <option value="" {{ !request('regional') ? 'selected' : '' }}>Regional</option>
                            {{-- Opsi untuk melihat semua regional --}}
                            <option value="All Regional" {{ request('regional') == 'All Regional' ? 'selected' : '' }}>All Regional</option>

                            {{-- Iterasi melalui nilai-nilai dari Enum Regional --}}
                            @foreach (\App\Enums\Regional::cases() as $regionalEnum)
                                <option value="{{ $regionalEnum->value }}"
                                    {{ request('regional') == $regionalEnum->value ? 'selected' : '' }}>
                                    {{ $regionalEnum->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                   
                    <div class="filter-item">
                        <label for="witel_filter" class="sr-only">Pilih Witel</label>
                        <select id="witel_filter" name="witel">
                            {{-- Opsi default jika tidak ada witel yang dipilih --}}
                            <option value="" {{ !request('witel') ? 'selected' : '' }}>Witel</option>
                            {{-- Opsi untuk melihat semua witel --}}
                            <option value="All Witel" {{ request('witel') == 'All Witel' ? 'selected' : '' }}>All Witel</option>

                            {{-- Iterasi melalui nilai-nilai dari Enum Witel --}}
                            @foreach (\App\Enums\Witel::cases() as $witelEnum)
                                <option value="{{ $witelEnum->value }}"
                                    {{ request('witel') == $witelEnum->value ? 'selected' : '' }}>
                                    {{ $witelEnum->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="filter-apply-btn">Apply Filters</button>
                  
                </div>
            </form>

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