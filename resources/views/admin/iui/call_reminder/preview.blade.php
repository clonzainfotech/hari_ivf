<style type="text/css">
    .call-data-list, .call-data-list-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .call-data-list{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
    }
    .call-data-list-table{
        text-align: left;
    }
    .call-data-list tr{
        height: 50px;
        font-size: 20px;
    }
    .call-data-list-table thead th{
        height: 35px;
    }
    .call-data-list-table thead th span{
        border-bottom: 1px solid #000000;
    }
    .call-data-list-table tr {
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
    <table class="table m-b-0 table-hover call-data-list" id="call-data-list-table" cellspacing="0">
        <thead>
            <tr>
                <th colspan="5">{{strtoupper(config('app.hospitalname1'))}}</th>
            </tr>
        </thead>
    </table>
    <table class="table m-b-0 table-hover call-data-list-table" id="call-data-list-table" cellspacing="0">
        <?php 
            $i = 1;
        ?>
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Code</th>                          
                <th class="report-header-tr-th">Cycle No</th>
                <th class="report-header-tr-th">Next Call Date</th>
                <th class="report-header-tr-th">Patient Name</th>
                <th class="report-header-tr-th">Mobile Number</th>
                <th class="report-header-tr-th">Response</th>
            </tr>
        </thead>
            <tbody>
            @forelse($callReminder as $row)
                <tr>
                    <td class="data-font seperator">{{($i++).'.'}}</td>
                    <td class="data-font seperator">{{ !empty($row->getPatientData['code']) ? $row->getPatientData['code'] : '-' }}</td>
                    <td class="data-font seperator">{{ !empty($row->getIuiPatientData['cycle_no']) ? $row->getIuiPatientData['cycle_no'] : '-' }}</td>
                    <td class="data-font seperator">{{ cdate($row->date)->format('d-m-Y') }}</td>
                    <td class="data-font seperator">{{ strtoupper($row->getPatientData['name']) }}</td>
                    <td class="data-font seperator">{{ $row->getPatientData['mobile_number'] }}</td>
                    <td class="data-font seperator"><div class="response">{{$row->response}}</div></td>
                </tr>
            @empty
                <td colspan="4" class="text-center">No records available</td>
            @endforelse
        </tbody>
    </table>
    