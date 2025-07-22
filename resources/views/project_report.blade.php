<x-app-layout>
<link rel="stylesheet" href="{{ asset('css/report.css') }}">

    
    <div class="report-wrapper">
    <div class="header-info">
        <div class="title-left">Mitra {{ Auth::user()->name }}</div>

    </div>
         
        
    <div class="table-container">

    <table class="sticky-header-table border border-gray-400">
    <thead class="border border-gray-400">
    <tr>
        <th rowspan="2">No</th>
        @if(auth()->user()->role === 'admin'|| auth()->user()->role === 'vendor')
        <th rowspan="2">Mitra</th>
        @endif
        <th rowspan="2">Regional</th>
        <th rowspan="2">Witel</th>
        <th rowspan="2">STO</th>
        <th rowspan="2">Site</th>
        <th rowspan="2">IHLD</th>
        <th rowspan="2">Catuan ID</th>
        


        @if(auth()->user()->role === 'mitra' || auth()->user()->role === 'admin')
            <th rowspan="2">Priority</th>
            <th colspan="2" class="header-group">SURVEY</th>
            <th colspan="2" class="header-group">DELIVERY</th>
            <th colspan="2" class="header-group">INSTALASI</th>
            <th colspan="2" class="header-group">INTEGRASI</th>
            <th rowspan="2">Drop</th>
            <th rowspan="2">Bukti Drop</th>
            <th colspan="4" class="header-group">RELOKASI</th>
        @endif
        
        @if(auth()->user()->role === 'mitra')
             <th rowspan="2">Status Uplink</th>
        @endif

        @if(auth()->user()->role === 'mitra' || auth()->user()->role === 'admin')
            <th rowspan="2">Remark</th>
        @endif

        @if(auth()->user()->role === 'vendor' || auth()->user()->role === 'admin')
            <th rowspan="2">Priority TA</th>
            <th rowspan="2">Dependensi</th>
            <th colspan="2" class="header-group">FTTH</th>
            <th colspan="2" class="header-group">Go Live</th>
            <th rowspan="2">Status OSP</th>
            <th colspan="2" class="header-group">Uplink</th>
            
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
        <th>Regional</th>
        <th>Witel</th>
        <th>STO</th>
        <th>Site</th>
        
        
    
    @endif

     @if(auth()->user()->role === 'vendor' || auth()->user()->role === 'admin')
    
    <th>CSF</th>
        <th>Port</th>
        <th>CSF</th>
        <th>Port</th>
        <th>Skenario</th>
        <th>Status</th>
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
            <td>
                @if($project->bukti_drop)
                    <a href="{{ asset('storage/bukti_drop/' . $project->bukti_drop) }}" target="_blank">Lihat</a>
                @else
                    -
                @endif
            </td>
            <td>{{ $project->relok_regional }}</td>
            <td>{{ $project->relok_witel }}</td>
            <td>{{ $project->relok_sto }}</td>
            <td>{{ $project->relok_site }}</td>
             @endif
        
        @if(auth()->user()->role === 'mitra')
             <td>{{ $project->status_uplink }}</td>
        @endif

        @if(auth()->user()->role === 'mitra' || auth()->user()->role === 'admin')
            <td>{{ $project->remark }}</td>
        @endif
            
            
        
        @if(auth()->user()->role === 'vendor' || auth()->user()->role === 'admin')
            <td>{{ $project->priority_ta }}</td>
            <td>{{ $project->dependensi }}</td>
            <td>{{ $project->ftth_csf }}</td>
            <td>{{ $project->ftth_port }}</td>
            <td>{{ $project->golive_csf }}</td>
            <td>{{ $project->golive_port }}</td>
            <td>{{ $project->status_osp }}</td>
            <td>{{ $project->scenario_uplink }}</td>
            <td>{{ $project->status_uplink }}</td>

            <td>{{ $project->remark_ta }}</td>

        @endif

        
        
        <td><a href="{{ route('project_update', $project->id) }}" class="edit-button">Edit</a></td>
    </tr>
    @endforeach
</tbody>

      
    </table>

</div>
</x-app-layout>