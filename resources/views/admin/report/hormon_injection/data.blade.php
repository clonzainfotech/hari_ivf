<?php
use App\Models\IndoorDeposit;
?>
<table class="table m-b-0 table-hover font" id="category-report-table">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Patient</th>
            <th>Injection</th>
            <th>Cycle No</th>
            <th>Net Price</th>
            <th>Amount</th>
            <th>Profit</th>

        </tr>
    </thead>
    <?php
        $i = 0;
        $j = 1;
        $grandTotal = $totalRecievedAmount = $total = $totalNetAmount = $totalProfit = 0;
    ?>
    <tbody>
        @forelse($injManager as $rowlist => $data)
            <tr class="refdocdata">
                <td colspan="8" class="sub-headline">{{ ucWords(strtolower($rowlist))}}</td>
            </tr>
            @foreach($data as $row)

                @php

                // $date = cdate($data[$i]->created_at)->format('Y-m-d');
                // print_r($row->injection);
                    // $indoorDeposit = IndoorDeposit::select('*',DB::raw('count(injection) as totalInj'),DB::raw('sum(amount) as totalAmount'))->where('charge_type',1)->where(\DB::raw('DATE(created_at)'),$date)->where('patient_id',$data[$i]->getPatients['id'])->groupBy('injection')->get();
                @endphp
                {{-- @foreach($indoorDeposit as $injection) --}}
                    <tr>
                        <td>{{$j}}</td>
                        <td>{{cdate($row->created_at)->format('d-m-Y')}}</td>
                        <td>{{$row->getPatientsDetails['name']}}</td>
                        <td>{{$row->injection.' ('. $row->qty.')'}}</td>
                        <td>{{$row->cycle_no}}</td>
                        <td>{{$row->net_price.' X '.$row->qty.' = '.($row->net_price * $row->qty)}}</td>
                        <td>{{$row->amount}}</td>
                        <td>{{($row->amount - ($row->net_price * $row->qty)) > 0 ? $row->amount - ($row->net_price * $row->qty) : 0}}</td>
                    </tr>
                    @php
                        $j++;
                        $totalProfit += ($row->amount - ($row->net_price * $row->qty)) > 0 ? $row->amount - ($row->net_price * $row->qty) : 0;
                        $total += $row->amount;
                        $totalNetAmount += ($row->net_price * $row->qty);
                    @endphp
                {{-- @endforeach --}}
            @endforeach
            @php
                $i++;
            @endphp
            <tr>
                <td colspan="4"></td>
                <td>Total</td>
                <td class="sub-headline upper-border">{{$totalNetAmount}}</td>
                <td class="sub-headline upper-border">{{$total}}</td>
                <td class="sub-headline upper-border">{{$totalProfit}}</td>
            </tr>
        @empty
            <td colspan="8" class="text-center">No records available</td>
        @endforelse
    </tbody>
</table>
