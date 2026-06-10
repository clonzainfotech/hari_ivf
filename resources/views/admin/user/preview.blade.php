<style type="text/css">
    .user-list, .user-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .user-list{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
    }
    .user-table{
        text-align: left;
    }
    .user-list tr{
        height: 50px;
        font-size: 20px;
    }
    .user-table thead th{
        height: 35px;
    }
    .user-table thead th span{
        border-bottom: 1px solid #000000;
    }
    .user-table tr {
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
    </style>
    <table class="table m-b-0 table-hover user-list" id="user-table" cellspacing="0">
        <thead>
            <tr>
                <th colspan="5">{{strtoupper(config('app.hospitalname1'))}}</th>
            </tr>
        </thead>
    </table>
    <table class="table m-b-0 table-hover user-table" id="user-table" cellspacing="0">
        <?php 
            $i = 1;
        ?>
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Name</th>
                <th class="report-header-tr-th">Profile Pic</th>
                <th class="report-header-tr-th">Email</th>
                <th class="report-header-tr-th">Role</th>
                <th class="report-header-tr-th">Phone</th>
                <th class="report-header-tr-th">Status</th>
            </tr>
        </thead>
            <tbody>
            @forelse($user as $row)
                @php
                    $file = cdnUrl($row->profile_picture, 'public/images/default_user.png');
                @endphp
                <tr>
                    <td class="data-font seperator">{{($i++).'.'}}</td>
                    <td class="data-font seperator">{{ ucwords(strtolower($row->name)) }}</td>
                    <td class="data-font seperator"><img  src="{{$file}}" style="width:50px; height: 50px; border-radius: 50%;" /></td>
                    <td class="data-font seperator">{{$row->email}}</td>
                    <td class="data-font seperator">{{$row->role}}</td>
                    <td class="data-font seperator">{{$row->mobile_number ? $row->mobile_number : '-'}}</td>
                    <td class="data-font seperator">{{$row->status}}</td>
                </tr>
            @empty
                <td colspan="7" class="text-center">No records available</td>
            @endforelse
        </tbody>
    </table>
    