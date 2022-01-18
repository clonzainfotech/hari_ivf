<table class="table m-b-0 table-hover font" id="report-table">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Category</th>
            <th>Deposit</th>
            <th>Service Given</th>
            <th>Payment</th>
            <th>Amount</th>
        </tr>

    </thead>
    <tbody>
        <td class="patientdata-loader" colspan="7">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @php
            $i = 1;
            $totalIncome = 0;
        @endphp
         <tr>
            <td colspan="7" class="sub-headline">Total Patients : {{ count($patientReportOpd)}}</td>
        </tr>
        @forelse($patientReportOpd as $rowList => $data)
            @if (count($patientReportOpd) > 0)
                <tr>
                    <td colspan="7" class="sub-headline">{{ $rowList }}</td>
                </tr>
            @endif

            @php
                $grandTotal = 0;
            @endphp
            @if (count($data) > 0)
                @php
                    $chargeType = ['1'=>'Hormon','2'=>'IVF','3'=>'IUI','4'=>'Indoor Deposit'];
                @endphp
                @forelse($data as $row)
                    <tr data-id="{{encrypt($row->id)}}">

                        <td>{{ ($i++) . '.' }}</td>
                        <td>
                            @if(isset($row['is_final_invoice']) && isset($row['amount']))
                                {{\Carbon\Carbon::parse($row->date)->format('d-m-Y')}}
                            @elseif(isset($row['amount']))
                                {{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}
                            @else
                                {{\Carbon\Carbon::parse($row->date)->format('d-m-Y')}}
                            @endif
                        </td>
                        <td>
                            @if(isset($row['is_final_invoice']) && isset($row['amount']))
                                IPD
                            @elseif(isset($row['amount']))
                                {{ucfirst(!empty($row->charge_type) ? $chargeType[$row->charge_type] : 'IPD')}}
                            @else
                                {{ucfirst($row->categoryDetails['name'])}}
                            @endif
                        </td>
                        <td>{{isset($row['amount']) ? 'Yes' : 'No'}}</td>
                        <td>
                            @if(isset($row['amount']))
                                @php
                                    $patientProcedure = explode(',', $row['procedure_id']);
                                        foreach($procedures as $key => $value) {
                                            if(in_array($value['id'], $patientProcedure)) {
                                                echo $value['name'] . '<br /> ';
                                            }
                                        }
                                @endphp
                            @else
                                OPD
                            @endif
                        </td>
                        <td>
                            @if(isset($row['amount']))
                                {{$row->payment_type == 1 ? 'Card' : 'Cash'}}
                            @else
                                {{($row->getAppointmentCharges['payment_mode'] == 1) ? 'Card' : 'Cash'}}
                            @endif
                        </td>
                        <td>
                            @php
                                $consultingCharges = $nst = $cut = $usg = $dressing = $ivf = $total = 0;
                                if ($row->getAppointmentCharges != null) {
                                    if($row->getAppointmentCharges['consulting_charges'] != null) {
                                        $total += $row->getAppointmentCharges['consulting_charges'];
                                    }

                                    if($row->getAppointmentCharges['nst'] != null) {
                                        $total += $row->getAppointmentCharges['nst'];
                                    }

                                    if($row->getAppointmentCharges['cut'] != null) {
                                        $total += $row->getAppointmentCharges['cut'];
                                    }

                                    if($row->getAppointmentCharges['usg'] != null) {
                                        $total += $row->getAppointmentCharges['usg'];
                                    }

                                    if($row->getAppointmentCharges['dressing'] != null) {
                                        $total += $row->getAppointmentCharges['dressing'];
                                    }

                                    if($row->getAppointmentCharges['ivf'] != null) {
                                        $total += $row->getAppointmentCharges['ivf'];
                                    }

                                    if($row->getAppointmentCharges['extra_field'] != null) {
                                        $extraField = unserialize($row->getAppointmentCharges['extra_field']);
                                        $extraField1 = $extraField[0];
                                        $extraField2 = $extraField[1];
                                        if($extraField1[1] != null) {
                                            $total += $extraField1[1];
                                        }
                                        if($extraField2[1] != null) {
                                            $total += $extraField2[1];
                                        }
                                    }
                                }
                                @endphp
                                <div class="amount">
                                    @php
                                    if(isset($row['amount'])){
                                        $total += $row['amount'];
                                        echo $row['amount'];
                                    }else{
                                        echo $total;
                                    }
                                    @endphp
                                </div>
                                @php
                                $grandTotal += $total;
                                $totalIncome += $total;
                            @endphp
                        </td>
                    </tr>
                @empty
                    <td colspan='7' class="text-center">No records available</td>
                @endforelse
            @endif
            @if (count($patientReportOpd) > 0)
                {{Form::hidden('print', 1, ['class'=>'print'])}}
                <tr class="bt-none">
                    <td class="bt-none" colspan="5"></td>
                    <th class="bt-none" colspan="1">Total : </th>
                    <th class="grand-total-top-border" colspan="1">{{$grandTotal}}</td>
                </tr>
            @else
                <td colspan='7' class="text-center">No records available</td>
            @endif
        @empty
            <td colspan="7" class="text-center cutdata">No records available</td>
        @endforelse
        @if ($totalIncome > 0)
            <tr class="bt-none cutdata">
                <th class="bt-none" colspan="5"></th>
                <th class="bt-none" colspan="1">Grand Total :</th>
                <th class="grand-total-top-border" colspan="1">
                    {{ $totalIncome }}
                </th>
            </tr>
        @endif
    </tbody>
</table>
