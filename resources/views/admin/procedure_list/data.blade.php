<div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
@php
    $i = 1;   
    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
@endphp
@foreach($days as $day)
@php
    $rowlist  = isset($procedure[$day]) ? $procedure[$day][0]->day : $day;
    $today = \Carbon\Carbon::now()->format('l');
    // echo $today;
@endphp
{{-- @foreach($procedure as $rowlist => $data) --}}
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="{{$i}}">
        <h4 class="panel-title"><a class="{{'candor-color ' .($today != $rowlist ? 'collapsed' : '') }}" role="button" data-toggle="collapse" data-parent="{{'#day_'.$i}}" href="{{'#day_'.$i}}" aria-expanded="false" aria-controls="{{'day_'.$i}}"><b>{{ucwords($rowlist)}}</b></a></h4>
    </div>
    <div id="{{'day_'.$i}}" class="{{'panel-collapse collapse'.($today == $rowlist ? 'show' : '')}}" role="tabpanel" aria-labelledby="{{$i}}">
        <div class="panel-body">
            <div class="row">
                @if(isset($procedure[$day]))
                    @foreach($procedure[$day] as $row)
                        <div class="col-md-3 mb-1">
                            <div class="procedure-content">
                                <ul>
                                    <li class=""><b><span>Date : </span>{{$row->date}}</b></li>
                                    <li class=""><b>{{ucwords($row->getPatientsDetails['name'])}}</b></li>
                                    <li class="">{{$row->getPatientsDetails['mobile_number'].(!empty($row->getPatientsDetails['other_mobile_number']) ? ' , '.$row->getPatientsDetails['other_mobile_number'] : '')}}</li>
                                    <li class="font-bold">{{ucwords($row->procedure)}}</li>
                                    <li class=""><span><b>Detail : </b></span>{{ucwords($row->description)}}</li>
                                    <li class="">
                                        <div class="{{'edit-remark-data edit-remark-'.$row->id}}">
                                            @if($row->remark)
                                            <span><b>Remark : </b></span>{!!wordwrap($row->remark, 40,"<br>\n") !!}
                                                <span class="edit-remark">
                                                    <i class="material-icons edit-remark-icon pencil-icon" data-value="{{$row->remark}}" data-procedureid="{{encrypt($row->id)}}" data-id="{{$row->id}}">edit</i>
                                                </span>
                                            @else
                                            <span><b>Remark : </b></span>
                                                <span class="edit-remark">
                                                    <i class="material-icons edit-remark-icon" data-value="{{$row->remark}}" data-procedureid="{{encrypt($row->id)}}" data-id="{{$row->id}}">add</i>
                                                </span>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12 mb-1 text-center">
                        No Procedure
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@php
$i++;    
@endphp
@endforeach
</div>