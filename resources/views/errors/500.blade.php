@extends('layouts.authentication')
@section('title', '500')
<style>
    .page-header .container>.content-center{
        top: 35% !important;
    }
    .main-text{
        color: #aca9a9 !important;
        font-size: 60px;
    }
    .sub-text{
        color: #aca9a9 !important;
        font-size: 30px;
    }
</style>
@php
    $systemSetting = systemSetting();
    $logo = isset($systemSetting->header_logo) && !empty($systemSetting->header_logo) ? $systemSetting->header_logo : null;
    $alt = isset($systemSetting->alt) && !empty($systemSetting->alt) ? $systemSetting->alt : null;
@endphp
@section('content')
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="card-plain">
                <div class="header">
                    <img src="{{ url('images/' . $logo )}}" alt="{{ !empty($alt) ? $alt : null }}"/>
                    <h5></h5>
                </div>
                <br>
                <p class="main-text">500</p>
            </div>
            <p class="sub-text">Whoops something went wrong</p>
            <br>
            <a href="{{URL::to('dashboard')}}" class="btn btn-primary">Go To Dashboard</a>
        </div>
    </div>
@stop