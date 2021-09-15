<style type="text/css">
    .category-list, .category-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .category-list{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
    }
    .category-table{
        text-align: left;
    }
    .category-list tr{
        height: 50px;
        font-size: 20px;
    }
    .category-table thead th{
        height: 35px;
    }
    .category-table thead th span{
        border-bottom: 1px solid #000000;
    }
    .category-table tr {
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
    <table class="table m-b-0 table-hover category-list" id="category-table" cellspacing="0">
        <thead>
            <tr>
                <th colspan="5">{{strtoupper(config('app.hospitalname1'))}}</th>
            </tr>
        </thead>
    </table>
    <table class="table m-b-0 table-hover category-table" id="category-table" cellspacing="0">
        <?php 
            $i = 1;
        ?>
        <thead>
            <tr class="report-header-tr seperator">
                <th class="report-header-tr-th">Sr No</th>
                <th class="report-header-tr-th">Plan</th>
                <th class="report-header-tr-th">Injection</th>
                <th class="report-header-tr-th">Net Price</th>
                <th class="report-header-tr-th">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($injection as $row)
                <tr>
                    <td class="data-font seperator">{{($i++).'.'}}</td>
                    <td class="data-font seperator">{{$row->type}}</td>
                    <td class="data-font seperator">{{$row->name}}</td>
                    <td class="data-font seperator">{{$row->net_price}}</td>
                    <td class="data-font seperator">{{$row->quantity}}</td>
                </tr>
            @empty
                <td colspan="3" class="text-center">No records available</td>
            @endforelse
        </tbody>
    </table>
    