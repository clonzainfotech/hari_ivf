@extends('layouts.main')
@section('parentPageTitle', 'Gynec Appointment History')
@section('title', 'Gynec Appointment History')
@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <link href="{{URL::to('css/image-uploader.css')}}" rel="stylesheet">
@stop

@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong class="text-secondary">{{ucwords($patient->name)}}</strong>
                    @php
                        $careof = (!empty($patient->reference_doctor_id) && isset($referenceDoctor[$patient->reference_doctor_id])) ? $referenceDoctor[$patient->reference_doctor_id] : '';
                        if(!empty($patient->reference_doctor_id) && $patient->reference_doctor_id == '1' )
                        {
                            $careof = !empty($patient->reference_pt_name) ? $patient->reference_pt_name.'('.$patient->reference_pt_mobile.')' :'SELF--';
                        }
                    @endphp
                    {{' care of '.$careof}}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix anc">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <div class="row mb-2">
                        <div class="col-md-12 col-lg-12">
                            <h2><strong>Stich Appointment</strong>
                            {{-- <small>Description text here...</small> --}}
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <span>Previous Visit :</span>
                            @if(count($date)>0)
                                <?php
                                $date = array_reverse($date);
                                $ii = 1;
                                ?>
                                @foreach($date as $k => $dt)
                                    <?php $ij = $ii++;?>
                                    {{Form::radio("date",$dt,'',[
                                            'id'=>'dt_'.$ij,
                                            'class'=>'stich-date',
                                        ])}}
                                    <label class="pl-0 pr-3" for="dt_{{$ij}}">
                                        {{cdate($dt)->format('d-m-Y') }}
                                    </label>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    {{-- <ul class="header-dropdown col-md-3">
                        <li class="w-100">
                            {{Form::select("date",$date,'',[
                                'class'=>'form-control select-padding-0 stich-date',
                                'required',
                                'placeholder'=>'Select Date'
                            ])}}
                        </li>
                    </ul> --}}
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">

                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['class'=>'form','files'=>true,'id'=>'stich-form'])}}
                                <div class="gynec-history">

                                </div>
                                {{Form::hidden('patients_id',$pId,['class'=>'patients-id'])}}
                                <div class="col-sm-12">
                                    {{Form::submit('submit',['class'=>'btn btn-primary submit'])}}
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
@section('page-script')
<script src="{{url('js/gynec.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
$.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
<script src="{{URL::to('js/image-uploader.js')}}"></script>

<script type="text/javascript">
    var code = '';
    var patientsId = $('.patients-id').val();
    var date = '';
    var qstring = '';
    $(document).ready(function(){
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        qstring = 'date='+date;
        stichFormData(qstring);
        $(document).on('click','.submit',function(e){
            e.preventDefault();
            var formData = new FormData($("#stich-form")[0]);
            if(this.value==1){
                formData.append('isprint', 1);
            }
            storeStichFormData(formData);
        });

        $(document).on('change','input.stich-date',function(e){
            date = $(this).val();
            qstring = 'date='+date;
            stichFormData(qstring);
        });

        $(document).on('change', 'select.medicine', function () {
            var value = $(this).val();
            medicineData(value, $(this).data('type'));
        });

    });

    function storeStichFormData(data,next=null){
        $('.seen-by-error').text('');
        if($('select.seen-by').val() == ''){
            console.log('okok');
            $('.seen-by-error').text('Please select doctor');
            $('html, body').animate({
                scrollTop: ($('.seen-by').offset().top - 150)
            }, 1000);
            return false;
        }
        var url = "{{URL::to('anc-iui-ivf')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'{{URL::to("stich")}}',
            type:'POST',
            dataType:'json',
            data:data,
            enctype: 'multipart/form-data',
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

    function stichFormData(qstring){
        $.ajax({
            url:"{{URL::to('stich/history')}}"+'/'+$('.patients-id').val()+'?'+qstring,
            dataType:'json',
            type:'GET',
        }).done(function(data){
            $('.gynec-history').html(data.editGynec);
            $('.ho-value .selectized').addClass('d-none');
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
                $('#treatment-medicine').select2();
            });

            $('.select-padding-0').selectpicker('refresh');
        }).fail(function(error){

        });
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
                    var dose = {"1":"જમ્યા પછી","2":"જમ્યા પહેલાં","3":"માસિકની જગ્યાએ મુકવી"};
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
    var medicinesValue = @json($medicines);
</script>
@stop
