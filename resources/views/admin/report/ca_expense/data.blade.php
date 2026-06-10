<table class="table m-b-0 table-hover font">
    <thead>
        <tr>
            <th colspan="24" class="text-center headline">OPD</th>
        </tr>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Patient Name</th>
            <th>Category</th>
            <th>Amount</th>
            <th class="expense-icon">Txt_Amount</th>
            <th class="expense-icon">Invoice No</th>
            <th class="expense-icon">Bank</th>
            <th class="expense-icon">Detail</th>
            <th class="expense-icon"></th>
            <th>Sr No</th>
            <th>Date</th>
            <th>Patient Name</th>
            <th>Category</th>
            <th>Amount</th>
            <th class="expense-icon">Txt_Amount</th>
            <th class="expense-icon">Invoice No</th>
            <th class="expense-icon">Bank</th>
            <th class="expense-icon">Detail</th>
            <th class="expense-icon"></th>
        </tr>
    </thead>
    <tbody>
        @php
            $number = $totalUsg = $totalUsgLeft = $totalUsgRight = 0;
            $count = count($usg);
        @endphp
        @if ($count > 0)
        <tr>
            <th colspan="24" class="sub-headline">USG</th>
        </tr>

            @foreach ($usg as $key => $value)
                @php
                    if($key > 0) {
                        $number++;
                    }
                    if ($loop->iteration >= 3 && $count > 8) {
                        $loop->iteration = $loop->iteration + ($loop->iteration - 1);
                    }

                    if ($loop->iteration > 1 && $count > 8 && $loop->iteration < 3) {
                        $loop->iteration += 1;
                    }
                @endphp
                @if ($number < $count)
                    <tr>
                        @php
                            $totalUsgLeft +=  $usg[$number]->getAppointmentCharges['usg'];
                        @endphp
                        <td>{{ ((($usg->currentPage() - 1 ) * $usg->perPage() ) + $loop->iteration) . '.'}}</td>
                        <td>{{ cdate($usg[$number]->date)->format('d-m-Y')}}</td>
                        <td>{{strtoupper(@$usg[$number]->getPatientsDetails['name'])}}</td>
                        <td>{{ucfirst(@$usg[$number]->categoryDetails['name'])}}</td>
                        <td>
                            <div class="amount">
                                {{$usg[$number]->getAppointmentCharges['usg']}}
                            </div>
                        </td>
                        <td class="expense-icon">{{$usg[$number]->getAppointmentCharges['txt_amount']}}</td>
                        <td class="expense-icon">{{$usg[$number]->getAppointmentCharges['invoice_no']}}</td>
                        <td class="expense-icon">{{(isset($bank_details[$usg[$number]->getAppointmentCharges['bank_id']])) ? $bank_details[$usg[$number]->getAppointmentCharges['bank_id']] : ''}}</td>
                        <td class="expense-icon">{{$usg[$number]->getAppointmentCharges['detail']}}</td>
                        <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$usg[$number]->getAppointmentCharges['id']}}" data-class="AppointmentCharges">edit</i></td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            @php
                                $totalUsgRight +=  $usg[$number]->getAppointmentCharges['usg'];
                            @endphp
                            <td>{{ ((($usg->currentPage() - 1 ) * $usg->perPage() ) + $loop->iteration + 1) . '.'}}</td>
                            <td>{{ cdate($usg[$number]->date)->format('d-m-Y')}}</td>
                            <td>{{strtoupper(@$usg[$number]->getPatientsDetails['name'])}}</td>
                            <td>{{ucfirst(@$usg[$number]->categoryDetails['name'])}}</td>
                            <td>
                                <div class="amount">
                                    {{$usg[$number]->getAppointmentCharges['usg']}}
                                </div>
                            </td>
                            <td class="expense-icon">{{$usg[$number]->getAppointmentCharges['txt_amount']}}</td>
                            <td class="expense-icon">{{$usg[$number]->getAppointmentCharges['invoice_no']}}</td>
                            <td class="expense-icon">{{(isset($bank_details[$usg[$number]->getAppointmentCharges['bank_id']])) ? $bank_details[$usg[$number]->getAppointmentCharges['bank_id']] : ''}}</td>
                            <td class="expense-icon">{{$usg[$number]->getAppointmentCharges['detail']}}</td>
                            <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$usg[$number]->getAppointmentCharges['id']}}" data-class="AppointmentCharges">edit</i></td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalUsg = $totalUsgLeft + $totalUsgRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border">
                    <div class="lower-border">
                        {{ $totalUsg }}
                    </div>
                </th>
                <td class="bt-none" colspan='7'>
                    {{$usg->links()}}
                </td>
            </tr>
        {{-- @else
            <td colspan='12' class="text-center">No records available</td> --}}
        @endif
        @php
            $number = $totalHormon = $totalHormonLeft = $totalHormonRight = 0;
            $count = count($hormon);
            // dd($count);
        @endphp
        @if ($count > 0)
        <tr>
            <th colspan="24" class="sub-headline">Hormon</th>
        </tr>
            @foreach ($hormon as $key => $value)
                @php
                    if ($key > 0) {
                        $number++;
                    }

                    if ($loop->iteration >= 3 && $count > 8) {
                        $loop->iteration = $loop->iteration + ($loop->iteration - 1);
                    }

                    if ($loop->iteration > 1 && $count > 8 && $loop->iteration < 3) {
                        $loop->iteration += 1;
                    }
                @endphp
                @if ($number < $count)
                    <tr>
                        @php
                            $totalHormonLeft +=  $hormon[$number]->amount;
                        @endphp
                        <td>{{ ((($hormon->currentPage() - 1 ) * $hormon->perPage()) + $loop->iteration) . '.' }}</td>
                        <td>{{ cdate($hormon[$number]->created_at)->format('d-m-Y')}}</td>
                        <td>{{strtoupper(@$hormon[$number]->getPatients['name'])}}</td>
                        <td></td>
                        <td>
                            <div class="amount">
                                {{$hormon[$number]->amount}}
                            </div>
                        </td>
                        <td class="expense-icon">{{$hormon[$number]->txt_amount}}</td>
                        <td class="expense-icon">{{$hormon[$number]->invoice_no}}</td>
                        <td class="expense-icon">{{(isset($bank_details[$hormon[$number]->bank_id])) ? $bank_details[$hormon[$number]->bank_id] : ''}}</td>
                        <td class="expense-icon">{{$hormon[$number]->detail}}</td>
                        <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$hormon[$number]->id}}" data-class="IndoorDeposit">edit</i></td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            @php
                                $totalHormonRight +=  $hormon[$number]->amount;
                            @endphp
                            <td>{{ ((($hormon->currentPage() - 1 ) * $hormon->perPage()) + $loop->iteration + 1) . '.'}}</td>
                            <td>{{ cdate($hormon[$number]->created_at)->format('d-m-Y')}}</td>
                            <td>{{strtoupper(@$hormon[$number]->getPatients['name'])}}</td>
                            <td></td>
                            <td>
                                <div class="amount">
                                    {{$hormon[$number]->amount}}
                                </div>
                            </td>
                            <td class="expense-icon">{{$hormon[$number]->txt_amount}}</td>
                            <td class="expense-icon">{{$hormon[$number]->invoice_no}}</td>
                            <td class="expense-icon">{{(isset($bank_details[$hormon[$number]->bank_id])) ? $bank_details[$hormon[$number]->bank_id] : ''}}</td>
                            <td class="expense-icon">{{$hormon[$number]->detail}}</td>
                            <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$hormon[$number]->id}}" data-class="IndoorDeposit">edit</i></td>
                        @endif
                    </tr>
                @endif
                @php
                    $totalHormon = $totalHormonLeft + $totalHormonRight;
                @endphp

            @endforeach
            <tr class="bt-none" >
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none">Total :</th>
                <th class="grand-total-top-border">{{ $totalHormon }} </th>
                <td  class="bt-none" colspan='7'>
                    {{$hormon->links()}}
                </td>
            </tr>
        {{-- @else
            <td colspan='12' class="text-center">No records available</td> --}}
        @endif
        @php
        $number = $totalIui = $totalIuiLeft = $totalIuiRight = 0;
        $count = count($iui);
    @endphp
    @if ($count > 0)
        <tr>
            <th colspan="24" class="sub-headline">IUI</th>
        </tr>

            @foreach ($iui as $key => $value)
                @php
                    if($key > 0) {
                        $number++;
                    }
                    if($loop->iteration >= 3 && $count > 8) {
                        $loop->iteration = $loop->iteration + ($loop->iteration - 1);
                    }
                    if($loop->iteration > 1 && $count > 8 && $loop->iteration < 3) {
                        $loop->iteration += 1;
                    }

                @endphp
                @if ($number < $count)
                    <tr>
                        @php
                            $totalIuiLeft +=  $iui[$number]->amount;
                        @endphp
                        <td>{{ ((($iui->currentPage() - 1 ) * $iui->perPage()) + $loop->iteration) . '.' }}</td>
                        <td>{{ cdate($iui[$number]->created_at)->format('d-m-Y')}}</td>
                        <td>{{ strtoupper(@$iui[$number]->getPatients['name']) }}</td>
                        <td></td>
                        <td>
                            <div class="amount">
                                {{$iui[$number]->amount}}
                            </div>
                        </td>
                        <td class="expense-icon">{{$iui[$number]->txt_amount}}</td>
                        <td class="expense-icon">{{$iui[$number]->invoice_no}}</td>
                        <td class="expense-icon">{{(isset($bank_details[$iui[$number]->bank_id])) ? $bank_details[$iui[$number]->bank_id] : ''}}</td>
                        <td class="expense-icon">{{$iui[$number]->detail}}</td>
                        <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$iui[$number]->id}}" data-class="IndoorDeposit">edit</i></td>

                        @php
                            if($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)

                            @php
                                $totalIuiRight +=  $iui[$number]->amount;
                            @endphp
                            <td>{{ ((($iui->currentPage() - 1 ) * $iui->perPage()) + $loop->iteration + 1) . '.' }}</td>
                            <td>{{ cdate($iui[$number]->created_at)->format('d-m-Y')}}</td>
                            <td>{{strtoupper(@$iui[$number]->getPatients['name'])}}</td>
                            <td></td>
                            <td>
                                <div class="amount">
                                    {{$iui[$number]->amount}}
                                </div>
                            </td>
                            <td class="expense-icon">{{$iui[$number]->txt_amount}}</td>
                            <td class="expense-icon">{{$iui[$number]->invoice_no}}</td>
                            <td class="expense-icon">{{(isset($bank_details[$iui[$number]->bank_id])) ? $bank_details[$iui[$number]->bank_id] : ''}}</td>
                            <td class="expense-icon">{{$iui[$number]->detail}}</td>
                            <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$iui[$number]->id}}" data-class="IndoorDeposit">edit</i></td>

                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIui = $totalIuiLeft + $totalIuiRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border">{{ $totalIui }} </th>
                <td class="bt-none"  colspan='7'>
                    {{$iui->links()}}
                </td>
            </tr>
        {{-- @else
            <td colspan='12' class="text-center">No records available</td> --}}
        @endif
        @php
            $number = $totalIvf = $totalIvfLeft = $totalIvfRight = 0;
            $count = count($ivf);
        @endphp
        @if ($count > 0)
        <tr>
            <th colspan="24" class="sub-headline">IVF</th>
        </tr>

            @foreach($ivf as $key => $value)
                @php
                    if($key > 0) {
                        $number++;
                    }

                    if($loop->iteration >= 3 && $count > 8) {
                        $loop->iteration = $loop->iteration + ($loop->iteration - 1);
                    }

                    if($loop->iteration > 1 && $count > 8 && $loop->iteration < 3) {
                        $loop->iteration += 1;
                    }
                @endphp
                @if($number < $count)
                    <tr>
                        @php
                            $totalIvfLeft +=  $ivf[$number]->amount;
                        @endphp
                        <td>{{((($ivf->currentPage() - 1 ) * $ivf->perPage()) + $loop->iteration) . '.' }}</td>
                        <td>{{cdate($ivf[$number]->created_at)->format('d-m-Y')}}</td>
                        <td>{{strtoupper(@$ivf[$number]->getPatients['name']) }}</td>
                        <td></td>
                        <td>
                            <div class="amount">
                                {{$ivf[$number]->amount}}
                            </div>
                        </td>
                        <td class="expense-icon">{{$ivf[$number]->txt_amount}}</td>
                        <td class="expense-icon">{{$ivf[$number]->invoice_no}}</td>
                        <td class="expense-icon">{{(isset($bank_details[$ivf[$number]->bank_id])) ? $bank_details[$ivf[$number]->bank_id] : ''}}</td>
                        <td class="expense-icon">{{$ivf[$number]->detail}}</td>
                        <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$ivf[$number]->id}}" data-class="IndoorDeposit">edit</i></td>

                        @php
                            if($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if($count > 8 && $number < $count)
                            @php
                                $totalIvfRight +=  $ivf[$number]->amount;
                            @endphp
                            <td>{{((($ivf->currentPage() - 1 ) * $ivf->perPage()) + $loop->iteration + 1) . '.' }}</td>
                            <td>{{cdate($ivf[$number]->created_at)->format('d-m-Y')}}</td>
                            <td>{{strtoupper(@$ivf[$number]->getPatients['name'])}}</td>
                            <td></td>
                            <td>
                                <div class="amount">
                                    {{$ivf[$number]->amount}}
                                </div>
                            </td>
                            <td class="expense-icon">{{$ivf[$number]->txt_amount}}</td>
                            <td class="expense-icon">{{$ivf[$number]->invoice_no}}</td>
                            <td class="expense-icon">{{(isset($bank_details[$ivf[$number]->bank_id])) ? $bank_details[$ivf[$number]->bank_id] : ''}}</td>
                            <td class="expense-icon">{{$ivf[$number]->detail}}</td>
                            <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$ivf[$number]->id}}" data-class="IndoorDeposit">edit</i></td>

                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIvf = $totalIvfLeft + $totalIvfRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none">Total :</th>
                <th class="grand-total-top-border">{{ $totalIvf }} </th>
                <td class="bt-none" colspan='7'>
                    {{$ivf->links()}}
                </td>
            </tr>
        {{-- @else
            <td colspan='12' class="text-center">No records available</td> --}}
        @endif
        @if(!empty($ivfCash) || !empty($iuiCash) || !empty($ancCash) || !empty($gynecCash))
            <tr>
                <th colspan="24" class="sub-headline">{{config('app.doctor') }}</th>
            </tr>
        @endif
        @if(!empty($ivfCash))
            <tr>
                <th colspan="24" class="sub-headline">IVF</th>
            </tr>
            @include('admin.report.ca_expense.collection_data', [
                'data' => $ivfCash
            ])
        @endif
            @php
                $totalIvfCash = (Session::get('total') != null) ? Session::get('total') : 0;
                Session::forget('total');
            @endphp
        @if(!empty($iuiCash))
            <tr>
                <th colspan="24" class="sub-headline">INF</th>
            </tr>
            @include('admin.report.ca_expense.collection_data', [
                'data' => $iuiCash
            ])
        @endif
            @php
                $totalIuiCash = (Session::get('total') != null) ? Session::get('total') : 0;
                Session::forget('total');
            @endphp
        @if(!empty($ancCash))
            <tr>
                <th colspan="24" class="sub-headline">ANC</th>
            </tr>
            @include('admin.report.ca_expense.collection_data', [
                'data' => $ancCash
            ])
        @endif
            @php
                $totalAncCash = (Session::get('total') != null) ? Session::get('total') : 0;
                Session::forget('total');
            @endphp
        @if(!empty($gynecCash))
            <tr>
                <th colspan="24" class="sub-headline">Gynec</th>
            </tr>
            @include('admin.report.ca_expense.collection_data', [
                'data' => $gynecCash
            ])
        @endif
            @php
                $totalGynecCash = (Session::get('total') != null) ? Session::get('total') : 0;
                Session::forget('total');
            @endphp
        {{-- new old cash --}}
        {{-- <tr>
            <th colspan="24" class="sub-headline">NEW - OLD</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $newOldCash
        ])
        @php
            $totalNewOldCash = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp --}}
        {{-- end new old cash --}}
        {{-- <tr>
            <th colspan="24" class="sub-headline">OLD</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $oldCash
        ])
        @php
            $totalOldCash = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp --}}

        {{-- <tr>
            <th colspan="24" class="sub-headline">Swipe</th>
        </tr>

        <tr>
            <th colspan="24" class="sub-headline">IVF</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $ivfCard
        ])
        @php
            $totalIvfCard = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp

        <tr>
            <th colspan="24" class="sub-headline">INF</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $iuiCard
        ])
        @php
            $totalIuiCard = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp

        <tr>
            <th colspan="24" class="sub-headline">ANC</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $ancCard
        ])
        @php
            $totalAncCard = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp
        <tr>
            <th colspan="24" class="sub-headline">Gynec</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $gynecCard
        ])
        @php
            $totalGynecCard = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp


        <tr>
            <th colspan="24" class="sub-headline">Cheque</th>
        </tr>

        <tr>
            <th colspan="24" class="sub-headline">IVF</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $ivfCheque
        ])
        @php
            $totalIvfCheque = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp

        <tr>
            <th colspan="24" class="sub-headline">INF</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $iuiCheque
        ])
        @php
            $totalIuiCheque = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp

        <tr>
            <th colspan="24" class="sub-headline">ANC</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $ancCheque
        ])
        @php
            $totalAncCheque = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp
        <tr>
            <th colspan="24" class="sub-headline">Gynec</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $gynecCheque
        ])
        @php
            $totalGynecCheque = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp


        <tr>
            <th colspan="24" class="sub-headline">UPI</th>
        </tr>

        <tr>
            <th colspan="24" class="sub-headline">IVF</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $ivfUPI
        ])
        @php
            $totalIvfUPI = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp

        <tr>
            <th colspan="24" class="sub-headline">INF</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $iuiUPI
        ])
        @php
            $totalIuiUPI = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp

        <tr>
            <th colspan="24" class="sub-headline">ANC</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $ancUPI
        ])
        @php
            $totalAncUPI = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp
        <tr>
            <th colspan="24" class="sub-headline">Gynec</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $gynecUPI
        ])
        @php
            $totalGynecUPI = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp


        <tr>
            <th colspan="24" class="sub-headline">NEFT</th>
        </tr>

        <tr>
            <th colspan="24" class="sub-headline">IVF</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $ivfNEFT
        ])
        @php
            $totalIvfNEFT = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp

        <tr>
            <th colspan="24" class="sub-headline">INF</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $iuiNEFT
        ])
        @php
            $totalIuiNEFT = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp

        <tr>
            <th colspan="24" class="sub-headline">ANC</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $ancNEFT
        ])
        @php
            $totalAncNEFT = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp
        <tr>
            <th colspan="24" class="sub-headline">Gynec</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $gynecNEFT
        ])
        @php
            $totalGynecNEFT = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp --}}


        {{-- new old card --}}
        {{-- <tr>
            <th colspan="24" class="sub-headline">NEW - OLD</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $newOldCard
        ])
        @php
            $totalNewOldCard = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp --}}
        {{-- end new old card --}}
        {{-- <tr>
            <th colspan="24" class="sub-headline">OLD</th>
        </tr>
        @include('admin.report.ca_expense.collection_data', [
            'data' => $oldCard
        ])
        @php
            $totalOldCard = (Session::get('total') != null) ? Session::get('total') : 0;
            Session::forget('total');
        @endphp --}}

        @php
        $number = $totalIndoorCash = $totalIndoorCashLeft = $totalIndoorCashRight = 0;
        $count = count($indoorCash);
    @endphp
    @if ($count > 0)
        <tr>
            <th colspan="24" class="sub-headline">Indoor</th>
        </tr>

            <tr>
                <th colspan="24" class="sub-headline">Invoices</th>
            </tr>
            @foreach ($indoorCash as $key => $value)
                @php
                    if($key > 0){
                        $number++;
                    }
                @endphp
                @if ($number < $count)
                    <tr>
                        <td>{{($j = $number + 1) . '.'}}</td>
                        <td>{{!empty($indoorCash[$number]['final_invoice_date']) ? cdate($indoorCash[$number]['final_invoice_date'])->format('d-m-Y') : '-'}}</td>
                        <td>{{strtoupper($indoorCash[$number]['get_patients_details']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorCash[$number]['procedure_id']);
                                foreach($procedures as $key => $value) {
                                    if(in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp
                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorCash[$number]['get_invoice']['grand_total_amt'];
                                    $totalIndoorCashLeft += $indoorCash[$number]['get_invoice']['grand_total_amt'];
                                @endphp
                            </div>
                        </td>
                        <td class="expense-icon">{{$indoorCash[$number]['get_invoice']['txt_amount']}}</td>
                        <td class="expense-icon">{{$indoorCash[$number]['get_invoice']['invoice_no']}}</td>
                        <td class="expense-icon">{{(isset($bank_details[$indoorCash[$number]['get_invoice']['bank_id']])) ? $bank_details[$indoorCash[$number]['get_invoice']['bank_id']] : ''}}</td>
                        <td class="expense-icon">{{$indoorCash[$number]['get_invoice']['detail']}}</td>
                        <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$indoorCash[$number]['get_invoice']['id']}}" data-class="IndoorInvoice">edit</i></td>

                        @php
                            if($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{ ($j = $number + 1) . '.'}}</td>
                            <td>{{ !empty($indoorCash[$number]['final_invoice_date']) ? cdate($indoorCash[$number]['final_invoice_date'])->format('d-m-Y') : '-'}}</td>
                            <td>{{ strtoupper($indoorCash[$number]['get_patients_details']['name']) }}</td>
                            <td></td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorCash[$number]['get_invoice']['grand_total_amt'];
                                        $totalIndoorCashRight += $indoorCash[$number]['get_invoice']['grand_total_amt'];
                                    @endphp
                                </div>
                            </td>
                            <td class="expense-icon">{{$indoorCash[$number]['get_invoice']['txt_amount']}}</td>
                            <td class="expense-icon">{{$indoorCash[$number]['get_invoice']['invoice_no']}}</td>
                            <td class="expense-icon">{{(isset($bank_details[$indoorCash[$number]['get_invoice']['bank_id']])) ? $bank_details[$indoorCash[$number]['get_invoice']['bank_id']] : ''}}</td>
                            <td class="expense-icon">{{$indoorCash[$number]['get_invoice']['detail']}}</td>
                            <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$indoorCash[$number]['get_invoice']['id']}}" data-class="IndoorInvoice">edit</i></td>

                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorCash = $totalIndoorCashLeft + $totalIndoorCashRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorCash}}</th>
            </tr>
        @endif
        @php
            $number = $totalIndoorDepositCash = $totalIndoorDepositCashLeft = $totalIndoorDepositCashRight = 0;
            $count = count($indoorCaseDeposit);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Deposit</th>
            </tr>
            @foreach ($indoorCaseDeposit as $key => $value)
                @php
                    if($key > 0){
                        $number++;
                    }
                @endphp
                @if($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ cdate($indoorCaseDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                        <td>{{ strtoupper($indoorCaseDeposit[$number]['get_patients']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorCaseDeposit[$number]['procedure_id']);
                                foreach($procedures as $key => $value){
                                    if(in_array($value['id'], $patientProcedure)){
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp
                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorCaseDeposit[$number]['amount'];
                                    $totalIndoorDepositCashLeft += $indoorCaseDeposit[$number]['amount'];
                                @endphp
                            </div>
                        </td>
                        <td class="expense-icon">{{$indoorCaseDeposit[$number]['txt_amount']}}</td>
                        <td class="expense-icon">{{$indoorCaseDeposit[$number]['invoice_no']}}</td>
                        <td class="expense-icon">{{(isset($bank_details[$indoorCaseDeposit[$number]['bank_id']])) ? $bank_details[$indoorCaseDeposit[$number]['bank_id']] : ''}}</td>
                        <td class="expense-icon">{{$indoorCaseDeposit[$number]['detail']}}</td>
                        <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$indoorCaseDeposit[$number]['id']}}" data-class="IndoorDeposit">edit</i></td>


                        @php
                            if($count > 8){
                                $number++;
                            }
                        @endphp
                        @if($count > 8 && $number < $count)
                            <td>{{ ($j = $number + 1) . '.'}}</td>
                            <td>{{ cdate($indoorCaseDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{ strtoupper($indoorCaseDeposit[$number]['get_patients']['name']) }}</td>
                            <td>
                                @php
                                    $patientProcedure = explode(',', $indoorCaseDeposit[$number]['procedure_id']);
                                    foreach ($procedures as $key => $value) {
                                        if (in_array($value['id'], $patientProcedure)) {
                                            echo $value['name'] . '<br /> ';
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorCaseDeposit[$number]['amount'];
                                        $totalIndoorDepositCashRight += $indoorCaseDeposit[$number]['amount'];
                                    @endphp
                                </div>
                            </td>
                            <td class="expense-icon">{{$indoorCaseDeposit[$number]['txt_amount']}}</td>
                            <td class="expense-icon">{{$indoorCaseDeposit[$number]['invoice_no']}}</td>
                            <td class="expense-icon">{{(isset($bank_details[$indoorCaseDeposit[$number]['bank_id']])) ? $bank_details[$indoorCaseDeposit[$number]['bank_id']] : ''}}</td>
                            <td class="expense-icon">{{$indoorCaseDeposit[$number]['detail']}}</td>
                            <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$indoorCaseDeposit[$number]['id']}}" data-class="IndoorDeposit">edit</i></td>

                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorDepositCash = $totalIndoorDepositCashLeft + $totalIndoorDepositCashRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorDepositCash}}</th>
            </tr>
        @endif

        {{-- <tr>
            <th colspan="24" class="sub-headline">Swipe</th>
        </tr>
        @php
            $number = $totalIndoorDepositCard = $totalIndoorDepositCardLeft = $totalIndoorDepositCardRight = 0;
            $count = count($indoorCardDeposit);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Deposit</th>
            </tr>
            @foreach ($indoorCardDeposit as $key => $value)
                @php
                    if($key > 0){
                        $number++;
                    }
                @endphp
                @if($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ cdate($indoorCardDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                        <td>{{ strtoupper($indoorCardDeposit[$number]['get_patients']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorCardDeposit[$number]['procedure_id']);
                                foreach ($procedures as $key => $value) {
                                    if (in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp

                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorCardDeposit[$number]['amount'];
                                    $totalIndoorDepositCardLeft += $indoorCardDeposit[$number]['amount'];
                                @endphp
                            </div>
                        </td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{($j = $number + 1) . '.'}}</td>
                            <td>{{cdate($indoorCardDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{strtoupper($indoorCardDeposit[$number]['get_patients']['name'])}}</td>
                            <td>
                                @php
                                    $patientProcedure = explode(',', $indoorCardDeposit[$number]['procedure_id']);
                                    foreach ($procedures as $key => $value) {
                                        if (in_array($value['id'], $patientProcedure)) {
                                            echo $value['name'] . '<br /> ';
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorCardDeposit[$number]['amount'];
                                        $totalIndoorDepositCardRight += $indoorCardDeposit[$number]['amount'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorDepositCard = $totalIndoorDepositCardLeft + $totalIndoorDepositCardRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorDepositCard}}</th>
            </tr>
        @endif
        @php
            $number = $totalIndoorCard = $totalIndoorCardLeft = $totalIndoorCardRight = 0;
            $count = count($indoorCard);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Invoices</th>
            </tr>
            @foreach ($indoorCard as $key => $value)
                @php
                    if ($key > 0) {
                        $number++;
                    }
                @endphp
                @if ($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ !empty($indoorCard[$number]['final_invoice_date']) ? cdate($indoorCard[$number]['final_invoice_date'])->format('d-m-Y') : '-'}}</td>
                        <td>{{ strtoupper($indoorCard[$number]['get_patients_details']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorCard[$number]['procedure_id']);
                                foreach ($procedures as $key => $value) {
                                    if (in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp
                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorCard[$number]['get_invoice']['grand_total_amt'];
                                    $totalIndoorCardLeft += $indoorCard[$number]['get_invoice']['grand_total_amt'];
                                @endphp
                            </div>
                        </td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{ ($j = $number + 1) . '.'}}</td>
                            <td>{{ cdate($indoorCard[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{ strtoupper($indoorCard[$number]['get_patients_details']['name']) }}</td>
                            <td></td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorCard[$number]['get_invoice']['grand_total_amt'];
                                        $totalIndoorCardRight += $indoorCard[$number]['get_invoice']['grand_total_amt'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorCard = $totalIndoorCardLeft + $totalIndoorCardRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorCard}}</th>
            </tr>
        @endif

        <tr>
            <th colspan="24" class="sub-headline">Cheque</th>
        </tr>
        @php
            $number = $totalIndoorDepositCheque = $totalIndoorDepositChequeLeft = $totalIndoorDepositChequeRight = 0;
            $count = count($indoorChequeDeposit);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Deposit</th>
            </tr>
            @foreach ($indoorChequeDeposit as $key => $value)
                @php
                    if($key > 0){
                        $number++;
                    }
                @endphp
                @if($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ cdate($indoorChequeDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                        <td>{{ strtoupper($indoorChequeDeposit[$number]['get_patients']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorChequeDeposit[$number]['procedure_id']);
                                foreach ($procedures as $key => $value) {
                                    if (in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp

                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorChequeDeposit[$number]['amount'];
                                    $totalIndoorDepositCardLeft += $indoorChequeDeposit[$number]['amount'];
                                @endphp
                            </div>
                        </td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{($j = $number + 1) . '.'}}</td>
                            <td>{{cdate($indoorChequeDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{strtoupper($indoorChequeDeposit[$number]['get_patients']['name'])}}</td>
                            <td>
                                @php
                                    $patientProcedure = explode(',', $indoorChequeDeposit[$number]['procedure_id']);
                                    foreach ($procedures as $key => $value) {
                                        if (in_array($value['id'], $patientProcedure)) {
                                            echo $value['name'] . '<br /> ';
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorChequeDeposit[$number]['amount'];
                                        $totalIndoorDepositChequeRight += $indoorChequeDeposit[$number]['amount'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorDepositCheque = $totalIndoorDepositChequeLeft + $totalIndoorDepositChequeRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorDepositCheque}}</th>
            </tr>
        @endif
        @php
            $number = $totalIndoorCheque = $totalIndoorChequeLeft = $totalIndoorChequeRight = 0;
            $count = count($indoorCheque);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Invoices</th>
            </tr>
            @foreach ($indoorCheque as $key => $value)
                @php
                    if ($key > 0) {
                        $number++;
                    }
                @endphp
                @if ($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ !empty($indoorCheque[$number]['final_invoice_date']) ? cdate($indoorCheque[$number]['final_invoice_date'])->format('d-m-Y') : '-'}}</td>
                        <td>{{ strtoupper($indoorCheque[$number]['get_patients_details']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorCheque[$number]['procedure_id']);
                                foreach ($procedures as $key => $value) {
                                    if (in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp
                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorCheque[$number]['get_invoice']['grand_total_amt'];
                                    $totalIndoorChequeLeft += $indoorCheque[$number]['get_invoice']['grand_total_amt'];
                                @endphp
                            </div>
                        </td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{ ($j = $number + 1) . '.'}}</td>
                            <td>{{ cdate($indoorCheque[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{ strtoupper($indoorCheque[$number]['get_patients_details']['name']) }}</td>
                            <td></td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorCheque[$number]['get_invoice']['grand_total_amt'];
                                        $totalIndoorChequeRight += $indoorCheque[$number]['get_invoice']['grand_total_amt'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorCheque = $totalIndoorChequeLeft + $totalIndoorChequeRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorCheque}}</th>
            </tr>
        @endif

        <tr>
            <th colspan="24" class="sub-headline">UPI</th>
        </tr>
        @php
            $number = $totalIndoorUPIDeposit = $totalIndoorUPIDepositLeft = $totalIndoorUPIDepositRight = 0;
            $count = count($indoorUPIDeposit);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Deposit</th>
            </tr>
            @foreach ($indoorUPIDeposit as $key => $value)
                @php
                    if($key > 0){
                        $number++;
                    }
                @endphp
                @if($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ cdate($indoorUPIDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                        <td>{{ strtoupper($indoorUPIDeposit[$number]['get_patients']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorUPIDeposit[$number]['procedure_id']);
                                foreach ($procedures as $key => $value) {
                                    if (in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp

                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorUPIDeposit[$number]['amount'];
                                    $totalIndoorDepositCardLeft += $indoorUPIDeposit[$number]['amount'];
                                @endphp
                            </div>
                        </td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{($j = $number + 1) . '.'}}</td>
                            <td>{{cdate($indoorUPIDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{strtoupper($indoorUPIDeposit[$number]['get_patients']['name'])}}</td>
                            <td>
                                @php
                                    $patientProcedure = explode(',', $indoorUPIDeposit[$number]['procedure_id']);
                                    foreach ($procedures as $key => $value) {
                                        if (in_array($value['id'], $patientProcedure)) {
                                            echo $value['name'] . '<br /> ';
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorUPIDeposit[$number]['amount'];
                                        $totalIndoorUPIDepositRight += $indoorUPIDeposit[$number]['amount'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorUPIDeposit = $totalIndoorUPIDepositLeft + $totalIndoorUPIDepositRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorUPIDeposit}}</th>
            </tr>
        @endif
        @php
            $number = $totalIndoorUPI = $totalIndoorUPILeft = $totalIndoorUPIRight = 0;
            $count = count($indoorUPI);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Invoices</th>
            </tr>
            @foreach ($indoorUPI as $key => $value)
                @php
                    if ($key > 0) {
                        $number++;
                    }
                @endphp
                @if ($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ !empty($indoorUPI[$number]['final_invoice_date']) ? cdate($indoorUPI[$number]['final_invoice_date'])->format('d-m-Y') : '-'}}</td>
                        <td>{{ strtoupper($indoorUPI[$number]['get_patients_details']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorUPI[$number]['procedure_id']);
                                foreach ($procedures as $key => $value) {
                                    if (in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp
                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorUPI[$number]['get_invoice']['grand_total_amt'];
                                    $totalIndoorUPILeft += $indoorUPI[$number]['get_invoice']['grand_total_amt'];
                                @endphp
                            </div>
                        </td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{ ($j = $number + 1) . '.'}}</td>
                            <td>{{ cdate($indoorUPI[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{ strtoupper($indoorUPI[$number]['get_patients_details']['name']) }}</td>
                            <td></td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorUPI[$number]['get_invoice']['grand_total_amt'];
                                        $totalIndoorUPIRight += $indoorUPI[$number]['get_invoice']['grand_total_amt'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorUPI = $totalIndoorUPILeft + $totalIndoorUPIRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorUPI}}</th>
            </tr>
        @endif

        <tr>
            <th colspan="24" class="sub-headline">NEFT</th>
        </tr>
        @php
            $number = $totalIndoorNEFTDeposit = $totalIndoorNEFTDepositLeft = $totalIndoorNEFTDepositRight = 0;
            $count = count($indoorNEFTDeposit);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Deposit</th>
            </tr>
            @foreach ($indoorNEFTDeposit as $key => $value)
                @php
                    if($key > 0){
                        $number++;
                    }
                @endphp
                @if($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ cdate($indoorNEFTDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                        <td>{{ strtoupper($indoorNEFTDeposit[$number]['get_patients']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorNEFTDeposit[$number]['procedure_id']);
                                foreach ($procedures as $key => $value) {
                                    if (in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp

                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorNEFTDeposit[$number]['amount'];
                                    $totalIndoorDepositCardLeft += $indoorNEFTDeposit[$number]['amount'];
                                @endphp
                            </div>
                        </td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{($j = $number + 1) . '.'}}</td>
                            <td>{{cdate($indoorNEFTDeposit[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{strtoupper($indoorNEFTDeposit[$number]['get_patients']['name'])}}</td>
                            <td>
                                @php
                                    $patientProcedure = explode(',', $indoorNEFTDeposit[$number]['procedure_id']);
                                    foreach ($procedures as $key => $value) {
                                        if (in_array($value['id'], $patientProcedure)) {
                                            echo $value['name'] . '<br /> ';
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorNEFTDeposit[$number]['amount'];
                                        $totalIndoorNEFTDepositRight += $indoorNEFTDeposit[$number]['amount'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorNEFTDeposit = $totalIndoorNEFTDepositLeft + $totalIndoorNEFTDepositRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorNEFTDeposit}}</th>
            </tr>
        @endif
        @php
            $number = $totalIndoorNEFT = $totalIndoorNEFTLeft = $totalIndoorNEFTRight = 0;
            $count = count($indoorUPI);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Invoices</th>
            </tr>
            @foreach ($indoorNEFT as $key => $value)
                @php
                    if ($key > 0) {
                        $number++;
                    }
                @endphp
                @if ($number < $count)
                    <tr>
                        <td>{{ ($j = $number + 1) . '.'}}</td>
                        <td>{{ !empty($indoorNEFT[$number]['final_invoice_date']) ? cdate($indoorNEFT[$number]['final_invoice_date'])->format('d-m-Y') : '-'}}</td>
                        <td>{{ strtoupper($indoorNEFT[$number]['get_patients_details']['name']) }}</td>
                        <td>
                            @php
                                $patientProcedure = explode(',', $indoorNEFT[$number]['procedure_id']);
                                foreach ($procedures as $key => $value) {
                                    if (in_array($value['id'], $patientProcedure)) {
                                        echo $value['name'] . '<br /> ';
                                    }
                                }
                            @endphp
                        </td>
                        <td>
                            <div class="amount">
                                @php
                                    echo $indoorNEFT[$number]['get_invoice']['grand_total_amt'];
                                    $totalIndoorNEFTLeft += $indoorNEFT[$number]['get_invoice']['grand_total_amt'];
                                @endphp
                            </div>
                        </td>
                        @php
                            if ($count > 8) {
                                $number++;
                            }
                        @endphp
                        @if ($count > 8 && $number < $count)
                            <td>{{ ($j = $number + 1) . '.'}}</td>
                            <td>{{ cdate($indoorNEFT[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{ strtoupper($indoorNEFT[$number]['get_patients_details']['name']) }}</td>
                            <td></td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorNEFT[$number]['get_invoice']['grand_total_amt'];
                                        $totalIndoorNEFTRight += $indoorNEFT[$number]['get_invoice']['grand_total_amt'];
                                    @endphp
                                </div>
                            </td>
                        @endif
                    </tr>
                @endif
            @endforeach
            @php
                $totalIndoorNEFT = $totalIndoorNEFTLeft + $totalIndoorNEFTRight;
            @endphp
            <tr class="bt-none">
                <td class="bt-none" colspan="3"></td>
                <th class="bt-none" >Total :</th>
                <th class="grand-total-top-border"> {{$totalIndoorNEFT}}</th>
            </tr>
        @endif --}}
</table>

@if(count($income) != 0 || count($expense) !=0 )
    <table  class="table m-b-0 table-hover font">
        <thead>
            <tr class="thead">
                <th>Sr No</th>
                <th>Date</th>
                <th>Payment</th>
                <th>Category</th>
                <th>Given For</th>
                <th>Notes</th>
                <th>Amount</th>
                <th class="expense-icon">Txt_Amount</th>
                <th class="expense-icon">Invoice No</th>
                <th class="expense-icon">Bank</th>
                <th class="expense-icon">Detail</th>
                <th class="expense-icon"></th>
                <th>Sr No</th>
                <th>Date</th>
                <th>Payment</th>
                <th>Category</th>
                <th>Given For</th>
                <th>Notes</th>
                <th>Amount</th>
                <th class="expense-icon">Txt_Amount</th>
                <th class="expense-icon">Invoice No</th>
                <th class="expense-icon">Bank</th>
                <th class="expense-icon">Detail</th>
                <th class="expense-icon"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $number = $totalIncome = $totalIncomeLeft = $totalIncomeRight = 0;
                $count = count($income);
            @endphp
            @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Income</th>
            </tr>

                @foreach ($income as $key => $value)
                    @php
                        if ($key > 0) {
                            $number++;
                        }

                        if ($loop->iteration >= 3 && $count > 8) {
                            $loop->iteration = $loop->iteration + ($loop->iteration - 1);
                        }

                        if ($loop->iteration > 1 && $count > 8 && $loop->iteration < 3) {
                            $loop->iteration += 1;
                        }
                    @endphp
                    @if ($number < $count)
                        <tr>
                            @php
                                $totalIncomeLeft +=  $income[$number]->amount;
                            @endphp
                            <td>{{ ((($income->currentPage() - 1 ) * $income->perPage()) + $loop->iteration) . '.' }}</td>
                            <td>{{ cdate($income[$number]->date)->format('d-m-Y')}}</td>
                            <td>{{$income[$number]->payment_mode}}</td>
                            <td>{{$income[$number]['getExpenseCategory']->name}}</td>
                            <td>{{strtoupper($income[$number]->given_by)}}</td>
                            <td>{{$income[$number]->note}}</td>
                            <td>
                                <div class="amount">
                                    {{$income[$number]->amount}}
                                </div>
                            </td>
                            <td class="expense-icon">{{$income[$number]->txt_amount}}</td>
                            <td class="expense-icon">{{$income[$number]->invoice_no}}</td>
                            <td class="expense-icon">{{(isset($bank_details[$income[$number]->bank_id])) ? $bank_details[$income[$number]->bank_id] : ''}}</td>
                            <td class="expense-icon">{{$income[$number]->detail}}</td>
                            <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$income[$number]->id}}" data-class="IncomeManager">edit</i></td>

                            @php
                                if ($count > 8) {
                                    $number++;
                                }
                            @endphp
                            @if ($count > 8 && $number < $count)
                                @php
                                    $totalIncomeRight +=  $income[$number]->amount;
                                @endphp
                                <td>{{ ((($income->currentPage() - 1 ) * $income->perPage() ) + $loop->iteration + 1) . '.' }}</td>
                                <td>{{ cdate($income[$number]->date)->format('d-m-Y')}}</td>
                                <td>{{$income[$number]->payment_mode}}</td>
                                <td>{{$income[$number]['getExpenseCategory']->name}}</td>
                                <td>{{strtoupper($income[$number]->given_by)}}</td>
                                <td>{{$income[$number]->note}}</td>
                                <td>{{$income[$number]->amount}}</td>
                                <td class="expense-icon">{{$income[$number]->txt_amount}}</td>
                                <td class="expense-icon">{{$income[$number]->invoice_no}}</td>
                                <td class="expense-icon">{{(isset($bank_details[$income[$number]->bank_id])) ? $bank_details[$income[$number]->bank_id] : ''}}</td>
                                <td class="expense-icon">{{$income[$number]->detail}}</td>
                                <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$income[$number]->id}}" data-class="IncomeManager">edit</i></td>

                            @endif
                        </tr>
                    @endif
                @endforeach
                @php
                    $totalIncome = $totalIncomeLeft + $totalIncomeRight;
                @endphp
                <tr class="bt-none">
                    <td class="bt-none" colspan="4"></td>
                    <th class="bt-none" >Total :</th>
                    <th class="grand-total-top-border">{{ $incomeGrandTotal }} </th>
                    <td class="bt-none" colspan='7'>
                        {{$income->links()}}
                    </td>
                </tr>
            {{-- @else
                <td colspan='12' class="text-center">No records available</td> --}}
            @endif
            @php
            $number = $totalExpense = $totalExpenseLeft = $totalExpenseRight = 0;
            $count = count($expense);
        @endphp
        @if ($count > 0)
            <tr>
                <th colspan="24" class="sub-headline">Expense</th>
            </tr>

                @foreach ($expense as $key => $value)
                    @php
                        if ($key > 0) {
                            $number++;
                        }
                        if ($loop->iteration >= 3 && $count > 8) {
                            $loop->iteration = $loop->iteration + ($loop->iteration - 1);
                        }
                        if ($loop->iteration > 1 && $count > 8 && $loop->iteration < 3) {
                            $loop->iteration += 1;
                        }
                    @endphp
                    @if ($number < $count)
                        <tr>
                            @php
                                $totalExpenseLeft += $expense[$number]->amount;
                            @endphp
                            <td>{{ ((($expense->currentPage() - 1 ) * $expense->perPage()) + $loop->iteration) . '.' }}</td>
                            <td>{{ cdate($expense[$number]->date)->format('d-m-Y')}}</td>
                            <td>{{$expense[$number]->payment_mode}}</td>
                            <td>{{$expense[$number]['getExpenseCategory']->name}}</td>
                            <td>{{strtoupper($expense[$number]->given_for)}}</td>
                            <td>{{$expense[$number]->note}}</td>
                            <td>
                                <div class="amount">
                                    {{$expense[$number]->amount}}
                                </div>
                            </td>
                            <td class="expense-icon">{{$expense[$number]->txt_amount}}</td>
                            <td class="expense-icon">{{$expense[$number]->invoice_no}}</td>
                            <td class="expense-icon">{{(isset($bank_details[$expense[$number]->bank_id])) ? $bank_details[$expense[$number]->bank_id] : ''}}</td>
                            <td class="expense-icon">{{$expense[$number]->detail}}</td>
                            <td class="expense-icon"><i class="material-icons expense-icon expense-modal pencil-icon a-time" data-id="{{$expense[$number]->id}}" data-class="ExpenseManager">edit</i></td>

                            @php
                                if($count > 8){
                                    $number++;
                                }
                            @endphp
                            @if($count > 8 && $number < $count)
                                @php
                                    $totalExpenseRight += $expense[$number]->amount;
                                @endphp
                                <td>{{((($expense->currentPage() - 1 ) * $expense->perPage() ) + $loop->iteration + 1) . '.' }}</td>
                                <td>{{cdate($expense[$number]->date)->format('d-m-Y')}}</td>
                                <td>{{$expense[$number]->payment_mode}}</td>
                                <td>{{$expense[$number]['getExpenseCategory']->name}}</td>
                                <td>{{strtoupper($expense[$number]->given_for)}}</td>
                                <td>{{$expense[$number]->note}}</td>
                                <td>
                                    <div class="amount">
                                        {{$expense[$number]->amount}}
                                    </div>
                                </td>
                                <td class="expense-icon">{{$expense[$number]->txt_amount}}</td>
                                <td class="expense-icon">{{$expense[$number]->invoice_no}}</td>
                                <td class="expense-icon">{{(isset($bank_details[$expense[$number]->bank_id])) ? $bank_details[$expense[$number]->bank_id] : ''}}</td>
                                <td class="expense-icon">{{$expense[$number]->detail}}</td>
                                <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$expense[$number]->id}}" data-class="ExpenseManager">edit</i></td>

                            @endif
                        </tr>
                    @endif
                @endforeach
                @php
                    $totalExpense = $totalExpenseLeft + $totalExpenseRight;
                @endphp
                <tr class="bt-none">
                    <td class="bt-none" colspan="4"></td>
                    <th class="bt-none">Total :</th>
                    <th class="grand-total-top-border">
                        <div class="lower-border">
                            {{ $expenseGrandTotal }}
                        </div>
                    </th>
                    <td class="bt-none" colspan='7'>
                        {{$expense->links()}}
                    </td>
                </tr>
            @else
                <td colspan='12' class="text-center">No records available</td>
            @endif
            {{-- @php
                $number = $totalIndoorDebitCashLeft = $totalIndoorDebitCash = $totalIndoorDepositCashLeft = $totalIndoorDebitCashRight = 0;
                $debitCount = count($indoorDebit);
            @endphp
            @if ($debitCount > 0)
                <tr>
                    <th colspan="24" class="sub-headline">Debit</th>
                </tr>
                @foreach ($indoorDebit as $key => $value)
                    @php
                        if ($key > 0) {
                            $number++;
                        }
                    @endphp
                    @if ($number < $debitCount)
                        <tr>
                            <td>{{ ($j = $number + 1) . '.'}}</td>
                            <td>{{ cdate($indoorDebit[$number]['created_at'])->format('d-m-Y')}}</td>
                            <td>{{ strtoupper($indoorDebit[$number]['get_patients']['name']) }}</td>
                            <td>
                                @php
                                    $patientProcedure = explode(',', $indoorDebit[$number]['procedure_id']);
                                    foreach ($procedures as $key => $value) {
                                        if (in_array($value['id'], $patientProcedure)) {
                                            echo $value['name'] . '<br /> ';
                                        }
                                    }
                                @endphp

                            </td>
                            <td>
                                <div class="amount">
                                    @php
                                        echo $indoorDebit[$number]['amount'];
                                        $totalIndoorDebitCashLeft += $indoorDebit[$number]['amount'];
                                    @endphp
                                </div>
                            </td>
                            @php
                                if ($debitCount > 8) {
                                    $number++;
                                }
                            @endphp
                            @if ($debitCount > 8 && $number < $debitCount)
                                <td>{{ ($j = $number + 1) . '.'}}</td>
                                <td>{{ cdate($indoorDebit[$number]['created_at'])->format('d-m-Y')}}</td>
                                <td>{{ strtoupper($indoorDebit[$number]['get_patients']['name']) }}</td>
                                <td>
                                    @php
                                        $patientProcedure = explode(',', $indoorDebit[$number]['procedure_id']);
                                        foreach ($procedures as $key => $value) {
                                            if (in_array($value['id'], $patientProcedure)) {
                                                echo $value['name'] . '<br /> ';
                                            }
                                        }
                                    @endphp
                                </td>
                                <td>
                                    <div class="amount">
                                        @php
                                            echo $indoorDebit[$number]['amount'];
                                            $totalIndoorDebitCashRight += $indoorDebit[$number]['amount'];
                                        @endphp
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endif
                @endforeach
                @php
                    $totalIndoorDebitCash = $totalIndoorDebitCashLeft + $totalIndoorDebitCashRight;
                @endphp
                <tr class="bt-none">
                    <td class="bt-none" colspan="3"></td>
                    <th class="bt-none" >Total :</th>
                    <th class="grand-total-top-border"> {{$totalIndoorDebitCash}}</th>
                </tr>
            @endif --}}
        </tbody>
    </table>
@endif
<table class="table m-b-0 table-hover grand-total" style="width:40%;">
    <?php $totalOpd = $totalUsg + $totalHormon + $totalIui + $totalIvf + $incomeGrandTotal + $totalIvfCash + $totalIuiCash + $totalAncCash + $totalIndoorCash + $totalIndoorDepositCash + $totalGynecCash; ?>
    <tr class="bt-none">
        <th class="bt-none">OPD Total</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalOpd }}</th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">Total</th>
        <th class="bt-none">:</th>
        <th class="top-border-first total-upper-border text-right">{{ $totalOpd }}</th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">Expense Total </th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right"> {{ $expenseGrandTotal }} </th>
    </tr>
    {{-- <tr class="bt-none">
        <th class="bt-none">Debit Total </th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right"> {{ $totalIndoorDebitCash }} </th>
    </tr> --}}
    <tr class="bt-none">
        <th class="bt-none">Grand Total</th>
        <th class="bt-none">:</th>
        <th class="top-border-first text-right"> {{ $totalOpd - $expenseGrandTotal}} </th>
    </tr>
</table>
