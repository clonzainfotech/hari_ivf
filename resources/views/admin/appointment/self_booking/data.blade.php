<table class="table m-b-0 table-hover" id="appointment-request-table">
    <thead>
        <tr>
            <th>Sr No.</th>
            <th>Name</th>
            <th>Mobile No</th>
            <th>Date</th>
            <th>Pregnancy</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($patients as $key => $patient)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$patient->name}}</td>
                <td>{{$patient->mobile_number}}</td>
                <td>{{\carbon\carbon::parse($patient->created_at)->format('d-m-Y')}}</td>
                <td>{{$patient->reason == 1 ? 'Yes' : 'No'}}</td>
                <td>
                    <a class="apt-approve" data-id="{{encrypt($patient->id)}}"><span class="badge is-bill badge-success">Approve</span></a>
                </td>
            </tr>
            @empty
            <td colspan='4' class="text-center">No records available</td>
            @endforelse
    </tbody>
</table>