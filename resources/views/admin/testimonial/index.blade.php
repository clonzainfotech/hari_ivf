@extends('layouts.main')
@section('parentPageTitle', 'Testimonial')
@section('title', 'Testimonial')
@section('page-style')

@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Testimonials</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <ul class="dropdown-menu dropdown-menu-right slideUp">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{URL::to('testimonials/create')}}">
                                <button class="btn btn-primary">
                                    Add
                                </button>
                            </a>
                        </li>
                        <li>
                            {{-- <a href="#"> <button class="btn btn-primary print-testimonial m-0">
                                    Print
                                </button> </a> --}}</li>
                    </ul>
                </div>

                <div class="body">

                    <!-- Nav tabs -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <form method="post" autocomplete="off" action="">
                                    <ul class="nav nav-tabs padding-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control search" placeholder="Search...">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                </form>
                            </div>
                            <div class="col-md-4">
                                {{Form::select('status',['1'=>'Active','2'=>'Deactive'],'',['class'=>'form-control select-padding-0 testimonial-status-value','placeholder'=>'Select Status'])}}
                            </div>
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

                        <div class="testimonial-data table-responsive active">
                            <!-- table data here include -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('page-script')
    <script type="text/javascript">
        var qstring = '';
        var page = '';
        var search = '';
        var testimonialId = '';
        var status = '';

        $(document).ready(function(){

            getTestimonialData(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&status='+status;
                getTestimonialData(qstring);
            });

            $(document).on('click','.delete-testimonial',function(){
                testimonialId = $(this).data('id');
                showConfirmMessage();
            });

            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&status='+status;
                getTestimonialData(qstring);
            });

            $(document).on('change','select.testimonial-status-value',function(e){
                e.preventDefault();
                status = $(this).val();
                qstring = 'page='+page+'&search='+search+'&status='+status;
                getTestimonialData(qstring);
            });

            $(document).on('dblclick', '#testimonial-table tbody tr', function(event) {
                if($(this).hasClass('main-testimonial')){
                    return false;
                }
                var testimonialId = $(this).data('id');
                if(typeof(testimonialId) !== 'undefined'){
                    var url = 'testimonials/'+testimonialId+'/edit';
                    window.location.href = url;
                }
            });

            $(document).on('click', '.print-testimonial',function (){
                qstring = 'page='+page+'&search='+search+'&status='+status+'&isprint=1';
                getTestimonialData(qstring);
            });

        });
        // get all category data
        function getTestimonialData(qstring){

            $.ajax({
                url: "{{URL::to('testimonials')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.testimonial-data').html(data.data);
                }
                if(data.status == 2){
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                }

            }).fail(function() {

            });
        }
        function showConfirmMessage() {
            swal({
                title: "Are you sure?",
                text: "You want to delete this record",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#00cfd1",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                removeTestimonial();
                $('.showSweetAlert').hide();
                location.reload();
                // swal("Deleted!", "Your testimonial has been deleted.", "success");
            });
        }
        // remove category
        function removeTestimonial(){
            $.ajax({
                url: "{{URL::to('testimonials/delete/')}}"+'/'+testimonialId,
                dataType: 'json',
            }).done(function(data) {
                getTestimonialData(qstring);
            }).fail(function() {

            });
        }
    </script>
@stop
