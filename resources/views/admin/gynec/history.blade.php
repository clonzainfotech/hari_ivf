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
                <div class="header d-flex">
                    <div class="col-md-6 col-sm-6">
                        <h2><strong class="text-secondary">{{ucwords($patient->name)}}</strong>
                        @php
                            $careof = (!empty($patient->reference_doctor_id) && isset($referenceDoctor[$patient->reference_doctor_id])) ? $referenceDoctor[$patient->reference_doctor_id] : '';
                            if(!empty($patient->reference_doctor_id) && $patient->reference_doctor_id == '1' )
                            {
                                $careof = !empty($patient->reference_pt_name) ? $patient->reference_pt_name. (!empty($patient->reference_pt_mobile) ? '('.$patient->reference_pt_mobile.')' : '') :'SELF--';
                            }
                        @endphp
                        {{' care of '.$careof}}</h2>
                    </div>
                    <div class="col-md-6 col-sm-6 ">
                        <a href="{{URL::to('get-all-report/'.encrypt($patient->id).'?status=gynec')}}" target="_blank" class="pull-right">
                            <button class="btn btn-primary ">View Reports</button>
                        </a>
                        @if($isIvfHistory == true)
                            <a href="{{URL::to('ivf/history/'.encrypt($patient->id))}}" target="_blank" class="btn btn-primary pull-right">IVF History</a>
                        @endif
                        @if($isAncHistory == true)
                            <a href="{{URL::to('anc/history/'.encrypt($patient->id))}}" target="_blank" class="btn btn-primary pull-right">ANC History</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix anc">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                <strong class="pr-3">GYNEC Previous Visit</strong>
                     {{-- <small>Description text here...</small> --}}
                    <!-- </h2> -->
                    <!-- <ul class="header-dropdown col-md-3">
                        <li class="w-100">
                            {{Form::select("date",$date,'',[
                                'class'=>'form-control select-padding-0 gynec-date',
                                'required',
                                'placeholder'=>'Select Date'
                            ])}}
                        </li>
                    </ul> -->
                    @if(count($date)>0)
                                <?php
                                // $date = array_reverse($date);
                                $ii = 1;
                                ?>
                                @foreach($date as $k => $dt)
                                    <?php $ij = $ii++;?>
                                    {{Form::radio("date",$dt,'',[
                                            'id'=>'dt_'.$ij,
                                            'class'=>'gynec-date',
                                        ])}}
                                    <label class="pl-0 pr-3" for="dt_{{$ij}}">
                                        {{cdate($dt)->format('d-m-Y') }}
                                    </label>
                                @endforeach
                            @endif
                    <a href="javascript:void(0)" class="preview-file mt-0"  data-id="{{$patientsId}}">Preview all</a>

                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">

                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['class'=>'form','files'=>true,'id'=>'gynec-form'])}}
                                <div class="gynec-history">

                                </div>
                                {{Form::hidden('patients_id',$patientsId,['class'=>'patients-id'])}}
                                <div class="col-sm-12">
                                    {{Form::submit('submit',['class'=>'btn btn-primary submit'])}}
                                    {{-- <a class="btn btn-primary next-appointment text-white">Save & Next Appointment</a> --}}
                                    <button type="submit" class="btn btn-primary submit" value="1">Save & Preivew</button>
                                    {{-- <button type="submit" class="btn btn-primary submit admission-print d-none" value="2">Admission Print</button> --}}
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
<div class="modal fade preview-file-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header header-bottom-border">

                
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="modal-title" id="myModalLabel">Gynec History</h5>
                    </div>
                </div>
                    <button type="button" class="close mb-2" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="gynec-details-data">
                    </div>
                </div>

                <div class="modal-footer footer-top-border text-right d-inline-block">
                    <button type="button" class="btn btn-primary waves-effect" data-dismiss="modal">CLOSE</button>
                    
                </div>
            </div>
        </div>
    </div>
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
    $('.timepicker').bootstrapMaterialDatePicker({
        date: false,
        shortTime: true,
        format: 'hh:mm a',
        switchOnClick: true
    });
    $(document).ready(function(){
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
        qstring = 'date='+date;
        getGynecData(qstring);
        $(document).on('click','.submit',function(e){
            e.preventDefault();
            var formData = new FormData($("#gynec-form")[0]);
            if(this.value==1){
                formData.append('is_print', 1);
            }
            if(this.value==2){
                formData.append('is_print', 2);
            }
            gynecFormData(formData);
        });

        $(document).on('change','input.gynec-date',function(e){
            date = $(this).val();
            qstring = 'date='+date;
            getGynecData(qstring);
        });

        $(document).on('change','select.duration-data',function(){
            var value = $(this).val();
            var dId = $(this).data('id');
            $('.'+dId).addClass('d-none');
            if(value == 'other'){
                $('.'+dId).removeClass('d-none');
            }
        });
        $(document).on('click','.preview-file',function(e){
            e.preventDefault();
            patientsId = $(this).data('id');
            $('.preview-file-modal').modal('hide');
            $('.anc-details-data').html('');
            $('.preview-file-modal').modal('show');
            
            qstring = 'patient_id='+patientsId;
            getGynecHistoryData(qstring);
            
        });
        $(document).on('click','.edit-btn',function(){
            date = $(this).data('date');
            qstring = 'date='+date;
            $("input:radio[name='date'][value='" + date + "']").attr('checked', 'checked');
            $('.preview-file-modal').modal('hide');
            getGynecData(qstring);
        });

        $(document).on('click','.print-btn',function(){
            date = $(this).data('date');
            qstring = 'patient_id='+patientsId+'&history_date='+date;
            getGynecHistoryData(qstring);
        });
    });

    function getGynecHistoryData(qstring)
    {
        $.ajax({
            url:'{{URL::to("get-gynec-details")}}?'+qstring,
            type:'GET',
            dataType:'json'
        }).done(function(data){
            $('.gynec-details-data').html('');
            if(data.gynec_type == 1){
                var ancPreview = $('.gynec-details-data').html();
                var buttonHtml = '';
                var previewData = '';
                for(i=0; i<data.data.length;i++)
                {
                    if(typeof data.date[i] != 'undefined'){
                        var linkDate = moment(new Date(data.date[i])).format('YYYY-MM-DD HH:mm:ss');
                        var date = moment(new Date(data.date[i])).format('DD MMMM YYYY');
                    }
                   
                        buttonHtml = ancPreview + '<div class="row mb-1"><div class="col-md-6 text-left"><h5 class="modal-title" id="myModalLabel">Date:- <span class="anc-appointment-date">'+date+'</span></h5></div><div class="col-md-6 text-right"><a class="btn edit-btn btn-sm btn-primary" data-date="'+linkDate+'">Edit</a>{{-- <a class="btn print-btn btn-sm btn-primary" data-date="'+linkDate+'">Print</a> --}}</div></div>';
                        ancPreview = buttonHtml + data.data[i];
                    $('.gynec-details-data').html(ancPreview);
                    ancPreview = ancPreview + '<div class="row sepreator"></div>';
                }
                // console.log(ancPreview);
            }
            if(data.gynec_type == 2){
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(data.data);
                w.document.close();
                w.window.print();
            }
        }).fail(function(error){

        });
    }
    function gynecFormData(data,next=null){
        // var isError = errorMessage();
        // if (isError == false) {
        //     return false;
        // }
        $('.seen-by-error').text('');
        $('.surgically-date-error').text('');
        $('.surgically-time-error').text('');
        $('.surgically-type-error').text('');
        $('.weight').text('');
        $('.p-info').removeClass('show');
        var weight=$('#weight').val();
        if(weight == ''){
            $('.weight').text('The weight is required');
            $('.p-info').addClass('show');
            return false;
        }
        if($('select.seen-by').val() == ''){
            $('.seen-by-error').text('Please select doctor');
            $('html, body').animate({
                scrollTop: ($('.seen-by').offset().top - 150)
            }, 1000);
            return false;
        }
        // if($('#surgically-type').prop('checked') == true){
        //     if($('select.surgically_type').val() == '')
        //     {
        //         $('.surgically-type-error').text('This field Required');
        //         return false;
        //     }
        //    if($('.surgically_date').val() == '' )
        //    {
        //        $('.surgically-date-error').text('This field Required');
        //        return false;
        //    }
        //    if($('.surgically_time').val() == '' )
        //    {
        //        $('.surgically-time-error').text('This field Required');
        //        return false;
        //    }
        // }
        $('.submit').prop('disabled', true);

        var url = "{{URL::to('anc-iui-ivf')}}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:'{{URL::to("gynec")}}',
            type:'POST',
            dataType:'json',
            data:data,
            enctype: 'multipart/form-data',
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(data){
            if(data.status == 'true'){
                window.location.href = url;
            }else if(data.status == 1){
                $('.gynecId').val(data.id);
                w = window.open(window.location.href, "_blank");
                w.document.open();
                w.document.write(data.data);
                w.document.close();
                w.window.print();
                // window.location.href = url;
                $('#next-appointment-modal').modal('hide');
            }else{
                location.reload();
            }
        });
    }
    function errorMessage() {
        var valid = 1;
        if ($("input[name='ho[follow_up]']").val() == '') {
            $('.gsac-no-data-followup').text('The Follow up Date is required.');
            $('html, body').animate({
                scrollTop: ($('.gsac-no-data-followup').offset().top - 150)
            }, 200);
            return false;
        }
        if(valid == 0){
            $('html, body').animate({
                scrollTop: ($('.weight').offset().top - 150)
            }, 1000);
            return false;
        }
        return true;
    }
    function getGynecData(qstring){
        $.ajax({
            url:"{{URL::to('gynec/history')}}"+'/'+$('.patients-id').val()+'?'+qstring,
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
                    create: function(input) {
                        return {
                            value: input,
                            text: input
                        }
                    }
                });
                if(typeof data.reportImagesData != 'undefined'){
                    $('.report-images').imageUploader({
                        preloaded: jQuery.parseJSON(data.reportImagesData),
                        imagesInputName: 'investigation[report][images]',
                        preloadedInputName: 'report_old'
                    });
                }else{
                    $('.report-images').imageUploader({
                        imagesInputName: 'investigation[report][images]',
                    });
                }
                if(typeof data.ancImagesData != 'undefined'){
                    $('.anc-images-data').imageUploader({
                        preloaded: jQuery.parseJSON(data.ancImagesData),
                        imagesInputName: 'investigation[anc][images]',
                        preloadedInputName: 'anc_old'
                    });
                }else{
                    $('.anc-images-data').imageUploader({
                        imagesInputName: 'investigation[anc][images]',
                    });
                }
                $('#treatment-medicine').select2();
                $('.duration-value .selectized').addClass('d-none');
                $('#personal-history .selectized').addClass('d-none');
                
            });

            $('.select-padding-0').selectpicker('refresh');
            regularType($('select.past-mh-2').val(),'past-ir-regular-data');
            regularType($('select.present-mh-2').val(),'present-ir-regular-data');
            
        }).fail(function(error){

        });
    }
    var medicinesValue = @json($medicines);
</script>
@stop
