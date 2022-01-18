@extends('layouts.main')
@section('parentPageTitle', 'Indoor')
@section('title', 'Indoor')
@section('page-style')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/fontawesome.js">


    <style>
        .review-delete{
              color:black;
            cursor: pointer;
          }
        .review-delete:hover{
            color:black;
        }
        .review-delete i {
            font-size: 20px;
        }
    </style>
@stop

@section('content')

    <div class="row clearfix review">
        <div class="col-lg-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Review</strong></h2>
                </div>
                <div class="body">
                    <div class="card">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                {{ Form::select('patient_id',$patients,'',[
                                    'class'=>'form-control select-padding-0 patient-id',
                                    'placeholder'=>'Select Patient',
                                    'id' => 'patient_id',
                                    'data-live-search' => 'true'
                                ])}}
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                {{Form::select('review-role', $reviewRole, '',[
                                    'class'=>'form-control select-padding-0 review_role',
                                    'placeholder'=>'Select Role',
                                ])}}
                            </div>
                            <div class="col-md-4">
                                <form method="post" autocomplete="off" action="">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control search-mobile-number" placeholder="Search by mobile no">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                </form>
                            </div>
                            <div class="col-md-2 mb-2">
                                <a href="javascript:void(0);">
                                    <button class="btn btn-primary print-review m-0">
                                        Print
                                    </button>
                                </a>
                            </div>
                        </div>
                        <div class="review-data table-responsive active">
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
        var qstring = '';
        var reviewId = ''
        var page = '';
        var patientId = '';
        var roleId = '';
        var search = '';
        $(document).ready(function(){

            getReviewData(qstring);

            $(document).on('change', 'select.patient-id', function() {
                patientId = $(this).val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&role_id=' + roleId+'&search='+search;
                getReviewData(qstring);
            });

            $(document).on('change', 'select.review_role', function() {
                roleId = $(this).val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&role_id=' + roleId+'&search='+search;
                getReviewData(qstring);
            });
            $(document).on('keyup','.search-mobile-number',function(){
                search = $(this).val();
                qstring = 'page=' + page + '&patient_id=' + patientId + '&role_id=' + roleId+'&search='+search;
                getReviewData(qstring);
            });

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page=' + page + '&patient_id=' + patientId + '&role_id=' + roleId+'&search='+search;
                getReviewData(qstring);
            });

            $(document).on('click','.review-delete',function(){
                reviewId = $(this).data('id');
                showConfirmMessage();
            });

            $(document).on('click', '.print-review',function (){
                qstring = 'page=' + page + '&patient_id=' + patientId + '&role_id=' + roleId+'&search='+search+'&isprint=1';
                getReviewData(qstring);
            });
        });

        function showConfirmMessage() {
            swal({
                title: 'Are you sure?',
                text: 'You want to delete this record',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#00cfd1',
                confirmButtonText: 'Yes, Delete!',
                closeOnConfirm: false,
                cancelButtonClass: 'btn btn-danger',
            }, function () {
                deleteReview();
                // swal('Deleted', 'You delete review.', 'success');
                $('.showSweetAlert').hide();
                location.reload();
            });
        }


        function getReviewData(qstring) {
            $.ajax({
                url: '{{ URL::to("/review") }}?' + qstring,
                type: 'GET',
                dataType: 'json',
            }).done(function (data) {
                if(data.status == 1){
                    $('.review-data').html(data.review);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.review);
                    w.document.close();
                    setTimeout(function () {
                        w.window.print();
                    }, 100);
                }
            }).fail(function () {

            });
        }
        function deleteReview() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "review/delete/" + reviewId,
                type: "POST",
                dataType: 'json',
            }).done(function () {
                page = 1;
                qstring = 'page=' + page + '&patient_id=' + patientId + '&role_id=' + roleId;
                getReviewData(qstring);
            }).fail(function () {

            });
        }
    </script>
@stop
