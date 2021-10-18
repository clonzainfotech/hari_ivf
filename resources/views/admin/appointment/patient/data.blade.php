<table class="table m-b-0 table-hover" id="patient-table">
    <thead>
        <tr>
            <th>Sr. No</th>
            <th>Name</th>
            <th>Age</th>
            <th>Code</th>
            <th>DOB</th>
            <th>Gender</th>
            <th>Mobile Number</th>
            <th>Other Number</th>
            <th>City, State</th>
            <th>Reference Doctor</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <td class="patient-loader" colspan="9">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($patient as $row)
            <tr class="patientdata" data-id="{{encrypt($row->id)}}">
                <td>{{(($patient->currentPage() - 1 ) * $patient->perPage() ) + $loop->iteration}}</td>
                <td>{{$row->name}}</td>
                <td>{{$row->age}}</td>
                <td>{{$row->code}}</td>
                <td>{{$row->dob}}</td>
                <td>{{$row->gender}}</td>
                <td>
                    {{ $row->mobile_number }}
                </td>
                <td>
                    {{ $row->other_mobile_number }}
                </td>
                <td>{{$row->main_area.', '.$row->city .', ' . $row->getState['name']}}</td>
                <td>{{$row->getReferenceDoctor['name']}}</td>
                <td>
                    <a href="#" class="mr-1 label-link"  data-toggle="modal" data-target="#label-modal" data-name="{{$row->name}}"><i class="fa fa-address-card-o candor-color font-20" title="Name Print"></i>
                    </a>
                    <a href="{{URL::to('get-all-report/'.encrypt($row->id).'?status=')}}" target="_blank" class=""><i class="fa fa-file candor-color font-20" title="All Reports"></i></a>
                </a></td>
            </tr>
        @empty
            <td colspan='10' class="text-center patientdata">No records available</td>
        @endforelse
    </tbody>
</table>
{{$patient->links()}}
