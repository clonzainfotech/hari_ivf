@extends('layouts.main')
@section('parentPageTitle', 'Patient All Report')
@section('title', 'Patient All Report')
@section('page-style')
<link href="https://cdn.rawgit.com/sachinchoolur/lightgallery.js/master/dist/css/lightgallery.css" rel="stylesheet">
<link href="{{URL::to('public/css/light-gallery.css')}}" rel="stylesheet">
  
@stop

<section class="content home">
	<div class="block-header">
        <div class="row">
            <div class="col-lg-7 col-md-7 col-sm-12 text-right">
                @if (Request::segment(2) === 'doctors' or Request::segment(2) === 'add-doctor' or Request::segment(2) === 'all-patients' or Request::segment(2) === 'add-patients' or Request::segment(2) === 'add-payment' or Request::segment(2) === 'all-departments' or Request::segment(2) === 'add-department')
                    <button class="btn btn-white btn-icon d-none d-md-inline-block float-right m-l-10" type="button">
                        <i class="zmdi zmdi-plus"></i>
                    </button>
                @endif

                @if (Request::segment(2) === 'profile' or Request::segment(2) === 'patients-profile')
                    <button class="btn btn-white btn-icon d-none d-md-inline-block float-right m-l-10" type="button">
                        <i class="zmdi zmdi-edit"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-md-12 p-0">
            <div class="card patients-list">
                <div class="header d-flex">
                    <div class="col-md-6">
                        @php
                            $careOf = (!empty($patientsDetails->reference_doctor_id) && isset($referenceDoctor[$patientsDetails->reference_doctor_id])) ? $referenceDoctor[$patientsDetails->reference_doctor_id] : '';
                        @endphp
                        <h2><strong class="text-secondary"> {{ucwords($patientsDetails->name)}}</strong>{{' care of '.$careOf}}</h2>
                    </div>
                    {{-- <div class="col-md-6 text-right">
                        <a href="{{url()->previous()}}" class="btn btn-primary m-n2">Back</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
		@php
			if(empty($status))
			{
				$ancReport = '';
				$ivfReport = '';
				$iuiReport = '';
				$gynecReport = '';
			}
			else 
			{
				$ancReport = !empty($status) && $status == 'anc' ? '' : 'd-none';
				$ivfReport = !empty($status) && $status == 'ivf' ? '' : 'd-none';
				$iuiReport = !empty($status) && $status == 'iui' ? '' : 'd-none';
				$gynecReport = !empty($status) && $status == 'gynec' ? '' : 'd-none';
			}
			
		@endphp
        <div class="{{'col-md-12 '.$ancReport}}">
            <div class="card">
                <div class="header">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <strong class="pr-3">ANC Reports</strong>
                            
                        </div>
                    </div>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        <div class="cont">
                            <div class="page-head">
                                <div class="demo-gallery">
                                    <ul id="lightgallery1">
                                    @if($ANCReports)
										@foreach($ANCReports as $key => $reports)
											@php
											$date = \Carbon\Carbon::parse($key)->format('D d M Y');
											@endphp
											@foreach($reports as $report => $value)
												@php
													switch($report)
													{
													case 'early_scan':
														$report_name = 'Early Scan Report';
														break;
													case 'growth_report':
														$report_name = 'Growth Report';
														break;
													case 'other_report':
														$report_name = 'Other Report';
														break;
													case 'other_report':
														$report_name = 'Other Report';
														break;
													case 'anc_report':
														$report_name = 'Anc Report';
														break;
													case 'usg_report':
														$report_name = 'Usg Report';
														break;
													}
												@endphp
												@if(!empty($value))
													@foreach($value as $image)
													@php
                                                    
                                                        $imageType = '';
                                                        if(is_file($image))
                                                        {
														    $imageType = mime_content_type($image);   
                                                        }
													@endphp
													@if($imageType == "application/pdf" && !empty($imageType))     
													<li data-responsive="{{asset($image)}}" data-iframe="true" data-src="{{asset($image)}}"
													data-sub-html="<h4>{{$report_name}}</h4><p>Uploaded On {{$date}}</p>">
														<a href="{{asset($image)}}" class="mb-1" target="_blank">
															<img class="img-responsive" src="{{asset('public/images/default-pdf.png')}}">
															<div class="demo-gallery-poster">
																<img src="https://sachinchoolur.github.io/lightgallery.js/static/img/zoom.png">
															</div>
														</a>
														<div class="content"><h6 class="candor-color">{{$report_name}}</h6><p>{{$date}}</p></div>
													</li>
													@endif
													@if($imageType == "image/png" || $imageType == "image/jpg" || $imageType == "image/jpeg" && !empty($imageType))     
														<li data-responsive="{{asset($image)}}" data-src="{{asset($image)}}"
														data-sub-html="<h4>{{$report_name}}</h4><p>Uploaded On {{$date}}</p>">
															<a href="" class="mb-1">
																<img class="img-responsive" src="{{asset($image)}}">
																<div class="demo-gallery-poster">
																	<img src="https://sachinchoolur.github.io/lightgallery.js/static/img/zoom.png">
																</div>
															</a>
															<div class="content"><h6 class="candor-color">{{$report_name}}</h6><p>{{$date}}</p></div>
														</li>
														@endif
													@endforeach
												@endif
											@endforeach
										@endforeach
                                    @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="{{'col-md-12 '.$ivfReport}}">
            <div class="card">
                <div class="header">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <strong class="pr-3">IVF Reports</strong>
                            
                        </div>
                    </div>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        <div class="cont">
                            <div class="page-head">
                                <div class="demo-gallery">
                                    <ul id="lightgallery2">
                                    @if($IVFReports)
										@foreach($IVFReports as $key => $reports)
											@php
											$date = \Carbon\Carbon::parse($key)->format('D d M Y');
											@endphp
											@foreach($reports as $report => $value)
												@php
													switch($report)
													{
													case 'hystroscopy':
														$report_name = 'Hystroscopy Report';
														break;
													case 'laproscopy':
														$report_name = 'Laproscopy Report';
														break;
													case 'hcg':
														$report_name = 'HCG Report';
														break;
													case 'blood_report':
														$report_name = 'Blood Report';
														break;
													case 'usg_report':
														$report_name = 'Usg Report';
														break;
													case 'hsa_report':
														$report_name = 'HSA Report';
														break;
													}
												@endphp
												@if(!empty($value))
													@foreach($value as $image)
													@php
                                                    
                                                        $imageType = '';
                                                        if(is_file($image))
                                                        {
														    $imageType = mime_content_type($image);   
                                                        }
													@endphp
													@if($imageType == "application/pdf" && !empty($imageType))     
													<li data-responsive="{{asset($image)}}" data-iframe="true" data-src="{{asset($image)}}"
													data-sub-html="<h4>{{$report_name}}</h4><p>Uploaded On {{$date}}</p>">
														<a href="{{asset($image)}}" class="mb-1" target="_blank">
															<img class="img-responsive" src="{{asset('public/images/default-pdf.png')}}">
															<div class="demo-gallery-poster">
																<img src="https://sachinchoolur.github.io/lightgallery.js/static/img/zoom.png">
															</div>
														</a>
														<div class="content"><h6 class="candor-color">{{$report_name}}</h6><p>{{$date}}</p></div>
													</li>
													@endif
													@if(($imageType == "image/png" || $imageType == "image/jpg" || $imageType == "image/jpeg" )&& !empty($imageType))     
														<li data-responsive="{{asset($image)}}" data-src="{{asset($image)}}"
														data-sub-html="<h4>{{$report_name}}</h4><p>Uploaded On {{$date}}</p>">
															<a href="" class="mb-1">
																<img class="img-responsive" src="{{asset($image)}}">
																<div class="demo-gallery-poster">
																	<img src="https://sachinchoolur.github.io/lightgallery.js/static/img/zoom.png">
																</div>
															</a>
															<div class="content"><h6 class="candor-color">{{$report_name}}</h6><p>{{$date}}</p></div>
														</li>
														@endif
													@endforeach
												@endif
											@endforeach
										@endforeach
                                    @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="{{'col-md-12 '.$iuiReport}}">
            <div class="card">
                <div class="header">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <strong class="pr-3">IUI Reports</strong>
                            
                        </div>
                    </div>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        <div class="cont">
                            <div class="page-head">
                                <div class="demo-gallery">
                                    <ul id="lightgallery3">
                                    @if($IUIReports)
										@foreach($IUIReports as $key => $reports)
											@php
											$date = \Carbon\Carbon::parse($key)->format('D d M Y');
											@endphp
											@foreach($reports as $report => $value)
												@php
													switch($report)
													{
													case 'hystroscopy':
														$report_name = 'Hystroscopy Report';
														break;
													case 'laproscopy':
														$report_name = 'Laproscopy Report';
														break;
													case 'hcg':
														$report_name = 'HCG Report';
														break;
													case 'blood_report':
														$report_name = 'Blood Report';
														break;
													case 'usg_report':
														$report_name = 'Usg Report';
														break;
													case 'hsa_report':
														$report_name = 'HSA Report';
														break;
													}
												@endphp
												@if(!empty($value))
													@foreach($value as $image)
													@php
                                                    
                                                        $imageType = '';
                                                        if(is_file($image))
                                                        {
														    $imageType = mime_content_type($image);   
                                                        }
													@endphp
													@if($imageType == "application/pdf" && !empty($imageType))     
													<li data-responsive="{{asset($image)}}" data-iframe="true" data-src="{{asset($image)}}"
													data-sub-html="<h4>{{$report_name}}</h4><p>Uploaded On {{$date}}</p>">
														<a href="{{asset($image)}}" class="mb-1" target="_blank">
															<img class="img-responsive" src="{{asset('public/images/default-pdf.png')}}">
															<div class="demo-gallery-poster">
																<img src="https://sachinchoolur.github.io/lightgallery.js/static/img/zoom.png">
															</div>
														</a>
														<div class="content"><h6 class="candor-color">{{$report_name}}</h6><p>{{$date}}</p></div>
													</li>
													@endif
													@if(($imageType == "image/png" || $imageType == "image/jpg" || $imageType == "image/jpeg" )&& !empty($imageType))     
														<li data-responsive="{{asset($image)}}" data-src="{{asset($image)}}"
														data-sub-html="<h4>{{$report_name}}</h4><p>Uploaded On {{$date}}</p>">
															<a href="" class="mb-1">
																<img class="img-responsive" src="{{asset($image)}}">
																<div class="demo-gallery-poster">
																	<img src="https://sachinchoolur.github.io/lightgallery.js/static/img/zoom.png">
																</div>
															</a>
															<div class="content"><h6 class="candor-color">{{$report_name}}</h6><p>{{$date}}</p></div>
														</li>
														@endif
													@endforeach
												@endif
											@endforeach
										@endforeach
                                    @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div class="{{'col-md-12 '.$gynecReport}}">
            <div class="card">
                <div class="header">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <strong class="pr-3">Gynec Reports</strong>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        <div class="cont">
                            <div class="page-head">
                                <div class="demo-gallery">
                                    <ul id="lightgallery4">
                                    @if($GynecReports)
										@foreach($GynecReports as $key => $reports)
											@php
											$date = \Carbon\Carbon::parse($key)->format('D d M Y');
											@endphp
											@foreach($reports as $report => $value)
												@php
													switch($report)
													{
													case 'report':
														$report_name = 'Report';
														break;
													
													}
												@endphp
												@if(!empty($value))
													@foreach($value as $image)
													@php
                                                    
                                                        $imageType = '';
                                                        if(is_file($image))
                                                        {
														    $imageType = mime_content_type($image);   
                                                        }
													@endphp
													@if($imageType == "application/pdf" && !empty($imageType))     
													<li data-responsive="{{asset($image)}}" data-iframe="true" data-src="{{asset($image)}}"
													data-sub-html="<h4>{{$report_name}}</h4><p>Uploaded On {{$date}}</p>">
														<a href="{{asset($image)}}" class="mb-1" target="_blank">
															<img class="img-responsive" src="{{asset('public/images/default-pdf.png')}}">
															<div class="demo-gallery-poster">
																<img src="https://sachinchoolur.github.io/lightgallery.js/static/img/zoom.png">
															</div>
														</a>
														<div class="content"><h6 class="candor-color">{{$report_name}}</h6><p>{{$date}}</p></div>
													</li>
													@endif
													@if(($imageType == "image/png" || $imageType == "image/jpg" || $imageType == "image/jpeg" )&& !empty($imageType))     
														<li data-responsive="{{asset($image)}}" data-src="{{asset($image)}}"
														data-sub-html="<h4>{{$report_name}}</h4><p>Uploaded On {{$date}}</p>">
															<a href="" class="mb-1">
																<img class="img-responsive" src="{{asset($image)}}">
																<div class="demo-gallery-poster">
																	<img src="https://sachinchoolur.github.io/lightgallery.js/static/img/zoom.png">
																</div>
															</a>
															<div class="content"><h6 class="candor-color">{{$report_name}}</h6><p>{{$date}}</p></div>
														</li>
														@endif
													@endforeach
												@endif
											@endforeach
										@endforeach
                                    @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
</section>

<script src="https://cdn.rawgit.com/sachinchoolur/lightgallery.js/master/dist/js/lightgallery.js"></script>
<script src="https://cdn.rawgit.com/sachinchoolur/lg-pager.js/master/dist/lg-pager.js"></script>
<script src="https://cdn.rawgit.com/sachinchoolur/lg-autoplay.js/master/dist/lg-autoplay.js"></script>
<script src="https://cdn.rawgit.com/sachinchoolur/lg-fullscreen.js/master/dist/lg-fullscreen.js"></script>
<script src="https://cdn.rawgit.com/sachinchoolur/lg-zoom.js/master/dist/lg-zoom.js"></script>
<script src="https://cdn.rawgit.com/sachinchoolur/lg-hash.js/master/dist/lg-hash.js"></script>
<script src="https://cdn.rawgit.com/sachinchoolur/lg-share.js/master/dist/lg-share.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    lightGallery(document.getElementById('lightgallery1'))
    lightGallery(document.getElementById('lightgallery2'))
    lightGallery(document.getElementById('lightgallery3'))
    lightGallery(document.getElementById('lightgallery4'))
	
  </script>
</body>