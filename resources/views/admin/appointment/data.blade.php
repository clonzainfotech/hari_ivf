<table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
        <tr>
            <th>Sr.No</th>
            <th>Arrival Time</th>
            <th>Appointment Date & Time</th>
            <th>Name</th>
            <th>Mobile</th>
            <th>Category</th>
            <th>Seen By</th>
            <th>Action</th>
            <th>Report</th>
            <th>OPD</th>
            <th>Next Appoint.</th>
            <th>Remark</th>
            {{-- <th>Action</th> --}}
            {{-- <th>Next Appointment</th> --}}
        </tr>
    </thead>
    <tbody>
        <td class="appointment-loader" colspan="10">
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
                $isDone = '';
                if($row->arrival_time){
                    $isDone = 'is_arrival';
                    if(empty($row->getAppointmentCharges)){
                        $isDone = ' appointment-opd-tr';
                    }
                    if($row->is_done == 1 && !empty($row->getAppointmentCharges)){
                        $isDone = 'is-done';
                    }
                }
                $uniqId = (($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration;
                // && !empty($row->arrival_time)
            @endphp
            <tr data-id="{{encrypt($row->id)}}" data-next="{{$row->next_appointment}}" class="{{'appointmentdata '.$isDone}}">
                <td>
                    {{-- <div class="inline @if(empty($row->time) || \Carbon\Carbon::parse($row->getPatientsDetails['created_at'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d')) new-opd-patient @endif"> --}}
                        {{ ((($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration) . '.' }}
                    {{-- </div> --}}
                </td>
                <td>
                    @php
                        $arrivalTime = $row->arrival_time ? date('h:i A', strtotime($row->arrival_time)) : null;
                        $appointmentTime = $row->time ? date('h:i A', strtotime($row->time)) : null;
                    @endphp
                    @if($row->arrival_time == null)
                        <span class="appointment-time">
                            <a class="edit-appointment-time" data-toggle="modal" data-target="#edit-appointment-time"><i class="material-icons edit-appointment-time a-time" data-appointmentid="{{encrypt($row->id)}}" data-date="{{\Carbon\Carbon::parse($row->date)->format('D d M Y')}}" data-time="{{$row->time ? array_search($appointmentTime,$hospitalTime) : null}}">add</i></a>
                        </span>
                    @else
                        {{date('h:i', strtotime($row->arrival_time))}}
                        <span class="appointment-time">
                            <a class="edit-appointment-time" data-toggle="modal" data-target="#edit-appointment-time"><i class="material-icons edit-appointment-time pencil-icon a-time" data-appointmentid="{{encrypt($row->id)}}" data-date="{{\Carbon\Carbon::parse($row->date)->format('D d M Y')}}" data-time="{{$row->time ? array_search($appointmentTime,$hospitalTime) : null}}" data-arrival="{{$row->arrival_time ? array_search($arrivalTime,$hospitalTime) : null}}">edit</i></a>
                        </span>
                    @endif
                </td>
                <td>
                    {!! !empty($row->time) ? \Carbon\Carbon::parse($row->date)->format('d-m-Y') . ' ' .  \Carbon\Carbon::parse($row->time)->format('h:i') : \Carbon\Carbon::parse($row->date)->format('d-m-Y') !!}
                    @if($row->time)
                        <span class="appointment-time">
                            <a class="edit-appointment-time" data-toggle="modal" data-target="#edit-appointment-time"><i class="material-icons edit-appointment-time pencil-icon a-time" data-appointmentid="{{encrypt($row->id)}}" data-date="{{\Carbon\Carbon::parse($row->date)->format('D d M Y')}}" data-time="{{array_search($appointmentTime,$hospitalTime)}}" data-arrival="{{$row->arrival_time ? array_search($arrivalTime,$hospitalTime) : null}}">edit</i></a>
                        </span>
                    @else
                        <span class="appointment-time">
                            <a class="edit-appointment-time" data-toggle="modal" data-target="#edit-appointment-time"><i class="material-icons edit-appointment-time a-time" data-appointmentid="{{encrypt($row->id)}}" data-date="{{\Carbon\Carbon::parse($row->date)->format('D d M Y')}}" data-arrival="{{$row->arrival_time ? array_search($arrivalTime,$hospitalTime) : null}}">add</i></a>
                        </span>
                    @endif
                </td>
                @php
                    $className = "font-weight-bold";
                    if($row->category_id && $row->getPatientsDetails['age'] && $row->getPatientsDetails['residence'] && $row->getPatientsDetails['city'] && $row->getPatientsDetails['state'] && $row->getPatientsDetails['main_area'] && $row->getPatientsDetails['reference_doctor_id']){
                    // if($row->category_id){
                        $className = '';
                    }
                @endphp
                <td class="{{"patient_dropdown ".$className}}">&nbsp;
                    {{ ucwords(strtolower($row->getPatientsDetails['name'])).' '.($row->getChildNumber() ? '('.$row->getChildNumber().')' : '')}}
                    {{-- <td class="patient_dropdown ">{{ucwords(strtolower($row->getPatientsDetails['name']))}}&nbsp; --}}
                        @if(in_array($row->categoryDetails['id'],[1,2,3,4,5,6,10,13]))
                            <i class="material-icons candor-color pencil-icon appoitment_content" data-category="{{$row->categoryDetails['id']}}" data-ptid="{{encrypt($row->getPatientsDetails['id'])}}" data-date="{{\Carbon\Carbon::parse($row->date)->format('d-m-Y')}}" data-class="{{'appointment_dropdown_content_'.$uniqId}}">visibility</i>
                            <div class="{{'appointment_dropdown_content appointment_dropdown_content_'.$uniqId}}">
                            </div>
                        @endif
                    {{-- </td> --}}
                </td>
                <td>{{$row->getPatientsDetails['mobile_number']}}</td>
                <td>{{ucfirst($row->categoryDetails['name'])}}</td>
                <td>{{$row->getSeenBy['name']}}</td>

                    @php
                    $paymentUrl = url('ivf/payments/'.encrypt($row->patients_id));
                    @endphp

                <td>
                    <div class="header custom-dropdown">
                        <ul class="header-dropdown">
                            <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    @if(ucfirst($row->categoryDetails['id'] == 1) || ucfirst($row->categoryDetails['id'] == 2))
                                    <li>
                                        <a href="{{$paymentUrl}}"   class="opd-patients"> IVF Payment</a>
                                    </li> |
                                    @endif
                                    <li>
                                        <a class="opd-patients" data-toggle="modal" data-target="#defaultModal"  id="{{encrypt($row->id)}}">OPD</a>
                                    </li> |
                                    <li>
                                        <a class="sticker-appointment" id="{{encrypt($row->id)}}">Sticker</a>
                                    </li> |
                                    <li>
                                        <a class="print-appointment" id="{{encrypt($row->id)}}">Print</a>
                                    </li>
                                    {!! !empty($row->getAppointmentCharges) ? '| <li><a class="print-opd-charge" id="'.encrypt($row->id).'">Print OPD</a></li>' : null !!}
                                    {!! (!$row->nextAppointmentDate() && $row->next_appointment == true && $row->date >= \Carbon\Carbon::now()->format('Y-m-d')) ? '| <li><a class="next-appointment" data-toggle="modal" data-target="#next-appointment-modal" id="'.encrypt($row->id).'">Next Appointment</a></li>' : null !!}
                                    @if(strtotime(\Carbon\Carbon::now()->format('Y-m-d')) <= strtotime($row->date))
                                        |
                                        <li>
                                            <a class="add-arrival" data-id="{{encrypt($row->id)}}">
                                                Arrival
                                            </a>
                                        </li>
                                    @endif
                                    @if($row->date > \Carbon\Carbon::now()->format('Y-m-d') || ($row->is_done == 0 && $row->date == \Carbon\Carbon::now()->format('Y-m-d')))
                                        |
                                        <li>
                                        <a href="#" class="delete-appointment" data-id="{{$row->id}}">
                                            <button class="btn  btn-icon candor-color btn-neutral btn-icon-mini">
                                                <i class="zmdi zmdi-delete material-icons"></i>
                                            </button>
                                        </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                </td>
                <td>-</td>
                <td>{{$row->getAppointmentCharges['netamount']}}</td>
                <td>{{($row->nextAppointmentDate() && $row->next_appointment == false && ($row->nextAppointmentDate() != \Carbon\Carbon::parse($row->date)->format('d-m-Y'))) ? (($row->nextAppointmentUsg()) ? 'USG - '.$row->nextAppointmentDate() : $row->nextAppointmentDate()) : $row->nextAppointmentDate() }}</td>
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
                {{-- <td>{{$row->nextAppointmentDate()}}</td> --}}
            </tr>
        @empty
            <td colspan='12' class="text-center">No records available</td>
        @endforelse
    </tbody>
</table>
{{$appointment->links()}}
