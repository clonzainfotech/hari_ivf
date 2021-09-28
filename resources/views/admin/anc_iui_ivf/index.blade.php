@extends('layouts.main')
@section('parentPageTitle', 'ANC-IUI-IVF')
@section('title', 'ANC-IUI-IVF')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')
    <div class="row clearfix anc-iui-ivf">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>ANC-IUI-IVF</strong></h2>
                </div>

                <div class="body">
                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3"><input type="text" class="form-control daterange" placeholder="Select Date"></div>
                            <div class="col-md-3">
                                <ul class="nav nav-tabs padding-0">
                                    {{Form::select('patient_id',$patients,'',[
                                        'class'=>'form-control select-padding-0 patient-id',
                                        'placeholder'=>'Select Patient',
                                        'id' => 'patient_id',
                                        'data-live-search' => 'true'
                                    ])}}
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <ul class="nav nav-tabs padding-0">
                                    <div class="input-group">
                                        <input type="number" class="form-control search-word" placeholder="Search by word">
                                        <span class="input-group-addon search-border">
                                            <i class="zmdi zmdi-search"></i>
                                        </span>
                                    </div>
                                </ul>
                            </div>
                            
                            <div class="col-md-1">
                                <a href="javascript:void(0);">
                                    <button class="btn btn-primary print-all m-0">
                                        Print
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-2 checkbox">
                                {{Form::checkbox('advanced_search','',false,[
                                    'id'=>'cash_box',
                                    'class'=>'advanced_search',
                                ])}}
                                <label for="cash_box">
                                    Advance Search
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2 advanced-search-box d-none">
                        <div class="col-lg-4 col-md- col-sm-6">
                            {{Form::select('reference_doctor',$referenceDoctor, '',[
                                'class'=>'form-control select-padding-0 reference-doctor',
                                'placeholder'=>'Select Reference',
                                'id' => 'reference_doctor',
                                'data-live-search' => 'true'
                            ])}}
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            {{Form::select('hospital_doctor',$hospitalDoctor, '',[
                                'class'=>'form-control select-padding-0 hospital-doctor',
                                'placeholder'=>'Select Hospital Doctor',
                                'data-live-search' => 'true'
                            ])}}

                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-6 ">
                            {{Form::select('category',$categoryData,'',[
                                'class'=>'form-control select-padding-0 category',
                                'placeholder'=>'Select Category',
                                'data-live-search' => 'true'
                            ])}}
                        </div>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content m-t-10">

                        <!-- notification -->
                        @if(Session::has('msg'))
                            <div class="alert alert-success">
                                <strong>Success!</strong> {{Session::get('msg')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">
                                        <i class="zmdi zmdi-close"></i>
                                    </span>
                                </button>
                            </div>
                        @endif

                        <div class="anc-iui-ivf-data table-responsive active">
                            <div class="row">
                                <div class="page-loader-wrapper medicine-loader">
                                    <div class="loader">
                                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-script')
<script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        $(".daterange").daterangepicker({
            locale: {
                direction: 'drop-down-date-range',
                cancelLabel: 'Clear',
                format: 'D/M/Y'
            }
        });
        var categoryId = '';
        var status = '';
        var date = $('.daterange').val();
        var qstring = 'date='+date;
        var search = '';
        var page = '';
        var patientId = '';
        var referenceDoctorId = '';
        var hospitalDoctorId = '';

        $(document).ready(function(){
            $(document).on('click','.cancelBtn',function(e){
                e.preventDefault();
                $('.daterange').val('');
                date = $('.daterange').val();
                qstring ='page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&categoryId=' + categoryId+'&search='+search;
                getAncIuiIvf(qstring);
            });
            $(document).on('keyup','.search-word',function(){
                search = $(this).val();
                qstring ='page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&categoryId=' + categoryId+'&search='+search;
                getAncIuiIvf(qstring);
            });

            $(document).on('click','.applyBtn',function(e){
                event.preventDefault();
                date = $('.daterange').val();
                qstring ='page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&categoryId=' + categoryId+'&search='+search;
                getAncIuiIvf(qstring);
            });

            $('.next-button').hide();
            getAncIuiIvf(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring ='page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&categoryId=' + categoryId+'&search='+search;
                getAncIuiIvf(qstring);
            });

            $(document).on('change','select.patient-id',function(){
                patientId = $(this).val();
                qstring ='page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&categoryId=' + categoryId+'&search='+search;
                getAncIuiIvf(qstring);
            });

            $(document).on('click', '.print-all', function () {
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&search='+search+'&isprint=1';
                getAncIuiIvf(qstring);
            });

            $(document).on('change','select.reference-doctor',function(){
                referenceDoctorId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' +categoryId;
                getAncIuiIvf(qstring);
            });

            $(document).on('change','select.hospital-doctor',function(){
                hospitalDoctorId = $(this).val();
                qstring = 'page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&search='+search+'&categoryId=' +categoryId;
                getAncIuiIvf(qstring);
            });
            $(document).on('change','.advanced_search',function(){
                $('.advanced-search-box').addClass('d-none')
                if($(this).prop('checked') == true)
                {
                    $('.advanced-search-box').removeClass('d-none');
                }
            })

            $(document).on('change', 'select.category', function () {
                categoryId = $(this).val();
                qstring ='page='+page+'&patient_id='+patientId+'&date='+date+'&reference_doctor_id='+referenceDoctorId+'&hospital_doctor_id='+hospitalDoctorId+'&categoryId=' + categoryId+'&search='+search;
                getAncIuiIvf(qstring);
            });
        });
        </script>
        @if(in_array(Auth::user()->role,[1,3])) 
            <script type="text/javascript">
            $(document).on('dblclick', '.anc-iui-ivf-edit', function(event) {
                var patientId = $(this).data('id');
                var appointmentId = $(this).data('apid');
                var type = $(this).data('type');
                var categoryName = $(this).data('catname');
                var res = categoryName.toLowerCase();
                if(typeof(patientId) !== 'undefined'){
                    var url =res+'/'+type+'/'+patientId+'/'+appointmentId;
                    window.location.href = url;
                }
            });
            </script>
        @endif
        <script type="text/javascript">
        // get all category data
        function getAncIuiIvf(qstring){
            $('.appointment-loader').removeClass('d-none');
            $('.appointmentdata').addClass('d-none');
            $('.pagination').addClass('d-none');
            $.ajax({
                url: "{{URL::to('anc-iui-ivf')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.anc-iui-ivf-data').html(data.appointmentData);
                    var pData = '';
                    pData += '<option value="">Select Patient</option>';
                    $.each(data.patientsData, function(key, value) {
                        pData +=  '<option value="' + key + '">'+value+'</option>';
                    });
                    $('select.patient-id').html(pData);
                    if(typeof data.pId != 'undefined' && data.pId != '' || data.pId != null){
                        $('.patient-id').val(data.pId);
                    }
                    $('.patient-id').selectpicker('refresh');
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.appointmentData);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }
        $(document).on('click','.edit-remark-icon',function(e){
            e.preventDefault();
            $(this).addClass('appointment-selected-tr');
            var dId = $(this).data('id');
            var appointmentId = $(this).data('appointmentid');
            var value = $(this).data('value');
            if($('.remark-data').hasClass('remark-val')){
                var previousId = $('.remark-val').data('id');
                var previousRemark = $('.remark-val').data('value');
                var data = "<div class='edit-remark-data edit-remark-'"+previousId+"'>"+
                    wordwrap(""+previousRemark+"", 30,'<br>\n')+
                    "<span class='edit-remark'>"+
                        "<i class='material-icons edit-remark-icon' data-value="+previousRemark+" data-id="+previousId+">edit</i>"+
                    "</span>"+
                "</div>";
                $('.edit-remark-'+previousId).html(data);
            }
            var remarkData = "<input type ='text' name='total' value='"+value+"' class='form-control remark-val remark-data remark-value-"+dId+"' data-appointmentid='"+appointmentId+"' data-value='"+value+"' data-id="+dId+">";
            $('.edit-remark-'+dId).html(remarkData);
        });

        function wordwrap( str, width, brk, cut){
            brk = brk || '\n';
            width = width || 75;
            cut = cut || false;

            if (!str) { return str; }

            var regex = '.{1,' +width+ '}(\\s|$)' + (cut ? '|.{' +width+ '}|.+$' : '|\\S+?(\\s|$)');

            return str.match( RegExp(regex, 'g') ).join( brk );
        }
        $(document).on('blur','.remark-data',function(){
            var remark = $(this).val();
            var appointmentId = $(this).data('appointmentid');
            var remarkValue = 'remark='+remark+'&appointmet_id='+appointmentId;
            updateRemark(remarkValue,'blur');
        });

        $(document).on('keyup','.remark-data',function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if(keycode == '13'){
                var remark = $(this).val();
                var appointmentId = $(this).data('appointmentid');
                var remarkValue = 'remark='+remark+'&appointmet_id='+appointmentId;
                updateRemark(remarkValue,'keyup');
            }
        });
        function updateRemark(remarkValue,type){
            $.ajax({
                url: "{{URL::to('appointment-update-remark')}}?"+remarkValue,
                dataType: 'json',
            }).done(function(data) {
                if(type == 'blur'){
                    showNotification('bg-blue', 'Remark changed successfully.', 'bottom', 'right', "", "");
                }
                getAncIuiIvf(qstring);
            }).fail(function() {

            });
        }
        // $(document).ready(function () {
            $(document).on('click','.appoitment_content',function(){
                // $('appointment_dropdown_content').css('display','none');
                $('.appointment_dropdown_content').slideUp('medium');
                var patient_id = $(this).data('ptid');
                var appoitmentDate = $(this).data('date');
                var appendClass = $(this).data('class');
                var category = $(this).data('category');
                if($(this).hasClass('dropdown-open'))
                {
                    $('.'+appendClass).slideUp('medium');
                    $(this).removeClass('dropdown-open');
                }
                else{
                    $(this).addClass('dropdown-open');
                    $.ajax({
                    url: "{{URL::to('get-patient-popup-Detail')}}?patients_id="+patient_id+"&appoitmentDate="+appoitmentDate+"&category="+category,
                    dataType: 'json',
                    }).done(function(data) {
                        $('.'+appendClass).html(data.data);
                        
                        // $(data.data).insertAfter($(this));
                        // function () {
                        $('.'+appendClass).slideDown('medium');
                        // }, 
                        // function () {
                        //     $('ul.file_menu').slideUp('medium');
                        // }
                        
                    }).fail(function() {

                    })
                }
                
                });
           
        // });
        $(document).on("click", function(event){
            var $trigger = $(".patient_dropdown");
            if($trigger !== event.target && !$trigger.has(event.target).length){
                $(".appointment_dropdown_content").slideUp("fast");
            }            
        });
    </script>
@stop
