@extends('layouts.main')
@section('parentPageTitle', 'Holiday Manager')
@section('title', 'Holiday Manager')
@section('page-style')
    <link rel="stylesheet" href="{{url('assets/plugins/fullcalendar/fullcalendar.min.css')}}" />
@stop
@section('content')
    <div class="row clearfix holiday">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2><strong>Calendar</strong></h2>
                </div>
                <div class="body">
                    <!-- Tab panes -->
                    <div class="tab-content m-t-10">
                        <!-- notification -->
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
                        <div class="holiday-data table-responsive active">
                            <!-- table data here include -->
                            <div class="card">
                                <div class="body">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('modal')
    <!-- appointment charges Size -->
        <div class="modal fade holiday" id="holiday" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!-- header -->
                    <div class="modal-header justify-content-center">
                        <h4 class="title" id="defaultModalLabel">Holiday!</h4>
                    </div>
                    <!-- body -->
                    {{Form::open(['class'=>'form-inline','id'=>'holiday-form'])}}
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                    {{Form::text('holiday_title','',['class'=>'d-block form-control w-100 holiday-title','placeholder'=>'Please enter holiday name'])}}
                                    {{Form::hidden('id','',['id'=>'holiday-id'])}}
                            </div>
                        </div>
                    </div>
                    <!-- footer -->
                    <div class="modal-footer justify-content-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary waves-effect holiday-btn update-holiday">UPDATE</button>
                            <button type="button" class="btn btn-dangerwaves-effect holiday-btn delete-holiday-btn" data-dismiss="modal">DELETE</button>
                            <button type="button" class="btn btn-info waves-effect holiday-btn" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    @stop
@stop
@section('page-script')
<script src="{{url('assets/bundles/fullcalendarscripts.bundle.js')}}"></script>
    {{--<script src="{{url('assets/js/pages/calendar/calendar.js')}}"></script>--}}
    <script type="text/javascript">

        $(document).ready(function () {

            $(document).on('click','.delete-holiday-btn',function(e){
                var holidayId = $('#holiday-id').val();
                swal({
                    title: "Are you sure?",
                    text: "You want to delete this holiday!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                }, function () {
                    removeHoliday(holidayId);
                    swal("Deleted!", "Your holiday has been deleted.", "success");
                });
            });

            $(document).on('click','.update-holiday',function(e){
                var title = $('.holiday-title').val();
                var id = $('#holiday-id').val();
                updateHoliday(null,null,title,id);
            });

            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: [
                        @foreach($holiday as $task)
                    {
                        title : '{{ $task->name }}',
                        start : '{{ $task->from_date }}',
                        end : '{{ $task->end_date }}',
                        id : '{{ $task->id }}',
                        className: 'bg-danger text-white'
                    },
                    @endforeach
               
                        @foreach($event as $tasks)
                    {
                        title : '{{ $tasks->title }}',
                        start : '{{ $tasks->start_date }}',
                        end : '{{ cdate($tasks->end_date)->addDays(1)}}',
                        id : '0',
                        event: '{{ encrypt($tasks->id) }}',
                        className: 'bg-cyan'
                    },
                    @endforeach
                ],
                displayEventTime: false,
                eventRender: function (event, element, view) {
                    if (event.allDay === 'true') {
                        event.allDay = true;
                    } else {
                        event.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,
                select: function (start, end, allDay) {
                    swal({
                        title: "Holiday!",
                        text: "Enter Holiday Name",
                        type: "input",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        animation: "slide-from-top",
                        inputPlaceholder: "Please enter holiday name"
                    }, function (title) {
                        if (title === false)
                        {
                            return false;
                        }
                        if (title === "") {
                            swal.showInputError("Please enter holiday name!");
                            return false
                        }

                        var startdate = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                        var enddate = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                        addHoliday(title, startdate, enddate);
                        swal("Holiday Added!", "Holiday has been added", "success");

                        calendar.fullCalendar('renderEvent',
                            {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay,
                                className: 'bg-cyan'
                            },
                            true
                        );
                    });
                    calendar.fullCalendar('unselect');
                },
                editable: true,
                eventDrop: function (event, delta) {
                    var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                    var title = event.title;
                    updateHoliday(start,end,title,event.id);
                },
                eventClick: function (event) {
                        if (event.id != 0) {
                        $('.holiday-title').val(event.title);
                        $('#holiday-id').val(event.id);
                        $('#holiday').modal('show');
                        }else{
                           var eventID=event.event;
                           editEventpage(eventID);
                        }
            }
            });
        });

        function editEventpage(eventID){
        var eventId =eventID;
            if(typeof(eventId) !== 'undefined'){
                var url = 'event/'+eventId+'/edit';
                window.location.href = url;
            }
        }
    
        function updateHoliday(start,end,title,id){
            $.ajax({
                url: '{{URL::to('holiday-manager/update')}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "startdate":start,
                    "enddate":end,
                    "name" : title,
                    "id":id
                },
                type: "PUT",
            }).done(function(data) {
                location.reload();
            });
        }
        function addHoliday(title,start,end){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },
                url: "{{URL::to('holiday-manager/store')}}",
                dataType: 'JSON',
                type: 'POST',
                data: {
                    "name": title,
                    "startdate":start,
                    "enddate":end
                },
            }).done(function(data) {
               location.reload();
            });
        }
        function removeHoliday(holidayId){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '<?= csrf_token() ?>'
                },
                url: "{{URL::to('holiday-manager/delete')}}"+"/"+holidayId,
                dataType: 'JSON',
            }).done(function(data) {
               if(data == true){
                   location.reload();
               }
            });
        }
    </script>
@stop