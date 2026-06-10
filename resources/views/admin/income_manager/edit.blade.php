@extends('layouts.main')
@section('parentPageTitle', 'Income Manager')
@section('title', 'Update Income')

@section('page-style')


@stop

@section('content')

    <div class="row clearfix income">
        <div class="col-md-12">
            <div class="card">
                <div class="header">
                    <h2><strong>Update Income</strong></h2>
                    <ul class="header-dropdown">
                        <li>
                            <a href="{{URL::to('income-manager')}}">
                                <button class="btn btn-primary">
                                    Back
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    <div class="col-md-12 col-lg-12">
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            {{Form::open(['method'=>'post','class'=>'form income-form','files'=>'true'])}}
                                <!-- patients basic information -->
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="headingThree_1">
                                        <h4 class="panel-title"> <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion_1" href="#patients" aria-expanded="true"
                                                aria-controls="patients"> Patients Basic Information</a> </h4>
                                    </div>
                                    <div id="patients" class="" role="tabpanel" aria-labelledby="headingThree_1">
                                        <div class="panel-body">
                                            <div class="row clearfix">

                                                <div class="col-sm-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="zmdi zmdi-calendar"></i>
                                                        </span>
                                                        {{Form::text('date',cdate($income->date)->format('D d M Y'),['class'=>'form-control datetimepicker date','placeholder'=>'Date','required'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('date')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{Form::number('amount',$income->amount,['class'=>'form-control amount','placeholder'=>'Amount','required'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('amount')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group">
                                                        {{Form::select('payment_method',['2'=>'Swipe','1'=>'Cash','3'=>'Cheque','4'=>'UPI','5'=>'NEFT'],$income->payment_method,['class'=>'form-control select-padding-0','id'=>'payment_method','placeholder'=>'Select Payment Method'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('gender')}}
                                                    </span>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <div class="form-group">
                                                        {{Form::select('incomecategory',$expensecategory,$income->income_category,['class'=>'form-control select-padding-0 category_data','placeholder'=>'Select Category','required'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('category')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{Form::text('given_by',$income->given_by,['class'=>'form-control given_by','placeholder'=>'Given by','required'])}}
                                                    </div>
                                                    <span class="form-error-msg">
                                                        {{$errors->first('given_by')}}
                                                    </span>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                        {{Form::select('patients_id',$patients,$income->patients_id,['class'=>'form-control patients_id','placeholder'=>'Select Patient','data-live-search' => 'true'])}}
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        {{Form::textarea('note',$income->note,['class'=>'form-control no-resize remark','placeholder'=>'Note','rows'=>'2'])}}
                                                    </div>
                                                </div>
                                                {{Form::hidden('is_print',0,['class'=>'form-control is_print'])}}
                                                {{Form::hidden('income_id',$income->id,['class'=>'form-control income-id'])}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    {{Form::submit('Submit',['class'=>'btn btn-primary income-save'])}}
                                    {{Form::submit('Save & Preview',['class'=>'btn btn-primary income-save btn-print'])}}
                                    <a href="{{URL::to('income-manager')}}" class="btn btn-default">Cancel</a>
                                </div>
                            {{Form::close()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script src="{{url('assets/plugins/momentjs/moment.js')}}"></script>
    <script>    $.fn.selectpicker.Constructor.DEFAULTS.iconBase = 'zmdi';
    $.fn.selectpicker.Constructor.DEFAULTS.tickIcon = 'zmdi-check';</script>
    <script type="text/javascript">

        var code = '';
        // $(".income-form").submit(function() { $(".income-save").attr("disabled", true); });
        $('.income-save').on('click',function(){
            $('.is_print').val(0);
            if($(this).hasClass('btn-print'))
            {
                $('.is_print').val(1);
            }
        })
        $(".income-form").submit(function(e) { 
            e.preventDefault();
            $(".income-save").attr("disabled", true);
            
            var incomeForm = new FormData($(".income-form")[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{URL::to('income-manager')}}"+'/'+$('.income-id').val(),
                dataType: 'json',
                method: 'POST',
                data:incomeForm,
                cache: false,
                contentType: false,
                processData: false,
            }).done(function(data) {
                console.log(data);
                if (data.status == 2) {
                    w = window.open(window.location.href, "_blank");
                    w.document.open();
                    w.document.write(data.data);
                    w.document.close();
                    w.window.print();
                } else {
                    window.location.href = "{{URL::to('income-manager')}}";
                }
            }).fail(function(error) {
                console.log(error);
            }); 
        });
        $(function () {
        //Datetimepicker plugin
            $('.datetimepicker').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY',
                clearButton: true,
                time:false,
                weekStart: 1
            });

            $('.timepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm',
                clearButton: true,
                date:false,
            });
        });
    </script>
@stop
