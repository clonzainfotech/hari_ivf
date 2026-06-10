@php
    $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
@endphp
<table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
    <tr>
        <th>Sr.No</th>
        {{-- <th>Arrival Time</th> --}}
        <th>Appointment Date & Time</th>
        <th>Name</th>
        {{-- <th>Mobile</th>
        <th>UHID </th> --}}
        <th>Plan</th>
        <th>Frozen/Embroy</th>
        <th>Seen By</th>
        <th>Remark</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <td class="ivf-loader" colspan="9">
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
            $viewUrl = url('ivf/create/'.encrypt($row->getPatientsDetails['id']));
            $checkIvf = '';
            if($row->getPatientsDetails->getIvf){
                $checkIvf = 'selected-tr ivf-history';
                $viewUrl = url('ivf/history/'.encrypt($row->getPatientsDetails['id']));
            }
            $paymentUrl = url('ivf/payments/'.encrypt($row->patients_id));
            $name = ucwords(strtolower($row->getPatientsDetails['name']));
            $frozen = '';
            $embroy = '';
            if(!empty($row->getIVFPLan()['plan']) && ($row->getIVFPLan()['plan'] == 3))
            {
                $frozen = !empty($row->getIVFHistory()['frozen']) ? $row->getIVFHistory()['frozen'] : null;
                $embroy = !empty($row->getIVFHistory()['embroy']) ? $row->getIVFHistory()['embroy'] : null;
            }
            if(!empty($row->getIVFPLan()['plan']) && ($row->getIVFPLan()['plan'] == 4))
            {
                $embroy = !empty($row->getIVFHistory()['embroy']) ? $row->getIVFHistory()['embroy'] : null;
            }
        @endphp
        <tr data-id="{{encrypt($row->getPatientsDetails['id'])}}" class="{{'ivfdata ' . $checkIvf}}">
            <td>{{ ((($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration) . '.' }}</td>
            <td>
                {{ !empty($row->time) ? cdate($row->date)->format('d-m-Y') . ' ' .  cdate($row->time)->format('h:i') : cdate($row->date)->format('d-m-Y') }}
                @if($row->arrival_time)
                    <br>
                    <br>
                    <span class="appointment-arrival-time">{{'Arrival:'.date('h:i', strtotime($row->arrival_time))}}</span>
                @endif
            </td>
            <td class="line-height">{{ $name }}<br>{{$row->getPatientsDetails['mobile_number'] .' | '.$row->getPatientsDetails['code']}}</td>
            {{-- <td>{{$row->getPatientsDetails['mobile_number']}}</td>
            <td>{{$row->getPatientsDetails['code'] }}</td> --}}
            <td>{{!empty($row->getIVFPLan()['plan']) ? $planData[$row->getIVFPLan()['plan']] : null}}</td>
            <td>{{!empty($frozen) ? 'Semen Freezing : '.$frozen : null}}<br>{{!empty($embroy) ? 'Embroy Ready : '.$embroy : null}}</td>
            
            <td>{{$row->getSeenBy['name']}}</td>
            <td><div class="text-wrraping">{{$row->remark}}</div></td>
            <td><a href="{{$paymentUrl}}"   class="btn btn-primary btn-sm ivf-payment-font">Payment</a><a href="{{$viewUrl}}" class="btn btn-primary btn-sm ivf-payment-font">View</a>
                
            </td>
        </tr>
    @empty
        <td colspan='9' class="text-center ivfdata">No records available</td>
    @endforelse
    </tbody>
</table>
{{$appointment->links()}}
