<table class="table m-b-0 table-hover font" id="category-report-table">
    <thead>
        <tr class="thead">
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
                <td colspan="6" class="sub-headline">{{ !in_array($refdoctor,config('app.social_reference')) ? 'Dr. ' . $doctorName : $doctorName}}</td>
            </tr>
            @php $totalNetAmount = 0; @endphp
            @foreach ($rowList as $row)
                @php
                    $totalNetAmount += ($row->charge_type == 3) ? $row->amount : $row->netamount;
                @endphp
                <tr class="refdocdata">
                    <td>{{ ($i++) . '.' }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                    <td>{{ ($row->charge_type == 3) ? strtoupper(@  $row->getPatients['name']) : strtoupper(@$row->getAppointment->getPatientsDetails['name']) }}</td>
                    <td>{{ ($row->charge_type == 3) ? $row->getPatients['mobile_number'] : @$row->getAppointment->getPatientsDetails['mobile_number']}}</td>
                     <td>{{ucfirst(@$row->getAppointment->categoryDetails['name'])}}</td>
                    <td class="procedure-font">
                        @if ($row->charge_type == 3)
                            IUI
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
