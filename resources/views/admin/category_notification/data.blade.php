{{-- <table class="table m-b-0 table-hover" id="notification-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Date</th>
        <th>Name</th>
        <th>Message</th>
    </tr>
    </thead>
    <tbody>
    @forelse($data as $row)
        <tr data-id="{{encrypt($row->id)}}" class="">
            <td>{{ ((($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration) . '.' }}</td>
            <td>{{ cdate($row->date)->format('d-m-Y h:i A') }}</td>
            <td><span class="list-name">{{ ucfirst($row->getPatients['name']) }}</span></td>
            <td class="">{{ $row->message}}</td>
        </tr>
    @empty
        <td colspan="4" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table> --}}
<div class="col-md-12 col-lg-12 col-xl-12">
    <ul class="mail_list list-group list-unstyled">
        @forelse($data as $row)
            @if($notificationType == 'category')
                @php
                    $read_by = explode(',',$row->read_by);
                    $status = '';
                    if($row->getPatients['gender'] == 2)
                    {
                        $image = !empty($row->getPatients['profile_picture']) ? cdnUrl($row->getPatients['profile_picture'], null) : URL::to('assets/images/female1.png');
                    }
                    if($row->getPatients['gender'] == 1)
                    {
                        $image = !empty($row->getPatients['profile_picture']) ? cdnUrl($row->getPatients['profile_picture'], null) : URL::to('assets/images/male1.png');
                    }
                    if(in_array($row->categoryDetails['id'],[1,2]))
                    {
                        $class="bg-red";
                    }
                    if(in_array($row->categoryDetails['id'],[3,4]))
                    {
                        $class="bg-blush";
                    }
                    if(in_array($row->categoryDetails['id'],[5,6]))
                    {
                        $class="bg-green";
                    }
                @endphp
                <li class="list-group-item unread">
                    <div class="media mb-0">
                        <div class="d-flex">                                
                            <div class="controls mt-3">
                                @if(in_array(Auth::user()->id,$read_by))
                                    @php 
                                    $status = "seen";
                                    @endphp
                                    <a href="javascript:void(0);" class="favourite hidden-sm-down" data-toggle="active"><i class="zmdi zmdi-star"></i></a>
                                @else
                                    @php 
                                    $status = "Unseen";
                                    @endphp
                                    <a href="javascript:void(0);" class="favourite hidden-sm-down" data-toggle="active"><i class="zmdi zmdi-star-outline"></i></a>
                                @endif
                            </div>
                            <div class="thumb hidden-sm-down mr-4 ml-4"> <img src={{$image}} class="rounded-circle" alt="" width="50" height="50"> </div>
                        </div>
                        <div class="media-body">
                            <div class="media-heading">
                                <a href="" class="m-r-10 text-dark">{{ ucWords($row->getPatients['name']) }}</a>
                                <span class="{{'badge rounded-pill '.$class}}">{{$row->categoryDetails['name']}}</span>
                                
                                <small class="float-right text-muted"><time class="hidden-sm-down" datetime="2017">{{ cdate($row->date)->format('d M Y h:i A') }}</time></small>
                                
                            </div>
                            <p class="msg">{{ $row->message}} <small class="text-muted font-bold">{{' - '.$status }}</small></p>
                        </div>
                    </div>
                </li>
            @endif
            @if($notificationType == 'payment')
            @php
                if($row->getPatientsData['gender'] == 2)
                {
                    $image = !empty($row->getPatientsData['profile_picture']) ? cdnUrl($row->getPatientsData['profile_picture'], null) : URL::to('assets/images/female1.png');
                }
                if($row->getPatientsData['gender'] == 1)
                {
                    $image = !empty($row->getPatientsData['profile_picture']) ? cdnUrl($row->getPatientsData['profile_picture'], null) : URL::to('assets/images/male1.png');
                }
                if($row->category == 'IVF')
                {
                    $class="bg-red";
                }
                if($row->category == 'Hormon')
                {
                    $class="bg-blush";
                }
                if($row->category == 'IUI')
                {
                    $class="bg-green";
                }
            @endphp
            <li class="list-group-item unread">
                <div class="media mb-0">
                    <div class="d-flex">                                
                        <div class="controls mt-3">
                            @if($row->status == 0)
                                @php 
                                $status = "Unpaid";
                                @endphp
                                <a href="javascript:void(0);" class="favourite hidden-sm-down payment-status" data-toggle="active"><i class="zmdi zmdi-star-outline"></i></a>
                            @else
                                @php 
                                $status = "paid";
                                @endphp
                                <a href="javascript:void(0);" class="favourite hidden-sm-down" data-toggle="active"><i class="zmdi zmdi-star"></i></a>
                            @endif
                        </div>
                        <div class="thumb hidden-sm-down mr-4 ml-4"> <img src={{$image}} class="rounded-circle" alt="" width="50" height="50"> </div>
                    </div>
                    <div class="media-body">
                        <div class="media-heading">
                            <a href="" class="m-r-10 text-dark">{{ ucWords($row->getPatientsData['name']) }}</a>
                            <span class="{{'badge rounded-pill '.$class}}">{{$row->category}}</span>
                            
                            <small class="float-right text-muted"><time class="hidden-sm-down" datetime="2017">{{ cdate($row->date)->format('d M Y') }}</time></small>
                            
                        </div>
                        <p class="msg">&#x20b9;&nbsp;{{ $row->payment}} <small class="text-muted font-bold">{{' - '.$status }}</small></p>
                    </div>
                </div>
            </li>
            @endif
            @empty
            <li class="list-group-item unread">No records available</li>
        @endforelse
    </ul>
    {{-- <div class="card m-t-5">
        <div class="body">
            <ul class="pagination pagination-primary m-b-0">
                <li class="page-item"><a class="page-link" href="javascript:void(0);">Previous</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
                <li class="page-item active"><a class="page-link" href="javascript:void(0);">2</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                <li class="page-item"><a class="page-link" href="javascript:void(0);">Next</a></li>
            </ul>
        </div>
    </div> --}}
</div>
{{$data->links()}}
