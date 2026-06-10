@extends('layouts.authentication')
@section('title', 'Register')
@php
$city = getCity();
$state = getState();

$systemSetting = systemSetting();
$logo = isset($systemSetting->header_logo) && !empty($systemSetting->header_logo) ? $systemSetting->header_logo : null;
$alt = isset($systemSetting->alt) && !empty($systemSetting->alt) ? $systemSetting->alt : null;
$primary = isset($systemSetting->primary) && !empty($systemSetting->primary) ? $systemSetting->primary : null;

@endphp
@section('content')
<link href="https://code.jquery.com/ui/1.12.1/themes/pepper-grinder/jquery-ui.css" rel="stylesheet" />
<style>
    .theme-cyan .btn-primary {
        background-color: <?php echo $primary ?> !important;
    }
    .header
    {
        margin-bottom: 10%;
    }
    .page-header .container>.content-center
    {
        top : 45%;
    }
</style>


<div class="container">
    <div class="col-md-12 content-center">
        <div class="header">
            <img class="reg-logo" src="{{ url('images/' . $logo )}}"
                alt="{{ !empty($alt) ? $alt : null }}" />
            <h5></h5>
        </div>
        <div class="card-plain-reg">
            {{Form::open(['route'=>'register','method'=>'post','class'=>'form'])}}
            
            <div class="content col-md-12">
                <div class="row">
                    <div class="form-group input-md f_name col-md-6">
                        {{Form::text('f_name','',['class'=>'form-control','placeholder'=>'Enter First Name','id'=>'f_name','required'])}}
                        @if($errors->has('f_name'))
                            <div class="login-error mt-2 mb-2">{{ $errors->first('f_name') }}</div>
                        @endif
                    </div>
                    <div class="form-group input-md l_name col-md-6">
                        {{Form::text('l_name','',['class'=>'form-control','placeholder'=>'Enter Last Name','id'=>'l_name','required'])}}
                        @if($errors->has('f_name'))
                            <div class="login-error mt-2 mb-2">{{ $errors->first('f_name') }}</div>
                        @endif
                    </div>
                    <div class="form-group input-md surname col-md-6">
                        {{Form::text('surname','',['class'=>'form-control','placeholder'=>'Surname','id'=>'surname','required'])}}
                        @if($errors->has('surname'))
                            <div class="login-error mt-2 mb-2">{{ $errors->first('surname') }}</div>
                        @endif
                    </div>
                    <div class="form-group input-md dob col-md-6">
                        {{Form::date('dob',!empty($patient->dob) ? \Carbon\Carbon::parse($patient->dob)->format('d-m-Y') : null,[
                        'id'=>'birthdate',
                            'class'=>'dob border-color border-1 form-control',
                            'placeholder'=>'Date of Birth',
                        ])}}
                        @if($errors->has('dob'))
                            <div class="login-error mt-2 mb-2">{{ $errors->first('dob') }}</div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="form-group input-md state col-md-4">
                        {{Form::select('gender',[''=>'Select Gender','1'=>'Male','2'=>'Female'],'',['class'=>'form-control select-padding-0 state','data-live-search'=>'true','required'])}}
                        @if($errors->has('gender'))
                            <div class="login-error mt-2 mb-2">{{ $errors->first('gender') }}</div>
                        @endif
                    </div>
                    <div class="form-group input-md mobile_number col-md-4">
                        {{Form::number('mobile_number','',['class'=>'form-control','placeholder'=>'Mobile Number','id'=>'mobile_number','required'])}}
                        @if($errors->has('mobile_number'))
                            <div class="login-error mt-2 mb-2">{{ $errors->first('mobile_number') }}</div>
                        @endif
                    </div>
                    <div class="form-group input-md other_mobile_number col-md-4">
                        {{Form::number('other_mobile_number','',['class'=>'form-control','placeholder'=>'Other Mobile Number','id'=>'other_mobile_number','required'])}}
                        @if($errors->has('other_mobile_number'))
                            <div class="login-error mt-2 mb-2">{{ $errors->first('other_mobile_number') }}</div>
                        @endif
                    </div>

                </div>

                <div class="form-group input-md residence">
                    {{Form::textarea('residence','',['class'=>'form-control','rows'=>'2','placeholder'=>'Residence','id'=>'residence','required'])}}
                    @if($errors->has('residence'))
                        <div class="login-error mt-2 mb-2">{{ $errors->first('residence') }}</div>
                    @endif
                </div>
                <div class="form-group input-md main_area">
                    {{Form::text('main_area','',['class'=>'form-control','placeholder'=>'Main Area','id'=>'main_area','required'])}}
                    @if($errors->has('main_area'))
                        <div class="login-error mt-2 mb-2">{{ $errors->first('main_area') }}</div>
                    @endif
                </div>
                <div class="row">
                    <div class="form-group input-md city col-md-6">
                        {{Form::select('city',$city,'',['class'=>'form-control select-padding-0 city','placeholder'=>'City','data-live-search'=>'true','required'])}}
                    </div>

                    <div class="form-group input-md state col-md-6">
                        {{Form::select('state',$state,'',['class'=>'form-control select-padding-0 state','placeholder'=>'State','data-live-search'=>'true','required'])}}
                    </div>
                </div>




                <div class="form-group col-md-12 text-center">
                    <input id="pregnate" type="checkbox" class="other_checkbox" name="reason" value="1">
                    <label for="pregnate" class="col-form-label text-md-right txt-color">Pragnancy</label>

                    <input id="noPregnate" type="checkbox" class="other_checkbox" checked="" name="reason" value="2">
                    <label for="noPregnate" class="col-form-label text-md-right">{{ __('No Pragnancy') }}</label>

                    <input id="other" type="checkbox" class="other_checkbox" name="reason" value="3">
                    <label for="other" class="col-form-label text-md-right">{{ __('Other') }}</label>
                    @if($errors->has('reason'))
                        <div class="login-error mt-2 mb-2">{{ $errors->first('reason') }}</div>
                    @endif
                </div>
            </div>

            <div class="text-center">
                {{Form::submit('Register',['class'=>'btn btn-primary btn-lg btn-block'])}}
            </div>
            {{Form::close()}}
        </div>
    </div>
</div>

@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function(){
        
        $(document).on('click','.other_checkbox',function(){
            $('.other_checkbox').prop('checked',false);
            $(this).prop('checked',true);
        })
    });   
</script>