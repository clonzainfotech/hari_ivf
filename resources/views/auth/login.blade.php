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

    /* ══════════════════════════════════════════════
       LOGIN — Minimal Professional UI
       Only frontend. Backend logic untouched.
    ══════════════════════════════════════════════ */

    /* ── Fix Theme Overlays ── */
    .page-header::before,
    .page-header::after {
        display: none !important;
        content: none !important;
    }

    /* ── Design Tokens ── */
    :root{
        --c-primary: <?php echo !empty($primary) ? $primary : '#9ca683'; ?>;
        --c-ink: #bcc6a7;
        --c-subtle: #9ca683;
        --c-border: #bcc6a7;
        --c-bg: #bcc6a7;
        --c-white: #ffffff;
    }

    @media(prefers-reduced-motion:reduce){
        *{animation:none!important;transition:none!important}
    }

    /* ── Page ── */
    .login-page{
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        padding: 16px;
    }

    /* ── Card Shell ── */
    .login-shell{
        width: 100%;
        max-width: 960px;
        display: flex;
        border-radius: 24px;
        overflow: hidden;
        background: #ffffff !important;
        box-shadow:
            0 1px 3px rgba(0,0,0,0.04),
            0 20px 60px -15px rgba(0,0,0,0.12);
        animation: fadeIn .5s ease-out;
    }

    /* ═══ LEFT — Brand Panel ═══ */
    .login-brand{
        flex: 0 0 44%;
        position: relative;
        padding: 48px 36px 32px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #fff !important;
        background: linear-gradient(165deg, #0f1b2d 0%, #9ca683 55%, #234e75 100%);
        overflow: hidden;
    }

    /* Soft light glow */
    .login-brand::before{
        content: '';
        position: absolute;
        width: 400px; height: 400px;
        top: -120px; right: -100px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        pointer-events: none;
    }

    /* Dot grid texture */
    .login-brand::after{
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
        background-size: 20px 20px;
        pointer-events: none;
    }

    .login-brand-inner{
        position: relative;
        z-index: 1;
    }

    .login-brand .brand-logo{
        display: inline-block;
        margin-bottom: 40px;
    }
    .login-brand .brand-logo img{
        height: 50px;
        width: auto;
        padding: 8px 14px;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        filter: brightness(0) invert(1);
    }

    .login-brand h2{
        font-size: 28px;
        font-weight: 700;
        letter-spacing: -0.01em;
        line-height: 1.25;
        margin: 0 0 12px;
        color: #fff !important;
    }
    .login-brand .brand-text{
        font-size: 14px;
        line-height: 1.65;
        opacity: 0.8;
        max-width: 32ch;
        margin: 0 0 32px;
        color: #fff !important;
    }

    /* Chips */
    .brand-chips{
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .brand-chip{
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.02em;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff !important;
    }
    .brand-chip i{ opacity: 0.85; font-size: 11px; color: #fff !important; }

    .login-brand .brand-footer{
        position: relative;
        z-index: 1;
        font-size: 11px;
        opacity: 0.45;
        margin-top: 28px;
        color: #fff !important;
    }

    /* ═══ RIGHT — Form Panel ═══ */
    .login-form-area{
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 48px 44px;
        background: #ffffff !important;
    }

    .login-card{
        width: 100%;
        max-width: 360px;
        background: transparent;
    }

    /* Logo on form side */
    .login-card .form-logo{
        margin-bottom: 32px;
    }
    .login-card .form-logo img{
        height: 32px;
        width: auto;
    }

    .login-card .form-title{
        font-size: 22px;
        font-weight: 700;
        color: #1e293b !important;
        letter-spacing: -0.01em;
        margin: 0 0 4px;
    }
    .login-card .form-subtitle{
        font-size: 13px;
        color: var(--c-subtle);
        margin: 0 0 28px;
        line-height: 1.5;
    }

    /* ── Fields ── */
    .f-group{
        margin-bottom: 18px;
    }
    .f-group .form-group {
        margin: 0 !important;
        padding: 0 !important;
    }
    .f-group label{
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #1e293b !important;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .f-group .form-control{
        width: 100%;
        height: 48px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 0 14px;
        font-size: 14px;
        color: #1e293b !important;
        background: #ffffff !important;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }
    .f-group .form-control::placeholder{
        color: #94a3b8 !important;
    }
    .f-group .form-control:focus{
        border-color: var(--c-primary);
        box-shadow: 0 0 0 3px rgba(26,60,94,0.1);
    }

    /* Password eye */
    .pw-field{ 
        position: relative !important; 
        height: 48px !important; 
    }
    .pw-field .form-control{ 
        padding-right: 44px !important; 
        height: 100% !important; 
    }
    .pw-field .show_hide{
        position: absolute !important;
        right: 14px !important;
        top: 50% !important;
        bottom: auto !important;
        transform: translateY(-50%) !important;
        margin: 0 !important;
        cursor: pointer;
        color: #94a3b8 !important;
        padding: 4px !important;
        border-radius: 6px;
        z-index: 10 !important;
        transition: color .2s;
        line-height: 1 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .pw-field .show_hide:hover{ color: #1e293b !important; }

    /* Errors */
    .f-error{
        display: block;
        margin-top: 5px;
        font-size: 11px;
        color: #dc2626 !important;
        line-height: 1.3;
    }
    .f-error:empty{ display: none; }

    .f-alert{
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 10px;
        background: #fef2f2 !important;
        border: 1px solid #fecaca !important;
        color: #991b1b !important;
        font-size: 12px;
        line-height: 1.4;
        margin-top: 6px;
    }
    .f-alert i{ flex-shrink: 0; font-size: 14px; }

    /* ── Button ── */
    .f-submit{
        margin-top: 24px;
    }
    .f-submit .btn{
        width: 100%;
        height: 48px;
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.02em;
        cursor: pointer;
        color: #ffffff !important;
        transition: opacity .2s, transform .15s;
        /* Ensure background applies even if theme overrides it */
        background-color: var(--c-primary) !important;
    }
    .f-submit .btn:hover{
        opacity: 0.92;
        transform: translateY(-1px);
    }
    .f-submit .btn:active{
        transform: scale(0.99);
    }

    /* ── Animation ── */
    @keyframes fadeIn{
        from{ opacity:0; transform: translateY(12px); }
        to{ opacity:1; transform: translateY(0); }
    }

    /* ── Responsive ── */
    @media(max-width:900px){
        .login-shell{
            flex-direction: column;
            max-width: 480px;
        }
        .login-brand{
            flex: none;
            padding: 32px 24px 24px;
        }
        .login-brand h2{ font-size: 22px; }
        .login-form-area{
            padding: 32px 24px;
        }
    }
    @media(max-width:480px){
        .login-page{ padding: 10px; }
        .login-brand{ padding: 24px 18px 20px; }
        .login-form-area{ padding: 24px 18px; }
        .login-card .form-title{ font-size: 20px; }
    }
</style>

<div class="login-page">
    <div class="login-shell">

        {{-- ═══ LEFT — Brand ═══ --}}
        <div class="login-brand">
            <div class="login-brand-inner">
                <div class="brand-logo">
                    @if(!empty($logo))
                        <img src="{{ url('images/' . $logo) }}" alt="{{ !empty($alt) ? $alt : 'Logo' }}"/>
                    @else
                        <img src="{{ asset('assets/images/civora_logo.svg') }}" alt="{{ !empty($alt) ? $alt : 'Civora' }}"/>
                    @endif
                </div>

                <h2>Welcome back</h2>
                <p class="brand-text">Sign in to continue to your dashboard. Your account, appointments, and records are ready when you are.</p>

                <div class="brand-chips">
                    <span class="brand-chip"><i class="fa fa-shield"></i> Secure access</span>
                    <span class="brand-chip"><i class="fa fa-bolt"></i> Fast login</span>
                    <span class="brand-chip"><i class="fa fa-heartbeat"></i> Care-first experience</span>
                </div>
            </div>
            <div class="brand-footer">&copy; {{ date('Y') }} {{ config('app.name') }}</div>
        </div>

        {{-- ═══ RIGHT — Form ═══ --}}
        <div class="login-form-area">
            <div class="login-card">

           

                <h3 class="form-title">Sign in</h3>
                <p class="form-subtitle">Enter your credentials to access your account.</p>

                {{-- Form — route, method, validation all preserved --}}
                {{Form::open(['route'=>'login','method'=>'post','class'=>'form','onsubmit'=>'return loginvalidation();'])}}

                <div class="lp-form-content">
                    {{-- Email --}}
                    <div class="f-group">
                        <label for="email">Email</label>
                        <div class="form-group input-lg log-email">
                            {{Form::text('email','',['class'=>'form-control','placeholder'=>'Email address','id'=>'email','autocomplete'=>'username'])}}
                        </div>
                        <span id="email_error" class="f-error"></span>
                        @if($errors->has('email'))
                            <div class="f-alert"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    {{-- Password --}}
                    <div class="f-group">
                        <label for="password">Password</label>
                        <div class="form-group input-lg log-password pw-field">
                            <i class="fa fa-eye icon show_hide" aria-hidden="true" onclick="myFunction()"></i>
                            {{Form::password('password',['class'=>'form-control','placeholder'=>'Password','id'=>'password','autocomplete'=>'current-password'])}}
                        </div>
                        <span id="password_error" class="f-error"></span>
                        @if ($errors->has('password'))
                            <div class="f-alert"><i class="fa fa-exclamation-circle"></i> {{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    @if(Session::has('msg'))
                        <div class="f-alert"><i class="fa fa-exclamation-triangle"></i> {{ Session::get('msg') }}</div>
                    @endif
                </div>

                <div class="f-submit">
                    {{Form::submit('Sign In',['class'=>'btn btn-primary btn-lg btn-block'])}}
                </div>

                {{Form::close()}}
            </div>
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