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
                <th class="report-header-tr-th">Appointment</th>
                <th class="report-header-tr-th">Code</th>
                <th class="report-header-tr-th">Name</th>
                <th class="report-header-tr-th">Arrival</th>
                <th class="report-header-tr-th">Category</th>
                <th class="report-header-tr-th">Seen By</th>
                <th class="report-header-tr-th">Mobile</th>
                <th class="report-header-tr-th">OPD</th>
                <th class="report-header-tr-th">Remark</th>
                <th class="report-header-tr-th">Next Appointment</th>
            </tr>
        </thead>
            <tbody>
            @forelse($appointment as $row)
                <tr>
                    <td class="data-font seperator">{{($i++).'.'}}</td>
                    <td class="data-font seperator">{{ !empty($row->time) ? cdate($row->date)->format('d-m-Y') . ' ' .  cdate($row->time)->format('h:i') : cdate($row->date)->format('d-m-Y') }}</td>
                    <td class="data-font seperator">{{$row->getPatientsDetails['code'] }}</td>
                    <td class="data-font seperator">{{ucwords(strtolower($row->getPatientsDetails['name']))}}</td>
                    <td class="data-font seperator">{{ date('h:i', strtotime($row->arrival_time)) }}</td>
                    <td class="data-font seperator">{{ucfirst($row->categoryDetails['name'])}}</td>
                    <td class="data-font seperator">{{$row->getPatientsDetails->getHospitalDoctor['name']}}</td>
                    <td class="data-font seperator">{{$row->getPatientsDetails['mobile_number']}}</td>
                    <td class="data-font seperator">{{$row->getAppointmentCharges['netamount']}}</td>
                    <td class="data-font seperator"><div class="remark">{{$row->remark}}</div></td>
                    <td class="data-font seperator">{{$row->nextAppointmentDate()}}</td>
                </tr>
            @empty
                <td colspan="10" class="text-center">No records available</td>
            @endforelse
        </tbody>
    </table>
    