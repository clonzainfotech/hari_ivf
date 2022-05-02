<table class="table m-b-0 table-hover font" id="category-report-table">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Date</th>
            <th>Patient</th>
            <th>Category</th>
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
        $categoryWiseIncome = [];
    ?>
    <tbody>
        <tr>
            <td colspan="8"  class="sub-headline">OPD Income</td>
        </tr>
        @forelse($income as $rowlist => $data)
            <tr class="refdocdata">
                <td colspan="9" class="sub-headline">{{ ucWords(strtolower($rowlist))}}</td>
            </tr>
            @php
                $total = 0;
            @endphp
            @foreach($data as $row)
                    <tr>
                        <td>{{$j}}</td>
                        <td>{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y')}}</td>
                        <td>{{ucWords(strtolower($row->getPatient['name']))}}</td>
                        <td></td>
                        <td>{{$row->given_by}}</td>
                        <td>{{$row->note}}</td>
                        <td>{{$row->getUser['name']}}</td>
                        <td>{{$row->amount}}</td>
                    </tr>
                    @php
                        $j++;
                        $total +=  $row->amount;
                        $totalOpd += $row->amount;
                        if(isset($categoryWiseIncome[$row->income_category]))
                        {
                            $categoryWiseIncome[$row->income_category] = $total;
                        } 
                        else {
                            $categoryWiseIncome[$row->income_category] = $total;
                        }
                    @endphp
            @endforeach
            @php
                $i++;
            @endphp
            <tr>
                <td colspan="7"></td>
                <td class="sub-headline upper-border">{{$total}}</td>

            </tr>
        @empty
            <td colspan="8" class="text-center">No records available</td>
        @endforelse
        <tr>
            <td colspan="8"  class="sub-headline">IPD Income</td>
        </tr>
        @foreach($indoorCaseDeposit as $rowList => $data)
            <tr>
                <td>{{$j}}</td>
                <td>{{\Carbon\Carbon::parse($data->created_at)->format('d-m-Y')}}</td>
                <td>{{ucWords(strtolower($data->getPatientsDetails['name']))}}</td>
                <td>{{$data->procedure_name}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$data->amount}}</td>
            </tr>
            @php
                $j++;
                $totalIpd += $data->amount;
            @endphp
        @endforeach
        @forelse($indoorBook as $rowlist => $data)
            <tr>
                <td>{{$j}}</td>
                <td>{{\Carbon\Carbon::parse($data->date)->format('d-m-Y')}}</td>
                <td>{{ucWords(strtolower($data->getPatientsDetails['name']))}}</td>
                <td></td>
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
            <td colspan="8" class="text-center">No records available</td>

        @endforelse
        <tr>
            <td colspan="7"></td>
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
                <td colspan="7"></td>
                <td class="sub-headline upper-border">{{$total}}</td>
            </tr>
        @empty
            <td colspan="8" class="text-center">No records available</td>
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
@if($is_display_bill_expense == 1)
    {{Form::open(['class'=>'form month-bill-expense d-flex','method'=>'POST','id'=>'ivf-form'])}}
    <div class="col-md-6">
        <table class="table m-b-0 table-hover grand-total" style="width:40%;">
            @php
                $total_category_amount = 0;
            @endphp
            @if(count($month_billing)  == 0)
                @forelse($categoryWiseIncome as $category => $amount)
                    <tr class="bt-none">
                        {{Form::hidden('income_category[]',$category,['class'=>'input-income_category'])}}
                        {{Form::hidden('category_wise_expense['.$category.']',0,['class'=>'category-wise-expense'.$category])}}
                        <th class="bt-none">{{ isset($incomeCategoryName[$category]) ? $incomeCategoryName[$category] : ''}}</th>
                        <th class="bt-none">:</th>
                        <th class="{{'text-right '.'th-income-'.$amount}}">{{$amount}}</th>
                        <th class="text-right">Expense : <input type="number" class="category-wise-expense" data-id="{{$category}}" data-type="{{'income-'.$amount}}" value="0"></th>
                        <th class="text-right ">Total : <span class="{{'category-income-with-expense '.'income-'.$amount}}">{{$amount}}</span></th>
                    </tr>
                    @php
                        $total_category_amount += $amount;
                    @endphp
                @empty
                    <td colspan="8" class="text-center">No records available</td>
                @endforelse
                <tr class="bt-none">
                    <th class="bt-none">Net Amount</th>
                    <th class="bt-none">:</th>
                    <th class="text-right net-amount"></th>
                    <th class="text-right net-expense-category-wise"></th>
                    <th class="text-right  top-border-first total-upper-border text-right">=<span class="net-amount-category-wise"></span></th>
                </tr>
            @endif
            @if(isset($month_billing) && count($month_billing)  > 0 && count($categoryWiseIncome) > 0)
                @forelse($month_billing as $category)
                    <tr class="bt-none">
                        {{Form::hidden('income_category[]',$category->expense_category,['class'=>'input-income_category'])}}
                        {{Form::hidden('category_wise_expense['.$category->expense_category.']',$category->bill_amount,['class'=>'category-wise-expense'.$category->expense_category])}}
                        <th class="bt-none">{{ isset($incomeCategoryName[$category->expense_category]) ? $incomeCategoryName[$category->expense_category] : ''}}</th>
                        <th class="bt-none">:</th>
                        <th class="{{'text-right '.'th-income-'.$categoryWiseIncome[$category->expense_category]}}">{{$categoryWiseIncome[$category->expense_category]}}</th>
                        <th class="text-right">Expense : <input type="number" class="category-wise-expense" data-id="{{$category->expense_category}}" data-type="{{'income-'.$categoryWiseIncome[$category->expense_category]}}" value="{{$category->bill_amount}}"></th>
                        <th class="text-right ">Total : <span class="{{'category-income-with-expense '.'income-'.$categoryWiseIncome[$category->expense_category]}}">{{$categoryWiseIncome[$category->expense_category]}}</span></th>
                    </tr>
                    @php
                        $total_category_amount += $categoryWiseIncome[$category->expense_category];
                    @endphp
                @empty
                    <td colspan="8" class="text-center">No records available</td>
                @endforelse
                <tr class="bt-none">
                    <th class="bt-none">Net Amount</th>
                    <th class="bt-none">:</th>
                    <th class="text-right net-amount"></th>
                    <th class="text-right">Expense : <span class="net-expense-category-wise"></span></th>
                    <th class="text-right top-border-first total-upper-border text-right">= &nbsp;&nbsp;<span class="net-amount-category-wise"></span></th>
                </tr>
            @endif
        </table>
        <button type="button" class="btn btn-primary expense-bill-apply">Apply</button>
    </div>
    <div class="col-md-3">
        <table class="table m-b-0 table-hover grand-total" style="width:40%;">
            <tr class="bt-none">
                <th class="bt-none">OPD 60% Income</th>
                <th class="bt-none">:</th>
                <th class="bt-none opd-60"></th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">OPD 40% Income</th>
                <th class="bt-none">:</th>
                <th class="bt-none opd-40"></th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Total</th>
                <th class="bt-none">:</th>
                <th class="top-border-first total-upper-border text-right total-opd-60-40"></th>
            </tr>
        </table>  
    </div>
    <div class="col-md-3">
        <table class="table m-b-0 table-hover grand-total" style="width:40%;">
            <tr class="bt-none">
                <th class="bt-none">IPD 60% Income</th>
                <th class="bt-none">:</th>
                <th class="bt-none ipd-60">{{$totalIpd != 0 ? ($totalIpd * 60)/100 : ''}}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">IPD 40% Income</th>
                <th class="bt-none">:</th>
                <th class="bt-none ipd-40">{{$totalIpd != 0 ? ($totalIpd * 40)/100 : ''}}</th>
            </tr>
            <tr class="bt-none">
                <th class="bt-none">Total</th>
                <th class="bt-none">:</th>
                <th class="top-border-first total-upper-border text-right">{{$totalIpd}}</th>
            </tr>
        </table>
    </div>  
    {{Form::close()}}
@endif
<script>
    function netAmountWithCategory()
    {
        var sum = 0;
        $("span.category-income-with-expense").each(function(){
            sum += +$(this).text();
        });
        console.log(sum);
        $(".net-amount").text(sum);
        
    }
    function netExpenseWithCategory()
    {
        var sum = 0;
        $("input.category-wise-expense").each(function(){
            sum += +$(this).val();
            $('.input'+className).val(0);
            var id= $(this).data('id');
            var className = $(this).data('type');
            $('.'+className).text($('.th-'+className).text());
            var amount = parseInt($('.'+className).text() - $(this).val());
            $('.'+className).text(amount);
            $('.category-wise-expense'+id).val($(this).val());
            
        });
        // removeExpenseFromNetAmount();
        $(".net-expense-category-wise").text(sum);
    }
    $(document).on('keyup','.category-wise-expense',function(){
        netExpenseWithCategory();
        removeExpenseFromNetAmount();
    })
    function removeExpenseFromNetAmount()
    {
        $('.net-amount-category-wise').text(parseInt($('.net-amount').text() - $('.net-expense-category-wise').text()))
        var net_amount= $('.net-amount-category-wise').text();
        var opd_60 = (60*parseInt(net_amount)) / 100;
        var opd_40 = (40*parseInt(net_amount)) / 100;
        $('.opd-60').text(opd_60);
        $('.opd-40').text(opd_40);
        $('.total-opd-60-40').text(opd_60+opd_40);

    }
    netAmountWithCategory();
    netExpenseWithCategory();
    removeExpenseFromNetAmount();

</script>
