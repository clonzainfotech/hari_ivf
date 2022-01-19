<table class="table m-b-0 table-hover font" id="category-report-table">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Patient</th>
            <th>Given For</th>
            <th>Note</th>
            <th>Created By</th>
            <th>Amount</th>

        </tr>
    </thead>
    <?php
        $i = 0;
        $j = 1;
        $totalOpd = 0;
        $totalIpd = 0;
    ?>
    <tbody>
        <tr>
            <td colspan="5"  class="sub-headline">OPD Income</td>
        </tr>
        @forelse($income as $rowlist => $data)
            <tr class="refdocdata">
                <td colspan="8" class="sub-headline">{{ ucWords(strtolower($rowlist))}}</td>
            </tr>
            @php
                $total = 0;
            @endphp
            @foreach($data as $row)
                    <tr>
                        <td>{{$j}}</td>
                        <td>{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                        <td>{{ucWords(strtolower($row->getPatient['name']))}}</td>
                        <td>{{$row->given_by}}</td>
                        <td>{{$row->note}}</td>
                        <td>{{$row->getUser['name']}}</td>
                        <td>{{$row->amount}}</td>
                    </tr>
                    @php
                        $j++;
                        $total +=  $row->amount;
                        $totalOpd += $row->amount;
                    @endphp
            @endforeach
            @php
                $i++;
            @endphp
            <tr>
                <td colspan="6"></td>
                <td class="sub-headline upper-border">{{$total}}</td>

            </tr>
        @empty
            <td colspan="7" class="text-center">No records available</td>
        @endforelse
        <tr>
            <td colspan="5"  class="sub-headline">IPD Income</td>
        </tr>
        @forelse($indoorBook as $rowlist => $data)
            <tr>
                <td>{{$j}}</td>
                <td>{{\Carbon\Carbon::parse($data->date)->format('d-m-Y')}}</td>
                <td>{{ucWords(strtolower($data->getPatientsDetails['name']))}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$data->getInvoice['grand_total_amt']}}</td>
            </tr>
            @php
                $j++;
                $totalIpd += $data->getInvoice['grand_total_amt'];
            @endphp
        @empty
            <td colspan="7" class="text-center">No records available</td>

        @endforelse
        <tr>
            <td colspan="6"></td>
            <td class="sub-headline upper-border">{{$totalIpd}}</td>
        </tr>
    </tbody>
</table>
<table class="table m-b-0 table-hover font" id="category-report-table">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Patient</th>
            <th>Given For</th>
            <th>Note</th>
            <th>Created By</th>
            <th>Amount</th>

        </tr>
    </thead>
    <?php
        $i = 0;
        $j = 1;
        $totalExpense = 0;
    ?>
    <tbody>
        @forelse($expense as $rowlist => $data)
            <tr class="refdocdata">
                <td colspan="8" class="sub-headline">{{ ucWords(strtolower($rowlist))}}</td>
            </tr>
            @php
                $total = 0;
            @endphp
            @foreach($data as $row)
                    <tr>
                        <td>{{$j}}</td>
                        <td>{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                        <td>{{ucWords(strtolower($row->getPatient['name']))}}</td>
                        <td>{{$row->given_by}}</td>
                        <td>{{$row->note}}</td>
                        <td>{{$row->getUser['name']}}</td>
                        <td>{{$row->amount}}</td>
                    </tr>
                    @php
                        $j++;
                        $total +=  $row->amount;
                        $totalExpense += $row->amount;
                    @endphp
                {{-- @endforeach --}}
            @endforeach
            @php
                $i++;
            @endphp
            <tr>
                <td colspan="6"></td>
                <td class="sub-headline upper-border">{{$total}}</td>
            </tr>
        @empty
            <td colspan="7" class="text-center">No records available</td>
        @endforelse
    </tbody>
</table>
<table class="table m-b-0 table-hover grand-total" style="width:40%;">
    <tr class="bt-none">
        <th class="bt-none">OPD Total</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalOpd }}</th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">IPD Total</th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right">{{ $totalIpd }}</th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">Total</th>
        <th class="bt-none">:</th>
        <th class="top-border-first total-upper-border text-right">{{ $totalOpd + $totalIpd }}</th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">Expense Total </th>
        <th class="bt-none">:</th>
        <th class="total-upper-border text-right"> {{ $totalExpense }} </th>
    </tr>
    <tr class="bt-none">
        <th class="bt-none">Grand Total</th>
        <th class="bt-none">:</th>
        <th class="top-border-first text-right"> {{ ($totalOpd + $totalIpd) - $totalExpense}} </th>
    </tr>
</table>
