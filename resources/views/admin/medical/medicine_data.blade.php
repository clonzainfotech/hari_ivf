{{-- anc --}}
@php
$medqty = ['1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5];
    $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
    $dose = ["1"=>"Daily","2"=>"Once a week","3"=>"Twice a week","4"=>"Stat","5"=>"SOS","6"=>"Alternate Day","7"=>"6 hourly","8"=>"8 hourly","9"=>"12 hourly","10"=>"24 hourly"];
@endphp
    <div class="card category-data category-data-3 d-none">
        <div class="body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-1">
                        <h5>ANC</h5>
                    </div>
                    <div class="col-md-7"></div>
                    <div class="col-md-3">
                        <input type="text" class="form-control daterange anc-date" data-id="anc-date" placeholder="Select Date">
                    </div>
                </div>
                @if(!empty($ancData) && count($ancData) != 0)
                    @php
                        $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
                        $dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                        $mTime = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                    @endphp
                    @foreach($ancData as $row)
                        @php
                            $treatment = json_decode($row->treatment);
                            unset($treatment->medicinedata);
                        @endphp<br>
                        <div class="row">
                            <div class="col-md-5 ml-2">Appointment Date :- <span class="font-weight-bold">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s')}}</span></div>
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
                        <h5>IUI</h5>
                    </div>
                    <div class="col-md-7"></div>
                    <div class="col-md-3">
                        <input type="text" class="form-control daterange iui-date" data-id="iui-date" placeholder="Select Date">
                    </div>
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
                            <div class="col-md-5 ml-2">Appointment Date :- <span class="font-weight-bold">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s')}}</span></div>
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
                        <h5>IVF</h5>
                    </div>
                    <div class="col-md-7"></div>
                    <div class="col-md-3">
                        <input type="text" class="form-control daterange ivf-date" data-id="ivf-date" placeholder="Select Date">
                    </div>
                </div>
                @if(!empty($ivfData) && count($ivfData) != 0)
                    @php
                        $mStatus = [1=>'જમ્યા પછી',2=>'જમ્યા પહેલાં',3=>'માસિકની જગ્યાએ મુકવી'];
                        $dose = ["1"=>"OD","2"=>"BD","3"=>"TDS","4"=>"ADS","5"=>"Weekly / 1","6"=>"Weekly / 2","7"=>"Stat","8"=>"SOS"];
                        $mTime = ["1"=>"Morning","2"=>"Afternoon","3"=>"Evening","4"=>'Night'];
                    @endphp
                    @foreach($ivfData as $row)
                        @php
                            $treatment = null;
                            // if(!empty($row->description)){
                            //     $medicine = json_decode($row->description);
                            //     $medicine = !empty($description->treatment) ? $description->treatment : [];
                            //     if(!empty($medicine)){
                            //         $treatment = $medicine;
                            //     }
                            // }else{
                            //     $treatment = json_decode($row->treatment);
                            //     unset($treatment->medicinedata);
                            // }
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
                            <div class="col-md-5 ml-2">Appointment Date :- <span class="font-weight-bold">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s')}}</span></div>
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
    {{-- gynec --}}
    <div class="card category-data category-data-4 d-none">
            <div class="body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-1">
                            <h5>GYNEC</h5>
                        </div>
                        <div class="col-md-7"></div>
                        <div class="col-md-3">
                            <input type="text" class="form-control daterange gynec-date" data-id="gynec-date" placeholder="Select Date">
                        </div>
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
                                <div class="col-md-5 ml-2">Appointment Date :- <span class="font-weight-bold">{{\Carbon\Carbon::parse($row->created_at)->format('d-m-Y H:i:s')}}</span></div>
                            </div>
                            <br>
                            <div class="medicines-table">
                                <table class="table m-b-0 table-hover" id="appointment-table">
                                    <thead>
                                        <tr>
                                            <th>Medicines</th>
                                            <th>Medicines Status</th>
                                            <th>Dose</th>
                                            <th>No </th>
                                            <th>Quantity</th>
                                            <th>Medicine Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($treatment))
                                            @foreach($treatment as $value)
                                                <tr>
                                                    <td>{{$value->medicine}}</td>
                                                    <td>{{!empty($value->medicine_status) ? $mStatus[$value->medicine_status] : null}}</td>
                                                    <td>{{!empty($value->dose) ? $dose[$value->dose] : null}}</td>
                                                    <td>{{$value->no}}</td>
                                                    <td>{{$value->quantity}}</td>
                                                    <td>
                                                        @if(!empty($value->medicine_time))
                                                            @php
                                                                $data = [];
                                                                foreach($value->medicine_time as $row){
                                                                    $data[] = $mTime[$row];
                                                                }
                                                            @endphp
                                                            {{implode(',',$data)}}
                                                        @endif
                                                    </td>
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
{{-- no data avaliable  --}}
@if(empty($lastType))
    <div class="card">
        <div class="body">
            <div class="col-md-12">
                <h5>No Medicine Available</h5>
            </div>
        </div>
    </div>
@endif
