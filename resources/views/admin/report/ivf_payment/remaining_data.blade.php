

<table class="table m-b-0 table-hover">
    <thead>
        <tr>  
            <th>Sr.No</th>           
            <th>Name</th>
            <th>Cycle No</th>
            <th>Mobile No</th>
            <th>Package</th>
            <th>Left Amount</th>
            <th>Payment Type</th>
            <th>Condition</th>
            <th>Is Completed</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
       <!--  <td class="refdocdata-loader" colspan="11">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td> -->
        @php
            $i = 1;
            $paymentTypeData = ['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'];

        @endphp
        @forelse($ivfPayment as $row)
            <tr class="ivfpayment">
                <!-- <td>{{($i++) . '.'}}</td> -->
                <td>{{ ((($ivfPayment->currentPage() - 1 ) * $ivfPayment->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>{{$row->patient_name}}</td>
                <td>
                    <div class="{{'edit-payment cycle-no-'.$row->id}}">
                        {{$row->cycle_no}}
                    </div>
                </td>
                <td>{{$row->getPatientsData['mobile_number']}}</td>
                <td><div class="{{'edit-payment package-'.$row->id}}">{{$row->package}}</div></td>
                @php
                    $lessamount = $row->total_payment;
                @endphp
                <td><div>
                        @if($lessamount < 0)
                            {{$lessamount = 0}}
                        @else
                            {{$lessamount}}
                        @endif
                 </div></td> 
                <td><div class="{{'edit-payment payment-type-'.$row->id}}">{{ucfirst(!empty($row->payment_type) ? $paymentTypeData[$row->payment_type] : null)}}</div></td>
                <td><div class="{{'edit-payment condition-'.$row->id}}">{{$row->condition}}</div></td>
                <td>{{$row->is_completed == 0 ? 'No' : 'Yes'}}</td>
                <td><div class="{{'edit-payment date-'.$row->id}}">{{$row->created_at->format('d-m-Y')}}</div></td>
            </tr>
        @empty
            <td colspan='11' class="text-center ivfpayment">No records available</td>
            <script type="text/javascript">
                $('.ivf-payment-table').css('display','table');
            </script>
        @endforelse
    </tbody>
</table>
{{$ivfPayment->links()}}