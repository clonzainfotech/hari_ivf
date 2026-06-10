<table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
        <tr>    
            <th>Sr.No</th>
            <th>EDD</th>
            <th>Name</th>
            <th>Mobile</th>
            <th>UHID </th>
        </tr>
    </thead>
    <tbody>
        <td class="anc-loader" colspan="5">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($eddPatientData as $row)
            <tr class="ancdata">
                <td>{{ ((($eddPatientData->currentPage() - 1 ) * $eddPatientData->perPage() ) + $loop->iteration) . '.' }}</td>
                <td> 
                   @php
                       $mhData = json_decode($row->m_h);
                   @endphp
                   {{cdate($mhData->edd)->format('d-m-Y')}}
                </td>
                <td>{{ ucwords(strtolower($row->getPatients['name'])) }}</td>
                <td>{{$row->getPatients['mobile_number'].(!empty($row->getPatients['other_mobile_number']) ? ', '.$row->getPatients['other_mobile_number'] : '')}}</td>
                <td>{{$row->getPatients['code'] }}</td>
            </tr>
        @empty
            <td colspan='5' class="text-center ancdata">No records available</td>
        @endforelse
    </tbody>
</table>
{{$eddPatientData->links()}}