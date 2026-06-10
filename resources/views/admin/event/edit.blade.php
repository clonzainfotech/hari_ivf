@extends('layouts.main')
@section('parentPageTitle', 'Event')
@section('title', 'Update Event')
@section('page-style')


@stop
@section('content')
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Edit Event</strong>
                    </h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('event')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    {{Form::open(['url'=>'event/'.$event->id,'method'=>'put','class'=>'form','files'=>'true'])}}
                        <div class="row clearfix">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    {{Form::text('title',$event->title,['class'=>'form-control','placeholder'=>'Title'])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('title')}}
                                </span>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    {{Form::text('discription',$event->discription,[
                                        'class'=>'form-control discription',
                                        'placeholder'=>'Discription Of Event',
                                        'required',
                                    ])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('discription')}}
                                </span>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <img src="{{ cdnUrl($event->event_picture, 'public/upload/event/event.jpg') }}"class="mt-2 mb-2 profile_icon"/>
                                    {{Form::file('event_picture',['class'=>'form-control','placeholder'=>'Select Event Picture'])}}
                                </div>
                                <span class="form-error-msg" id="image">
                                    {{$errors->first('Event_picture')}}
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{Form::text('venue',$event->venue,[
                                        'class'=>'form-control venue',
                                        'placeholder'=>'Venue',
                                        'required'
                                    ])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('venue')}}
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{Form::text('time',(!empty($event->time)) ? \Carbon\Carbon::parse($event->time)->format('h:i a') : null,[
                                        'class'=>'form-control time timepicker',
                                        'placeholder'=>'Time',
                                        'required'
                                    ])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('time')}}
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{Form::text('startDate',\Carbon\Carbon::parse($event->start_date)->format('D d M Y'),[
                                        'class'=>'form-control datetimepicker date startDate',
                                        'placeholder'=>'Start Date',
                                        'required'
                                    ])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('startDate')}}
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{Form::text('endDate',\Carbon\Carbon::parse($event->end_date)->format('D d M Y'),[
                                        'class'=>'form-control datetimepicker date endDate',
                                        'placeholder'=>'End Date',
                                        'required'
                                    ])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('endDate')}}
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{Form::select('status',[1=>'Active',2=>'Deactive'],$event->status,['class'=>'form-control select-padding-0','placeholder'=>'Select Status'])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('status')}}
                                </span>
                            </div>
                            <div class="col-sm-12">
                                {{Form::submit('submit',['class'=>'btn btn-primary submit event-save'])}}
                                <a href="{{URL::to('event')}}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script type="text/javascript">
    $(document).on('click', '.submit', function(event) {
    });
    window.URL = window.URL || window.webkitURL;
    $("form").submit( function( e ) {
        $('.event-save').attr("disabled", true);
        var form = this;
        e.preventDefault(); //Stop the submit for now
                                    //Replace with your selector to find the file input in your form
        var fileInput = $(this).find("input[type=file]")[0],
            file = fileInput.files && fileInput.files[0];
    if( file ) {
        var img = new Image();
        img.src = window.URL.createObjectURL( file );
        img.onload = function() {
        var width = img.naturalWidth,
        height = img.naturalHeight;
        console.log(width,height);
        window.URL.revokeObjectURL( img.src );
        // if( width >= 1000 && height >= 600 ) {
            form.submit();
        // }
        // else {
        //     document.getElementById('image').innerHTML=" image doesn't look like the size we wanted.we require 1000 x 640 size image.";
        // return false;
        // }
        };
    }
    else {
        form.submit();
    }
    });
    $(function() {
        //Datetimepicker plugin
        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time: false,
            weekStart: 1
        });
    });
</script>
@stop
