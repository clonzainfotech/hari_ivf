<div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
@php
    $i = 1;   
    $days = ['Monday','Tuesday','Wednesday','Thrusday','Friday','Saturday','Sunday'];
@endphp
@foreach($days as $day)
@php
    $rowlist  = isset($procedure[$day]) ? $procedure[$day][0]->day : $day;
@endphp
{{-- @foreach($procedure as $rowlist => $data) --}}
<div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="{{$i}}">
        <h4 class="panel-title"><a class="collapsed candor-color" role="button" data-toggle="collapse" data-parent="{{'#day_'.$i}}" href="{{'#day_'.$i}}" aria-expanded="false" aria-controls="{{'day_'.$i}}"><b>{{ucwords($rowlist)}}</b></a></h4>
    </div>
    <div id="{{'day_'.$i}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$i}}">
        <div class="panel-body">
            <div class="row">
                @if(isset($procedure[$day]))
                    @foreach($procedure[$day] as $row)
                        <div class="col-md-3">
                            <div class="procedure-content">
                                <ul>
                                    <li class=""><b>{{ucwords($row->getPatientsDetails['name'])}}</b></li>
                                    <li class="">{{$row->getPatientsDetails['mobile_number']}}</li>
                                    <li class="font-bold">{{ucwords($row->procedure)}}</li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
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