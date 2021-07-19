<!-- usg -->
        @php 
            $count = count($usg);
            $monthUsgArray = [];
            $totalUsg = 0;
            $totalOpd = 0;
            $totalIpd = 0;
            $totalIncome = 0;
            $totalExpense = 0;
        @endphp
        @if ($count > 0)
            @foreach ($usg as $key => $value)
                @php 
                   
                    $date = \Carbon\Carbon::parse($usg[$key]['date'])->format('d-m-Y');
                    if(isset($monthUsgArray[$date]['usg']))
                    {
                        $monthUsgArray[$date]['usg'] += $usg[$key]['getAppointmentCharges']['usg'];
                    }
                    else{
                        $monthUsgArray[$date]['usg'] = $usg[$key]['getAppointmentCharges']['usg'];
                    }
                    $totalUsg += $usg[$key]['getAppointmentCharges']['usg'];
                @endphp
            @endforeach
        @endif
        <!-- //hormon -->
        @php 
            $number = $totalHormon = $totalHormonLeft = $totalHormonRight = 0;
            $count = count($hormon);
            $totalHormon = 0;
        @endphp
        @if ($count > 0)
            @foreach ($hormon as $key => $value)
                @php 
                    $date = \Carbon\Carbon::parse($hormon[$key]['created_at'])->format('d-m-Y');
                    if(isset($monthUsgArray[$date]['hormon']))
                    {
                        $monthUsgArray[$date]['hormon'] += $hormon[$key]['amount'];
                    }
                    else{
                        $monthUsgArray[$date]['hormon'] = $hormon[$key]['amount'];
                    }
                    $totalHormon += $hormon[$key]['amount'];
                @endphp
                
            @endforeach
        @endif
        @php 
        $number = $totalIui = $totalIuiLeft = $totalIuiRight = 0;
        $count = count($iui);
        $totalIui = 0;
    @endphp
    <!-- //iui -->
    @if ($count > 0)
        @foreach ($iui as $key => $value)
            @php
               
                $date = \Carbon\Carbon::parse($iui[$key]['created_at'])->format('d-m-Y');
                if(isset($monthUsgArray[$date]['iui']))
                {
                    $monthUsgArray[$date]['iui'] += $iui[$key]['amount'];
                }
                else{
                    $monthUsgArray[$date]['iui'] = $iui[$key]['amount'];
                }
                $totalIui +=  $iui[$key]['amount'];
            @endphp
        @endforeach
    @endif
    <!-- //ivf -->
    @php 
        $count = count($ivf);
        $totalIvf = 0;
    @endphp
    @if ($count > 0)
    
        @foreach($ivf as $key => $value)
            @php 
                $date = \Carbon\Carbon::parse($ivf[$key]['created_at'])->format('d-m-Y');
                if(isset($monthUsgArray[$date]['ivf']))
                {
                    $monthUsgArray[$date]['ivf'] += $ivf[$key]['amount'];
                }
                else{
                    $monthUsgArray[$date]['ivf'] = $ivf[$key]['amount'];
                }
                $totalIvf += $ivf[$key]['amount'];
            @endphp
        @endforeach
    @endif
    <!-- //opd -->
    @if(!empty($ivfCash))
        @foreach($ivfCash as $key => $value)
            @php
                if(isset($ivfCash[$key]))
                {
                    $date = \Carbon\Carbon::parse($ivfCash[$key]['date'])->format('d-m-Y');
                    if(isset($monthUsgArray[$date]['opd']))
                    {
                        $monthUsgArray[$date]['opd'] += $ivfCash[$key]['get_appointment_charges']['total'] - $ivfCash[$key]['get_appointment_charges']['usg'];
                    }
                    else{
                        $monthUsgArray[$date]['opd'] = $ivfCash[$key]['get_appointment_charges']['total']- $ivfCash[$key]['get_appointment_charges']['usg'];
                    }
                    $totalOpd += $ivfCash[$key]['get_appointment_charges']['total'] - $ivfCash[$key]['get_appointment_charges']['usg'];
                }
            @endphp
        @endforeach
    @endif
    @if(!empty($iuiCash))
        @foreach($iuiCash as $key => $value)
            @php
                if(isset($iuiCash[$key]))
                {
                $date = \Carbon\Carbon::parse($iuiCash[$key]['date'])->format('d-m-Y');

                    if(isset($monthUsgArray[$date]['opd']))
                    {
                        $monthUsgArray[$date]['opd'] += $iuiCash[$key]['get_appointment_charges']['total'] - $iuiCash[$key]['get_appointment_charges']['usg'];
                    }
                    else{
                        $monthUsgArray[$date]['opd'] = $iuiCash[$key]['get_appointment_charges']['total']- $iuiCash[$key]['get_appointment_charges']['usg'];
                    }
                    $totalOpd += $iuiCash[$key]['get_appointment_charges']['total'] - $iuiCash[$key]['get_appointment_charges']['usg'];
                }
            @endphp
        @endforeach
    @endif
    @if(!empty($ancCash))
        @foreach($ancCash as $key => $value)
            @php
                if(isset($ancCash[$key]))
                {
                $date = \Carbon\Carbon::parse($ancCash[$key]['date'])->format('d-m-Y');

                    if(isset($monthUsgArray[$date]['opd']))
                    {
                        $monthUsgArray[$date]['opd'] += $ancCash[$key]['get_appointment_charges']['total'] - $ancCash[$key]['get_appointment_charges']['usg'];
                    }
                    else{
                        $monthUsgArray[$date]['opd'] = $ancCash[$key]['get_appointment_charges']['total']- $ancCash[$key]['get_appointment_charges']['usg'];
                    }
                    $totalOpd +=  $ancCash[$key]['get_appointment_charges']['total'] - $ancCash[$key]['get_appointment_charges']['usg'];
                }
            @endphp
        @endforeach
    @endif
    @if(!empty($gynecCash))
        @foreach($gynecCash as $key => $value)
            @php
                if(isset($gynecCash[$key]))
                {
                $date = \Carbon\Carbon::parse($gynecCash[$key]['date'])->format('d-m-Y');

                    if(isset($monthUsgArray[$date]['opd']))
                    {
                        $monthUsgArray[$date]['opd'] += $gynecCash[$key]['get_appointment_charges']['total'] - $gynecCash[$key]['get_appointment_charges']['usg'];
                    }
                    else{
                        $monthUsgArray[$date]['opd'] = $gynecCash[$key]['get_appointment_charges']['total']- $gynecCash[$key]['get_appointment_charges']['usg'];
                    }
                    $totalOpd += $gynecCash[$key]['get_appointment_charges']['total'] - $gynecCash[$key]['get_appointment_charges']['usg'];
                }
            @endphp
        @endforeach
    @endif
     <!-- //ipd   -->
    @php
    $count = count($indoorCash);
    @endphp
    @if ($count > 0)
        @foreach ($indoorCash as $key => $value)
            @php
                
                if(isset($indoorCash[$key]) && !empty($indoorCash[$key]['final_invoice_date']))
                {
                    $date = \Carbon\Carbon::parse($indoorCash[$key]['final_invoice_date'])->format('d-m-Y');

                    if(isset($monthUsgArray[$date]['ipd']))
                    {
                        $monthUsgArray[$date]['ipd'] += $indoorCash[$key]['get_invoice']['grand_total_amt'];
                    }
                    else{
                        $monthUsgArray[$date]['ipd'] = $indoorCash[$key]['get_invoice']['grand_total_amt'];
                    }
                    $totalIpd +=  $indoorCash[$key]['get_invoice']['grand_total_amt'];
                }
            @endphp
                
        @endforeach
    @endif
    @php
        $count = count($indoorCaseDeposit);
    @endphp
    @if ($count > 0)
        @foreach ($indoorCaseDeposit as $key => $value)
            @php
                
                if(isset($indoorCaseDeposit[$key]))
                {
                    $date = \Carbon\Carbon::parse($indoorCaseDeposit[$key]['created_at'])->format('d-m-Y');

                    if(isset($monthUsgArray[$date]['ipd']))
                    {
                        $monthUsgArray[$date]['ipd'] += $indoorCaseDeposit[$key]['amount'];
                    }
                    else{
                        $monthUsgArray[$date]['ipd'] = $indoorCaseDeposit[$key]['amount'];
                    }
                    $totalIpd += $indoorCaseDeposit[$key]['amount'];
                }
            @endphp
            
        @endforeach
    @endif

        <!-- income & expense -->
    @if(count($income) != 0 || count($expense) !=0 )
                @php 
                    $number = $totalIncome = $totalIncomeLeft = $totalIncomeRight = 0;
                    $count = count($income);
                @endphp
                @if ($count > 0)
                    @foreach ($income as $key => $value)
                        @php 
                            
                            $date = \Carbon\Carbon::parse($income[$key]['date'])->format('d-m-Y');

                            if(isset($monthUsgArray[$date]['income']))
                            {
                                $monthUsgArray[$date]['income'] += $income[$key]['amount'];
                            }
                            else{
                                $monthUsgArray[$date]['income'] = $income[$key]['amount'];
                            }
                            $totalIncome += $income[$key]['amount'];
                        @endphp
                    @endforeach
                @endif
            @php 
                $count = count($expense);
            @endphp
            @if ($count > 0)
                    @foreach ($expense as $key => $value)
                        @php 
                            $date = \Carbon\Carbon::parse($expense[$key]['date'])->format('d-m-Y');
                            if(isset($monthUsgArray[$date]['expense']))
                            {
                                $monthUsgArray[$date]['expense'] += $expense[$key]['amount'];
                            }
                            else{
                                $monthUsgArray[$date]['expense'] = $expense[$key]['amount'];
                            }
                            $totalExpense += $expense[$key]['amount'];
                        @endphp
                    @endforeach
                @endif
    @endif
<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>USG</th>
            <th>H.INJ</th>
            <th>IUI</th>
            <th>IVF</th>
            <th>OPD</th>
            <th>IPD</th>
            <th>O.INCOME</th>
            <th>EXPENSE</th>
        </tr>
    </thead>
    <tbody>
        @php
        ksort($monthUsgArray);
        @endphp
        @foreach($monthUsgArray as $key => $value)
        @php
        @endphp
        <tr>
            <td>{{$key}}</td>
            <td>{{isset($value['usg']) ? $value['usg'] : '-'}}</td>
            <td>{{isset($value['hormon']) ? $value['hormon'] : '-'}}</td>
            <td>{{isset($value['iui']) ? $value['iui'] : '-'}}</td>
            <td>{{isset($value['ivf']) ? $value['ivf'] : '-'}}</td>
            <td>{{isset($value['opd']) ? $value['opd'] : '-'}}</td>
            <td>{{isset($value['ipd']) ? $value['ipd'] : '-'}}</td>
            <td>{{isset($value['income']) ? $value['income'] : '-'}}</td>
            <td>{{isset($value['expense']) ? $value['expense'] : '-'}}</td>
        </tr>
        @endforeach
    
    </tbody>
        
</table>
<table class="table m-b-0 table-hover all_type_payment grand-total" style="width:40%;">
    <?php $totalGrandIncome = $totalUsg + $totalHormon + $totalIui + $totalIvf + $totalOpd + $totalIpd + $totalIncome; ?>
    <tr class="bt-none">
        <th class="bt-none">USG</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalUsg }}</th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">H.Inj</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalHormon }}</th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">IUI</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalIui}}</th>
    </tr><tr class="bt-none">
        <th class="bt-none">IVF</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalIvf }}</th>
    </tr>
    </tr><tr class="bt-none">
        <th class="bt-none">OPD</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalOpd }}</th>
    </tr>
    </tr><tr class="bt-none">
        <th class="bt-none">IPD</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalIpd }}</th>
    </tr>
    </tr><tr class="bt-none">
        <th class="bt-none">O.Income</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalIncome}}</th>
    </tr>
    </tr><tr class="bt-none">
        <th class="bt-none">Total Income</th>
        <th class="bt-none">:</th>
        <th class="top-border-first total-upper-border text-right">{{ $totalGrandIncome}}</th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">Total Expense </th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right"> {{ $totalExpense }} </th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">Grand Total</th>
        <th class="bt-none">:</th>
        <th class="top-border-first text-right"> {{ $totalGrandIncome - $totalExpense}} </th>
    </tr>
</table>
