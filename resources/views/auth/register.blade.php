@extends('layouts.app')
@php
    $city = getCity();
    $state = getState();
    // print_r($city);
@endphp
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="f_name" class="col-form-label text-md-right">{{ __('First Name') }}</label>
                                    <input id="f_name" type="text" class="form-control @error('f_name') is-invalid @enderror" name="f_name" value="{{ old('f_name') }}" required autocomplete="f_name" autofocus>

                                    @error('f_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="l_name" class="col-form-label text-md-right">{{ __('Last Name') }}</label>
                                    <input id="l_name" type="text" class="form-control @error('l_name') is-invalid @enderror" name="l_name" value="{{ old('l_name') }}" required autocomplete="l_name" autofocus>
    
                                    @error('l_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="surname" class="col-form-label text-md-right">{{ __('Surname') }}</label>
                                    <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname') }}" required autocomplete="surname" autofocus>
    
                                    @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                        
                            <div class="form-group col-md-4">
                                <label for="dob" class="col-form-label text-md-right">{{ __('Date Of Birth') }}</label>
                                    <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" required autocomplete="dob" autofocus>
    
                                    @error('dob')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="mobile_number" class="col-form-label text-md-right">{{ __('Mobile Number') }}</label>
                                    <input id="mobile_number" type="text" class="form-control @error('mobile_number') is-invalid @enderror" name="mobile_number" value="{{ old('mobile_number') }}" required autocomplete="mobile_number" autofocus>
                                    @error('mobile_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="other_mobile_number" class="col-form-label text-md-right">{{ __('Other Mobile Numer') }}</label>
                                    <input id="other_mobile_number" type="text" class="form-control @error('other_mobile_number') is-invalid @enderror" name="other_mobile_number" value="{{ old('other_mobile_number') }}" autocomplete="other_mobile_number" autofocus>
                                    @error('other_mobile_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group col-md-12">
                                <label for="residence" class="col-form-label text-md-right">{{ __('Residence') }}</label>
                                    <textarea id="residence" type="text" class="form-control @error('residence') is-invalid @enderror" name="residence" required autocomplete="residence" autofocus>{{ old('residence') }}
                                    </textarea>
                                    @error('residence')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="main_area" class="col-form-label text-md-right">{{ __('Main Area') }}</label>
                                    <input id="main_area" type="text" class="form-control @error('main_area') is-invalid @enderror" name="main_area" value="{{ old('main_area') }}" required autocomplete="main_area" autofocus>
                                    @error('main_area')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="city" class="col-form-label text-md-right">{{ __('City') }}</label>
                                {{Form::select('city',!empty($city) ? $city : null,['class'=>'form-control','placeholder'=>'City','data-live-search'=>'true','required'])}}
                                    {{-- <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required autocomplete="city" autofocus> --}}
                                    
                                    @error('city')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="state" class="col-form-label text-md-right">{{ __('State') }}</label>
                                {{Form::select('state',!empty($state) ? $state : null,['class'=>'form-control','placeholder'=>'State','data-live-search'=>'true','required'])}}
                                {{-- <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" required autocomplete="state" autofocus> --}}
                                    @error('state')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label for="reference_doctor" class="col-form-label text-md-right">{{ __('Reference Doctor') }}</label>
                                <input id="reference_doctor" type="text" class="form-control"  name="reference_doctor" value="{{ old('reference_doctor') }}" autocomplete="reference_doctor" autofocus>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="reference_patient" class="col-form-label text-md-right">{{ __('Old Patient') }}</label>
                                <input id="reference_patient" type="text" class="form-control" name="reference_patient" value="{{ old('reference_patient') }}" autocomplete="reference_patient" autofocus>
                            </div> --}}
                            {{-- <div class="form-group col-md-12 text-center mt-2"><h4 class=""> <u>Other </u></h4></div> --}}
                            {{-- <div class=" col-md-12 card-header">{{ __('Other') }}</div>
                            <div class="form-group col-md-12 text-center mt-2">
                                <input id="benner" type="checkbox" class="" name="other[]" value="benner">
                                <label for="benner" class="col-form-label text-md-right mr-2">{{ __('Benner') }}</label>

                                <input id="youtube" type="checkbox" class="" name="other[]" value="youtube">
                                <label for="youtube" class="col-form-label text-md-right mr-2">{{ __('YouTube') }}</label>

                                <input id="camp" type="checkbox" class="" name="other[]" value="camp">
                                <label for="camp" class="col-form-label text-md-right mr-2">{{ __('Camp') }}</label>

                                <input id="call" type="checkbox" class="" name="other[]" value="call">
                                <label for="call" class="col-form-label text-md-right mr-2">{{ __('Call') }}</label>

                                <input id="pamplet" type="checkbox" class="" name="other[]" value="pamplet">
                                <label for="pamplet" class="col-form-label text-md-right mr-2">{{ __('Pamplet') }}</label>

                                <input id="facebook" type="checkbox" class="" name="other[]" value="facebook">
                                <label for="facebook" class="col-form-label text-md-right mr-2">{{ __('Facebook') }}</label>
                            </div> --}}
                            <div class="form-group col-md-12 text-center mt-2">
                                <input id="pregnate" type="checkbox" class="other_checkbox" name="reason" value="1">
                                <label for="pregnate" class="col-form-label text-md-right mr-2">{{ __('Pragnancy') }}</label>

                                <input id="noPregnate" type="checkbox" class="other_checkbox" name="reason" value="2">
                                <label for="noPregnate" class="col-form-label text-md-right mr-2">{{ __('No Pragnancy') }}</label>

                                <input id="other" type="checkbox" class="other_checkbox" name="reason" value="3">
                                <label for="other" class="col-form-label text-md-right mr-2">{{ __('Other') }}</label>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        $(document).on('click','.other_checkbox',function(){
            $('.other_checkbox').prop('checked',false);
            $(this).prop('checked',true);
        })
    })
   
</script>

