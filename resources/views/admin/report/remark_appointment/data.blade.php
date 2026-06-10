
<table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
        <tr>    
            <th>Sr.No</th>
            <th>Date</th>
            <th>Name</th>
            <th>Remark</th>
        </tr>
    </thead>
    <tbody>
        <td class="anc-loader" colspan="4">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($appointment as $row)
            <tr>
                <td>{{ ((($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>{{cdate($row['date'])->format('d-m-Y')}}</td>
                <td>{{ ucwords(strtolower($row->getPatientsDetails['name'])).' '.($row->getChildNumber() ? '('.$row->getChildNumber().')' : '')}}</td>
                <td>{{$row->remark}}</td>
            </tr>
        @empty
            <td colspan='4' class="text-center ancdata">No records available</td>
        @endforelse
    </tbody>
</table>
{{$appointment->links()}}