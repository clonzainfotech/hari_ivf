@extends('layouts.main')
@section('parentPageTitle', 'IUI Appointment')
@section('title', 'Add IUI Appointment')

@section('page-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.default.min.css" integrity="sha256-ibvTNlNAB4VMqE5uFlnBME6hlparj5sEr1ovZ3B/bNA=" crossorigin="anonymous" />
    <style>
        .table td, .table th{
            padding: .35rem !important;
        }
        .iui-history-visit-data{
            border: 1px solid;
            padding: 20px;
        }
        .visit-lable{
            color:#999;
        }
        .visit-lable-value{
            color:black;
        }
        .selectize-control .selectize-input.disabled {
            opacity: 1 !important;
            background-color: #fff !important;

        }
        .selectize-control.multi .selectize-input.disabled [data-value]{
            color: black !important;
        }
        .selectize-input{
            height: 100% !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 17px !important;
            padding-left: 16px;
            padding-right: 16px;
        }
        .selectize-control.multi .selectize-input.has-items{
            height: auto !important;
            padding: 6px 16px !important;
        }
        .selectize-input > input{
            width: auto !important;
        }
        .selectize-dropdown-content{
            height: auto !important;
            font-size: 13px !important;
            line-height: 24px !important;
        }
        .selectize-dropdown{
            height: auto !important;
            z-index: 1000 !important;
            width: 100% !important;
            margin-top: 11px !important;
            background: #fff !important;
        }
    </style>
@stop
@section('content')
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong class="text-secondary">{{ucwords($iuiPatients->name)}}</strong></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix ivf">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>IUI Appointment</strong></h2>
                    <ul class="header-dropdown col-md-6 text-right">
                        <li class="w-50">
                            {{Form::select("date",$iuiHistoryDate,'',['class'=>'form-control select-padding-0 iui-date','placeholder'=>'Select Date'])}}
                        </li>
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
                        <h3>Today : {{\Carbon\Carbon::now()->format('d M Y')}}</h3>
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['class'=>'form extra-iui-form','files'=>'true'])}}
                                <div class="iui-extra-data">
                                    {{-- appned form data --}}
                                </div>
                                {{Form::hidden('patient_id',encrypt($iuiPatients->id),['class'=>'patient-id'])}}
                                {{Form::hidden('cycle_no',encrypt($cycle_no),['class'=>'cycle-no'])}}
                                <div class="col-sm-12">
                                    {{Form::submit('submit',['class'=>'btn btn-primary submit'])}}
                                    <button type="submit" class="btn btn-primary submit" value="1">Save & Preview</button>
                                    <a href="{{URL::to('iui')}}" class="btn btn-default">Cancel</a>
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
    <script src="{{asset('public/js/iui.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script src="{{URL::to('public/js/image-uploader.js')}}"></script>
    <script type="text/javascript">
        var code = '';
        var patientsId = $('.patients-id').val();
        
        $(document).ready(function(){
            getIuiData();
            $('.complain-multi .show-tick').addClass('d-none');
            
            $(document).on('click','.submit',function(e){
                e.preventDefault();
                $('.submit').attr('disabled',true);
                var iuiFormData = new FormData($(".extra-iui-form")[0]);
                if(this.value==1){
                    iuiFormData.append('isprint', 1);
                }
                extraIuiFormData(iuiFormData);
            });


            $(document).on('change','select.iui-date',function(){
                var date = $(this).val();
                $('.iui-extra-data').html('');
                getIuiData('date='+date);
            });

        });
        
        function extraIuiFormData(data){
            $.ajax({
                url:'{{URL::to("iui/store-extra-visit")}}',
                type:'POST',
                enctype: 'multipart/form-data',
                dataType:'json',
                data:data,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data){
                if(data.status == '1'){
                    window.location.href = "{{URL::to('iui')}}";
                }
                else if(data.status == '2')
                {
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.preview);
                    w.document.close();
                    w.window.print();
                }
                else{
                    
                }
            });
        }

        function getIuiData(qstring){
            var pId = $('.patient-id').val();
            var cycleNo = $('.cycle-no').val();
            $.ajax({
                url: "{{URL::to('iui/extra-visit')}}"+'/'+pId+'/'+cycleNo+'?'+qstring,
                dataType: 'json',
                type:'GET',
            }).done(function(data){
                if(data.status == 1){
                    $('.iui-extra-data').html(data.extra_visit_data);
                    $(function () { 
                        //Datetimepicker plugin
                        $('.datetimepicker').bootstrapMaterialDatePicker({
                            format: 'dddd DD MMMM YYYY',
                            // minDate:new Date(),
                            clearButton: true,
                            time:false,
                            weekStart: 1
                        });
                        $('.lmd-date').bootstrapMaterialDatePicker({
                            format: 'dddd DD MMMM YYYY',
                            clearButton: true,
                            time:false,
                            weekStart: 1
                        });
                        $('.select-padding-0').selectpicker('refersh');
                    });

                    $('.co_value_data').selectize({
                        delimiter: ',',
                        persist: false,
                        create: function(input) {
                            return {
                                value: input,
                                text: input
                            }
                        },
                        createFilter: function(input) {
                            if(input.length > 250) {
                                alert('length must be less than 250 characters');
                            }
                            return input;
                        }
                    });
                    $('#treatment-medicine').select2();
                    $('.s-picker').selectize({
                        delimiter: ',',
                        persist: false,
                        create: function(input) {
                            return {
                                value: input,
                                text: input
                            }
                        },
                        createFilter: function(input) {
                            if(input.length > 250) {
                                alert('length must be less than 250 characters');
                            }
                            return input;
                        }
                    });
                }
            }).fail(function(error){

            });
        }

        var medicinesValue = @json($medicines);
    </script>
@stop