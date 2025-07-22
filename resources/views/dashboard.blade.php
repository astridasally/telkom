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
                            <th rowspan="2" class="regional-header">Regional</th>
                            <th rowspan="2">PLAN CSF</th>
                            <th colspan="2" class="header-group">FTTH READY</th>
                            <th colspan="2" class="header-group">DELIVERY</th>
                            <th colspan="2" class="header-group">INSTALASI</th>
                            <th colspan="2" class="header-group">INTEGRASI</th>
                            <th colspan="2" class="header-group">GO LIVE</th>
                            <th colspan="2" class="header-group">UPLINK MINI OLT READINESS</th>
                        </tr>
                        <tr>
                            <th>CSF</th>
                            <th>Port</th>
                            <th>Plan</th>
                            <th>Done</th>
                            <th>Plan</th>
                            <th>Done</th>
                            <th>Plan</th>
                            <th>Done</th>
                            <th>CSF</th>
                            <th>Port</th>
                            <th>Ready</th>
                            <th>Not Ready</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach (\App\Enums\Regional::cases() as $regionalEnum)
                    <tr>
                        <td class="regional-header">{{ $regionalEnum->value }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['plan_csf'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['ftth_ready_csf'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['ftth_ready_port'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['delivery_plan'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['delivery_done'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['instalasi_plan'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['instalasi_done'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['integrasi_plan'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['integrasi_done'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['golive_csf'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['golive_port'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['uplink_ready'] ?? 0 }}</td>
                        <td>{{ $funnelingData[$regionalEnum->value]['uplink_not_ready'] ?? 0 }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td class="regional-header">TOTAL</td>
                    <td>{{ $totalFunnelingCounts['plan_csf'] }}</td>
                    <td>{{ $totalFunnelingCounts['ftth_ready_csf'] }}</td>
                    <td>{{ $totalFunnelingCounts['ftth_ready_port'] }}</td>
                    <td>{{ $totalFunnelingCounts['delivery_plan'] }}</td>
                    <td>{{ $totalFunnelingCounts['delivery_done'] }}</td>
                    <td>{{ $totalFunnelingCounts['instalasi_plan'] }}</td>
                    <td>{{ $totalFunnelingCounts['instalasi_done'] }}</td>
                    <td>{{ $totalFunnelingCounts['integrasi_plan'] }}</td>
                    <td>{{ $totalFunnelingCounts['integrasi_done'] }}</td>
                    <td>{{ $totalFunnelingCounts['golive_csf'] }}</td>
                    <td>{{ $totalFunnelingCounts['golive_port'] }}</td>
                    <td>{{ $totalFunnelingCounts['uplink_ready'] }}</td>
                    <td>{{ $totalFunnelingCounts['uplink_not_ready'] }}</td>
                </tr>
            </tbody>
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