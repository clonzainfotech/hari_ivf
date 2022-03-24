@extends('layouts.main')
@section('parentPageTitle', 'Stich Appointment')
@section('title', 'Add Stich Appointment')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('public/css/image-uploader.css')}}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong class="text-secondary">{{ucwords($patient->name)}}</strong></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix anc">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Stich Appointment</strong>
                    </h2>
                    <ul class="header-dropdown">

                    </ul>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        @if(Session::has('msg'))
                            <div class="alert alert-danger">
                                {{Session::get('msg')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">
                                        <i class="zmdi zmdi-close"></i>
                                    </span>
                                </button>
                            </div>
                        @endif
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['class'=>'form stich','files'=>true,'id'=>'stich-form','enctype'=>'multipart/form-data'])}}
                                {{Form::hidden('patient_id',encrypt($patient->id))}}
                                {{Form::hidden('stich_id','',['class'=>'stich-id'])}}
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="vertical-form-label pr-0">
                                            Seen By :
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{Form::select('seen_by',$hospitalDoctor,'',['class'=>'form-control select-padding-0 seen-by','placeholder'=>'Select Doctor'])}}
                                        </div>
                                        <span class="seen-by-error text-danger mb-2"></span>
                                    </div>
                                </div>

                                <!-- H/O -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                                   data-parent="#co" href="#ho-tab" aria-expanded="false"
                                                    aria-controls="co">1. H/O</a></h4>
                                        </div>
                                        <div id="ho-tab" class="panel-collapse collapse" role="tabpanel"
                                            aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        H/O :
                                                    </label>
                                                </div>
                                                <div class='col-md-8 complain-multi duration-value'>
                                                    {{Form::select('ho[ho_details]',$hoData,'',['class'=>'form-control ho-data select-padding-0 duration-data anc-dose-val ho_type_value','placeholder'=>'Select H/O','data-medicine'=>2])}}
                                                    <span class="form-error-msg ho-data-msg">
                                                        {{$errors->first('ho_details')}}
                                                    </span>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        {{Form::text("ho[amenorrhoea]",'Amenorrhoea',['class'=>'form-control','readonly'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('amenorrhoea')}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="radio is-conceived">
                                                        {{Form::radio("ho[ho_type_value]",'ftnd','',[
                                                            'id'=>'ftnd',
                                                        ])}}
                                                        <label for="ftnd">
                                                            FTND
                                                        </label>

                                                        {{Form::radio("ho[ho_type_value]",'lscs','',[
                                                            'id'=>'lscs'
                                                        ])}}
                                                        <label for="lscs">
                                                            L.S.C.S
                                                        </label>

                                                        {{Form::radio("ho[ho_type_value]",'tlh','',[
                                                            'id'=>'tlh'
                                                        ])}}
                                                        <label for="tlh">
                                                            TLH
                                                        </label>

                                                        {{Form::radio("ho[ho_type_value]",'vh','',[
                                                            'id'=>'vh'
                                                        ])}}
                                                        <label for="vh">
                                                            VH
                                                        </label>

                                                        {{Form::radio("ho[ho_type_value]",'myomectomy','',[
                                                            'id'=>'myomectomy'
                                                        ])}}
                                                        <label for="myomectomy">
                                                            Myomectomy
                                                        </label>

                                                        {{Form::radio("ho[ho_type_value]",'ectopic','',[
                                                            'id'=>'ectopic'
                                                        ])}}
                                                        <label for="ectopic">
                                                            Laparoscopic Ectopic
                                                        </label>

                                                        {{Form::radio("ho[ho_type_value]",'diagnostic_hystrolapro','',[
                                                            'id'=>'diagnostic_hystrolapro'
                                                        ])}}
                                                        <label for="diagnostic_hystrolapro">
                                                            Diagnostic Hystrolapro
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        {{Form::textarea("ho[other_info]",'',['class'=>'form-control no-resize other_info','placeholder'=>'Other Information','rows'=>'5'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- C/O -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                               data-parent="#co" href="#co" aria-expanded="false"
                                                aria-controls="co">2. C/O</a></h4>
                                    </div>
                                    <div id="co" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        C/O :
                                                    </label>
                                                </div>
                                                <div class="col-md-8 complain-multi">
                                                    {{Form::select('co[co_type][]',$complaints,'',['class'=>'form-control co-value co_value_data complaint-data','placeholder'=>'Enter complain','multiple'=>true,'data-type'=>'0','data-medicine'=>1])}}
                                                    <span class="form-error-msg co-value-msg">
                                                        {{$errors->first('since')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Since : &nbsp;</span>
                                                        {{Form::text("co[since]",'',['class'=>'form-control'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('since')}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- O/E -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                               data-parent="#oe-tab" href="#oe-tab" aria-expanded="false"
                                                aria-controls="co">3. O/E</a></h4>
                                    </div>
                                    <div id="oe-tab" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Vitals :
                                                    </label>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">B.P : &nbsp;</span>
                                                        {{Form::text("oe[le][bp]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <span class="col-md-1 p-2">MMHG</span>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Temp : &nbsp;</span>
                                                        {{Form::text("oe[le][temp]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Pulse : &nbsp;</span>
                                                        {{Form::text("oe[le][pulse]",'',['class'=>'form-control'])}}
                                                    </div>
                                                </div>
                                                <span class="col-md-1 p-2">/ Min</span>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Breast :
                                                    </label>
                                                </div>
                                                <div class="col-md-2">
                                                    {{Form::text("oe[breast][right]",'',['class'=>'form-control','placeholder'=>'Right'])}}
                                                </div>
                                                <div class="col-md-2">
                                                    {{Form::text("oe[breast][left]",'',['class'=>'form-control','placeholder'=>'Left'])}}
                                                </div>
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Lochia :
                                                    </label>
                                                </div>
                                                <div class="col-md-2">
                                                    {{Form::text("oe[lochia]",'',['class'=>'form-control','placeholder'=>'Lochia'])}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="input-group">
                                                        {{Form::textarea("oe[other_info]",'',['class'=>'form-control no-resize other_info','placeholder'=>'Other Information','rows'=>'5'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- stich tab -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                    <h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse"
                                                               data-parent="#stich-tab" href="#stich-tab" aria-expanded="false"
                                                aria-controls="stich-tab">4. Stich Line</a></h4>
                                    </div>
                                    <div id="stich-tab" class="panel-collapse collapse" role="tabpanel"
                                        aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-1 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        LE :
                                                    </label>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        {{Form::text("stich_line[le]",'',['class'=>'form-control','placeholder'=>'LE'])}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                 <!-- Treatment history  -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#treatment" href="#treatment" aria-expanded="false"
                                                aria-controls="past-history">5. Treatment</a></h4>
                                    </div>
                                    <div id="treatment" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body" id="parent">
                                            <div class="row treatment-data" id="t_data_1">
                                                <div class="col-md-2 pr-0">
                                                    <label class="vertical-form-label pr-0">
                                                        Select Medicine :
                                                    </label>
                                                </div>
                                                <div class="col-md-9 complain-multi medicine-picker">
                                                {{Form::select("treatment[medicinedata][]",$medicines,'',['id'=>'treatment-medicine','class'=>'form-control medicine','multiple'=>true,])}}
                                                </div>
                                            </div>
                                            <div class="page-loader-wrapper medicine-loader d-none">
                                                <div class="loader">
                                                    <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                                </div>
                                            </div>
                                            <div class="medicine-data">
                                                {{-- append new medicines data --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{Form::hidden('old_medicine_data','',['class'=>'old-medicine-data'])}}
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                Follow Up : &nbsp;
                                            </span>
                                            {{-- {{Form::text("oe[follow_up]", '',['class'=>'form-control datetimepicker followup followup-date'])}} --}}
                                            {{Form::text("oe[follow_up]", '',['class'=>'form-control datetimepicker followup next-date'])}}
                                            {{Form::hidden("appointment_time", '',['class'=>'form-control next-time'])}}
                                        </div>
                                        <span class="gsac-no-data-followup form-error-msg"></span>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            {{-- {{Form::text("oe[follow_up_date_diff]",'',['class'=>'form-control followup-date-diff ','maxlength'=>3,'placeholder'=>'Date Diff'])}} --}}
                                            {{-- {{Form::text("oe[follow_up_date_diff]",'',['class'=>'form-control next-day','maxlength'=>3,'placeholder'=>'Date Diff'])}} --}}
                                            {{Form::hidden('appointment_date',$lastAppointment->date,['class'=>'last-appointment-date'])}}
                                        </div>
                                    </div>
                                    {{-- <span class="col-md-1 p-2 history-lmp-date">Day</span> --}}
                                </div>
                                <div class="col-sm-12">
                                    {{Form::submit('submit',['class'=>'btn btn-primary submit submit-btn'])}}
                                    <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                                    <a href="{{URL::to('anc-iui-ivf')}}" class="btn btn-default">Cancel</a>
                                </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('modal')
@stop
@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>$.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script src="{{URL::to('public/js/image-uploader.js')}}"></script>
    <script type="text/javascript">
        var code = '';
        $(function () {
            //Datetimepicker plugin
            $('.datetimepicker').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                // minDate:new Date(),
                time:false,
                weekStart: 1
            });
            $('.lmd-date').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                time:false,
                weekStart: 1
            });

            $('.timepicker').bootstrapMaterialDatePicker({
                date: false,
                shortTime: true,
                format: 'hh:mm a',
                switchOnClick: true
            });

        });

        $('#treatment-medicine').select2();

        $('.co_value_data').selectize({
            delimiter: ',',
            persist: false,
            // create: function(input) {
            //     return {
            //         value: input,
            //         text: input
            //     }
            // }
        });
        $(document).ready(function(){
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
            $('.complain-multi .show-tick').addClass('d-none');
            $('.ho-past-personal-data .btn-group').addClass('d-none');
            $('.select2-search__field').css('width','280px');
            $(document).on('click','.next-appointment',function(){
                var isError = errorMessage();
                if (isError == true) {
                    $('#next-appointment').trigger('reset');
                    $('#next-appointment-modal').modal('show');
                }
            });

            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var isError = errorMessage();
                if (isError == false) {
                    return false;
                }
                var formData = new FormData($("#stich-form")[0]);
                if(this.value==1){
                    formData.append('isprint', 1);
                }
                if(this.value==2){
                    formData.append('is_pdf', 1);
                    $(this).prop('disabled',true);
                }
                stichForm(formData);
            });

            $(document).on('change','.followup',function(){
                var fDate = $(this).val();
                $('.f-date').val(fDate ? fDate : '');
            });

            $(document).on('change','select.ho_type',function(){
                hoType($(this).val());
            });

            $(document).on('change', 'select.medicine', function () {
                var value = $(this).val();
                medicineData(value, $(this).data('type'));
            });

        });

        function stichForm(data,next=null){
            $('.submit-btn').prop('disabled','disabled');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url:'{{URL::to("stich")}}',
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'json',
                data:data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data){
                if(data.status == 1){
                    window.location.href = "{{URL::to('anc-iui-ivf')}}";
                }else if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                    $('.stich-id').val(data.id);
                    $('.submit-btn').prop('disabled',false);
                }
                else{
                    location.reload();
                }
            });
        }

        function errorMessage() {
            var valid = 1;
            $('.ho-data-msg').text('');
            $('.gsac-no-data-followup').text('');
            $('.seen-by-error').text('');
            $('#oe').removeClass('show');
            // $('.ho-tab').removeClass('show');
            $('.p-info').removeClass('show');
            // if($('select.ho-data').val() == ''){
            //     valid = 0;
            //     $('.ho-data-msg').text('The ho field is required.');
            //     $('.ho-tab').addClass('show');
            // }
            if($('select.seen-by').val() == ''){
                $('.seen-by-error').text('Please select doctor');
                $('html, body').animate({
                    scrollTop: ($('.seen-by').offset().top - 150)
                }, 1000);
                return false;
            }
            return true;
        }

        var medicinesValue = @json($medicines);

        function hoType(value){
            var valueArray = ["2","3","4"];
            if(jQuery.inArray(value, valueArray) != -1){
                $('.when-where').removeClass('d-none');
            }else{
                $('.when-where').addClass('d-none');
            }
        }
        function medicineData(value) {
            // console.log($('.old-medicine-data').val());
            var getUrl = window.location;
            var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
            var avoid = "/gynec";
            baseUrl = baseUrl.replace(avoid,'');
            var oldMedicineData = [];
            if ($('.old-medicine-data').val() != '') {
                oldMedicineData = $('.old-medicine-data').val().split(',');
            }
            // console.log(oldMedicineData);
            var difference = [];
            jQuery.grep(value, function(el) {
                if (jQuery.inArray(el, oldMedicineData) == -1) difference.push(el);
            });
            var differenceMedicine = difference.toString().replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_');
            if (value.length > oldMedicineData.length) {
                $('.medicine-loader').removeClass('d-none');
                $('.anc .selectize-dropdown').addClass('d-none');
                var id = parseInt(parseInt($('#total_medicines').val()) + 1);
                var baseUrl = baseUrl.replace('stich','');
                $.ajax({
                    url: baseUrl+'anc/get-existed-medicine-data',
                    dataType: 'json',
                    type: 'GET',
                    // async: false,
                    data: {
                        medicine_name: difference.toString(),
                    },
                }).done(function(data) {
                    madicineData = "";
                    madicineData += "<div class='row' data-id=" + differenceMedicine + ">"+
                                        "<div class='col-md-4'><div class='input-group'><input type='checkbox' name='treatment["+differenceMedicine+"][injection_status]' class='medicines-checkbox' value=1><span class='input-group-addon'>Medicine : &nbsp</span>"+
                                        "<input type ='text' name='treatment["+differenceMedicine+"][medicine]' value='"+difference.toString()+"' readonly class='form-control'></div></div>";
                        // empty stomach and after meal
                        var dose = {"0":"How to take","1":"જમ્યા પછી","2":"જમ્યા પહેલાં","3":"માસિકની જગ્યાએ મુકવી"};
                        madicineData += "<div class='col-md-2'><div class='form-group'><select name='treatment["+differenceMedicine+"][medicine_status]' class='form-control select-padding-0 dose medicine-status'>";
                        $.each(dose, function(key, value) {
                            madicineData +=  '<option value="' + key + '"' + ((data.data != null && data.data.medicine_status != null && key == data.data.medicine_status) ? 'selected' : '') + '>'+value+'</option>';
                        });
                        madicineData += "</select></div></div>";
                        // dose
                        var dose = {"1":"OD","2":"BD","3":"TDS","4":"ADS","5":"Weekly / 1","6":"Weekly / 2","7":"Stat","8":"SOS"};
                        madicineData += "<div class='col-md-2'><div class='form-group'><select name='treatment["+differenceMedicine+"][dose]' class='form-control select-padding-0 dose'>";
                        madicineData += '<option value="">Select Dose</option>';
                        $.each(dose, function(key, value) {
                            madicineData += '<option value="' + key + '"' + ((data.data != null && data.data.dose != null && key == data.data.dose) ? 'selected' : '') + '>' +value+'</option>';
                        });
                        madicineData += "</select></div></div>";
                        // end dose
                        // number
                        madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Days. : &nbsp</span>"+
                        "<input type ='number' name='treatment["+differenceMedicine+"][no]' class='form-control' value='" + ((data.data != null && data.data.number != null) ? data.data.number : '') + "'></div></div>";
                        // quantity
                        madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Quantity : &nbsp</span>"+
                                        "<input type ='text' name='treatment["+differenceMedicine+"][quantity]' class='form-control' value='" + ((data.data != null && data.data.quantity != null) ? data.data.quantity : '') + "'></div></div>";
                        // end quantity
                        madicineData += "</div><div class='row' data-id=" + differenceMedicine + ">";
                        // medicine time morning,afternoon,evening
                        var dose = {"1":"Morning","2":"Afternoon","3":"Evening","4":"Night"};
                        madicineData += "<div class='col-md-3'><div class='form-group'><select name='treatment["+differenceMedicine+"][medicine_time][]' class='form-control select-padding-0 dose' multiple='true' title='Select Medicine Time'>";
                        $.each(dose, function(key, value) {
                            madicineData +=  '<option value="' + key + '"' + ((data.data != null &&  data.data.medicine_time != null &&($.inArray(key, data.data.medicine_time) != -1)) ? 'selected' : '') + '>'+value+'</option>';
                        });
                        madicineData += "</select></div></div></div>";
                        $('.medicine-data').append(madicineData);
                        $('.dose').selectpicker('refresh');
                        $('.medicine-loader').addClass('d-none');
                        $('.anc .selectize-dropdown').removeClass('d-none');
                        // $('.treatment-data').bind('click');
                        // $('.medicine').removeClass('d-none');
                        // $('.selectize-dropdown-content').on('click');
                }).fail(function() {
                });
            } else {
                // console.log(differenceMedicine);
                jQuery.grep(oldMedicineData, function(el) {
                    if (jQuery.inArray(el, value) == -1) difference.push(el);
                });
                $('.row[data-id="' + difference.toString().replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_') + '"]').remove();
                // $('medicine-data.row[data-id="' + difference.toString().replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_') + '"]').remove();
                // $('#' + difference.toString().replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_')).remove();
            }
            // console.log(value);
            $('.old-medicine-data').val(value.toString())
        }
    </script>
@stop
