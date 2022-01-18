@extends('layouts.main')
@section('parentPageTitle', 'Html Page')
@section('title', 'Html Page')
@section('page-style')

@stop
@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Html Page List</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <ul class="dropdown-menu dropdown-menu-right slideUp">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{URL::to('html-page/create')}}">
                                <button class="btn btn-primary">
                                    Add
                                </button>
                            </a>
                        </li>
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
                                            <input type="text" class="form-control search" placeholder="Search..." autocomplete="off">
                                            <span class="input-group-addon search-border">
                                                <i class="zmdi zmdi-search"></i>
                                            </span>
                                        </div>
                                    </ul>
                                </form>
                            </div>
                            <div class="col-md-4">
                                {{Form::select('status',['1'=>'Active','2'=>'Deactive'],'',['class'=>'form-control select-padding-0 category-status-value','placeholder'=>'Select Status'])}}
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

                        <div class="html-data table-responsive active">
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
        var htmlId = '';

        $(document).ready(function(){

            getHtmlPages(qstring);

            $(document).on('click', '.pagination a',function(event){
                event.preventDefault();
                page=$(this).attr('href').split('page=')[1];
                qstring = 'page='+page+'&search='+search+'&status='+status;
                getHtmlPages(qstring);
            });

            $(document).on('click','.delete-html',function(){
                htmlId = $(this).data('id');
                showConfirmMessage();
            });
            $(document).on('dblclick', '#html-page-table tbody tr', function(event) {
                var htmlId = $(this).data('id');
                if(typeof(htmlId) !== 'undefined'){
                    var url = 'html-page/edit/'+htmlId;
                    window.location.href = url;
                }
            });
            $(document).on('click', '.view-html', function(event) {
                var slug = $(this).data('id');
                if(typeof(slug) !== 'undefined'){
                    var url = 'html-page/view/'+slug;
                    window.location.href = url;
                }
            });
            $(document).on('keyup','.search',function(){
                search = $(this).val();
                qstring = 'page='+page+'&search='+search+'&status='+status;
                getHtmlPages(qstring);
            });

        });
        // get all category data
        function getHtmlPages(qstring){
            $.ajax({
                url: "{{URL::to('html-page')}}?"+qstring,
                dataType: 'json',
            }).done(function(data) {
                if(data.status == 1){
                    $('.html-data').html(data.html_page);
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
                removeCategory();
                $('.showSweetAlert').hide();
                location.reload();
                // swal("Deleted!", "Your category has been deleted.", "success");
            });
        }
        // remove category
        function removeCategory(){
            $.ajax({
                url: "{{URL::to('html-page/delete/')}}"+'/'+htmlId,
                dataType: 'json',
            }).done(function(data) {
                getHtmlPages(qstring);
            }).fail(function() {

            });
        }
    </script>
@stop
