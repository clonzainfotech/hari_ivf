<table class="table m-b-0 table-hover font" id="category-report-table">
    <thead>
    <tr class="thead">
        <th>Sr No</th>
        <th>Date</th>
        <th>Patient Name</th>
        <th>Mobile</th>
        <th>Procedure</th>
        <th>Received Amt</th>
        <th>CUT Amt(30%)</th>
    </tr>
    </thead>
    <tbody>
    @php
        $i = $totalNetAmount = $totalCutAmount = $totalIui = $grandTotal = $grandCutTotal = 0;
    @endphp
    <td class="cutdata-loader" colspan="9">
        <div class="row">
            <div class="page-loader-wrapper medicine-loader">
                <div class="loader">
                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                </div>
            </div>
        </div>
    </td>
    @forelse ($cutReport as $cut => $rowList)
        @php 
            $doctorName = (strtolower(substr($cut, 0, 3)) == 'dr.') ? ucwords(strtolower(substr($cut, 3))) : ucwords(strtolower($cut));
            // dd($rowList);
            $totalNetAmount = 0;
            $totalCutAmount = 0;
        @endphp
        <tr class="cutdata">
            <td colspan="7" class="sub-headline">{{ 'Dr. ' . $doctorName }}</td>
        </tr>
        
        @foreach ($rowList as $row)
            <tr class="cutdata">
                @php
                    $totalNetAmount += ($row->charge_type == 3) ? $row->amount : (($row->is_final_invoice == 1) ? $row->getInvoice['grand_total_amt'] + $row->getInvoice['deposit_amt'] : $row->netamount);
                    $amount = ($row->charge_type == 3) ? $row->amount : (($row->is_final_invoice == 1) ? $row->getInvoice['grand_total_amt'] + $row->getInvoice['deposit_amt'] : $row->netamount);
                    $totalCutAmount += round(($amount * 30 / 100));
                    $patient_name = ($row->charge_type == 3) ? strtoupper(@$row->getPatients['name']) : (($row->is_final_invoice == 1) ? strtoupper($row->getPatientsDetails['name']) : strtoupper($row->getAppointment->getPatientsDetails['name'])) ;
                    $mobile = ($row->charge_type == 3) ? @$row->getPatients['mobile_number'] : (($row->is_final_invoice == 1) ? $row->getPatientsDetails['mobile_number'] : $row->getAppointment->getPatientsDetails['mobile_number']) ;
                @endphp
                <td>{{ ($i + 1) . '.' }}</td>
                <td>{{ ($row->final_invoice_date) ? \Carbon\Carbon::parse($row->final_invoice_date)->format('d-m-Y') : \Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                <td>{{ $patient_name}}</td>
                <td>{{ $mobile }}</td>
                <td class="procedure-font">
                    @if ($row->charge_type == 3)
                        IUI
                    @elseif($row->procedure_id)
                        {{isset($row->procedure_name) ? $row->procedure_name : ''}}
                    @else
                        @if ($row->total = 0)
                            -
                        @endif
                        @if ($row->consulting_charges > 0)
                            Consulting Charges: <span class="procedure-value">{{ $row->consulting_charges }}</span>
                        @endif
                        @if ($row->cut > 0)
                              CUT: <span class="procedure-value">{{ $row->cut }} </span> <br />
                        @endif
                        @if ($row->nst > 0)
                              NST: <span class="procedure-value">{{$row->nst}} </span>
                        @endif
                        @if ($row->usg > 0)
                              USG: <span class="procedure-value">{{$row->usg}} </span>
                        @endif
                        @if ($row->ivf > 0)
                              IVF: <span class="procedure-value">{{$row->ivf}} </span>
                        @endif
                        @if ($row->dressing > 0)
                              Dressing: <span class="procedure-value">{{$row->dressing}} </span><br/>
                        @endif
                        @php
                            $extraField = unserialize($row->extra_field);
                            $extraField1 = $extraField[0];
                            $extraField2 = $extraField[1];
                            if($extraField1[0] && $extraField1[1] > 0)
                            {
                                echo ucfirst($extraField1[0]) . ': <span class="procedure-value">' . $extraField1[1] . '</span>, ';
                            }
                            if($extraField2[0] && $extraField2[1] > 0)
                            {
                                echo ucfirst($extraField2[0]) . ': <span class="procedure-value">' .  $extraField2[1] . '</span>';
                            }
                        @endphp
                        @if ($row->charge_types == null)
                            -
                        @endif
                    @endif
                    
                </td>
                <td>
                    <div class="amount">
                        @if ($row->charge_type == 3)
                            {{$row->amount}}
                        @elseif($row->is_final_invoice == 1)
                            {{(!empty($row->getInvoice['grand_total_amt']) || !empty($row->getInvoice['deposit_amt'])) ?  $row->getInvoice['grand_total_amt'] + $row->getInvoice['deposit_amt'] : 0}}
                        @else
                            {{$row->netamount}}
                        @endif
                    </div>
                </td>
                <td>
                    <div class="amount">
                        {{  round(($amount * 30) / 100) }}
                    </div>
                </td>
                @php
                    $i++;
                @endphp
            </tr>
        @endforeach
        <tr class="cutdata">
            <td class="bt-none" colspan="4"></td>
            <th class="bt-none" colspan="1">Total :</th>
            <th class="grand-total-top-border">{{$totalNetAmount}}</th>
            <th class="grand-total-top-border">{{$totalCutAmount}}</th>
            @php 
                $grandTotal += $totalNetAmount;
                $grandCutTotal += $totalCutAmount;
            @endphp
            <td class="bt-none"></td>
        </tr>
    @empty
        <td colspan="7" class="text-center cutdata">No records available</td>
    @endforelse
    @if ($grandTotal > 0)
        <tr class="bt-none cutdata">
            <th class="bt-none" colspan="4"></th>
            <th class="bt-none" colspan="1">Grand Total :</th>
            <th class="grand-total-top-border" colspan="1">
                {{ $grandTotal }}
            </th>
            <th class="grand-total-top-border">
                {{$grandCutTotal}}
            </th>
        </tr>
    @endif
    </tbody>
</table>
