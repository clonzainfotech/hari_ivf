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
        ?>
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Name</th>
                <th class="report-header-tr-th">Injection</th>
                <th class="report-header-tr-th">Amount</th>
                <th class="report-header-tr-th">Total</th>
                <th class="report-header-tr-th">Category</th>
                <th class="report-header-tr-th">Reference Doctor Name</th>
                <th class="report-header-tr-th">Payment Type</th>
                <th class="report-header-tr-th">Date Time</th>
            </tr>
        </thead>
            <tbody>
            @forelse($hormon as $row)
                <tr>
                    <td class="data-font seperator">{{ ($i++) . '.' }}</td>
                    <td class="data-font seperator">{{ucwords(strtolower($row->getPatients['name']))}}</td>
                    <td class="data-font seperator">{{$row->injection}}</td>
                    <td class="data-font seperator">{{$row->amount}}</td>
                    <td class="data-font seperator">{{$row->total}}</td>
                    <td class="data-font seperator">
                        @if($row->charge_type == 1)
                            Hormon
                        @elseif ($row->charge_type == 2)
                            IVF
                        @elseif ($row->charge_type == 3)
                            IUI
                        @endif
                    </td>
                    <td class="data-font seperator">{{$row->reDrName['name']}}</td>
                    <td class="data-font seperator">{{$row->case_type}}</td>
                    <td class="data-font seperator">{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A') }}</td>
                </tr>
            @empty
                <td colspan="9" class="text-center">No records available</td>
            @endforelse
        </tbody>
    </table>
    