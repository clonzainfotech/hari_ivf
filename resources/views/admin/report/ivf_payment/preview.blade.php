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
        -webkit-print-color-adjust: exact;
    }
    
    tr td th {
        padding: 12px 12px;
    }
    </style>
    <table class="table m-b-0 table-hover referene-doctor-list" id="reference-report-table" cellspacing="0">
        <thead>
            <tr>
                <th colspan="5">{{strtoupper(config('app.hospitalname1'))}}</th>
            </tr>
        </thead>
    </table>
    <table class="table m-b-0 table-hover reference-report-table" id="reference-report-table" cellspacing="0">
        <?php 
            $i = 1;
            $paymentTypeData = ['1'=>'Swipe','2'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'];
        ?>
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Name</th>
                <th class="report-header-tr-th">Cycle No</th>
                <th class="report-header-tr-th">Payment</th>
                <th class="report-header-tr-th">Package</th>
                <th class="report-header-tr-th">Payment Type</th>
                <th class="report-header-tr-th">Time</th>
                <th class="report-header-tr-th">Condition</th>
                <th class="report-header-tr-th">Is Completed</th>
                <th class="report-header-tr-th">Date</th>
            </tr>
        </thead>
            <tbody>
            @forelse($ivfPayment as $row)
                <tr>
                    <td class="data-font seperator">{{($i++).'.'}}</td>
                    <td class="data-font seperator">{{$row->patient_name}}</td>
                    <td class="data-font seperator">{{$row->cycle_no}}</td>
                    <td class="data-font seperator">{{$row->payment}}</td>
                    <td class="data-font seperator">{{$row->package}}</td>
                    <td class="data-font seperator">{{ucfirst(!empty($row->payment_type) ? $paymentTypeData[$row->payment_type] : null)}}</td>
                    <td class="data-font seperator">{{$row->time}}</td>
                    <td class="data-font seperator">{{$row->condition}}</td>
                    <td class="data-font seperator">{{$row->is_completed == 0 ? 'No' : 'Yes'}}</td>
                    <td class="data-font seperator">{{$row->created_at->format('d-m-Y')}}</td>
                </tr>
            @empty
                <td colspan="11" class="text-center">No records available</td>
            @endforelse
        </tbody>
    </table>
    