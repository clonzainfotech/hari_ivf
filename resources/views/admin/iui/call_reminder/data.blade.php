<table class="table m-b-0 table-hover w-100" id="call-reminder-table">
    <thead>
        <tr>             
            <th>Code</th>                          
            <th>Cycle No</th>
            <th>Next Call Date</th>
            <th>Patient Name</th>
            <th>Mobile Number</th>
            <th>Response</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
        @forelse($callReminder as $row)
            <tr>
                <td>{{ !empty($row->getPatientData['code']) ? $row->getPatientData['code'] : '-' }}</td>
                <td>{{ !empty($row->getIuiPatientData['cycle_no']) ? $row->getIuiPatientData['cycle_no'] : '-' }}</td>
                <td>{{ cdate($row->date)->format('d-m-Y') }}</td>
                <td>{{ strtoupper($row->getPatientData['name']) }}</td>
                <td>{{ $row->getPatientData['mobile_number'] }}</td>
                <td>
                    <div class="text-wrraping">
                        {{ !empty($row->response) ? $row->response : '-' }}
                    </div>
                </td>
                {{-- <td>
                    <a class="a-color">
                        <button class="btn  btn-icon  btn-neutral candor-color btn-icon-mini call-reminder-edit" data-id="{{encrypt($row->id)}}">
                            <i class="zmdi zmdi-edit material-icons"></i>
                        </button>
                    </a>
                </td> --}}
            </tr>
        @empty
            <td colspan="7" class="text-center">No records available</td>
        @endforelse
    <tbody>
    </tbody>
</table>
{{$callReminder->links() }}