<style type="text/css">
    .all-list, .all-list-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .all-list{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
    }
    .all-list-table{
        text-align: left;
    }
    .all-list tr{
        height: 50px;
        font-size: 20px;
    }
    .all-list-table thead th{
        height: 35px;
    }
    .all-list-table thead th span{
        border-bottom: 1px solid #000000;
    }
    .all-list-table tr {
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
    .remark{
        word-break: break-all !important;
        width:  230px !important;
    }
    </style>
    <table class="table m-b-0 table-hover all-list" id="all-list-table" cellspacing="0">
        <thead>
            <tr>
                <th colspan="5">{{strtoupper(config('app.hospitalname1'))}}</th>
            </tr>
        </thead>
    </table>
    <table class="table m-b-0 table-hover all-list-table" id="all-list-table" cellspacing="0">
        <?php 
            $i = 1;
        ?>
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Date</th>
                <th class="report-header-tr-th">Time</th>
                <th class="report-header-tr-th">Code</th>
                <th class="report-header-tr-th">Name</th>
                <th class="report-header-tr-th">Arrival Time</th>
                <th class="report-header-tr-th">Category</th>
                <th class="report-header-tr-th">Mobile Number</th>
                <th class="report-header-tr-th">Remark</th>
            </tr>
        </thead>
            <tbody>
            @forelse($appointment as $row)
                <tr>
                    <td class="data-font seperator">{{($i++).'.'}}</td>
                    <td class="data-font seperator">{{cdate($row->date)->format('d-m-Y')}}</td>
                    <td class="data-font seperator">{{cdate($row->time)->format('h:i a')}}</td>
                    <td class="data-font seperator">{{$row->getPatientsDetails['code'] }}</td>
                    <td class="data-font seperator">{{ucwords(strtolower($row->getPatientsDetails['name']))}}</td>
                    <td class="data-font seperator">{{$row->arrival_time}}</td>
                    <td class="data-font seperator">{{$row->categoryDetails['name']}}</td>
                    <td class="data-font seperator">{{$row->getPatientsDetails['mobile_number']}}</td>
                    <td class="data-font seperator"><div class="remark">{{$row->remark}}</div></td>
                </tr>
            @empty
                <td colspan="9" class="text-center">No records available</td>
            @endforelse
        </tbody>
    </table>
    