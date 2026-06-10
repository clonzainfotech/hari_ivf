@extends('layouts.main')
@section('parentPageTitle', 'System Setting')
@section('title', 'System Setting')

@section('page-style')
@stop

@section('content')
<div class="row clearfix">
    <div class="col-md-12">
        <div class="card">
            <div class="header">
                <h2><strong>System Setting</strong>
                </h2>
			</div>
		</div>
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
		@php
			$url = ($data == null) ? 'system-create' : 'system-update';
		@endphp
        <div class="body">
        	{{Form::open([
				'url'=> $url,
				'method'=>'post',
				'class'=>'form',
				'files'=>true,
				'onsubmit'=>'return createvalidation()'
			])}}
			{{Form::hidden('setting_id', !empty($data) ? encrypt($data->id) : null)}}
	            <div class="col-md-12 grid-margin stretch-card">
	        		 <div class="card position-relative report-details">
			            <div class="header">
			                <h2><strong>HTML Head</strong></h2>
			            </div>
			            <div class="card-body">
			            	<div class="row clearfix">
			            		<div class="col-md-12">
			            			<div class="row mt-1">
			            				<div class="col-md-2">
			            					<label class="unik-lbl-spn">Favicon</label>
			            				</div>
			            				<div class="col-md-5">
			            					{{Form::file('html_favicon',[
												'class'=>'form-control',
											])}}
											<div class="col-md-12"><span class="text-mute instruction-filetype">Allowed file types: ico, png, gif, jpg, jpeg, apng. Not all browsers support all these formats!</span></div>
											<span class="form-error-msg">
												{{$errors->first('html_favicon')}}
											</span>
										</div>
										@if (!empty($data->html_favicon))
											<div class="col-md-4">
												<img src="{{url('assets/' . $data->html_favicon)}}"  class="system-setting-favicon"/>
											</div>
										@endif
			            			</div>
			            		</div>
			            		<div class="col-md-12">
			            			<div class="row mt-1">
			            				<div class="col-md-2">
			            					<label class="unik-lbl-spn">Default Page Title</label>
			            				</div>
			            				<div class="col-md-5">
			            					{{Form::text('html_title',!empty($data->html_title) ? $data->html_title : null , [
												'class'=>'form-control',
												'placeholder'=>''
											])}}
			            				</div>
			            			</div>
			            		</div>
			            	</div>
			           	</div>
	            	</div>	
	        	</div>
			    <div class="col-md-12 grid-margin stretch-card">
			        <div class="card position-relative report-details">
			            <div class="header">
			                <h2><strong>Header</strong></h2>
			            </div>
			            <div class="card-body">
			            	<div class="row clearfix">
			            		<div class="col-md-12">
			            			<div class="row mt-1">
			            				<div class="col-md-2">
			            					<label class="unik-lbl-spn">Logo Image</label>
			            				</div>
			            				<div class="col-md-5">
			            					{{Form::file('header_logo',[
												'class'=>'form-control',
											])}}
											<div class="col-md-12"><span class="text-mute instruction-filetype">Allowed file types: png, gif, jpg, jpeg.</span></div>
											<span class="form-error-msg">
												{{$errors->first('header_logo')}}
											</span>
											
										</div>
										@if (!empty($data->header_logo))
											<div class="col-md-4">
												<img src="{{url('images/' . $data->header_logo)}}" class="system-setting-logo"/>
											</div>
										@endif
										
			            			</div>
			            			
			            		</div>
			            		<div class="col-md-12">
			            			<div class="row mt-1">
			            				<div class="col-md-2">
			            					<label class="unik-lbl-spn">Logo Attribute width</label>
			            				</div>
			            				<div class="col-md-2">
			            					{{Form::number('header_logo_width',!empty($data->header_logo_width) ? $data->header_logo_width : null,['class'=>'form-control','placeholder'=>''])}}
										</div>
										<div class="col-md-1">
			            					px
			            				</div>
			            				<div class="col mt-1">
								</div>
			            			</div>
			            		</div>
			            		<div class="col-md-12">
			            			<div class="row mt-1">
			            				<div class="col-md-2">
			            					<label class="unik-lbl-spn">Logo Attribute height</label>
			            				</div>
			            				<div class="col-md-2">
			            					{{Form::number('header_logo_height',!empty($data->header_logo_height) ? $data->header_logo_height : null ,['class'=>'form-control','placeholder'=>''])}}
										</div>
										<div class="col-md-1">
			            					px
			            				</div>
			            				<div class="col mt-1">
								</div>
			            			</div>
			            		</div>
			            		<div class="col-md-12">
			            			<div class="row mt-1">
			            				<div class="col-md-2">
			            					<label class="unik-lbl-spn">Tag Line</label>
			            				</div>
			            				<div class="col-md-5">
			            					{{Form::text('tag_line',!empty($data->tag_line) ? $data->tag_line : null,[
												'class'=>'form-control',
												'id'=>'title',
												'placeholder'=>''
											])}}
			            				</div>
			            			</div>
			            		</div>
			            		<div class="col-md-12">
			            			<div class="row mt-1">
			            				<div class="col-md-2">
			            					<label class="unik-lbl-spn">Logo Image Alt</label>
			            				</div>
			            				<div class="col-md-5">
			            					{{Form::text('header_logo_alt',!empty($data->header_logo_alt) ? $data->header_logo_alt : null,[
												'class'=>'form-control',
												'placeholder'=>''
											])}}
			            				</div>
			            			</div>
			            		</div>
			            	</div>
			            </div>
			        </div>
			    </div>
			{{Form::close()}} 
    	</div>
	</div>
</div>
<div class="row clearfix">
	<div class="col-md-12">
		<div class="card patients-list">
			<div class="header">
				<h2><strong>Hospital Address</strong></h2>
			</div>
			<div class="body">
				@if(count($hospitalAddress) > 0	)
					<div class="card-body">
						<div class="list-wrapper pt-2">
							<ul class="d-flex flex-column-reverse todo-list todo-list-custom">
								@foreach ($hospitalAddress as $row)
									<li>
										<div class="col-md-4 hospital-address-wrap">
											{{$row->address}}
										</div>
										<div class="col-md-3">
											{{$row->mobile}}
										</div>
										<div class="col-md-3">
											{{$row->email}}
										</div>
										<div class="col-md-2">
											<a href="javascript:void(0)" class="btn btn-icon btn-neutral candor-color btn-icon-mini edit-address" data-id="{{ encrypt($row->id) }}">
												<i class="zmdi zmdi-edit material-icons"></i>
											</a>
											<a href="javascript:void(0)" class="btn btn-icon btn-neutral candor-color btn-icon-mini delete-address" data-id="{{ encrypt($row->id) }}">
												<i class="zmdi zmdi-delete material-icons"></i>
											</a>
										</div>
									</li>
								@endforeach
							</ul>
						</div>
					</div>
					<hr />
				@endif
					{{Form::open([
						'id' => 'hospital_address_form'
					])}}
					{{Form::hidden('hospital_address_id', null, [
						'id' => 'hospital_address_id'
					])}}
					<div class="card-body">
						<div class="row">
							<div class="col-md-1">
							<label class="unik-lbl-spn">Address</label>
							</div>
							<div class="col-md-5">
								<div class="input-group">
									{{Form::textarea('address', null, [
										'class'=>'form-control no-resize address',
										'placeholder'=>'Address',
										'rows'=>'3',
										'required',
									])}}
								</div>
								<span class="form-error-msg address"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
								<span class="input-group-addon unik-lbl-spn">Mobile</span>
									{{Form::text('mobile', null, [
										'class'=>'form-control no-resize mobile',
										'placeholder'=>'Mobile',
										// 'oninput' => 'checkMobile(this.value)',
										// 'onpaste' => 'return false',
										// 'maxlength' => 10,
										'autocomplete' => 'off',
										'required'
									])}}
								</div>
								<span class="form-error-msg mobile"></span>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-group">
								<span class="input-group-addon unik-lbl-spn">Email</span>
									{{Form::email('email', null, [
										'class'=>'form-control email',
										'placeholder'=>'Email',
										// 'required',
									])}}
								</div>
								<span class="form-error-msg email"></span>
						</div>
					</div>
					<div class="row">
						{{Form::submit('Save',['class'=>'btn btn-primary save-hospital-address'])}}
						<a href="{{URL::to('dashboard')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
{{-- system config --}}
<div class="row clearfix">
	<div class="col-md-12">
		<div class="card patients-list">
			<div class="header">
				<h2><strong>General Configuration</strong></h2>
			</div>
			<div class="body">
				{{Form::open(['url'=>'general-configuration','method'=>'post','files'=>'true'])}}
					<div class="card-body">
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Clear all logs/Database backups</label>
							</div>
							<div class="col-md-5">
								{{Form::number('clear_log_days',$data->clear_logs_day,['class'=>'form-control','placeholder'=>'','min'=>1,'max'=>'180'])}}
							</div>
						</div>
						<div class="row">
							<div class="col-md-2"></div>
							<span class="text-mute instruction-filetype ml-4">Clear all laravel logs and database backups according above value.</span>
						</div>
						<div class="row">
							<div class="col-md-2"></div>
							<span class="form-error-msg ml-4">
								{{$errors->first('clear_log_days')}}
							</span>
						</div>
						<div class="row mt-3">
							{{Form::submit('Save',['class'=>'btn btn-primary'])}}
							<a href="{{URL::to('systemsetting')}}" class="btn btn-default">Cancel</a>
						</div>
					</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
<div class="row clearfix">
	<div class="col-md-12">
		<div class="card patients-list">
			{{-- <div class="header">
				<h2><strong></strong></h2>
			</div> --}}
			<div class="body">
				{{Form::open(['url'=>'button','method'=>'post','files'=>'true'])}}
					<div class="card-body">
					
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Primary</label>
							</div>
							<div class="col-md-5">
								{{Form::text('primary',$data->primary,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Secondary</label>
							</div>
							<div class="col-md-5">
								{{Form::text('secondary',$data->secondary,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Link</label>
							</div>
							<div class="col-md-5">
								{{Form::text('link',$data->link,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-3">
							{{Form::submit('Save',['class'=>'btn btn-primary'])}}
							<a href="{{URL::to('systemsetting')}}" class="btn btn-default">Cancel</a>
						</div>
					</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
<div class="row clearfix">
	<div class="col-md-12">
		<div class="card patients-list">
			{{-- <div class="header">
				<h2><strong></strong></h2>
			</div> --}}
			<div class="body">
				{{Form::open(['url'=>'appointmentdata','method'=>'post','files'=>'true'])}}
					<div class="card-body">
					
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Before Visit</label>
							</div>
							<div class="col-md-5">
								{{Form::text('before_visits',$data->before_visits,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">After Visit</label>
							</div>
							<div class="col-md-5">
								{{Form::text('after_visits',$data->after_visits,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Unpaid Opd</label>
							</div>
							<div class="col-md-5">
								{{Form::text('unpaid_opd',$data->unpaid_opd,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-3">
							{{Form::submit('Save',['class'=>'btn btn-primary'])}}
							<a href="{{URL::to('systemsetting')}}" class="btn btn-default">Cancel</a>
						</div>
					</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
<div class="row clearfix">
	<div class="col-md-12">
		<div class="card patients-list">
			{{-- <div class="header">
				<h2><strong></strong></h2>
			</div> --}}
			<div class="body">
				{{Form::open(['url'=>'patientdetail','method'=>'post','files'=>'true'])}}
					<div class="card-body">
					
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Doctor 1</label>
							</div>
							<div class="col-md-5">
								{{Form::text('docter_1',$data->docter_1,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Doctor 2</label>
							</div>
							<div class="col-md-5">
								{{Form::text('docter_2',$data->docter_2,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Footer 1</label>
							</div>
							<div class="col-md-5">
								{{Form::text('footer_1',$data->footer_1,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Footer 2</label>
							</div>
							<div class="col-md-5">
								{{Form::text('footer_2',$data->footer_2,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">water mark</label>
							</div>
							<div class="col-md-5">
									{{Form::file('water_mark',[
												'class'=>'form-control',
											])}}
							</div>
							@if (!empty($data->water_mark))
							<div class="col-md-4">
								<img src="{{url('images/' . $data->water_mark)}}" class="system-setting-logo"/>
							</div>
						@endif
						</div>
						<div class="row mt-3">
							{{Form::submit('Save',['class'=>'btn btn-primary'])}}
							<a href="{{URL::to('systemsetting')}}" class="btn btn-default">Cancel</a>
						</div>
					</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
<div class="row clearfix">
	<div class="col-md-12">
		<div class="card patients-list">
			{{-- <div class="header">
				<h2><strong></strong></h2>
			</div> --}}
			<div class="body">
				{{Form::open(['url'=>'app-version','method'=>'post','files'=>'true'])}}
					<div class="card-body">
					
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">Android App Version</label>
							</div>
							<div class="col-md-5">
								{{Form::text('app_android_version',$data->app_android_version,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-1">
							<div class="col-md-2">
								<label class="unik-lbl-spn">iOS App Version</label>
							</div>
							<div class="col-md-5">
								{{Form::text('app_ios_version',$data->app_ios_version,['class'=>'form-control'])}}
							</div>
						</div>
						<div class="row mt-3">
							{{Form::submit('Save',['class'=>'btn btn-primary'])}}
							<a href="{{URL::to('systemsetting')}}" class="btn btn-default">Cancel</a>
						</div>
					</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
@stop
@section('page-script')
	<script src="{{url('js/system_setting.js')}}"></script>
@stop