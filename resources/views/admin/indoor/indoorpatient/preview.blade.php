<style type="text/css">

    .report-table tr {
        border: 1px solid #000000;
        height: 36px;
    }
    .report-header-tr-th {
        font-size: 13px;
    }
    .report-table {
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .report-date-font {
        font-size: 14px;
    }
    
    .report-height-fifty {
        height: 50px;
    }
    .report-header {
        font-weight: 900;
        font-size: 20px;
    }
    .report-header-tr{
        text-align: left;
        height: 35px;
        background-color: #bdf3f5;
        border: 1px solid #000000;
    }
    .charges {
        font-weight: 600;
        font-size: 11px;
    }
    .upper-border {
        border-top: 1px solid #000000;
    }
    .text-center {
        text-align: center;
        font-size: 13px;
    }
    .seperator {
        border-top: 0.5px solid #dee2e6;
    }
    .data-font {
        font-size: 11px;
    }
    tr, th, td {
        padding: 0px;
    }

    .word-wrap {
        width: 250px;
        word-wrap: break-word;
    }
    </style>
<table id="report-table" class="report-table font" cellspacing="0">
    <thead>
        <tr>
            <th colspan="7" class="report-header report-height-fifty">{{strtoupper(config('app.hospitalname1'))}}</th>
        </tr>
        <tr>
            <th colspan="7" class="report-header">Indoor Summary Report</th>
        </tr>
        <tr class="report-header-tr seperator">
            <th class="report-header-tr-th">Sr No</th>
            <th class="report-header-tr-th">Code</th>
            <th class="report-header-tr-th">Patient Name</th>
            <th class="report-header-tr-th">Procedure / Surgery</th>
            <th class="report-header-tr-th">Room Type</th>
            <th class="report-header-tr-th">DOA</th>
            <th class="report-header-tr-th">DOD</th>
        </tr>
    </thead>
    <tbody>
    @php
        $i = 1;
    @endphp
    @forelse($indoorData as $patients)
        
        <tr data-id="{{encrypt($patients->id)}}" >
            <td  class="data-font seperator">
                <div class="inline">{{ $i++ . '.' }}</div>
            </td>
            <td class="data-font seperator">{{ $patients->getPatientsDetails['code'] }} </td>
            <td class="data-font seperator">{{ ucwords(strtolower($patients->getPatientsDetails['name'])) }} </td>
            <td class="data-font seperator">
                <div class="word-wrap">
                    {{ !empty($patients->procedure_name) ? $patients->procedure_name : '-' }}
                </div>
            </td>
            <td class="data-font seperator">{{ $patients->getRoomType['name'] }}</td>
            <td class="data-font seperator">{{ (!empty($patients->doa_date)) ? cdate($patients->doa_date)->format('d-m-Y') : '-' }}</td>
            <td class="data-font seperator">{{ (!empty($patients->dod_date)) ? cdate($patients->dod_date)->format('d-m-Y') : '-' }}</td>
        </tr>
    @empty
        <td colspan="7" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
