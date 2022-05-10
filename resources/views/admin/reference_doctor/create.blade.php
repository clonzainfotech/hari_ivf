@extends('layouts.main')
@section('parentPageTitle', 'Reference Doctor')
@section('title', 'Add Reference Doctor')

@section('page-style')

@stop

@section('content')

<div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Add Reference</strong>
                    </h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('reference-doctor')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['url'=>'reference-doctor','method'=>'post','class'=>'form reference-form','files'=>'true'])}}

                                <!-- patients basic information -->
                                <div class="panel panel-primary">  
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" aria-expanded="true"
                                                aria-controls="patients">Basic Information</a> </h4>
                                    </div>
                                    <div id="patients" class="" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row clearfix">
                                                <div class="col-sm-12">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Name : &nbsp;</span>
                                                        {{Form::text('name','',[
                                                            'class'=>'form-control name',
                                                            'placeholder'=>'Name',
                                                            'required'
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('name')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-addon unik-lbl-spn">Mobile :</span>
                                                        {{Form::text('mobile_number','',[
                                                            'class'=>'form-control mobile_number',
                                                            'placeholder'=>'Mobile Number',
                                                            'oninput' => 'checkMobileNumber(this.value)',
                                                            'required',
                                                            'maxlength' => 10,
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('mobile_number')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        {{Form::select('reference_type',['1'=>'Offline','2'=>'Online'],null,[
                                                            'class'=>'form-control select-padding-0 reference_type',
                                                            'title'=>'Select Refernce Type',
                                                            'required',
                                                        ])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('reference_type')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="checkbox">
                                                        {{Form::checkbox('is_lead','0','',['id'=>'is_lead','class'=>'is_lead'])}}
                                                        <label for="is_lead">
                                                            Is Lead
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        {{Form::textarea('address', null, ['class'=>'form-control no-resize','placeholder'=>'Address','rows'=>'2'])}}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- location and communication -->
                                <div class="col-sm-12">
                                    {{Form::submit('Save',['class'=>'btn btn-primary reference-save'])}}
                                    <a href="{{URL::to('reference-doctor')}}" class="btn btn-default">Cancel</a>
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
    <script>
        $(".reference-form").submit(function() { $(".reference-save").attr("disabled", true); });
        function validMobileNumber(value) {
            if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value)) {
                return value.substring(0, (value.length - 1));
            } else {
                return value;
            }
        }
        function checkMobileNumber(value) {
            $('.mobile_number').val(validMobileNumber(value));
        }
        $(document).on('click','.is_lead',function(){
            $(this).val(0);
            if($(this).prop('checked') == true)
            {
                $(this).val(1);
            }
        });
    </script>
@stop