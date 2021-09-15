@extends('layouts.main')
@section('parentPageTitle', 'User')
@section('title', 'Add User')

@section('page-style')

@stop
@section('content')
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Add User</strong>
                    </h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('user')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    {{Form::open([
                        'url'=>'user',
                        'method'=>'post',
                        'class'=>'form',
                        'files'=>'true',
                        'onsubmit'=>'return createvalidation();'
                    ])}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Name : &nbsp;</span>
                                    {{Form::text('name','',[
                                        'class'=>'form-control',
                                        'placeholder'=>'Name',
                                        'id'=>'name',
                                        ''
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error name" id="error_name">
                                    {{$errors->first('name')}}
                                </span>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Mobile : &nbsp;</span>
                                    {{Form::number('mobile_number','',[
                                        'class'=>'form-control mobile_number',
                                        'placeholder'=>'Mobile Number',
                                        'id'=>'mobile',
                                        'autocomplete' => 'off'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error mobile" id="error_mobile">
                                    {{$errors->first('mobile_number')}}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Email : &nbsp;</span>
                                    {{Form::email('email','',[
                                        'class'=>'form-control',
                                        'placeholder'=>'Email Address',
                                        'id'=>'email',
                                        'autocomplete' => 'off'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error email" id="error_email">
                                    {{$errors->first('email')}}
                                </span>
                            </div>

                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Password : &nbsp;</span>
                                    {{Form::password('password',[
                                        'class'=>'form-control',
                                        'placeholder'=>'Password',
                                        'id'=>'password',
                                        'autocomplete' => 'off'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error password" id="error_password">
                                    {{$errors->first('password')}}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Birth Date : &nbsp;</span>
                                    {{Form::text('birth_date','',[
                                        'class'=>'form-control datetimepicker dob_date',
                                        'placeholder'=>'Date of Birth',
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error dob_date">
                                    {{$errors->first('dob_date')}}
                                </span>
                            </div>

                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Designation : &nbsp;</span>
                                    {{Form::text('designation','',[
                                        'class'=>'form-control designation',
                                        'placeholder'=>'Designation',
                                    ])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('designation')}}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                {{Form::select('role',[
                                    1=>'Main Admin',
                                    2=>'Reception',
                                    3=>'Doctor',
                                    4=>'Accountant',
                                    5=>'Medical',
                                    6=>'IVF',
                                    7=>'IUI',
                                    8=>'ANC'
                                ],'',[
                                    'class'=>'form-control select-padding-0 user-roles',
                                    'placeholder'=>'Select Role',
                                    'id'=>'role'
                                ])}}
                                <span class="form-error-msg user_error role" id="error_role">
                                    {{$errors->first('role')}}
                                </span>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::select('status',[1=>'Active',2=>'Deactive'],'',['class'=>'form-control select-padding-0','placeholder'=>'Select Status' ,'id'=>'status'])}}
                                </div>
                                <span class="form-error-msg user_error status" id="error_status">
                                    {{$errors->first('status')}}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 doctor-fields">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Degree : &nbsp;</span>
                                    {{Form::text('degree','',[
                                        'class'=>'form-control degree',
                                        'placeholder'=>'Degree'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error degree">
                                    {{$errors->first('degree')}}
                                </span>
                            </div>
                            <div class="col-md-6 doctor-fields">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Specialist : &nbsp;</span>
                                    {{Form::text('specialist','',[
                                        'class'=>'form-control specialist',
                                        'placeholder'=>'Specialities'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error specialist">
                                    {{$errors->first('specialist')}}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 doctor-fields">
                                <div class="row">
                                    <div class="col-md-5 unik-lbl-spn">
                                        <label>Achievements Images :</label>
                                    </div>
                                    <div class="col-md-7">
                                        {{Form::file('achievement[]',[
                                            'class'=>'form-control achievement',
                                            'multiple' => true
                                        ])}}
                                    </div>
                                </div>
                                <span class="form-error-msg user_error achievement">
                                    {{$errors->first('achievement')}}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-3 unik-lbl-spn">
                                        <label>Profile Images :</label>
                                    </div>
                                    <div class="col-md-9">
                                        {{Form::file('profile_picture',['class'=>'form-control'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 doctor-fields">
                                <div class="row">
                                    <div class="col-sm-2 unik-lbl-spn">
                                        <label>Description :</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            {{Form::textarea('description','',[
                                                'class'=>'form-control description',
                                                'placeholder'=>'Description',
                                                'rows'=>'4',
                                                'cols'=>'50'
                                            ])}}
                                        </div>
                                    </div>
                                </div>
                                <span class="form-error-msg user_error descriptions">
                                    {{$errors->first('description')}}
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    {{Form::checkbox('is_mobile_view',0,'',['id'=>'is_mobile_view'])}}
                                    <label for="is_mobile_view">
                                        Mobile Preview
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6 doctor-fields">
                                <div class="checkbox">
                                    {{Form::checkbox('is_rmo_doctor',0,'',['id'=>'is_rmo_doctor'])}}
                                    <label for="is_rmo_doctor">
                                        RMO Doctor
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                {{Form::submit('submit',['class'=>'btn btn-primary'])}}
                                <a href="{{URL::to('user')}}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{asset('public/js/validation.js')}}"></script>
    <script>
        var roleValue = '';

        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time: false,
            weekStart: 1
        });
        $('#is_mobile_view, #is_rmo_doctor').click(function(){
            if($(this).prop("checked") == true){
                $(this).val(1);
            }
            else{
                $(this).val(0);
            }
        });
    </script>
@stop