<?php
use App\Models\IndoorDeposit;
?>
<table class="table m-b-0 table-hover font" id="category-report-table">
    <thead>
        <tr class="thead">
            <th>Sr No</th>
            <th>Date</th>
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
        @forelse($injectionCharge as $rowlist => $data)
            <tr class="refdocdata">
                <td colspan="6" class="sub-headline">{{ ucWords(strtolower($rowlist))}}</td>
            </tr>
            @foreach($data as $row)
            
                @php
                // $date = \Carbon\Carbon::parse($data[$i]->created_at)->format('Y-m-d');
                // print_r($row->injection);
                    // $indoorDeposit = IndoorDeposit::select('*',DB::raw('count(injection) as totalInj'),DB::raw('sum(amount) as totalAmount'))->where('charge_type',1)->where(\DB::raw('DATE(created_at)'),$date)->where('patient_id',$data[$i]->getPatients['id'])->groupBy('injection')->get();
                @endphp
                {{-- @foreach($indoorDeposit as $injection) --}}
                    <tr>
                        <td>{{$j}}</td>
                        <td>{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                        <td>{{$row->getInjectionCharge['name'].' ('. $row->totalInj.')'}}</td>
                        <td>{{$row->cycle_no}}</td>
                        <td>{{$row->net_price.' X '.$row->totalInj.' = '.($row->net_price * $row->totalInj)}}</td>
                        <td>{{$row->getTotalAmount()}}</td>
                        <td>{{($row->getTotalAmount() - ($row->net_price * $row->totalInj)) > 0 ? $row->getTotalAmount() - ($row->net_price * $row->totalInj) : 0}}</td>
                    </tr>
                    @php
                        $j++;
                    @endphp  
                {{-- @endforeach --}}
            @endforeach
            @php
                $i++;
            @endphp
        @empty
            <td colspan="6" class="text-center">No records available</td>
        @endforelse
    </tbody>
</table>
