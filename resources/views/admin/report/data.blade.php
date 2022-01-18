<table class="table m-b-0 table-hover font" id="report-table">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Rec No</th>
            <th>Patient Name</th>
            <th>Category</th>
            <th>Service Given</th>
            <th>Payment</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <td class="reportdata-loader" colspan="9">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($report as $row)
            <tr class="reportdata" data-id="{{encrypt($row->id)}}">
                <td>{{ ((($report->currentPage() - 1 ) * $report->perPage() ) + $loop->iteration) . '.'}}</td>
                <td>{{ \Carbon\Carbon::parse($row->getAppointment->date)->format('d-m-Y') }} </td>
                <td>{{$row->getAppointment->getPatientsDetails['code'] }}</td>
                <td>{{strtoupper($row->getAppointment->getPatientsDetails['name'])}}</td>
                <td>{{ucfirst($row->getAppointment->categoryDetails['name'])}}</td>
                <td>{{ !empty($row->charge_types) ? $row->charge_types : '-' }}</td>
                <td>{{$row->payment_mode}}</td>
                <td>
                    <div class="amount">
                        {{$row->netamount}}
                    </div>
                </td>
            </tr>
        @empty
            <td colspan='8' class="text-center reportdata">No records available</td>
        @endforelse
        @if ($netAmountCount > 0)
            <tr class="bt-none reportdata">
                <td class="bt-none" colspan="6"></td>
                <th class="bt-none" colspan="1">Grand Total :</th>
                <th class="grand-total-top-border">{{$netAmountCount}}</th>
            </tr>
        @endif
    </tbody>
</table>
{{$report->links()}}
