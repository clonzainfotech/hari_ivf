<table class="table m-b-0 table-hover" id="p-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>UHID </th>
        <th>Name</th>
        <th>Note</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse($patients as $row)
    @php
        $class = $row->getCurrentDoneAppointment()['status'] == 1 && $row->getCurrentDoneAppointment()['medicine_status'] == 0 ? 'medicine-not-given' : 'medicine-given';
    @endphp
        <tr data-id="{{encrypt($row->id)}}" class="{{$class}}">
            <td>{{((($patients->currentPage() - 1 ) * $patients->perPage()) + $loop->iteration) . '.'}}</td>
            <td>{{$row->code}}</td>
            <td>{{$row->name}}</td>
            <td>{{$row->lastDoneAppointmentData['id']}}
                {{-- <div class="{{'edit-follow-data edit-follow-'.$iuiHistoryData[count($iuiHistoryData)-1]['id']}}">
                    {{$prevAppointmentDate}}
                        <span class="edit-follow">
                            <i class="material-icons edit-remark-icon pencil-icon ml-0" data-value="{{$prevAppointmentDate}}" data-id="{{$iuiHistoryData[count($iuiHistoryData)-1]['id']}}">edit</i>
                        </span>
                </div></th> --}}
            </td>   
            <td>
                
                <a href="{{URL::to('get-medicine/'.encrypt($row->id))}}" class="btn btn-primary btn-sm m-0">View</a>
                @if($row->getCurrentDoneAppointment()['status'] == 1 && $row->getCurrentDoneAppointment()['medicine_status'] == 0)
                    <a href="javascript:void(0);" data-pid={{($row->id)}} class="medicine-given btn btn-primary btn-sm m-0">Done</a>
                @endif
            </td>
        </tr>
    @empty
        <td colspan="3" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$patients->links()}}
