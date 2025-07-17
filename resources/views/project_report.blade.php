<x-app-layout>

    <h1>Mitra {{ Auth::user()->name }} </h1>
    <br>


    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                @if(auth()->user()->role === 'mitra')
                <th rowspan="2">Regional</th>
                <th rowspan="2">Witel</th>
                <th rowspan="2">Priority</th>
                <th rowspan="2">Drop</th>
                <th colspan="2" class="header-group">SURVEY</th>
                <th colspan="2" class="header-group">DELIVERY</th>
                <th colspan="2" class="header-group">INSTALASI</th>
                <th colspan="2" class="header-group">INTEGRASI</th>
                <th rowspan="2">Bukti Drop</th>
                <th rowspan="2">Catuan ID</th>
                <th rowspan="2">IHLD</th>
                <th rowspan="2">Remark</th>
                @endif
                
                @if(auth()->user()->role === 'vendor')
                <th rowspan="2">Priority TA</th>
                <th rowspan="2">Status OSP</th>
                <th rowspan="2">Dependensi</th>
                <th rowspan="2">Scenario Uplink</th>
                <th rowspan="2">Status Uplink</th>
                @endif
                <th rowspan="2">Action</th>
            </tr>
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
        </thead>

        <tbody>
            @php $no = 1; @endphp
            @foreach($projects as $project)
            <tr>
                <td scope="row">{{ $no++ }}</td>
                @if(auth()->user()->role === 'vendor')
                <td rowspan="2"><b> {{ $project->user->name }}</b></td>
                @endif
                <td scope="row">{{ $project->regional }}</td>
                <td scope="row">{{ $project->witel }}</td>
                <td scope="row">{{ $project->priority }}</td>
                <td scope="row">{{ $project->drop_data }}</td>
                <td scope="row">{{ $project->plan_survey }}</td>
                <td scope="row">{{ $project->realisasi_survey }}</td>
                <td scope="row">{{ $project->plan_delivery }}</td>
                <td scope="row">{{ $project->realisasi_delivery }}</td>
                <td scope="row">{{ $project->plan_instalasi }}</td>
                <td scope="row">{{ $project->realisasi_instalasi }}</td>
                <td scope="row">{{ $project->plan_integrasi }}</td>
                <td scope="row">{{ $project->realisasi_integrasi }}</td>
                <td scope="row">
                    @if ($project->bukti_drop)
                    <a href="{{ asset('storage/' . $project->bukti_drop) }}" target="_blank">Lihat File</a>
                    @else
                    Tidak Ada
                    @endif
                </td>
                <td scope="row">{{ $project->catuan_id }}</td>
                <td scope="row">{{ $project->ihld }}</td>
                <td scope="row">{{ $project->remark }}</td>
                @if(auth()->user()->role === 'vendor')
                <td scope="row">{{ $project->priority_ta }}</td>
                <td scope="row">{{ $project->status_osp }}</td>
                <td scope="row">{{ $project->dependensi }}</td>
                <td scope="row">{{ $project->scenario_uplink }}</td>
                <td scope="row">{{ $project->status_uplink }}</td>
                @endif
                <td scope="row">
                    <a href="{{ route('project_update_vendor', $project->id) }}">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>


    </table>


</x-app-layout>