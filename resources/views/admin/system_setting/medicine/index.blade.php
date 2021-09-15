@extends('layouts.main')
@section('parentPageTitle', 'Medicine')
@section('title', 'Medicine')

@section('page-style')
<style>
    .medicine-body-padding{
        padding-bottom: 10px !important;
    }
    .form-error-msg{
        margin-left: 35% !important;
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
                            <h5>Medicines Settings</h5>
                        </div>
                        
                        <div class="col-md-7 text-right">
                            <a href="{{URL::to('medicines-mapping/'.encrypt(1))}}">
                                <button class="btn btn-primary print-sms-report">
                                    CO
                                </button>
                            </a>
                            <a href="{{URL::to('medicines-mapping/'.encrypt(2))}}">
                                <button class="btn btn-primary print-sms-report">
                                    HO
                                </button>
                            </a>
                            <a href="#" class="btn btn-primary add-medicine" data-toggle="modal" data-target="#medicine-modal">Add Medicine</a>
                            <button class="btn btn-primary delete_all">Delete Medicins</button>   
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100"></div>
            <div class="card">
                <div class="header">
                    <div class="col-md-3 text-right">
                        <ul class="nav nav-tabs padding-0">
                            <div class="input-group">
                                <input type="text" class="form-control search-medicine" placeholder="Search..." readonly="readonly" onfocus="this.removeAttribute('readonly')">
                                <span class="input-group-addon search-border">
                                    <i class="zmdi zmdi-search"></i>
                                </span>
                            </div>
                        </ul>
                    </div>
                </div>
                <div class="body">
                    <div class="tab-content m-t-10">
                    
                        <div class="medicines-data"></div>
                    </div>
                </div>
            </div>
    </div>
    @section('modal')
        <div class="modal fade" id="medicine-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- header -->
                    <div class="modal-header justify-content-center">
                        <h4 class="title" id="next-appointment">Medicine</h4>
                    </div>
                    <!-- body -->
                    {{Form::open(['class'=>'form-inline','id'=>'medicine-form'])}}
                        {{Form::hidden('m_id','',['class'=>'medicine-id'])}}
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Name
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::text('name','',['class'=>'form-control name','placeholder'=>'Medine Name'])}}
                                    </div>
                                </div>
                            </div>
                            <span class="form-error-msg name-error"></span>


                            <div class="row tab_medicine_status">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        When to take
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::select('status',$mStatus,'',['class'=>'form-control status medicine-picker','placeholder'=>'Medicine Status'])}}
                                        <span class="form-error-msg date"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                       Frequency
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::select('dose',$dose,'',['class'=>'form-control dose ivf medicine-picker','placeholder'=>'Medicine Dose'])}}
                                        <span class="form-error-msg date"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row div_number">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        How many Days
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::number('number','',['class'=>'form-control number','placeholder'=>'Number','min'=>1,'oninput'=>"validity.valid||(value='');"])}}
                                    </div>
                                    <span class="form-error-msg p-total-payment-error"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Route
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::select('medicine_time',$mTime,'',['class'=>'form-control m-time ivf medicine-picker','title'=>'Medicine Time'])}}
                                    </div>
                                    <span class="form-error-msg time"></span>
                                </div>
                            </div>

                            <div class="row tab_qty">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Morning
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::number('quantity','',['class'=>'form-control quantity','placeholder'=>'Quantity','min'=>1,'oninput'=>"validity.valid||(value='');"])}}
                                    </div>
                                    <span class="form-error-msg p-total-payment-error"></span>
                                </div>
                            </div>
                            <div class="row tab_qty">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Afternoon
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::number('quantity_2','',['class'=>'form-control quantity_2','placeholder'=>'Quantity','min'=>1,'oninput'=>"validity.valid||(value='');"])}}
                                    </div>
                                    <span class="form-error-msg p-total-payment-error"></span>
                                </div>
                            </div>
                            <div class="row tab_qty">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Evening
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::number('quantity_3','',['class'=>'form-control quantity_3','placeholder'=>'Quantity','min'=>1,'oninput'=>"validity.valid||(value='');"])}}
                                    </div>
                                    <span class="form-error-msg p-total-payment-error"></span>
                                </div>
                            </div>
                            <div class="row tab_qty">
                                <div class="form-group col-md-12">
                                    <div class="col-md-4 form-padding">
                                        Night
                                    </div>
                                    <div class="col-md-5 form-padding">
                                        {{Form::number('quantity_4','',['class'=>'form-control quantity_4','placeholder'=>'Quantity','min'=>1,'oninput'=>"validity.valid||(value='');"])}}
                                    </div>
                                    <span class="form-error-msg p-total-payment-error"></span>
                                </div>
                            </div>
                        </div>
                        <!-- footer -->
                        <div class="modal-footer w-100 justify-content-center">
                            <button type="button" class="btn btn-primary waves-effect medicine-submit">Save</button>
                            <button type="button" class="btn btn-default waves-effect ml-3" data-dismiss="modal">Close</button>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    @stop
@stop
@section('page-script')
    <script src="{{asset('assets/js/pages/ui/notifications.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
    <script type="text/javascript">
        var page = '';
        var search = '';
        var medicineId = '';
        var referenceDoctorId = '';
        var isOld = "{{$isOld}}";
        var qstring = 'is_old='+isOld;
        $(document).ready(function(){

            getMedicinesData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&is_old='+isOld;
                getMedicinesData(qstring);
            });

            $(document).on('click', '.print-sms-report', function () {
                qstring = 'page='+page+'&isprint=1'+'&search='+search+'&is_old='+isOld;
                getMedicinesData(qstring);
            });

            $(document).on('click', '.medicine-submit', function () {
                var formData = $('#medicine-form').serialize();
                storeMedicne(formData);
            });

            $(document).on('click', '.edit-medicine', function () {
                var mId = $(this).data('mid');
                mId = 'm_id='+mId;
                
                getMedicine(mId);
                
            });

            $(document).on('click', '.add-medicine', function () {
                resetForm();
            });

            $(document).on('keyup', '.search-medicine', function () {
                search = $(this).val();
                qstring = 'page='+page+'&isprint=1'+'&search='+search+'&is_old='+isOld;
                getMedicinesData(qstring);
            });

            $(document).on('click','.delete-medicine',function(){
                medicineId = $(this).data('id');
                // alert(medicineId);
                var removeMdata = 'medicine_id='+medicineId+'&type=1'
                showConfirmMessage(removeMdata);
            });

            $('.tab_qty').css('display','block');
            $('.inj_medicine_time').css('display','none');
            $('.tab_medicine_status').css('display','block');
            
            $(document).on('keyup','.name',function(){
                var medicine_name = $(this).val();
                displayMedicineField(medicine_name);
            });
        });
        //display field based on medicine name
        
        function displayMedicineField(medicine_name)
        {
            var header = medicine_name.slice(0,3).toUpperCase();
            $('.tab_qty').css('display','block');
            $('.tab_medicine_status').css('display','block');
            $('.inj_medicine_time').css('display','none');
            $('.dose').selectpicker('refresh');
            if(header == 'INJ')
            {
                $('.tab_qty').css('display','none');
                $('.tab_medicine_status').css('display','none');
                $('.inj_medicine_time').css('display','block');
                var dose_array = ["7","8","9","10"];
                //display only dose_array option
                // $('div.dose .dropdown-menu li').each(function() {
                //     var index = $(this).attr('data-original-index'); 
                //     if(jQuery.inArray(index, dose_array) == -1){
                //         $(this).remove();
                //     }
                //     else{

                //     }
                // });
            }
            else{
                var dose_array = ["7","8","9","10"];
                //hide dose_array option 
                $('div.dose .dropdown-menu li').each(function() {
                    var index = $(this).attr('data-original-index'); 
                    if(jQuery.inArray(index, dose_array) !== -1){
                        $(this).remove();
                    }
                });
            }
        }
        // get all medicine data
        function getMedicinesData(qstring){
            $.ajax({
                url: "{{URL::to('medicines-setting')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.medicines-data').html(data.medicines);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.sms);
                    w.document.close();
                    w.window.print();
                }
            }).fail(function() {
                
            });
        }

        function storeMedicne(formData){
            $.ajax({
                url: "{{URL::to('store-medicine')}}",
                dataType: 'json',
                type: 'POST',
                data:formData
            }).done(function(data) {
                $('.form-error-msg').text('');
                if(data.status == 2){
                    var errorData = data.error;
                    var madicineData = '';
                    $.each(errorData, function(key, value) {   
                       $('.'+key+'-error').text(value);
                    });
                }
                if(data.status == 1){
                    $('#medicine-modal').modal('hide');
                    resetForm();
                    showNotification('bg-blue', 'medicine added successfully.', 'bottom', 'right', "", "");
                    getMedicinesData(qstring);
                }
            }).fail(function(error) {

            });
        }

        function getMedicine(mid){
            resetForm();
            $.ajax({
                url: "{{URL::to('get-medicine-data?')}}"+mid,
                dataType: 'json',
            }).done(function(data) {
               if(data.status == 1){
                   var medicneData = data.medicine;
                   $('.name').val(medicneData.name);
                   $('.medicine-id').val(medicneData.id);
                   $('.number').val(medicneData.number);
                   $('.quantity').val(medicneData.quantity);
                   $('.quantity_2').val(medicneData.quantity_2);
                   $('.quantity_3').val(medicneData.quantity_3);
                   $('.quantity_4').val(medicneData.quantity_4);
                   $('.dose').val(medicneData.dose);
                   $('.m-time').val(medicneData.medicine_time);
                //    var mTime = medicneData.medicine_time;
                //    if(mTime != '' && typeof mTime != 'undefined' && mTime != null){
                //        mTime = mTime.replace(/\"/g, "");
                //    }
                //    $('.m-time').selectpicker('val', JSON.parse(mTime));
                   $('.status').val(medicneData.medicine_status);
                   $('.dose').selectpicker('refresh');
                   $('.m-time').selectpicker('refresh');
                   $('.status').selectpicker('refresh');
                displayMedicineField(medicneData.name);
                //    $('.m-time').selectpicker('refresh'); 
               }
            }).fail(function(error) {

            });
        }

        function resetForm(){
            $('.medicine-id').val('');
            $('#medicine-form').trigger('reset');
            $('select.dose').val('');
            $('select.status').val('');
            $('select.m-time').val('');
            $('.medicine-picker').selectpicker('refresh');
            $('.form-error-msg').text('');
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
                    getMedicinesData(qstring);
                }
            }).fail({

            });
        }

         $(document).on('click','.delete_all',function(){

            var allVals = [];  
            $(".sub_chk:checked").each(function() {  
                allVals.push($(this).attr('data-id'));
            });  

            if(allVals.length <=0)  
            {  
                showNotification('bg-blue', 'Please select Medicine', 'bottom', 'right', "", "");
            } else {  
                showConfirmMessage_deleteall(allVals); 
            }  
        });

           function showConfirmMessage_deleteall(allVals) {
            swal({
                title: "Are you sure?",
                text: "You want to delete Selected medicine!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                cancelButtonClass: 'btn btn-danger',
            }, function () {
                removeallMedicine(allVals);
                // swal("Deleted!", "Your appointment has been deleted.", "success");
                $('.showSweetAlert').hide();
                $('.sweet-overlay').css('display','none');
                location.reload();
            });
        }

         function removeallMedicine(allVals){
                var join_selected_values = allVals.join(","); 
                 $.ajax({
                    url: "{{url('delete_selected_medicine')}}",
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: 'ids='+join_selected_values,
                }).done(function(data) {
                    if(data.status == 1){
                        showNotification('bg-red', 'medicine removed successfully', 'bottom', 'right', "", "");
                        getMedicinesData(qstring);
                    }
                }).fail({

                });
        }
    </script>
@stop