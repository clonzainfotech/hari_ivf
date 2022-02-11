@extends('layouts.main')
@section('parentPageTitle', 'User')
@section('title', 'Update User')

@section('page-style')

@stop

@section('content')
 @php
                $file = url('public/images/default_user.png');
                if (!empty($user->profile_picture) && file_exists($user->profile_picture))
                {
                    $file = url($user->profile_picture);
                }
            @endphp
    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Edit User</strong>
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
                        'class'=>'form',
                        'files'=>'true',
                        'id' => 'update_user'
                    ])}}
                        <div class="row clearfix">
                            {{Form::hidden('user_id', encrypt($user->id), [
                                'id' => 'user_id'
                            ])}}
                            {{Form::hidden('logged_in_user', Auth::user()->role, [
                                'id' => 'logged_in_user'
                            ])}}
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Name :</span>
                                    {{Form::text('name',$user->name,[
                                        'class'=>'form-control name',
                                        'placeholder'=>'Name'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error name">
                                    {{$errors->first('name')}}
                                </span>
                            </div>

                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Mobile :</span>
                                    {{Form::number('mobile_number',$user->mobile_number,[
                                        'class'=>'form-control mobile mobile_number',
                                        'placeholder'=>'Mobile Number',
                                        'autocomplete' => 'off'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error mobile_number">
                                    {{$errors->first('mobile_number')}}
                                </span>
                            </div>

                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Email :</span>
                                    {{Form::email('email',$user->email,[
                                        'class'=>'form-control email',
                                        'placeholder'=>'Email Address',
                                        Auth::user()->role == 1 ? '' : 'disable',
                                        'autocomplete' => 'off'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error email">
                                    {{$errors->first('email')}}
                                </span>
                            </div>

                            @if(Auth::user()->role == 1)
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon unik-lbl-spn">Password :</span>
                                        {{Form::password('password',[
                                            'class'=>'form-control',
                                            'placeholder'=>'Password',
                                            'autocomplete' => 'off'
                                        ])}}
                                    </div>
                                    <span class="form-error-msg user_error password">
                                        {{$errors->first('password')}}
                                    </span>
                                </div>
                                
                                <div class="col-md-6 col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon unik-lbl-spn">Birth Date : &nbsp;</span>
                                        {{Form::text('dob_date','',[
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
                                        {{Form::text('designation',$user->designation,[
                                            'class'=>'form-control designation',
                                            'placeholder'=>'Designation'
                                        ])}}
                                    </div>
                                    <span class="form-error-msg user_error designation">
                                        {{$errors->first('designation')}}
                                    </span>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        {{Form::select('role',$role,$user->role,['class'=>'form-control select-padding-0 user-roles','placeholder'=>'Select Role'])}}
                                    </div>
                                    <span class="form-error-msg user_error role">
                                        {{$errors->first('role')}}
                                    </span>
                                </div>

                            @endif

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {{Form::select('status',[1=>'Active',2=>'Deactive'],$user->status,['class'=>'form-control select-padding-0','placeholder'=>'Select Status'])}}
                                </div>
                                <span class="form-error-msg user_error status">
                                    {{$errors->first('status')}}
                                </span>
                            </div>
                            @php
                                $doctorFields = (!empty($user->role) && $user->role == 3) ? '' : 'd-none';
                            @endphp
                            <div class="{{ 'col-sm-6 doctor-fields '. $doctorFields }}">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Degree : &nbsp;</span>
                                    {{Form::text('degree',$user->degree,[
                                        'class'=>'form-control degree',
                                        'placeholder'=>'Degree'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error degree">
                                    {{$errors->first('degree')}}
                                </span>
                            </div>
                            <div class="{{ 'col-sm-6 doctor-fields '. $doctorFields }}">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Specialist : &nbsp;</span>
                                    {{Form::text('specialist',$user->specialist,[
                                        'class'=>'form-control specialist',
                                        'placeholder'=>'Specialities'
                                    ])}}
                                </div>
                                <span class="form-error-msg user_error specialist">
                                    {{$errors->first('specialist')}}
                                </span>
                            </div>
                            
                            <div class="{{ 'col-sm-6 doctor-fields '. $doctorFields }}">
                                
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
                                </span>
                            </div>

                            
                            <div class="col-md-4">
                                @if (!empty($user->profile_picture))
                                    <img src="{{ $file}}" class="mt-2 mb-2 profile_icon"/>
                                @endif
                                <div class="form-group">
                                    {{Form::file('profile_picture',['class'=>'form-control','placeholder'=>'Select Profile Picture'])}}
                                </div>
                            </div>
                                <span class="form-error-msg">
                                    {{$errors->first('profile_picture')}}
                                </span>
                            </div>

                            <div class="{{ 'col-sm-12 doctor-fields ' . $doctorFields }}">
                                <div class="row">
                                    <div class="col-sm-2 unik-lbl-spn">
                                        <label>Description :</label>        
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            {{Form::textarea('description',$user->description,[
                                                'class'=>'form-control description',
                                                'placeholder'=>'Description',
                                                'rows'=>'4',
                                                'cols'=>'50'
                                            ])}}
                                        </div>
                                    </div>
                                </div>
                                <span class="form-error-msg  user_error descriptions">
                                    {{$errors->first('description')}}
                                </span>
                            </div>

                            @php
                                $achievement = json_decode($user->achievement, true);
                            @endphp
                            <div class="{{ 'file_manager doctor-fields ' . $doctorFields }}">
                                <div class="row clearfix">
                                    <div class="col-lg-12">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="1">
                                                <div class="row clearfix">
                                                    @if ($achievement != null)
                                                        @foreach ($achievement as $key => $value)
                                                            <div class="col-lg-3 col-md-4 col-sm-12 p-4" id="{{ 'user_achievement_' . $key}}">
                                                                <div class="file">
                                                                    <a href="javascript:void(0);">
                                                                        <div class="hover">
                                                                        <button type="button" class="btn btn-icon btn-icon-mini btn-round btn-danger user-achievement" data-id="{{ encrypt($key) }}">
                                                                                <i class="zmdi zmdi-delete"></i>
                                                                            </button>
                                                                        </div>
                                                                        <div class="image">
                                                                            <img src="{{ url($value)}}" alt="img" class="img-fluid">
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="checkbox">
                                        {{Form::checkbox('is_mobile_view',$user->is_mobile_view,$user->is_mobile_view == 1 ? true: false,['id'=>'is_mobile_view'])}}
                                        <label for="is_mobile_view">
                                            Mobile Preview
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 doctor-fields ' . $doctorFields}}">
                                    <div class="checkbox">
                                        {{Form::checkbox('is_rmo_doctor',$user->is_rmo_doctor,$user->is_rmo_doctor == 1 ? true: false,['id'=>'is_rmo_doctor'])}}
                                        <label for="is_rmo_doctor">
                                            RMO Doctor
                                        </label>
                                    </div>
                                </div>
                                <div class="{{'col-md-4 doctor-fields ' . $doctorFields}}">
                                    <label class="vertical-form-label pr-0">
                                        Absence Date :
                                    </label>
                                    <div class="form-group">
                                        {{Form::text("absence_dates",!empty($user->absence_dates) ? $user->absence_dates : '',['class'=>'form-control datetime','placeholder'=>'Select Absence Date'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                {{Form::submit('submit',['class'=>'btn btn-primary submit'])}}
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
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.1/dist/jquery.validate.min.js"></script>
    <script>

        var roleValue = '';

        $('.datetimepicker').bootstrapMaterialDatePicker({
            format: 'dddd DD MMMM YYYY',
            clearButton: true,
            time: false,
            weekStart: 1
        });
        $(document).ready(function(){
            $('.datetime').multiDatesPicker({
                minDate: 0
            });
        });

        $('.mobile_number').keypress(function(event){
            var length = $(this).val().length;
            if(length == 10){
                event.preventDefault(); //stop character from entering input
            }
        });
        $(document).ready(function () {
            $(document).on('change','select.user-roles',function(){
                roleValue = $(this).val();
                if(roleValue == 3) {
                    $('.doctor-fields').removeClass('d-none');
                    $('.doctor-fields').addClass('d-block');
                }
                else {
                    $('.doctor-fields').removeClass('d-block');
                    $('.doctor-fields').addClass('d-none');
                }
            });

            $(document).on('click','.user-achievement',function(){
                var achievementId = $(this).data('id');
                var userId = $('#user_id').val();
                if(typeof(achievementId) !== 'undefined' && typeof(userId) !== 'undefined') {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{URL::to('/delete-achievement-image')}}",
                        type: 'POST',
                        data: {
                            user_id: userId,
                            achievement_id: achievementId,
                        },
                        dataType: 'json',
                    }).done(function(data) {
                        if (data.status == 1) {
                            $('#user_achievement_' + data.achievement_id).hide();
                        }
                        if (data.status == 2) {
                            swal({
                                title: 'Oops!',
                                text: data.message,
                                type: 'error'
                            }, function() {
                                window.location.reload();
                            });
                        }
                    });
                }
            });

            $(document).on('click','.submit',function(e){
                e.preventDefault();
                var formData = new FormData($("#update_user")[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url:'{{URL::to("update-user")}}',
                    type:'POST',
                    enctype: 'multipart/form-data',
                    dataType:'json',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                }).done(function(response){
                    if (response.status == 1) {
                        swal({
                            title: 'Success!',
                            text: response.message,
                            type: 'success'
                        }, function() {
                            window.location.href = '{{URL::to("user")}}';
                        });
                        
                    }
                    if (response.status == 2) {
                        swal({
                            title: 'Oops!',
                            text: response.message,
                            type: 'error'
                        }, function() {
                            window.location.reload();
                        });
                    }
                    if (response.status == 3) {
                        $('.form-error-msg').text('');
                        if(response.error != null){
                            var formError = response.error;
                            $.each(formError,function(key,value){
                                if (key.includes('achievement')) {
                                    var error = value[0].replace(/-?[0-9]*\.?[0-9]+/, '');
                                    $('.achievement').text(error);
                                }
                                $('.'+key).text(value);
                            });
                        }
                    }
                });

            });
            $('#is_mobile_view,#is_rmo_doctor').click(function(){
                if($(this).prop("checked") == true){
                    $(this).val(1);
                }
                else{
                    $(this).val(0);
                }
            });
        });
    </script>
@stop
