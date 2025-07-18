<x-app-layout>
<link rel="stylesheet" href="{{ asset('css/report.css') }}">

    <h1>Mitra {{ Auth::user()->name }} </h1>
    <br>


    <table class="table-auto w-full border border-gray-400">
    <thead class="border border-gray-400">
    <tr>
        <th rowspan="2">No</th>
        @if(auth()->user()->role === 'admin'|| auth()->user()->role === 'admin')
        <th rowspan="2">Mitra</th>
        @endif
        <th rowspan="2">Regional</th>
        <th rowspan="2">Witel</th>
        <th rowspan="2">STO</th>
        <th rowspan="2">Site</th>
        <th rowspan="2">IHLD</th>
        <th rowspan="2">Catuan ID</th>
        <th rowspan="2">Status Uplink</th>
        <th rowspan="2">Jumlah Port</th>


        @if(auth()->user()->role === 'mitra' || auth()->user()->role === 'admin')
            <th rowspan="2">Priority</th>
            <th colspan="2" class="header-group">SURVEY</th>
            <th colspan="2" class="header-group">DELIVERY</th>
            <th colspan="2" class="header-group">INSTALASI</th>
            <th colspan="2" class="header-group">INTEGRASI</th>
            <th rowspan="2">Drop</th>
            <th rowspan="2">Regional (Relokasi)</th>
            <th rowspan="2">Witel (Relokasi)</th>
            <th rowspan="2">STO (Relokasi)</th>
            <th rowspan="2">Site (Relokasi)</th>
            <th rowspan="2">Bukti Drop</th>
            <th rowspan="2">Remark</th>
        @endif

        @if(auth()->user()->role === 'vendor' || auth()->user()->role === 'admin')
            <th rowspan="2">Priority TA</th>
            <th rowspan="2">Dependensi</th>
            <th rowspan="2">Status OSP</th>
            <th rowspan="2">Scenario Uplink</th>
            <th rowspan="2">Drop TA</th>
            <th rowspan="2">Remark TA</th>
        @endif
        
        <th rowspan="2">Action</th>
    </tr>

    {{-- BARIS 2 - hanya ditampilkan kalau role MITRA/ADMIN --}}
    @if(auth()->user()->role === 'mitra' || auth()->user()->role === 'admin')
    <tr>
        <th>Plan</th>
        <th>Realisasi</th>
        <th>Plan</th>
        <th>Realisasi</th>
        <th>Plan</th>
        <th>Realisasi</th>
        <th>Plan</th>
        <th>Realisasi</th>
    </tr>
    @endif
</thead>

<tbody>
    @php $no = 1; @endphp
    @foreach($projects as $project)
    <tr>
        <td>{{ $no++ }}</td>
        @if(auth()->user()->role === 'vendor' || auth()->user()->role === 'admin')
        <td>{{ $project->user->name }}</td>
        @endif
        
        <td>{{ $project->regional }}</td>
        <td>{{ $project->witel }}</td>
        <td>{{ $project->sto }}</td>
        <td>{{ $project->site }}</td>
        <td>{{ $project->ihld }}</td>
        <td>{{ $project->catuan_id }}</td>
        <td>{{ $project->status_uplink }}</td>
        <td>{{ $project->jumlah_port }}</td>
      

        @if(auth()->user()->role === 'mitra' || auth()->user()->role === 'admin')
            <td>{{ $project->priority }}</td>
            <td>{{ $project->plan_survey }}</td>
            <td>{{ $project->realisasi_survey }}</td>
            <td>{{ $project->plan_delivery }}</td>
            <td>{{ $project->realisasi_delivery }}</td>
            <td>{{ $project->plan_instalasi }}</td>
            <td>{{ $project->realisasi_instalasi }}</td>
            <td>{{ $project->plan_integrasi }}</td>
            <td>{{ $project->realisasi_integrasi }}</td>
            <td>{{ $project->drop_data }}</td>
            <td>{{ $project->relok_regional }}</td>
            <td>{{ $project->relok_witel }}</td>
            <td>{{ $project->relok_sto }}</td>
            <td>{{ $project->relok_site }}</td>
            
            <td>
                @if($project->bukti_drop)
                    <a href="{{ asset('storage/bukti_drop/' . $project->bukti_drop) }}" target="_blank">Lihat</a>
                @else
                    -
                @endif
            </td>
            <td>{{ $project->remark }}</td>
        @endif

        @if(auth()->user()->role === 'vendor' || auth()->user()->role === 'admin')
            <td>{{ $project->priority_ta }}</td>
            <td>{{ $project->dependensi }}</td>
            <td>{{ $project->status_osp }}</td>
            <td>{{ $project->scenario_uplink }}</td>
            <td>{{ $project->status_uplink }}</td>
            <td>{{ $project->jumlah_port }}</td>
            <td>{{ $project->drop_ta }}</td>
            <td>{{ $project->remark_ta }}</td>
        @endif

        
        
        <td><a href="{{ route('project_update', $project->id) }}">Edit</a></td>
    </tr>
    @endforeach
</tbody>

      
    </table>


</x-app-layout>