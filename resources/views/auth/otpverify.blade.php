@extends('layouts.authentication')
@section('title', 'Login')
@php
    $systemSetting = systemSetting();
    $logo = isset($systemSetting->header_logo) && !empty($systemSetting->header_logo) ? $systemSetting->header_logo : null;
    $alt = isset($systemSetting->alt) && !empty($systemSetting->alt) ? $systemSetting->alt : null;
    $primary = isset($systemSetting->primary) && !empty($systemSetting->primary) ? $systemSetting->primary : null;
@endphp
@section('content')
<style>
    .theme-cyan .btn-primary{
        background-color:<?php echo $primary ?>!important;
    }
</style>
    <div class="container">
        <div class="col-md-12 content-center">

            <div class="card-plain">
                {{Form::open(['route'=>'admin-otp-verify','method'=>'post','class'=>'form','onsubmit'=>'return loginvalidation();'])}}
                <div class="header">
                    <img src="{{ url('images/' . $logo )}}" alt="{{ !empty($alt) ? $alt : null }}"/>
                    <h5></h5>
                </div>
                <span class="candor-color">Your OTP is send on {{Auth::User()->mobile_number}}</span>
                <div class="content">
                    <div class="form-group input-lg log-email">
                        {{Form::number('otp','',['class'=>'form-control','placeholder'=>'Enter OTP number','id'=>'otp'])}}
                    </div>
                    <span id="otp_error" class="text-danger"></span>
                    @if($errors->has('otp'))
                        <div class="login-error mt-2 mb-2">{{ $errors->first('otp') }}</div>
                    @endif
                    @if(Session::has('msg'))
                        <div class="login-error mt-2">{{ Session::get('msg') }}</div>
                    @endif
                </div>
                <div class="footer text-center">
                    {{Form::submit('Verify',['class'=>'btn btn-primary btn-lg btn-block'])}}
                </div>
                <span class="candor-color"><a href="{{URL::to('resend-otp')}}" class="candor-color">Resend OTP</a></span>

                {{Form::close()}}
            </div>
        </div>
    </div>
@stop
@push('after-scripts')
<script src="{{url('assets/plugins/bootstrap-notify/bootstrap-notify.js')}}"></script>
<script type="text/javascript">
     function loginvalidation() {
            document.getElementById('email_error').innerHTML="";
            document.getElementById('password_error').innerHTML="";

           var mailformat = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
            var email=document.getElementById('email').value;
            var password=document.getElementById('password').value;
            var i=0;

            if (email == null) {
                 document.getElementById('email_error').innerHTML="Please enter  email address";
                i=1;
            }

            if (!email.match(mailformat)) {
                 document.getElementById('email_error').innerHTML="Please enter valid email address";
                i=1;
            }
            if (password == null) {
                 document.getElementById('password_error').innerHTML="Please enter password";
                i=1;
            }
            if(i==1)
         {
            return false;
         }
         return true;
           }

            $('.mobile_number').keypress(function(event){
            var length = $(this).val().length;
       if(length == 10){
           event.preventDefault(); //stop character from entering input
       }

   });
        function myFunction() {
            var password = document.getElementById("password");
            if (password.type === "password") {
                password.type = "text";
                } else {
                password.type = "password";
                }
            }

            $('#password').keyup(function() {
  
                if ($(this).val().length == 0) {
                   $('.show_hide').hide();
                } 
                else {
                   $('.show_hide').show();
                }
            }).keyup();
</script>
@endpush