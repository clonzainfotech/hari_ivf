<table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
    <tr>
        <th>Sr.No</th>
        {{-- <th>Arrival Time</th> --}}
        <th>Appointment Date & Time</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>UHID </th>
        <th>Seen By</th>
        <th>Category</th>
        <th>Remark</th>
    </tr>
    </thead>
    <tbody>
    <td class="anc-loader" colspan="9">
        <div class="row">
            <div class="page-loader-wrapper medicine-loader">
                <div class="loader">
                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                </div>
            </div>
        </div>
    </td>
    @forelse($appointment as $row)
        @php
            $checkAnc = '';
            if($row->getPatientsDetails->getAnc && $row->is_new_anc == 0){
                $checkAnc = 'selected-tr anc';
            }
            $name = ucwords(strtolower($row->getPatientsDetails['name']));
            $catname = $row->categoryDetails['name'];
        @endphp
        <tr data-id="{{encrypt($row->getPatientsDetails['id'])}}" class="{{'ancdata '.$checkAnc}}">
            <td>{{ ((($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration) . '.' }}</td>
            <td>
                {{ !empty($row->time) ? cdate($row->date)->format('d-m-Y') . ' ' .  cdate($row->time)->format('h:i') : cdate($row->date)->format('d-m-Y') }}
                @if($row->arrival_time)
                    <br>
                    <br>
                    <span class="appointment-arrival-time">{{'Arrival:'.date('h:i', strtotime($row->arrival_time))}}</span>
                @endif
            </td>
            <td>{{ $name }}</td>
            <td>{{$row->getPatientsDetails['mobile_number']}}</td>
            <td>{{$row->getPatientsDetails['code'] }}</td>
            <td>{{$row->getSeenBy['name']}}</td>
            <td>{{$row->categoryDetails['name']}}</td>
            <td><div class="text-wrraping">{{$row->remark}}</div></td>
        </tr>
    @empty
        <td colspan='8' class="text-center ancdata">No records available</td>
    @endforelse
    </tbody>
</table>
{{$appointment->links()}}
