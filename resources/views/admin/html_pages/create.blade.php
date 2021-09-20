@extends('layouts.main')
@section('parentPageTitle', 'Html Page')
@section('title', 'Html Page')

@section('page-style')

@stop

@section('content')

    <div class="row clearfix">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Html Page</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('html-page')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    {{-- @if(Session::has('categorymsg'))
                        <div class="alert alert-warning">
                            <strong>Warning!</strong> {{Session::get('categorymsg')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">
                                    <i class="zmdi zmdi-close"></i>
                                </span>
                            </button>
                        </div>
                    @endif --}}
                    {{Form::open(['url'=>'html-page/store','method'=>'post','class'=>'form html-pagea-form','files'=>'true'])}}
                        <div class="row clearfix">
                            {{Form::hidden('html_id',!empty($html_page) && !empty($html_page->id) ? encrypt($html_page->id) : '',['class'=>'form-control','placeholder'=>'title'])}}
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Title :</span>
                                    {{Form::text('title',!empty($html_page) && !empty($html_page->title) ? $html_page->title : '',['class'=>'form-control','placeholder'=>'title'])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('title')}}
                                </span>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon unik-lbl-spn">Slug :</span>
                                    {{Form::text('slug',!empty($html_page) && !empty($html_page->slug) ? $html_page->slug : '',['class'=>'form-control','placeholder'=>'Slug',!empty($html_page) ? 'readonly' : ''])}}
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('slug')}}
                                </span>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><strong>Description :</strong></label>
                                    <textarea class="form-control" id="description" name="description">{{!empty($html_page) && !empty($html_page->description) ? $html_page->description : ''}}</textarea>
                                </div>
                                <span class="form-error-msg">
                                    {{$errors->first('description')}}
                                </span>
                            </div>
                            <div class="col-sm-12">
                                {{Form::submit('Save',['class'=>'btn btn-primary html-page-save'])}}
                                <a href="{{URL::to('html-page')}}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
        CKEDITOR.replace('description', {
            filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
});
    
    $(document).ready(function(){
        $(".html-pagea-form").submit(function() { $(".html-page-save").attr("disabled", true); });
    });
</script>
@stop