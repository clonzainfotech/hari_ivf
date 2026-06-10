<table class="table m-b-0 table-hover" id="report-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Date</th>
        <th>Name</th>
        <th>Mobile No</th>
        <th>Category</th>
        <th>Advice Report</th>
    </tr>
    </thead>
    <tbody>
    @forelse($appointment as $row)
        @if(!empty($row->advice_report))
            <tr data-id="{{encrypt($row->id)}}">
                <td>{{ ((($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>{{ cdate($row->date)->format('d-m-Y')}}</td>
                <td><span class="list-name">{{ucwords(strtolower($row->getPatientsDetails['name']))}}</span></td>
                <td>{{$row->getPatientsDetails['mobile_number']}}</span></td>
                <td>{{$row->categoryDetails['name']}}</td>
                <td class="line-height">
                    {{$row->advice_report}}
                        @if(isset($row->advice_report_male) && !empty($row->advice_report_male))
                            <br>Husbund Report : 
                            {{$row->advice_report_male}}
                        @endif
                </td>
            </tr>
        @endif
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$appointment->links()}}
