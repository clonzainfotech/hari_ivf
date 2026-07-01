
<table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
        <tr>    
            <th>Sr.No</th>
            <th>Date</th>
            <th>Name</th>
            <th>Mobile</th>
            {{-- <th>Category</th> --}}
            <th>Reference Doctor</th>
            <th>Package</th>
            {{-- <th>Print</th> --}}
        </tr>
    </thead>
    <tbody>
        <td class="anc-loader" colspan="7">
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
                $isDone = '';
                if((in_array($row->category_id,[1,2]) && $row->getPatientsDetails->getIvf) || (in_array($row->category_id,[3,4]) && $row->getPatientsDetails->getIui)){
                    $isDone = ' appointment-opd-tr';
                }
            @endphp
            <tr class="{{'ancdata '.$isDone}}">
                <td>{{ ((($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>{{cdate($row->created_at)->format('d-m-Y')}}</td>
                <td>{{ ucwords(strtolower($row->getPatientsDetails['name'])) }}</td>
                <td>{{$row->getPatientsDetails['mobile_number']}}</td>
                {{-- <td>{{ucfirst($row->categoryDetails['name'])}}</td> --}}
                <td>{{$row->getPatientsDetails->getReferenceDoctor['name']}}</td>
                <td>
                    @if(in_array($row->category_id,[1,2]) && $row->getPatientsDetails->getIvf)
                        {{$row->getPatientsDetails->getIVFPayment['package']}}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(in_array($row->category_id,[1,2]) && $row->getPatientsDetails->getIVFPayment)
                        <a class="btn btn-primary btn-sm ivf-payment-font ivf-package" data-id="{{encrypt($row->getPatientsDetails->getIVFPayment['id'])}}">Package</a>
                    @endif
                </td>
            </tr>
        @empty
            <td colspan='7' class="text-center ancdata">No records available</td>
        @endforelse
    </tbody>
</table>
{{$appointment->links()}}