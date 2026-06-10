
    <div class="file_manager font" id="event-table">
        <div class="row clearfix">
            @forelse($event as $row)
                <div class="col-lg-3 col-md-4 col-sm-12 editEvent" data-id="{{encrypt($row->id)}}">
                    <div class="card">
                        <div class="file">
                            <a href="javascript:void(0);">
                                <div class="hover">
                                    <button type="button" class="btn btn-icon candor-color btn-icon-mini btn-round btn-danger delete-event" data-id="{{$row->id}}">
                                        <i class="zmdi zmdi-delete material-icons"></i>
                                    </button>
                                </div>
                                <div class="image popup">
                                    <img src="{{ cdnUrl($row->event_picture, null) }}" alt="img" class="img-fluid">
                                </div>
                                <div class="file-name">

                                    <p class="m-b-5 text-muted">{{ucfirst($row->title)}}</p>
                                        @if($row->start_date <= $today && $row->end_date >= $today && $row->status == 1)
                                            <small class="text-success">Now<span class="date text-muted">
                                            {{date('j F, Y', strtotime($row->start_date))}} &nbsp  to &nbsp<!-- {{Carbon\Carbon::parse($row->end_date)->toFormattedDateString()}} -->
                                            {{date('j F, Y', strtotime($row->end_date))}}
                                            </span></small>
                                        @elseif($row->start_date <  $today && $row->status == 1)
                                            <small class=" text-secondary">Finished<span class="date text-muted">{{date('j F, Y', strtotime($row->start_date))}} &nbsp  to &nbsp{{date('j F, Y', strtotime($row->end_date))}}</span></small>
                                        @elseif($row->start_date >  $today && $row->status == 1)
                                            <small class="text-danger">Upcoming<span class="date text-muted">
                                                {{date('j F, Y', strtotime($row->start_date))}} &nbsp  to &nbsp{{date('j F, Y', strtotime($row->end_date))}}</span></small>
                                        @elseif($row->status == 2)
                                            <small class=" text-secondary">Deactivated<span class="date text-muted">{{date('j F, Y', strtotime($row->start_date))}} &nbsp  to &nbsp    {{date('j F, Y', strtotime($row->end_date))}}</span></small>
                                        @endif
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
            {{-- <div class="row"> --}}
            <div class="col-sm-12 text-center">
                No records found
            {{-- </div> --}}
            </div>
            @endforelse
        </div>
    </div>
{{$event->links()}}
