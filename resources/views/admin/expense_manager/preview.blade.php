<style type="text/css">
    .referene-doctor-list, .reference-report-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .referene-doctor-list{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
    }
    .reference-report-table{
        text-align: left;
    }
    .referene-doctor-list tr{
        height: 50px;
        font-size: 20px;
    }
    .reference-report-table thead th{
        height: 35px;
    }
    .reference-report-table thead th span{
        border-bottom: 1px solid #000000;
    }
    .reference-report-table tr {
        height: 27px;
    }
    .table-footer{
        font-weight: 900;
        color: #01d8da;
        height: 50px;
        font-size: 20px;
    }
    td{
        height: 25px;
        font-size: 14px;
    }
    .upper-border {
        border-top: 1px solid #000000;
    }
    .report-header-tr {
        text-align: left;
        height: 35px;
    }
    .report-header-tr-th {
        background-color: #c7dfe0;
        font-size: 13px;
    }
    .amount {
        font-weight: 600;
    }
    .text-center {
        text-align: center;
    }
    .data-font {
        font-size: 11px;
    }
    
    .td-padding {
        padding: 12px 12px;
    }
    .sub-heading {
        font-size: 13px;
    }
    
    .seperator {
        border-top: 0.5px solid #dee2e6;
    }
    
    tr td th {
        padding: 12px 12px;
    }
    .response{
        word-break: break-all !important;
        width:  230px !important;
    }
    </style>
    @php
        $total = 0;
    @endphp
    <table class="table m-b-0 table-hover referene-doctor-list" id="reference-report-table" cellspacing="0">
        <thead>
            <tr>
                <th colspan="9">{{strtoupper(config('app.hospitalname1'))}}</th>
            </tr>
        </thead>
    </table>
    <table class="table m-b-0 table-hover reference-report-table" id="reference-report-table" cellspacing="0">
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Expense Date</th>
                <th class="report-header-tr-th">Amount</th>
                <th class="report-header-tr-th">Given For</th>
                <th class="report-header-tr-th">Notes</th>
            </tr>
        </thead>
            <tbody>
            @forelse($expense as $key=>$row)
                <?php 
                    $i = 1;
                ?>
                <tr>
                    <th colspan="12" class="sub-heading">{{$key}}</th>
                </tr>
                @foreach($row as $item)
                @php
                    $total = $total + $item->amount;
                @endphp
                    <tr>
                        <td class="data-font seperator">{{($i++).'.'}}</td>
                        <td class="data-font seperator">{{cdate($item->date)->format('d-m-Y')}}</td>
                        <td class="data-font seperator">{{$item->amount}}</td>
                        <td class="data-font seperator">{{$item->given_for}}</td>
                        <td class="data-font seperator"><div class="response">{{$item->note}}</div></td> 
                    </tr>
                @endforeach
            @empty
                <td colspan="4" class="text-center">No records available</td>
                <tr>
                    <td colspan=""></td>
                    <td class="font-bold">Total : </td>
                    <td class="font-bold">{{$total}}</td>
                    <td colspan="2"></td>
                </tr>
            @endforelse
        </tbody>
    </table>
    