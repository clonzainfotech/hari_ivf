
<style>
@media print {
    #Header, #Footer { display: none !important; }
}

.sticker-table{
    font-family: 'Montserrat', Arial, Tahoma, sans-serif;
    /* width: 100%; */
    margin: 0 auto !important;
}
td{
    font-size: 11px;
    text-transform: capitalize;
    padding-bottom: 0 !important;
    padding-top: 0 !important;
}
.sticker-bold{
 font-weight: 900;
}
.sticker-sub-header{
    /* width: 40%;
    text-align: right; */
}
.sticker-sub-span{
    /* width: 50%; */
    display: inline-block;
    text-align: left;
}

.sticket-data {
    width: 50%;
    height: auto;
    vertical-align: initial;
}
.left-border
{
    border-left: 1px solid black;
}
.float-right
{
    float: right;
}
.w-12{
    width: 11rem;
}
.label-table
{
    border: 1px solid;
    padding-left: 5%;
    border-radius: 25px;
    width: 100%;
    height: 223px;
    -ms-transform: rotate(20deg);
    transform: rotate(90deg);
    margin: 60% 0;
    -webkit-print-color-adjust: exact;
}
.label-table .row {
    align-items: center;
    height: 223px;
}
@media print {
    .label-table
    {
        border: 1px solid;
        padding-left: 5%;
        border-radius: 25px;
        width: 100%;
        height: 223px;
        -ms-transform: rotate(20deg);
        transform: rotate(90deg);
        margin: 60% 0;
        -webkit-print-color-adjust: exact;
    }
    .font-7
    {
        font-size: 60px;
        width: 1300px;
    }
}
.font-7
{
    font-size: 60px;
    font-weight: bold;
    -webkit-print-color-adjust: exact;
    width: 1300px;
    overflow: hidden;
}
.pro_name
{
    margin-left: 20px;
    font-weight: bold;
    font-size: 24px;
    -webkit-print-color-adjust: exact;
}
/* width: 50%;
    height: auto;
    vertical-align: initial; */

</style>
@if(isset($is_label) && $is_label == 1)
<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
<div class="label-table">
    @php
        $namePart = explode(' ',$label_name);
        $lastName = isset($namePart[2]) ? $namePart[2] : '';

    @endphp
    <div class="row"><p class="font-7">{{ucwords(strtolower($namePart[0].' '.(isset($namePart[1]) && !empty($namePart[1]) ? $namePart[1][0].'.' : '' ).' '.$lastName))}}<sub class="pro_name">({{$procedure_name}})</sub></p></div>
</div>
@else

<table id="sticker-table" class="sticker-table">
    <tbody>
        <tr>
            <td style="width: 50%" class="sticket-data">
                <table id="sticker-table" class="sticker-table float-right">
                    <tbody>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Name</span></td>
                            <td>: {{ucwords(strtolower($appointment->getPatientsDetails['name']))}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Age/Sex</span></td>
                            <td>: {{($appointment->getPatientsDetails['age'] ? $appointment->getPatientsDetails['age'].'/ Years ' : '').($appointment->getPatientsDetails['months'] ? $appointment->getPatientsDetails['months'].'/ months ' : '').($appointment->getPatientsDetails['days'] ? $appointment->getPatientsDetails['days'].'/ days ' : ''). $appointment->gender}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Address</span></td>
                            <td class="w-12">: {{$appointment->getPatientsDetails['residence'].', '. $appointment->getPatientsDetails['main_area'].', '. $appointment->getPatientsDetails['city']}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Dr. Name</span></td>
                            <td>: {{$appointment->getPatientsDetails->getHospitalDoctor['name']}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Ref. Dr. Name</span></td>
                            <td class="sticker-bold">: {{$appointment->getPatientsDetails->getReferenceDoctor['name']}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Reg.date</span></td>
                            <td>: {{\Carbon\Carbon::parse($appointment->date)->format('D d M Y')}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">UHID</span></td>
                            <td class="sticker-bold">: {{$appointment->getPatientsDetails['code']}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Mobile No.</span></td>
                            <td>: {{$appointment->getPatientsDetails['mobile_number'].', '. $appointment->getPatientsDetails['other_mobile_number']}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            @if($is_indoor == 1)
            <td style="width: 50%" class="sticket-data">
                <table id="sticker-table" class="sticker-table float-right">
                    <tbody>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Name</span></td>
                            <td>: {{ucwords(strtolower($appointment->getPatientsDetails['name']))}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Age/Sex</span></td>
                            <td>: {{($appointment->getPatientsDetails['age'] ? $appointment->getPatientsDetails['age'].'/ Years ' : '').($appointment->getPatientsDetails['months'] ? $appointment->getPatientsDetails['months'].'/ months ' : '').($appointment->getPatientsDetails['days'] ? $appointment->getPatientsDetails['days'].'/ days ' : ''). $appointment->gender}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Address</span></td>
                            <td class="w-12">: {{$appointment->getPatientsDetails['residence'].', '. $appointment->getPatientsDetails['main_area'].', '. $appointment->getPatientsDetails['city']}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Dr. Name</span></td>
                            <td>: {{$appointment->getPatientsDetails->getHospitalDoctor['name']}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Ref. Dr. Name</span></td>
                            <td class="sticker-bold">: {{$appointment->getPatientsDetails->getReferenceDoctor['name']}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Reg.date</span></td>
                            <td>: {{\Carbon\Carbon::parse($appointment->date)->format('D d M Y')}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">UHID</span></td>
                            <td class="sticker-bold">: {{$appointment->getPatientsDetails['code']}}</td>
                        </tr>
                        <tr>
                            <td class="sticker-sub-header"><span class="sticker-sub-span">Mobile No.</span></td>
                            <td>: {{$appointment->getPatientsDetails['mobile_number'].', '. $appointment->getPatientsDetails['other_mobile_number']}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            @else
                <td class="sticker-svg">

                    <?php
                    $gen = 'M';
                    if ($appointment->gender=='Female') {
                        $gen =  'F';
                    }
                    $name=strtolower($appointment->getPatientsDetails['name']);
                    $dr=strtolower($appointment->getPatientsDetails->getReferenceDoctor['name']);
                    $firstline=ucwords($name);
                    $age=($appointment->getPatientsDetails['age'] ? $appointment->getPatientsDetails['age'].'/ Years ' : '').($appointment->getPatientsDetails['months'] ? $appointment->getPatientsDetails['months'].'/ months ' : '').($appointment->getPatientsDetails['days'] ? $appointment->getPatientsDetails['days'].'/ days ' : '');
                    $gen=$gen;
                    $address=$appointment->getPatientsDetails['residence'].", ".$appointment->getPatientsDetails['main_area'].", ".$appointment->getPatientsDetails['city'];
                    $thirdline=$appointment->getPatientsDetails['mobile_number'].",".$appointment->getPatientsDetails['other_mobile_number'];
                    $refd=ucwords($dr);
                    $code=$appointment->code;
                    $date=date(" D, d M Y", strtotime($appointment->date));
                    $arrData = [
                        'id' => $appointment->getPatientsDetails['id'],
                        'name'  => $firstline,
                        'age'=>$age,
                        'gender'=>$gen,
                        'add' => $address,
                        'mobile'=>$thirdline,
                        ' refdr' => $refd,
                        'code'=>$code,
                        'date'=>$date
                        /* Add here all the data you need*/
                    ];
                    $jsonData = json_encode($arrData);
                    ?>
                    {!!
                    $png=QrCode::size(170)->Color(0, 207, 209)->generate($jsonData);
                    !!}

                </td>
            @endif
        </tr>
    </tbody>
</table>
@endif
