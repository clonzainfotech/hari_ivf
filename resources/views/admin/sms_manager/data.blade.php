<table class="table m-b-0 table-hover">
    <thead>
        <tr>             
            <th>Reference Doctor Name</th>                          
            <th>Mobile Number</th>
            <th>Message</th>
            <th>Date</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
    <tbody>
        <td class="smsdata-loader" colspan="9">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($smsData as $row)
            <tr class="smsdata">
                <td>{{$row->getReffDoctor['name']}}</td>
                <td>{{$row->mobile_number}}</td>
                <td>{{$row->message}}</td>
                <td>{{cdate($row->created_at)->format('d-m-Y')}}</td>
            </tr>
        @empty
            <td colspan='4' class="text-center smsdata">No records available</td>
        @endforelse
    </tbody>
</table>
{{$smsData->links()}}          