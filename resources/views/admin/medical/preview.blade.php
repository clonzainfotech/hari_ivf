<style type="text/css">
    .medicines-list, .medical-table, #appointment-table{
        font-family: 'Montserrat', Arial, Tahoma, sans-serif;
        width: 100%;
    }
    .medicines-list{
        border-bottom: 1px solid #000000;
        margin-bottom: 10px;
    }
    .medical-table{
        text-align: left;
    }
    .medicines-list tr{
        height: 50px;
        font-size: 20px;
    }
    .medical-table thead th{
        height: 35px;
    }
    .medical-table thead th span{
        border-bottom: 1px solid #000000;
    }
    .medical-table tr {
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

    .main-title{
        font-size: 20px;
        font-weight: bolder;
    }
    #medical-table{
        text-align: center !important;
        font-size: 24px;
    }
    .sub-title{
        font-size: 20px;
    }
    #appointment-table thead tr th
    {
        text-align: left;
    }
    </style>
    <table class="table m-b-0 table-hover medical-table" id="medical-table" cellspacing="0">
        <thead>
            <tr>
                <th colspan="5">{{strtoupper(config('app.hospitalname1'))}}</th>
            </tr>
        </thead>
    </table>
    {{-- anc --}}
    <div class="card category-data category-data-3 d-none">
        <div class="body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <h5 class="main-title">ANC</h5>
                    </div>
                    <div class="col-md-7"></div>
                </div>
                @if(!empty($ancData) && count($ancData) != 0)
                    @php
                        $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
                        $dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                        $mTime = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                    @endphp
                    @foreach($ancData as $key=>$row)
                        @php
                            $treatment = json_decode($row->treatment);
                            unset($treatment->medicinedata);
                        @endphp<br>
                        <div class="row">
                            @if(!empty($key) && $key != 0)
                                <hr>
                            @endif
                            <div class="col-md-5 ml-2 sub-title">Appointment Date :- <span class="font-weight-bold">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s')}}</span></div>
                        </div>
                        <br>
                        <div class="medicines-table">
                            <table class="table m-b-0 table-hover" id="appointment-table">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold">Name</th>
                                        <th class="font-weight-bold">Dose</th>
                                        <th class="font-weight-bold">Timing</th>
                                        <th class="font-weight-bold">Freq.</th>
                                        <th class="font-weight-bold">Duration</th>
                                        <th class="font-weight-bold">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($treatment))
                                        @foreach($treatment as $key=>$row)
                                        <tr>
                                            <?php
                                                $medicine_status = '';
                                                $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                $firstCharacter = strtoupper(substr($mId, 0, 3));
                                                if($firstCharacter == "INJ"){
                                                    if(!empty($row->medicine_time)){
                                                        switch($row->medicine_time){
                                                            case '1':
                                                                $medicine_status = 'IV';
                                                                break;
                                                            case '2':
                                                                $medicine_status = 'IM';
                                                                break;
                                                            case '3':
                                                                $medicine_status = 'SC';
                                                                break;
                                                            case '4':
                                                                $medicine_status = 'Oral';
                                                                break;
                                                            case '5':
                                                                $medicine_status = 'P/V';
                                                                break;
                                                            case '6':
                                                                $medicine_status = 'P/A';
                                                                break;
                                                        }
                                                    }
                                                    $mData = !empty($row->medicine_time) ? $medicine_status : $medicine_status;
                                                    if($mData==$medicine_status) {
                                                        $medicine_status = "-";
                                                    }
                                                }else{
                                                    $mData = [0,0,0,0];

                                                    if(@$row->quantity>0) {
                                                        $mData[0] = $row->quantity;
                                                    }
                                                    if(@$row->quantity_2>0) {
                                                        $mData[1] = $row->quantity_2;
                                                    }
                                                    if(@$row->quantity_3>0) {
                                                        $mData[2] = $row->quantity_3;
                                                    }
                                                    if(@$row->quantity_4>0) {
                                                        $mData[3] = $row->quantity_4;
                                                    }
                                                    $mData = implode('-',$mData);
                                                    switch($row->medicine_status){
                                                        case '1':
                                                            $medicine_status = 'જમ્યા પછી';
                                                            break;
                                                        case '2':
                                                            $medicine_status = 'જમ્યા પહેલાં';
                                                            break;
                                                        case '3':
                                                            $medicine_status = 'માસિકની જગ્યાએ મુકવી';
                                                            break;
                                                    }
                                                }
                                            ?>
                                            <td>{{$row->medicine}}</td>
                                            <td>{{$mData}}</td>
                                            <td>{{$medicine_status}}</td>
                                            <td>{{isset($dose[$row->dose]) ? $dose[$row->dose] : ''}}</td>
                                            <td>{{$row->no.' days'}}</td>
                                            <td>{{isset($row->note)  && !empty($row->note) ? $row->note : '-'}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <td colspan='6' class="text-center">No records available</td>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @else
                    <span class="m-text">No Medicine Available</span></h5>
                @endif
            </div>
        </div>
    </div>
    {{-- iui --}}
    <div class="card category-data category-data-2 d-none">
        <div class="body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <h5 class="main-title">IUI</h5>
                    </div>
                    <div class="col-md-7"></div>
                </div>
                @if(!empty($iuiData) && count($iuiData) != 0)
                    @php
                        $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
                        $dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                        $mTime = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                    @endphp
                    @foreach($iuiData as $row)
                        @php
                            if(!empty($row->description)){
                                $data = json_decode($row->description);
                                $treatment = !empty($data->treatment) ? $data->treatment : [];
                                if(isset($data->treatment) && !empty($data->treatment))
                                {
                                    unset($treatment->medicinedata);
                                }
                            }else{
                                $treatment = json_decode($row->treatment);
                                unset($treatment->medicinedata);
                            }
                        @endphp<br>
                        <div class="row">
                            @if(!empty($key) && $key != 0)
                                <hr>
                            @endif
                            <div class="col-md-5 ml-2 sub-title">Appointment Date :- <span class="font-weight-bold">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s')}}</span></div>
                        </div>
                        <br>
                        <div class="medicines-table">
                            <table class="table m-b-0 table-hover" id="appointment-table">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold">Name</th>
                                        <th class="font-weight-bold">Dose</th>
                                        <th class="font-weight-bold">Timing</th>
                                        <th class="font-weight-bold">Freq.</th>
                                        <th class="font-weight-bold">Duration</th>
                                        <th class="font-weight-bold">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($treatment))
                                        @foreach($treatment as $key=>$row)
                                        <tr>
                                            <?php
                                                $medicine_status = '';
                                                $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                $firstCharacter = strtoupper(substr($mId, 0, 3));
                                                if($firstCharacter == "INJ"){
                                                    if(!empty($row->medicine_time)){
                                                        switch($row->medicine_time){
                                                            case '1':
                                                                $medicine_status = 'IV';
                                                                break;
                                                            case '2':
                                                                $medicine_status = 'IM';
                                                                break;
                                                            case '3':
                                                                $medicine_status = 'SC';
                                                                break;
                                                            case '4':
                                                                $medicine_status = 'Oral';
                                                                break;
                                                            case '5':
                                                                $medicine_status = 'P/V';
                                                                break;
                                                            case '6':
                                                                $medicine_status = 'P/A';
                                                                break;
                                                        }
                                                    }
                                                    $mData = !empty($row->medicine_time) ? $medicine_status : $medicine_status;
                                                    if($mData==$medicine_status) {
                                                        $medicine_status = "-";
                                                    }
                                                }else{
                                                    $mData = [0,0,0,0];

                                                    if(@$row->quantity>0) {
                                                        $mData[0] = $row->quantity;
                                                    }
                                                    if(@$row->quantity_2>0) {
                                                        $mData[1] = $row->quantity_2;
                                                    }
                                                    if(@$row->quantity_3>0) {
                                                        $mData[2] = $row->quantity_3;
                                                    }
                                                    if(@$row->quantity_4>0) {
                                                        $mData[3] = $row->quantity_4;
                                                    }
                                                    $mData = implode('-',$mData);
                                                    switch($row->medicine_status){
                                                        case '1':
                                                            $medicine_status = 'જમ્યા પછી';
                                                            break;
                                                        case '2':
                                                            $medicine_status = 'જમ્યા પહેલાં';
                                                            break;
                                                        case '3':
                                                            $medicine_status = 'માસિકની જગ્યાએ મુકવી';
                                                            break;
                                                    }
                                                }
                                            ?>
                                            <td>{{$row->medicine}}</td>
                                            <td>{{$mData}}</td>
                                            <td>{{$medicine_status}}</td>
                                            <td>{{isset($dose[$row->dose]) ? $dose[$row->dose] : ''}}</td>
                                            <td>{{$row->no.' days'}}</td>
                                            <td>{{isset($row->note)  && !empty($row->note) ? $row->note : '-'}}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <td colspan='6' class="text-center">No records available</td>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @else
                    <span class="m-text">No Medicine Available</span></h5>
                @endif
            </div>
        </div>
    </div>
    {{-- ivf --}}
    <div class="card category-data category-data-1 d-none">
        <div class="body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <h5 class="main-title">IVF</h5>
                    </div>
                    <div class="col-md-7"></div>
                </div>
                @if(!empty($ivfData) && count($ivfData) != 0)
                    @php
                        $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
                        $dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                        $mTime = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                    @endphp
                    @foreach($ivfData as $row)
                        @php
                            if(!empty($row->description)){
                                $data = json_decode($row->description);
                                $treatment = !empty($data->medicinedata) ? $data->medicinedata : [];
                                // print_r($treatment);
                                if(isset($data->medicinedata) && !empty($data->medicinedata))
                                {
                                    unset($data->medicinedata);
                                }
                            }else{
                                $treatment = json_decode($row->treatment);
                                unset($treatment->medicinedata);
                            }
                        @endphp<br>
                        <div class="row">
                            @if(!empty($key) && $key != 0)
                                <hr>
                            @endif
                            <div class="col-md-5 ml-2 sub-title">Appointment Date :- <span class="font-weight-bold">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s')}}</span></div>
                        </div>
                        <br>
                        @if(!empty($treatment))
                        <div class="medicines-table">
                            <table class="table m-b-0 table-hover" id="appointment-table">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold">Name</th>
                                        <th class="font-weight-bold">Dose</th>
                                        <th class="font-weight-bold">Timing</th>
                                        <th class="font-weight-bold">Freq.</th>
                                        <th class="font-weight-bold">Duration</th>
                                        <th class="font-weight-bold">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($treatment))
                                        @foreach($treatment as $key=>$row)
                                        <tr>
                                            <?php
                                                $medicine_status = '';
                                                $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                $firstCharacter = strtoupper(substr($mId, 0, 3));
                                                if($firstCharacter == "INJ"){
                                                    if(!empty($row->medicine_time)){
                                                        switch($row->medicine_time){
                                                            case '1':
                                                                $medicine_status = 'IV';
                                                                break;
                                                            case '2':
                                                                $medicine_status = 'IM';
                                                                break;
                                                            case '3':
                                                                $medicine_status = 'SC';
                                                                break;
                                                            case '4':
                                                                $medicine_status = 'Oral';
                                                                break;
                                                            case '5':
                                                                $medicine_status = 'P/V';
                                                                break;
                                                            case '6':
                                                                $medicine_status = 'P/A';
                                                                break;
                                                        }
                                                    }
                                                    $mData = !empty($row->medicine_time) ? $medicine_status : $medicine_status;
                                                    if($mData==$medicine_status) {
                                                        $medicine_status = "-";
                                                    }
                                                }else{
                                                    $mData = [0,0,0,0];

                                                    if(@$row->quantity>0) {
                                                        $mData[0] = $row->quantity;
                                                    }
                                                    if(@$row->quantity_2>0) {
                                                        $mData[1] = $row->quantity_2;
                                                    }
                                                    if(@$row->quantity_3>0) {
                                                        $mData[2] = $row->quantity_3;
                                                    }
                                                    if(@$row->quantity_4>0) {
                                                        $mData[3] = $row->quantity_4;
                                                    }
                                                    $mData = implode('-',$mData);
                                                    switch($row->medicine_status){
                                                        case '1':
                                                            $medicine_status = 'જમ્યા પછી';
                                                            break;
                                                        case '2':
                                                            $medicine_status = 'જમ્યા પહેલાં';
                                                            break;
                                                        case '3':
                                                            $medicine_status = 'માસિકની જગ્યાએ મુકવી';
                                                            break;
                                                    }
                                                }
                                            ?>
                                            <td>{{$row->medicine}}</td>
                                            <td>{{$mData}}</td>
                                            <td>{{$medicine_status}}</td>
                                            <td>{{isset($dose[$row->dose]) ? $dose[$row->dose] : ''}}</td>
                                            <td>{{$row->no.' days'}}</td>
                                            <td>{{isset($row->note)  && !empty($row->note) ? $row->note : '-'}}</td>

                                        </tr>
                                        @endforeach
                                    @else
                                        <td colspan='6' class="text-center">No records available</td>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @endif
                    @endforeach
                @else
                    <span class="m-text">No Medicine Available</span></h5>
                @endif
            </div>
        </div>
    </div>
    {{-- gynec --}}
    <div class="card category-data category-data-4 d-none">
            <div class="body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-1">
                            <h5 class="main-title">GYNEC</h5>
                        </div>
                        <div class="col-md-7"></div>
                    </div>
                    @if(!empty($gynecData) && count($gynecData) != 0)
                        @php
                            $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
                            $dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                            $mTime = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                        @endphp
                        @foreach($gynecData as $row)
                            @php
                                $treatment = json_decode($row->treatment);
                                unset($treatment->medicinedata);
                            @endphp<br>
                            <div class="row">
                                @if(!empty($key) && $key != 0)
                                    <hr>
                                @endif
                                <div class="col-md-5 ml-2 sub-title">Appointment Date :- <span class="font-weight-bold">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s')}}</span></div>
                            </div>
                            <br>
                            <div class="medicines-table">
                                <table class="table m-b-0 table-hover" id="appointment-table">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold">Name</th>
                                            <th class="font-weight-bold">Dose</th>
                                            <th class="font-weight-bold">Timing</th>
                                            <th class="font-weight-bold">Freq.</th>
                                            <th class="font-weight-bold">Duration</th>
                                            <th class="font-weight-bold">Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($treatment))
                                            @foreach($treatment as $key=>$row)
                                            <tr>
                                                <?php
                                                    $medicine_status = '';
                                                    $mId = preg_replace('/[^a-zA-Z0-9]+/', '_', $row->medicine);
                                                    $firstCharacter = strtoupper(substr($mId, 0, 3));
                                                    if($firstCharacter == "INJ"){
                                                        if(!empty($row->medicine_time)){
                                                            switch($row->medicine_time){
                                                                case '1':
                                                                    $medicine_status = 'IV';
                                                                    break;
                                                                case '2':
                                                                    $medicine_status = 'IM';
                                                                    break;
                                                                case '3':
                                                                    $medicine_status = 'SC';
                                                                    break;
                                                                case '4':
                                                                    $medicine_status = 'Oral';
                                                                    break;
                                                                case '5':
                                                                    $medicine_status = 'P/V';
                                                                    break;
                                                                case '6':
                                                                    $medicine_status = 'P/A';
                                                                    break;
                                                            }
                                                        }
                                                        $mData = !empty($row->medicine_time) ? $medicine_status : $medicine_status;
                                                        if($mData==$medicine_status) {
                                                            $medicine_status = "-";
                                                        }
                                                    }else{
                                                        $mData = [0,0,0,0];
    
                                                        if(@$row->quantity>0) {
                                                            $mData[0] = $row->quantity;
                                                        }
                                                        if(@$row->quantity_2>0) {
                                                            $mData[1] = $row->quantity_2;
                                                        }
                                                        if(@$row->quantity_3>0) {
                                                            $mData[2] = $row->quantity_3;
                                                        }
                                                        if(@$row->quantity_4>0) {
                                                            $mData[3] = $row->quantity_4;
                                                        }
                                                        $mData = implode('-',$mData);
                                                        switch($row->medicine_status){
                                                            case '1':
                                                                $medicine_status = 'જમ્યા પછી';
                                                                break;
                                                            case '2':
                                                                $medicine_status = 'જમ્યા પહેલાં';
                                                                break;
                                                            case '3':
                                                                $medicine_status = 'માસિકની જગ્યાએ મુકવી';
                                                                break;
                                                        }
                                                    }
                                                ?>
                                                <td>{{$row->medicine}}</td>
                                                <td>{{$mData}}</td>
                                                <td>{{$medicine_status}}</td>
                                                <td>{{isset($dose[$row->dose]) ? $dose[$row->dose] : ''}}</td>
                                                <td>{{$row->no.' days'}}</td>
                                                <td>{{isset($row->note)  &&  !empty($row->note) ? $row->note : '-'}}</td>
    
                                            </tr>
                                            @endforeach
                                        @else
                                            <td colspan='6' class="text-center">No records available</td>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    @else
                        <span class="m-text">No Medicine Available</span></h5>
                    @endif
                </div>
            </div>
    </div>
