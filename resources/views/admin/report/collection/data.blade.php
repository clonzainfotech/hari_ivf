<table class="table m-b-0 table-hover font">
    <thead>
        <tr>
            <th colspan="12" class="text-center headline">OPD</th>
        </tr>
        <tr class="thead">
            <th>Sr No</th>
            <th>Date</th>
            <th>Patient Name</th>
            <th>Category</th>
            <th>Amount</th>
            <th>Sr No</th>
            <th>Date</th>
            <th>Patient Name</th>
            <th>Category</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php 
            $number = $totalUsg = $totalUsgLeft = $totalUsgRight = 0;
            $count = count($usg);
        @endphp
        @if ($count > 0)
        <tr>
            <th colspan="12" class="sub-headline">USG</th>
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
            <th colspan="12" class="sub-headline">Hormon</th>
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
            <th colspan="12" class="sub-headline">IUI</th>
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
            <th colspan="12" class="sub-headline">IVF</th>
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
                <th colspan="12" class="sub-headline">{{config('app.doctor') }}</th>
            </tr>
        @endif
        @if(!empty($ivfCash))
            <tr>
                <th colspan="12" class="sub-headline">IVF</th>
            </tr>
            @include('admin.report.collection.collection_data', [
                'data' => $ivfCash
            ])
        @endif
            @php
                $totalIvfCash = (Session::get('total') != null) ? Session::get('total') : 0;
                Session::forget('total');
            @endphp
        @if(!empty($iuiCash))
            <tr>
                <th colspan="12" class="sub-headline">INF</th>
            </tr>
            @include('admin.report.collection.collection_data', [
                'data' => $iuiCash
            ])
        @endif
            @php
                $totalIuiCash = (Session::get('total') != null) ? Session::get('total') : 0;
                Session::forget('total');
            @endphp
        @if(!empty($ancCash))
            <tr>
                <th colspan="12" class="sub-headline">ANC</th>
            </tr>
            @include('admin.report.collection.collection_data', [
                'data' => $ancCash
            ])
        @endif
            @php
                $totalAncCash = (Session::get('total') != null) ? Session::get('total') : 0;
                Session::forget('total');
            @endphp
        @if(!empty($gynecCash))
            <tr>
                <th colspan="12" class="sub-headline">Gynec</th>
            </tr>
            @include('admin.report.collection.collection_data', [
                'data' => $gynecCash
            ])
        @endif
            @php
                $totalGynecCash = (Session::get('total') != null) ? Session::get('total') : 0;
                Session::forget('total');
            @endphp
        

        @php
        $number = $totalIndoorCash = $totalIndoorCashLeft = $totalIndoorCashRight = 0;
        $count = count($indoorCash);
    @endphp
    @if ($count > 0)
        <tr>
            <th colspan="12" class="sub-headline">Indoor</th>
        </tr>
       
            <tr>
                <th colspan="12" class="sub-headline">Invoices</th>
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
                <th colspan="12" class="sub-headline">Deposit</th>
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
                <th>Sr No</th>
                <th>Date</th>
                <th>Payment</th>
                <th>Category</th>
                <th>Given For</th>
                <th>Notes</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $number = $totalIncome = $totalIncomeLeft = $totalIncomeRight = 0;
                $count = count($income);
            @endphp
            @if ($count > 0)
            <tr>
                <th colspan="12" class="sub-headline">Income</th>
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
                <th colspan="12" class="sub-headline">Expense</th>
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
            
        </tbody>
    </table>
@endif
<div class="row">
    <div class="col-md-6">
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
            
            <tr class="bt-none">
                <th class="bt-none">Grand Total</th>
                <th class="bt-none">:</th>
                <th class="top-border-first text-right"> {{ $totalOpd - $expenseGrandTotal}} </th>
            </tr>
        </table>
    </div>

    <div class="col-md-3">
        <table class="table m-b-0 table-hover all_type_payment grand-total" style="width:40%;">
            <tr class="bt-none">
                <th class="bt-none">New Collection Income</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $totalOpd }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Pediatric Total Income</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $pediatric_income }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Medicare Total Income</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $medicare_income }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Total Income</th>
                <th class="bt-none">:</th>
                <th class="top-border-first text-right"> {{ $totalOpd + $pediatric_income + $medicare_income}} </th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Total Expense</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $expenseGrandTotal + $pediatric_expense + $medicare_expense }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Grand Total</th>
                <th class="bt-none">:</th>
                <th class="top-border-first text-right"> {{($totalOpd + $pediatric_income + $medicare_income) - ($expenseGrandTotal + $pediatric_expense + $medicare_expense)}} </th>
            </tr>
        </table>
    </div> 
    <div class="col-md-3">
        <table class="table m-b-0 table-hover all_type_payment grand-total" style="width:40%;">
            <tr class="bt-none">
                <th class="bt-none">New Collection Expense</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $expenseGrandTotal }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Pediatric Total Expense</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $pediatric_expense }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Medicare Total Expense</th>
                <th class="bt-none">:</th>
                <th class="total-upper-border text-right">{{ $medicare_expense }}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Total Expense</th>
                <th class="bt-none">:</th>
                <th class="top-border-first text-right"> {{ $expenseGrandTotal + $pediatric_expense + $medicare_expense}} </th>
            </tr>
        </table>
    </div> 
</div>