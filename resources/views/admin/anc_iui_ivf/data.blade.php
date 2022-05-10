<table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
        <tr>             
            <th>Sr.No</th>
            <th>Date/Time</th>
            <th>Arrival Time</th>
            <th>Name</th>
            <th>Mob. No/Code</th>
            <th>Seen By</th>
            <th>Category</th>
            @if(in_array(Auth::user()->role,[1,3])) 
            <th>Remark</th>
            <th>Action</th>
            @endif
            
        </tr>
    </thead>
    <tbody>
        <td class="appointment-loader d-none" colspan="9">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($appointment as $row)
            @php
                // $isDone = $row->is_done == 1 && !empty($row->getAppointmentCharges) ? 'is-done' : '';
                // if(empty($row->getAppointmentCharges) && !empty($row->arrival_time)){
                //     $isDone = ' appointment-opd-tr';
                // }
                $isDone = '';
                $inConsultingroom = '';
                if(!empty($row->arrival_time) && $row->arrival_time > 0){
                    $isDone = 'is_arrival';
                    // if(empty($row->getAppointmentCharges)){
                    //     $isDone = ' is-done';
                    // }
                    if($row->is_done == 1){
                        $isDone = 'is-done';
                    }
                    if($row->in_consulting_room == 1){
                        $inConsultingroom = 'in-cosulting';
                    }
                    // if($row->is_done == 1 && !empty($row->getAppointmentCharges) ){
                    //     $isDone = 'is-done';
                    // }
                }
            @endphp
            @php
                $categoryId = $row->category_id;
            @endphp
            @if($categoryId == '6' || $categoryId == '5')
                @php
                    $categoryName = 'ANC';
                    $cName = 'ANC';
                    $type = $row->getPatientsDetails->getAnc && $row->is_new_anc == 0? 'history' : 'next-appointment';
                    if(($row->getAppointmentDateANC() == 1))
                    {
                        $type = 'history';
                    }
                @endphp
            @endif
            @if($categoryId == '4' || $categoryId == '3')
                @php
                    $categoryName = 'INF';
                    $cName = 'IUI';
                    $type = $row->getPatientsDetails->getIui || $row->checkFirstVisitExists() ? 'history' : 'create';
                @endphp
            @endif
            @if($categoryId == '1' || $categoryId == '2' || $categoryId == '11' || $categoryId == '14')
                @php
                    $categoryName = 'IVF';
                    $cName = 'IVF';
                    $type = !empty($row->getPatientsDetails->getIvf)  || $row->checkFirstVisitExists() ? 'history' : 'create';
                @endphp
            @endif
            @if($categoryId == '17' || $categoryId == '18' || strtolower($row->categoryDetails['name']) == 'gynec' || strtolower($row->categoryDetails['name']) == 'new gynec' || strtolower($row->categoryDetails['name']) == 'old gynec')
                @php
                    $categoryName = 'gynec';
                    $cName = 'gynec';
                    $type = $row->getPatientsDetails->getGynec ? 'history' : 'create';
                @endphp
            @endif
            @if($categoryId == '22' || strtolower($row->categoryDetails['name']) == 'stich')
                @php
                    $categoryName = 'Stich';
                    $cName = 'stich';
                    $type = $row->getPatientsDetails->getStich ? 'history' : 'create';
                @endphp
            @endif
            @if($categoryId != '4' && $categoryId != '3' && $categoryId != '1' && $categoryId != '2' && $categoryId != '17' && $categoryId != '18' && $categoryId != '11' && $categoryId != '14' && $categoryId != '22')
                @php
                    $categoryName = 'ANC'; 
                    $cName = 'ANC';
                    $type = $row->getPatientsDetails->getAnc && $row->is_new_anc == 0 ? 'history' : 'next-appointment';
                    if(($row->getAppointmentDateANC() == 1))
                    {
                        $type = 'history';
                    }
                @endphp
            @endif
            @php
                $name = ucwords(strtolower($row->getPatientsDetails['name']));
                $catname = $row->categoryDetails['name'];
                $paymentUrl = url('ivf/payments/'.encrypt($row->patients_id));
                $uniqId = (($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration;
            @endphp
            <tr data-id="{{encrypt($row->getPatientsDetails['id'])}}" data-type="{{$type}}" data-catname="{{$cName}}"
                class="appointment_dropdown anc-iui-ivf-edit appointmentdata
                    @if($categoryId != '4' && $categoryId != '3' && $categoryId != '1' && $categoryId != '2' && $categoryId != '17')
                        {{!empty($row->getPatientsDetails->getAnc) &&  $row->is_new_anc == 0  ? 'old-anc' : 'new-anc'}}
                    @endif
                    @if($categoryId == '4' || $categoryId == '3')
                        {{!empty($row->getPatientsDetails->getIui) || $row->checkFirstVisitExists() ? 'old-iui' : 'new-iui'}}
                    @endif
                    @if($categoryId == '1' || $categoryId == '2' || $categoryId == '11' || $categoryId == '14')
                        {{!empty($row->getPatientsDetails->getIvf) || $row->checkFirstVisitExists() ? 'old-ivf' : 'new-ivf'}}
                    @endif
                    @if($categoryId == '22')
                        {{!empty($row->getPatientsDetails->getIvf) ? 'old-stich' : 'new-stich'}}
                    @endif
                    @if($categoryId == '17' || $categoryId == '18' || strtolower($row->categoryDetails['name']) == 'gynec' || strtolower($row->categoryDetails['name']) == 'new gynec' || strtolower($row->categoryDetails['name']) == 'old gynec')
                        {{!empty($row->getPatientsDetails->getGynec) ? 'old-gynec' : 'new-gynec'}}
                    @endif
                    {{$isDone}} {{$inConsultingroom}}" data-apid="{{encrypt($row->id)}}">
                <td> {{ ((($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration) . '.' }}</td>
                <td class="line-height">{{\Carbon\Carbon::parse($row->date)->format('d-m-Y')}}<br>{{\Carbon\Carbon::parse($row->time)->format('h:i a')}}</td>
                <td>{{$row->arrival_time}}</td>
                <td class="patient_dropdown ">
                    @php
                        $isBirthDay = 0;
                        $isBirthDay =   !empty($row->getPatientsDetails['dob']) && \Carbon\Carbon::parse($row->getPatientsDetails['dob'])->format('d-m') == \Carbon\Carbon::now()->format('d-m') ? 1 : 0;
                    @endphp
                    @if($isBirthDay == 1)
                        <i class="zmdi zmdi-card-giftcard candor-color" title="Birthday"></i>
                    @endif
                    {{ucwords(strtolower($row->getPatientsDetails['name']))}}&nbsp;
                    @if(in_array($row->categoryDetails['id'],[1,2,3,4,5,6,10,13]))
                        <i class="material-icons candor-color pencil-icon appoitment_content" data-category="{{$row->categoryDetails['id']}}" data-ptid="{{encrypt($row->getPatientsDetails['id'])}}" data-date="{{\Carbon\Carbon::parse($row->date)->format('d-m-Y')}}" data-class="{{'appointment_dropdown_content_'.$uniqId}}">visibility</i>
                        <div class="{{'appointment_dropdown_content appointment_dropdown_content_'.$uniqId}}">
                        </div>
                    @endif
                    @if($row->categoryDetails['id'] == 2 && !empty($row->getPatientsDetails->getSurrogateFromLastCycle()) && isset($row->getPatientsDetails->getSurrogateFromLastCycle()['surrogate']) && !empty($row->getPatientsDetails->getSurrogateFromLastCycle()['surrogate']))
                        <br>&nbsp;<b>Surrogate : </b>
                        {{$row->getPatientsDetails->getSurrogateFromLastCycle()['surrogate']}}
                    @endif
                </td>
                <td class="line-height">{{$row->getPatientsDetails['mobile_number']}}<br>{{$row->getPatientsDetails['code']}}</td>
                <td>{{$row->getSeenBy['name']}}</td>
                <td>{{$row->categoryDetails['name']}}</td>
               <!--  <td>
                    {{$categoryName}} 
                </td> -->
                @if(in_array(Auth::user()->role,[1,3])) 
                    <td>
                        <div class="{{'edit-remark-data edit-remark-'.$row->id}}">
                            @if($row->remark)
                                {!!wordwrap($row->remark, 30,"<br>\n") !!}
                                <span class="edit-remark">
                                    <i class="material-icons edit-remark-icon pencil-icon" data-value="{{$row->remark}}" data-appointmentid="{{encrypt($row->id)}}" data-id="{{$row->id}}">edit</i>
                                </span>
                            @else
                                <span class="edit-remark">
                                    <i class="material-icons edit-remark-icon" data-value="{{$row->remark}}" data-appointmentid="{{encrypt($row->id)}}" data-id="{{$row->id}}">add</i>
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($row->categoryDetails['id'] == 1 || $row->categoryDetails['id'] == 2)
                        <a href="{{$paymentUrl}}" class="btn btn-primary btn-sm ivf-payment-font"> IVF Payment</a>
                        @endif
                        @if($patient_notification['name'] == $name)
                            <span>{{!empty($patient_notification['read_by']) ? 'Read by '.$patient_notification['read_by'] : 'Unseen'}}</span>
                        @else
                            @if($row->arrival_time)
                                <button class="btn btn-danger btn-sm notify-patient" id="{{encrypt($row->patients_id)}}"  onclick="callPatient('{{$name}}','{{$cName}}',this)">Call Patient</button>
                            @endif
                        @endif
                        @if($row->date != date('Y-m-d'))
                                <button class="btn btn-sm emergency-patient" id="{{encrypt($row->patients_id)}}">Emergency</button>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <td colspan='9' class="text-center">No records available</td>
        @endforelse
    </tbody>
</table>
{{$appointment->links()}}