@extends('layouts.main')
@section('parentPageTitle', 'Medicine')
@section('title', 'Medicine')

@section('page-style')
<style>
    .medicine-body-padding{
        padding-bottom: 10px !important;
    }
    .add-mapdata{
        margin-top: -5px !important;
        padding: 0px !important;
    }
</style>

@stop

@section('content')

    <div class="row clearfix">
        <div class="card category-data">
            <div class="body medicine-body-padding">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-5">
                            <h5 class="main-title-text">Complaint Wise Medicine</h5>
                        </div>
                        <div class="col-md-2 ivf">
                            {{Form::select("co_data",$hoco, '',['class'=>'form-control select-padding-0 hoco-data','title'=>'Select Complaint','data-live-search'=>"true",'data-type'=>1])}}  
                            {{-- {{Form::select('co_data',$hoco,'',['class'=>'hoco-data','placeholder'=>'Select Complaint','data-live-search'=>"true",'data-type'=>1])}} --}}
                            <span class="form-error-msg co-error d-none">This field is required</span>
                        </div>
                        <div class="col-md-2 ivf">
                            <div class="form-group">
                                {{Form::select("medicine_data[]",$medicineData, '',['class'=>'form-control select-padding-0 hoco-medicines','multiple','title'=>'Select Medicine','data-live-search'=>"true",'disabled'])}}  
                                <span class="form-error-msg medicine-error d-none">This field is required</span>
                            </div>
                        </div>
                        <div class="col-md-1 add-mapdata">
                            {{-- <a href="{{URL::to('medicines-setting/create')}}"> --}}
                                {{Form::button('Add',['class'=>'btn btn-primary add-medicine','disabled'])}}
                                {{-- <button class="">
                                    Add
                                </button> --}}
                            {{-- </a> --}}
                        </div>
                        <div class="col-md-1 add-mapdata">
                            <a href="{{URL::to('medicines-setting')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </div>
                        {{-- <div class="col-md-2">
                            {{-- <a href="{{URL::to('medicines-setting?is_old='.encrypt(1))}}"> <button class="btn btn-primary print-sms-report">
                                    Old Medicine
                                </button> </a> --}}</div> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="map-data w-100"></div>
        {{-- <div class="card">
            <div class="header">
                <h2><strong>Medicine</strong></h2>
                <ul class="header-dropdown">
                    <li>
                        {{-- <a href="{{URL::to('medicines-setting/create')}}"> <button class="btn btn-primary print-sms-report">
                                Add
                            </button> </a> --}}</li>
                </ul>
            </div>
            <div class="body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="nav nav-tabs padding-0">
                                <div class="input-group">
                                    <input type="text" class="form-control search-medicine" placeholder="Search...">
                                    <span class="input-group-addon search-border">
                                        <i class="zmdi zmdi-search"></i>
                                    </span>
                                </div>
                            </ul>
                        </div>
                    </div>    
                </div>
                <div class="tab-content m-t-10">
                @if(Session::has('msg'))
                    <div class="alert alert-danger">
                        <strong>Success!</strong> {{Session::get('msg')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">
                                <i class="zmdi zmdi-close"></i>
                            </span>
                        </button>
                    </div>
                @endif
                    <div class="medicines-data"></div>
                </div>
            </div>
        </div> --}}
    </div>
@stop
@section('page-script')
<script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script src="{{url('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script type="text/javascript">
        var page = '';
        var search = '';
        var medicineId = '';
        var referenceDoctorId = '';
        var type = "{{$type}}";
        var qstring = '';
        $(document).ready(function(){
            mappingData(qstring);
            $(document).on('change','select.hoco-data',function(){
                $('.ho-co-medicines-data').removeClass('d-none');
                $('.hoco-medicines').attr('disabled');
                $('.hoco-medicines').selectpicker('refresh');
                $('.add-medicine').attr('disabled');
                var value = $(this).val();
                var mType = $(this).data('type');
                qstring = 'medicine_type='+mType+'&value='+value;
                if(value != '' && typeof value != 'undefined'){
                    getMedicineData(qstring);
                    if($('.ho-co-medicines-data').hasClass('ho-co-medicines-data-'+value)){
                        $('.ho-co-medicines-data').addClass('d-none');
                        $('.ho-co-medicines-data-'+value).removeClass('d-none');
                    }
                }
            });
            $(document).on('change','select.hoco-medicines',function(){
                var value = $(this).val();
                $('.add-medicine').attr('disabled');
                if(value != '' && typeof value != 'undefined'){
                    $('.add-medicine').removeAttr('disabled');
                }
            });
            $(document).on('click','.add-medicine',function(){
                storeMedicine();
            });

            $(document).on('click','.delete-medicine',function(){
                medicineId = $(this).data('id');
                var removeMdata = 'medicine_id='+medicineId+'&type='+type;
                showConfirmMessage(removeMdata);
            });
        });

        // get all referance doctor data
        function mappingData(qstring){
            $.ajax({
                url: "{{URL::to('medicines-mapping')}}"+'/'+type,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('select.hoco-data').data('type',data.hocoType);
                    if(data.hocoType == 2){
                        $('.main-title-text').text('HO Wise Medicine');
                        $("select.hoco-data").attr("title","Select HO");
                        $('.hoco-data').selectpicker('refresh');
                    }
                    $('.map-data').html(data.map_data);
                }
            }).fail(function() {
                
            });
        }

        function getMedicineData(qstring){
            $('.hoco-medicines').attr('disabled');
            $.ajax({
                url: "{{URL::to('get-medicines-data')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                var mData = '';
                mData += '<option value="">Select Medicine</option>';
                $.each(data.medicines, function(key, value) {   
                    mData +=  '<option value="' + key + '">'+value+'</option>';
                });
                $('select.hoco-medicines').html(mData);
                $('.hoco-medicines').removeAttr('disabled');
                $('.hoco-medicines').selectpicker('refresh');
            }).fail(function() {
                
            });
        }

        function storeMedicine(){
            $('.co-error').addClass('d-none');
            $('.medicine-error').addClass('d-none');
            var medicinesId = $('select.hoco-medicines').val();
            if(medicinesId == ''){
                $('.medicine-error').removeClass('d-none');
                return true;
            }
            var coId = $('select.hoco-data').val();
            if(coId == ''){
                $('.co-error').removeClass('d-none');
                return true;
            }
            var csrf = "{{csrf_token()}}";
            $.ajax({
                url: "{{URL::to('store-medicines-data')}}",
                dataType: 'json',
                type: 'POST',
                data: {medicinesId:medicinesId,coId:coId,type:type,_token:csrf}
            }).done(function(data) {
                if(data.status == 1){
                    mappingData(qstring);
                    getMedicineData(qstring);
                    $('.add-medicine').removeAttr('disabled');
                    $('.ho-co-medicines-data').addClass('d-none');
                    $('.ho-co-medicines-data-'+coId).removeClass('d-none');
                    showNotification('bg-blue', 'medicine added successfully.', 'bottom', 'right', "", "");
                }
            }).fail(function() {
                
            });
        }

        function showConfirmMessage(removeMdata) {
            swal({
                title: "Are you sure?",
                text: "You want to delete this medicine!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                cancelButtonClass: 'btn btn-danger',
            }, function () {
                removeMedicine(removeMdata);
                // swal("Deleted!", "Your appointment has been deleted.", "success");
                $('.showSweetAlert').hide();
                $('.sweet-overlay').css('display','none');
                location.reload();
            });
        }

        function removeMedicine(removeMdata){
            $.ajax({
                url: "{{URL::to('remove-medicine')}}?"+removeMdata,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    showNotification('bg-blue', 'medicine removed successfully.', 'bottom', 'right', "", "");
                    mappingData(qstring);
                }
            }).fail({

            });
        }
    </script>
@stop