@extends('layouts.main')
@section('parentPageTitle', 'Medical')
@section('title', 'Medical')
@section('page-style')
    <link href="https://use.fontawesome.com/releases/v5.0.7/css/all.css" rel="stylesheet">
@stop
@section('content')

<div class="card">
    <div class="body medicine-body-padding">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <h5>{{ucwords(strtolower($patients->name))}}</h5>
                </div>
                <div class="col-md-6 text-right">
                    {{Form::select('category',$categoryData,'',['class'=>'category-data-value','placeholder'=>'Select Category'])}}
                
                    {{-- <a href="#"> <button class="btn btn-primary print-medicine">
                            Print
                        </button> </a> --}}<a href="{{URL::to('medical')}}">
                        <button class="btn btn-primary">
                            Back
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="row clearfix ivf">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6"><h5 class="ml-2"></h5></div>
                <div class="col-md-2"></div>
                @if(count($categoryData) > 1)
                    <div class="col-md-2">
                        
                    </div>
                @endif
            </div>
        </div>
        <div class="medicine-data table-responsive active">
            <!-- table data here include -->
        </div>
    </div>
@stop
@section('page-script')
<script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script src="{{url('assets/js/pages/ui/notifications.js')}}"></script>
    <script type="text/javascript">
        var qstring = '';
        var lastCId = '';
        var page = '';
        var patientId = "{{$patientsId}}";
        var status = '';
        var cId = '';
        var date = '';
        var type = '';

        $(document).ready(function(){
            getMedicineData();
            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&patient_id='+patientId;
                getMedicineData(qstring);
            });

            $(document).on('click', '.print-medicine',function(event){
                event.preventDefault();
                qstring = 'page='+page+'&patient_id='+patientId+'&is_print=1';
                getMedicineData(qstring);
            });
            $(document).on('change','select.category-data-value',function(){
                cId = $(this).val();
                $('.category-data').addClass('d-none');
                if(cId != ''){
                    $('.category-data-'+cId).removeClass('d-none');
                }else{
                    $('.'+lastCId).removeClass('d-none');
                }
            });

            $(document).on('dblclick', '#p-table tbody tr', function(event) {
                var patientsId = $(this).data('id');
                if(typeof(patientsId) !== 'undefined'){
                    var url = "{{URL::to('get-medicine')}}"+'/'+patientId;
                    window.location.href=url;
                }
            });
            $(document).on('click','.edit-remark-icon',function(e){
                e.preventDefault();
                var dId = $(this).data('id');
                var value = $(this).data('value');
                if($('.remark-data').hasClass('remark-val')){
                    var previousId = $('.remark-val').data('id');
                    var previousRemark = $('.remark-val').data('value');
                    var data = "<div class='edit-remark-data edit-remark-'"+previousId+"'>"+
                        wordwrap(""+previousRemark+"", 100,'<br>\n')+
                        "<span class='edit-remark'>"+
                            "<i class='material-icons edit-remark-icon' data-value="+previousRemark+" data-id="+previousId+">edit</i>"+
                        "</span>"+
                    "</div>";
                    $('.edit-remark-'+previousId).html(data);
                }
                var remarkData = "<input type ='text' name='total' value='"+value+"' class='form-control remark-val remark-data remark-value-"+dId+"' data-value='"+value+"' data-id="+dId+" autocomplete='off'>";
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
                var appointmentId = $(this).data('id');
                var remarkValue = 'note='+remark+'&appointmet_id='+appointmentId;
                updateMedicalRemark(remarkValue,'blur');
            });

            $(document).on('keyup','.remark-data',function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    var remark = $(this).val();
                    var appointmentId = $(this).data('id');
                    var remarkValue = 'note='+remark+'&appointmet_id='+appointmentId;
                    updateMedicalRemark(remarkValue,'keyup');
                }
            });
        });

        // get appointment data
        function getMedicineData(qstring){
            $.ajax({
                url: "{{URL::to('get-medicine')}}"+'/'+'{{$patientsId}}'+'?'+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.medicine-data').html(data.patients);
                   
                    $(".daterange").daterangepicker({
                        // autoUpdateInput: false,
                        locale: {
                            direction: 'drop-down-date-range',
                            cancelLabel: 'Cancel',
                            format: 'D/M/Y'
                        }
                    }).on("change", function() {
                        type = $(this).data('id');
                        date = $(this).val();
                        qstring = 'date='+date+'&type='+type;
                        getMedicineData(qstring);
                    });
                    if(data.dateType != '' && data.date != '' && typeof data.dateType != 'undefined' && typeof data.date != 'undefined'){
                        $('.'+data.dateType).val(data.date);
                    }
                    if(data.lastType != '' && data.lastType != null){
                        lastCId = data.lastType;
                        $('.'+data.lastType).removeClass('d-none');
                    }
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.patients);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {

            });
        }
        //update medical note
        function updateMedicalRemark(remarkValue,type){
            $.ajax({
                url: "{{URL::to('appointment-update-medicalRemark')}}?"+remarkValue,
                dataType: 'json',
            }).done(function(data) {
                if(type == 'blur'){
                    showNotification('bg-blue', 'Medical note changed successfully.', 'bottom', 'right', "", "");
                }
                getMedicineData();
            }).fail(function() {

            });
        }

    </script>
@stop
