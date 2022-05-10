@if($charge_type != 7)
    <table class="table m-b-0 table-hover font" id="category-report-table">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Date</th>
                <th>Patient Name</th>
                <th>Mobile</th>
                <th>Category</th>
                <th>Service Given</th>
                <th>Amount</th>

            </tr>
        </thead>
        <tbody>
            <td class="refdocdata-loader" colspan="9">
                <div class="row">
                    <div class="page-loader-wrapper medicine-loader">
                        <div class="loader">
                            <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                        </div>
                    </div>
                </div>
            </td>
            <?php
                $i = 1;
                $grandTotal = $totalRecievedAmount = $total = $totalNetAmount = $totalIui = 0;

            ?>
            @forelse ($refDoctorReport as $refdoctor => $rowList)
                @php
                    $doctorName = (strtolower(substr($refdoctor, 0, 3)) == 'dr.') ? ucwords(strtolower(substr($refdoctor, 3))) : ucwords(strtolower($refdoctor));
                @endphp
                <tr class="refdocdata">
                    <td colspan="6" class="sub-headline">{{ $doctorName}}</td>
                </tr>
                @php $totalNetAmount = 0; @endphp
                @foreach ($rowList as $row)
                    @php
                        $totalNetAmount += ($row->charge_type == 3) ? $row->amount : (($row->is_final_invoice == 1) ? $row->getInvoice['grand_total_amt'] + $row->getInvoice['deposit_amt'] : $row->netamount);

                        // $totalNetAmount += ($row->charge_type == 3) ? $row->amount : $row->netamount;
                    @endphp
                    <tr class="refdocdata">
                        <td>{{ ($i++) . '.' }}</td>
                        <td>{{ ($row->final_invoice_date) ? \Carbon\Carbon::parse($row->final_invoice_date)->format('d-m-Y') : \Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                        <td>{{ ($row->charge_type == 3) ? strtoupper(@$row->getPatients['name']) : (($row->is_final_invoice == 1) ? strtoupper($row->getPatientsDetails['name']) : strtoupper($row->getAppointment->getPatientsDetails['name'])) }}</td>
                        <td>{{ ($row->charge_type == 3) ? strtoupper(@$row->getPatients['mobile_number']) : (($row->is_final_invoice == 1) ? strtoupper($row->getPatientsDetails['mobile_number']) : strtoupper($row->getAppointment->getPatientsDetails['mobile_number']))}}</td>
                        <td>{{ucfirst(@$row->getAppointment->categoryDetails['name'])}}</td>
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
                                    {{$row->getInvoice['grand_total_amt'] + $row->getInvoice['deposit_amt']}}
                                @else
                                    {{$row->netamount}}
                                @endif
                            </div>
                        </td>

                    </tr>

                @endforeach
                <tr class="refdocdata">
                    <td class="bt-none" colspan="5"></td>
                    <th class="bt-none" colspan="1">Total :</th>
                    <th class="grand-total-top-border">{{$totalNetAmount}}</th>
                    @php
                        $grandTotal += $totalNetAmount;
                    @endphp
                    <td class="bt-none"></td>
                </tr>
            @empty
                <td colspan="7" class="text-center refdocdata">No records available</td>
            @endforelse
            @if ($grandTotal > 0)
                <tr class="refdocdata">
                    <td class="bt-none" colspan="5"></td>
                    <th class="bt-none" colspan="1">Grand Total :</th>
                    <th class="grand-total-top-border">{{$grandTotal}}</th>
                </tr>
            @endif
        </tbody>
    </table>
@endif
@if($charge_type == 7)
<div class="row m-0 clearfix dashboard">
    <div class="col-sm-2">
        <div class="card ref-box" data-key="table-offline">
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Offline Reference</p>
                        <h4 class="number mt-0 mb-0">{{$total_offline}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card ref-box" data-key="table-online">
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Online Reference</p>
                        <h4 class="number mt-0 mb-0">{{$total_online}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card ref-box box-border" data-key="table-lead">
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Lead Reference</p>
                        <h4 class="number mt-0 mb-0">{{$total_lead}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="col-sm-2">
        <div class="card ref-box" data-key="table-pt">
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Patient to Patient</p>
                        <h4 class="number mt-0 mb-0">{{$total_pt_to_pt}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <table class="table m-b-0 table-hover table-lead table-ref">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Total Patients</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ref_lead_patients as $refDr)
            <tr>
                <td>{{$refDr->getReferenceDoctor['name']}}</td>
                <td>{{$refDr->total_patients}}</td>
            </tr>
            @endforeach
        </tbody>    
    </table>
    
    <table class="table m-b-0 table-hover table-online d-none table-ref">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Total Patients</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ref_online_patients as $refDr)
            <tr>
                <td>{{$refDr->getReferenceDoctor['name']}}</td>
                <td>{{$refDr->total_patients}}</td>
            </tr>   
            @endforeach
        </tbody>
    </table>
    
    <table class="table m-b-0 table-hover table-offline d-none table-ref">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Total Patients</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ref_offline_patients as $refDr)
            <tr>
                <td>{{$refDr->getReferenceDoctor['name']}}</td>
                <td>{{$refDr->total_patients}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <table class="table m-b-0 table-hover table-pt d-none table-ref">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Total Patients</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ref_pt_to_pt_patients as $refDr)
            <tr>
                <td>{{$refDr->reference_pt_name}}</td>
                <td>{{$refDr->total_patients}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
