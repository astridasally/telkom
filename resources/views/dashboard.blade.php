<x-app-layout>
    {{-- Sesuaikan path CSS Anda --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />


    {{-- SERTAKAN LIBRARY CHART.JS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="page-content-wrapper">

        {{-- Ini adalah bagian tabel utama Funneling OLT --}}
        {{-- Karena Anda tidak meminta perubahan pada bagian ini, saya akan biarkan struktur dasarnya. --}}
        {{-- Data di tabel ini perlu diisi secara dinamis jika Anda ingin menampilkan ringkasan per Regional. --}}
   <div class="main-table-container">
    <div class="header-info">
        <div class="left-group">
            <div class="title-left">FUNNELING OLT</div>
            <div class="filter-buttons">
                <a href="{{ route('dashboard', ['project_type' => 'Project TA']) }}"
                   class="filter-btn {{ $selectedType == 'Project TA' ? 'active' : '' }}">
                    Project TA
                </a>
                <a href="{{ route('dashboard', ['project_type' => 'Project Mitratel']) }}"
                   class="filter-btn {{ $selectedType == 'Project Mitratel' ? 'active' : '' }}">
                    Project Mitratel
                </a>
            </div>
        </div>
        <div class="date-right">Cut Off Data: {{ date('d F Y') }}</div>
    </div>




            <div class="table-wrapper">
                <table>
    <thead>
        
        <tr>
            <th rowspan="2" class="regional-header">Regional</th>
            <th rowspan="2">PLAN CSF</th>
            <th rowspan="2">FTTH READY</th>
            <th rowspan="2">JUMLAH PORT</th>
            <th colspan="2">MOS</th>
            <th colspan="2">INSTALASI</th>
            <th colspan="2">INTEGRASI</th>
            <th rowspan="2">GO LIVE</th>
            <th colspan="2">UPLINK MINI OLT READINESS</th>
        </tr>
        <tr>
            <th>Plan</th>
            <th>Done</th>
            <th>Plan</th>
            <th>Done</th>
            <th>Plan</th>
            <th>Done</th>
            <th>Ready</th>
            <th>Not Ready</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($regions as $region)
        <tr>
            <td class="regional-header">{{ $region->value }}</td>
            <td>{{ $tableData[$region->value]['plan_csf'] }}</td>
            <td>{{ $tableData[$region->value]['ftth_ready_csf'] }}</td>
            <td>{{ $tableData[$region->value]['jumlah_port'] }}</td>
            <td>{{ $tableData[$region->value]['delivery_plan'] }}</td>
            <td>{{ $tableData[$region->value]['delivery_done'] }}</td>
            <td>{{ $tableData[$region->value]['instalasi_plan'] }}</td>
            <td>{{ $tableData[$region->value]['instalasi_done'] }}</td>
            <td>{{ $tableData[$region->value]['integrasi_plan'] }}</td>
            <td>{{ $tableData[$region->value]['integrasi_done'] }}</td>
            <td>{{ $tableData[$region->value]['golive_status'] }}</td>
            <td>{{ $tableData[$region->value]['uplink_ready'] }}</td>
            <td>{{ $tableData[$region->value]['uplink_not_ready'] }}</td>
        </tr>
        @endforeach

        <tr class="total-all-row">
            <td class="regional-header">TOTAL</td>
            <td>{{ $totalCounts['plan_csf'] }}</td>
            <td>{{ $totalCounts['ftth_ready_csf'] }}</td>
            <td>{{ $totalCounts['jumlah_port'] }}</td>
            <td>{{ $totalCounts['delivery_plan'] }}</td>
            <td>{{ $totalCounts['delivery_done'] }}</td>
            <td>{{ $totalCounts['instalasi_plan'] }}</td>
            <td>{{ $totalCounts['instalasi_done'] }}</td>
            <td>{{ $totalCounts['integrasi_plan'] }}</td>
            <td>{{ $totalCounts['integrasi_done'] }}</td>
            <td>{{ $totalCounts['golive_status'] }}</td>
            <td>{{ $totalCounts['uplink_ready'] }}</td>
            <td>{{ $totalCounts['uplink_not_ready'] }}</td>
        </tr>
    </tbody>
</table>

            </div>
        </div>

        {{-- Bagian Graph Placeholder --}}
        <div class="dashboard-wrapper">
            <div class="graph-placeholder">
                    <h3>GRAFIK INTEGRASI</h3>
                <div class="graph-content">
                    <canvas id="sCurveChart"></canvas>
                </div>
            </div>

            {{-- Bagian Main Dashboard dengan kotak-kotak angka --}}
            <div class="main-container">
                <h3>MAIN DASHBOARD</h3>

                {{-- Filter (tetap seperti sebelumnya) --}}
                <form action="{{ route('dashboard') }}" method="GET">
                <div class="dashboard-filters-row">

                    <div class="filter-item">
                    <label for="project_type_filter" class="sr-only">Pilih Project Type</label>
                    <select id="project_type_filter" name="project_type">
                        {{-- Opsi untuk melihat semua project type --}}
                        <option value="All Project" {{ request('project_type', 'All Project') == 'All Project' ? 'selected' : '' }}>
                            Project All
                        </option>

                        {{-- Iterasi melalui nilai-nilai dari $allProjectTypes --}}
                        @foreach ($allProjectTypes as $type)
                            <option value="{{ $type }}" {{ request('project_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-item">
                        <label for="regional_filter" class="sr-only">Pilih Regional</label>
                        <select id="regional_filter" name="regional">
                            {{-- Opsi untuk melihat semua regional --}}
                            <option value="All Regional" {{ request('regional', 'All Regional') == 'All Regional' ? 'selected' : '' }}>All Regional</option>

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
                    <label for="filter_assign_to" class="sr-only">Pilih Mitra</label>
                    <select id="filter_assign_to" name="filter_assign_to">
                       
                        
                       {{-- Opsi untuk melihat semua mitra --}}
                        <option value="all" {{ request('filter_assign_to', 'all') == 'all' ? 'selected' : '' }}>All Mitra</option>

                        {{-- Iterasi dari daftar mitra unik --}}
                        @foreach ($allMitras as $mitraName)
                            <option value="{{ $mitraName }}" {{ request('filter_assign_to', 'all') == $mitraName ? 'selected' : '' }}>
                                {{ $mitraName }}
                            </option>
                        @endforeach
                    </select>
                </div>


                   
                   <div class="filter-item">
                    <label for="witel_filter" class="sr-only">Pilih Witel</label>
                    <select id="witel_filter" name="witel">
                        {{-- Opsi untuk melihat semua witel --}}
                        <option value="All Witel" {{ request('witel', 'All Witel') == 'All Witel' ? 'selected' : '' }}>All Witel</option>

                        {{-- Iterasi melalui nilai-nilai dari Enum Witel --}}
                        @foreach (\App\Enums\Witel::cases() as $witelEnum)
                            <option value="{{ $witelEnum->value }}"
                                {{ request('witel', 'All Witel') == $witelEnum->value ? 'selected' : '' }}>
                                {{ $witelEnum->value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                    <button type="submit" class="filter-apply-btn">Apply</button>
                  
                </div>
            </form>

               {{-- Kotak-kotak Angka --}}
                <div class="content-grid">
                    <div class="stage-card" onclick="showPopup('lainnya')">
                        <div class="card-header">
                            <span>LAINNYA</span>
                        </div>
                        <div class="card-body">
                            <span class="material-symbols-outlined">rate_review</span>
                            <div class="card-text-group">
                                <p>Plan: <strong>{{ $planSurveyCount ?? 0 }}</strong></p>
                                <p>Realisasi: <strong>{{ $realSurveyCount ?? 0 }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="stage-card" onclick="showPopup('mos')">
                        <div class="card-header">
                            <span>MOS</span>
                        </div>
                        <div class="card-body">
                            <span class="material-symbols-outlined">local_shipping</span>
                            <div class="card-text-group">
                                <p>Plan: <strong>{{ $planDeliveryCount ?? 0 }}</strong></p>
                                <p>Realisasi: <strong>{{ $realDeliveryCount ?? 0 }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="stage-card" onclick="showPopup('instalasi')">
                        <div class="card-header">
                            <span>INSTALASI</span>
                        </div>
                        <div class="card-body">
                            <span class="material-symbols-outlined">build</span>
                            <div class="card-text-group">
                                <p>Plan: <strong>{{ $planInstalasiCount ?? 0 }}</strong></p>
                                <p>Realisasi: <strong>{{ $realInstalasiCount ?? 0 }}</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="stage-card" onclick="showPopup('integrasi')">
                        <div class="card-header">
                            <span>INTEGRASI</span>
                        </div>
                        <div class="card-body">
                            <span class="material-symbols-outlined">hub</span>
                            <div class="card-text-group">
                                <p>Plan: <strong>{{ $planIntegrasiCount ?? 0 }}</strong></p>
                                <p>Realisasi: <strong>{{ $realIntegrasiCount ?? 0 }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kotak Drop --}}
                <div class="status-boxes-grid">
                    <div class="stage-card" onclick="showPopup('drop')">     {{-- DROP --}}
                        <div class="card-header">
                            <span>DROP</span>
                        </div>
                        <div class="card-body">
                            <span class="material-symbols-outlined">call_split</span>
                            <div class="card-text-group">
                                <p>Yes: <strong>{{ $dropYesCount ?? 0 }}</strong></p>
                                <p>No: <strong>{{ $dropNoCount ?? 0 }}</strong></p>
                                <p>Relokasi: <strong>{{ $dropRelokasiCount ?? 0 }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>


                </div>
            </div>
                        
                <div class="sken-area">
                    <h3>SKENARIO INTEGRASI</h3>
                    <hr class="custom-line">
                    <table class="table table-bordered table-striped" style="width:100%">
                    <thead>
                            <tr>
                                <th>Regional</th>
                                {{-- Loop melalui kolom-kolom skenario uplink yang sudah didefinisikan di controller --}}
                                @foreach($skenarioUplinkColumns as $column)
                                    <th>
                                        @if($column === 'DIRECT')
                                            Direct
                                        @elseif($column === 'Re_engineering')
                                            Re-Engineering
                                        @elseif($column === 'lainnya')
                                            Lainnya
                                        @else
                                            {{ str_replace('_', '-', $column) }} {{-- Untuk format tampilan default, misal SFP Bidi --}}
                                        @endif
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop melalui setiap regional --}}
                            @foreach($regions as $regionalEnum)
                                @php
                                    $regionName = $regionalEnum->value;
                                    // Pastikan ada data untuk regional ini, jika tidak, tampilkan 0
                                    $rowData = $skenarioIntegrasiByRegional[$regionName] ?? [];
                                @endphp
                                <tr>
                                    <td>{{ $regionName }}</td>
                                    {{-- Loop melalui setiap kolom skenario untuk regional saat ini --}}
                                    @foreach($skenarioUplinkColumns as $column)
                                        <td>{{ $rowData[$column] ?? 0 }}</td>
                                    @endforeach
                        
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td>Total</td>
                                {{-- Loop melalui setiap kolom skenario untuk menampilkan total per kolom --}}
                                @foreach($skenarioUplinkColumns as $column)
                                    <td>{{ $totalSkenarioIntegrasiPerColumn[$column] ?? 0 }}</td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>

            <div class="integrasi-wrapper">
                {{-- Di sini nanti bisa tambahkan div untuk Daily Integrasi --}}
                <div class="daily-area">
                    <h3>DAILY INTEGRASI</h3>
                    <div class="table-scroll-container"> {{-- DIV INI HARUS ADA --}}
                    <table class="table table-bordered table-striped" style="width:100%">
                        <thead>
                            <tr class="main-header-row">
                                <th>No</th>
                                <th>Mitra</th>
                                <th>Regional</th>
                                <th>Witel</th>
                                <th>STO</th>
                                <th>IHLD</th>
                                <th>Catuan ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyIntegrasiProjects as $index => $project)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $project->assign_to}}</td> {{-- Mengakses nama mitra dari relasi user --}}
                                    <td>{{ $project->regional }}</td>
                                    <td>{{ $project->witel }}</td>
                                    <td>{{ $project->sto }}</td>
                                    <td>{{ $project->ihld }}</td>
                                    <td>{{ $project->catuan_id }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada proyek dengan plan integrasi hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                    </div>

            {{-- Di sini nanti bisa tambahkan div untuk Failed Integrasi --}}
            <div class="failed-area">
                <h3>FAILED INTEGRASI</h3>
                 <div class="table-scroll-container"> {{-- DIV INI HARUS ADA --}}

                <table class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr class="main-header-row">
                            <th>No</th>
                            <th>Mitra</th>
                            <th>Regional</th>
                            <th>Witel</th>
                            <th>STO</th>
                            <th>IHLD</th>
                            <th>Catuan ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($failedIntegrasiProjects as $index => $project)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $project->assign_to }}</td>
                                <td>{{ $project->regional }}</td>
                                <td>{{ $project->witel }}</td>
                                <td>{{ $project->sto }}</td>
                                <td>{{ $project->ihld }}</td>
                                <td>{{ $project->catuan_id }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada proyek integrasi yang gagal/terlambat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        {{-- ========== MODAL POPUP DETAIL (Dinamis untuk semua stage) ========== --}}
        <div id="popupDetail" class="popup-modal" style="display: none;">
            <div class="popup-content">
                <span class="close-btn" onclick="closePopup()">&times;</span>
                <h3 id="popup-title">Detail</h3>
                <div class="table-scroll-container">
                    <table class="table table-bordered table-striped" style="width: 100%;">
                        <thead>
                            <tr id="popup-table-head"></tr>
                        </thead>
                        <tbody id="popup-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>







     {{-- Script untuk Chart.js (HARUS DI BAWAH ELEMEN CANVAS) --}}
    
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
    const sCurveLabels = @json($sCurveLabels);
    const sCurvePlanData = @json($sCurvePlanData);
    const sCurveRealData = @json($sCurveRealData);

    const ctx = document.getElementById('sCurveChart');
    if (ctx) {
new Chart(ctx, {
    type: 'line',
    data: {
        labels: sCurveLabels,
        datasets: [
            {
                label: 'PLAN',
                data: sCurvePlanData,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.3,
                fill: false,
                datalabels: {
                    align: 'top',
                    anchor: 'end',
                    color: 'rgb(54, 162, 235)',
                    font: {
                        weight: 'bold'
                    }
                }
            },
            {
                label: 'REALISASI',
                data: sCurveRealData,
                borderColor: 'rgb(255, 159, 64)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                tension: 0.3,
                fill: false,
                datalabels: {
                    align: 'bottom',
                    anchor: 'start',
                    color: 'rgb(255, 159, 64)',
                    font: {
                        weight: 'bold'
                    }
                }
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'BULAN'
                },
                ticks: {
                    padding: 20,
                    autoSkip: false,
                    maxRotation: 45,
                    minRotation: 0,
                    maxTicksLimit: 12
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Jumlah Project'
                },
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        },
        plugins: {
            tooltip: {
                mode: 'index',
                intersect: false
            }
        }
    },
    plugins: [ChartDataLabels] // ini WAJIB supaya datalabels muncul
});

    } else {
        console.error("Canvas element with ID 'sCurveChart' not found.");
    }
</script>

@push('scripts')
<script>
const popupConfig = {
    lainnya: {
        title: "Detail Survey",
        columns: ['No', 'Regional', 'Witel', 'STO', 'IHLD', 'Catuan ID', 'Plan Survey', 'Realisasi Survey'],
        fields: ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_survey', 'realisasi_survey']
    },
    mos: {
        title: "Detail Delivery (MOS)",
        columns: ['No', 'Regional', 'Witel', 'STO', 'IHLD', 'Catuan ID', 'Plan Delivery', 'Realisasi Delivery'],
        fields: ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_delivery', 'realisasi_delivery']
    },
    instalasi: {
        title: "Detail Instalasi",
        columns: ['No', 'Regional', 'Witel', 'STO', 'IHLD', 'Catuan ID', 'Plan Instalasi', 'Realisasi Instalasi'],
        fields: ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_instalasi', 'realisasi_instalasi']
    },
    integrasi: {
        title: "Detail Integrasi",
        columns: ['No', 'Regional', 'Witel', 'STO', 'IHLD', 'Catuan ID', 'Plan Integrasi', 'Realisasi Integrasi'],
        fields: ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'plan_integrasi', 'realisasi_integrasi']
    },
    drop: {
        title: "Detail Drop",
        columns: ['No', 'Regional', 'Witel', 'STO', 'IHLD', 'Catuan ID', 'Drop Status'],
        fields: ['regional', 'witel', 'sto', 'ihld', 'catuan_id', 'drop_data']
    }
};

function showPopup(stage, page = 1) {
    const config = popupConfig[stage];
    if (!config) return;

    document.getElementById("popupDetail").style.display = "flex";
    document.getElementById("popup-title").innerText = config.title;

    const headRow = document.getElementById("popup-table-head");
    headRow.innerHTML = config.columns.map(col => `<th>${col}</th>`).join('');

    const tbody = document.getElementById("popup-table-body");
    tbody.innerHTML = `<tr><td colspan="${config.columns.length}" class="text-center">Loading...</td></tr>`;

    // 🔹 Ambil filter dari dashboard
    const mitra = document.getElementById("filter_assign_to")?.value || 'all';
    const regional = document.getElementById("regional_filter")?.value || 'All Regional';
    const witel = document.getElementById("witel_filter")?.value || 'All Witel';

    // 🔹 Kirim filter ke API
    const url = `/popup-detail?stage=${stage}&page=${page}&filter_assign_to=${encodeURIComponent(mitra)}&regional=${encodeURIComponent(regional)}&witel=${encodeURIComponent(witel)}`;

    fetch(url)
        .then(res => res.json())
        .then(result => {
            const data = result.data || [];
            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="${config.columns.length}" class="text-center">Tidak ada data.</td></tr>`;
                return;
            }

            const rows = data.map((row, index) => {
                const rowHTML = config.fields.map(field => `<td>${row[field] ?? '-'}</td>`).join('');
                return `<tr><td>${(result.from || 1) + index}</td>${rowHTML}</tr>`;
            }).join('');

            tbody.innerHTML = rows;

            let pagination = `<tr><td colspan="${config.columns.length}" class="text-center">`;
            if (result.prev_page_url) {
                pagination += `<button onclick="showPopup('${stage}', ${result.current_page - 1})">Previous</button>`;
            }
            pagination += ` Page ${result.current_page} of ${result.last_page} `;
            if (result.next_page_url) {
                pagination += `<button onclick="showPopup('${stage}', ${result.current_page + 1})">Next</button>`;
            }
            pagination += `</td></tr>`;
            tbody.innerHTML += pagination;
        })
        .catch(error => {
            console.error("Error:", error);
            tbody.innerHTML = `<tr><td colspan="${config.columns.length}" class="text-danger text-center">Gagal memuat data.</td></tr>`;
        });
}



function closePopup() {
    document.getElementById("popupDetail").style.display = "none";
}

// agar bisa diakses dari onclick
document.addEventListener('DOMContentLoaded', function () {
    window.showPopup = showPopup;
    window.closePopup = closePopup;
    console.log("popupDetail found:", document.getElementById("popupDetail"));

});


</script>
@endpush
</x-app-layout>