@php
    $pMethod = ['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'];
@endphp
<table class="table m-b-0" id="hormon-table">
    <thead>
        <tr>
            <th>Sr No</th> 
            <th>Name</th>
            <th>Cycle</th>
            <th>Injection</th>
            <th>Amount</th>
            <th>Total</th>
            <th>package</th>
            {{-- <th>Total Disc.</th> --}}
            <th>Left Amount</th>
            <th>Category</th>
            <th>Reference Doctor Name</th>
            <th>Payment Type</th>
            <th>Payment Method</th>
            <th>Date Time</th>
            <th>Remark</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if($chargeValue)
            <tr>
                <td colspan="11">Charge Category : {{ $chargeValue}}</td>
            </tr>
        @endif
        <td class="hormon-loader" colspan="11">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($hormon as $key=>$row)
            <tr class="hormondata" data-id="{{encrypt($row->id)}}" data-type="{{$row->charge_type}}">
                <td>{{((($hormon->currentPage() - 1 ) * $hormon->perPage() ) + $loop->iteration) . '.'}}</td>
                <td>{{ucwords(strtolower($row->getPatients['name']))}}</td>
                <td>{{$row->cycle_no}}</td>
                <td>{{!empty($row->getInjectionCharge['name']) ? $row->getInjectionCharge['name'] : $row->injection}}</td>
                <td>{{$row->amount}}</td>
                <td><div class={{'amount-'.$key}}>{{($row->charge_type == 2) ? $row->getTotalPaidAmountIVF() : $row->total}}</div></td>
                <td><div>{{$row->package - $row->getTotalDiscount()}}</div></td>
                @php
                    $totalDiscount = $row->getTotalDiscount();
                    $lessamount = $row->package - $row->total - $totalDiscount;
                    if($row->charge_type == 2)
                    {
                        $lessamount = $row->package - $row->getTotalPaidAmountIVF() - $totalDiscount;
                    }
                @endphp
                <td><div>
                    @if($row->charge_type == 2)
                        @if($lessamount < 0)
                            {{$lessamount = 0}}
                        @else
                            {{$lessamount}}
                        @endif
                    @endif
                 </div></td> 
                <td>
                    <div class={{'category-data-value-'.$key}}>
                        @if($row->charge_type == 1)
                            Hormon
                        @elseif($row->charge_type == 2)
                            IVF
                        @elseif($row->charge_type == 3)
                            IUI
                        @endif
                    </div>
                </td>
                <td>{{$row->reDrName['name']}}</td>
                <td>{{$row->case_type}}</td>
                <td>{{!empty($row->payment_type) ? $pMethod[$row->payment_type] : null}}</td>
                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A') }}</td>
                <td>{{ $row->remark}}</td>
                <td>
                    @if($row->checkIndorDeposit()['id'] == $row->id && $row->total != 0 && (strtotime(\Carbon\Carbon::parse($row->created_at)->format('Y-m-d')) >= strtotime(\Carbon\Carbon::now()->format('Y-m-d'))))
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm change-hormon change-hormon-{{$key}} ivf-payment-font" data-id={{$key}} data-amount={{$row->total}} data-categoryid={{$row->charge_type}} data-hormon={{encrypt($row->id)}} data-nextpayment={{!empty($row->getIvfPaymentReminder()['next_payment']) ? $row->getIvfPaymentReminder()['next_payment'] : '0'}} data-nextpaymentdate={{!empty($row->getIvfPaymentReminder()['next_payment_date']) ? $row->getIvfPaymentReminder()['next_payment_date'] : ''}}>Change</a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm save-hormon save-hormon-{{$key}} ivf-payment-font d-none" data-id={{$key}} data-amount={{$row->total}} data-categoryid={{$row->charge_type}} data-hormon={{encrypt($row->id)}}>Save</a>
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm delete-hormon ivf-payment-font " data-hormon={{encrypt($row->id)}}>Delete</a>
                    @endif
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm receipt-hormon ivf-payment-font"  data-hormon={{encrypt($row->id)}}>Print</a>

                </td>
            </tr>
        @empty
            <td colspan="15" class="text-center hormondata">No records available</td>
        @endforelse
    </tbody>
</table>
{{$hormon->links()}}

