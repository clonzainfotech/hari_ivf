<table class="table table-striped m-b-0" id="bookpatient-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Code</th>
        <th>Patient Name</th>
        <th>Procedure/Surgery</th>
        <th>Room Type</th>
        <th>DOA</th>
        <th>DOD</th>
        <th>Amount</th>
        <th>Bill</th>
        <th>Remark</th>
        <th>Deposit</th>
        <th>Discharge Card</th>
        <th>UnderCare</th>
    </tr>
    </thead>
    <tbody>
        <td class="indoorpatient-loader" colspan="12">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($indoorData as $patients)
            <tr class="indoorpatient" data-id="{{encrypt($patients->id)}}">
                <td>
                    <div class="inline">{{ ((($indoorData->currentPage() - 1 ) * $indoorData->perPage() ) + $loop->iteration) . '.' }}</div>
                </td>
                <td>{{ $patients->getPatientsDetails['code'] }} </td>
                <td>{{ ucwords(strtolower($patients->getPatientsDetails['name'])) }} </td>
                <td>{{ $patients->procedurename }}</td>
                <td>{{ $patients->getRoomType['name'] }}</td>
                <td><a href="#" class="sticker-link text-dark" data-toggle="modal" data-target="#sticker-modal" data-date="{{$patients->doa_date}}" data-appointmentid="{{isset($patients->getPatientsDetails->getAppointment['id']) ? encrypt($patients->getPatientsDetails->getAppointment['id']) : ''}}">{{(!empty($patients->doa_date)) ? \Carbon\Carbon::parse($patients->doa_date)->format('d-m-Y') : '-' }}</a></td>
                <td>{{ (!empty($patients->dod_date)) ? \Carbon\Carbon::parse($patients->dod_date)->format('d-m-Y') : '-' }}</td>
                <td>{{ (!empty($patients->getInvoice['grand_total_amt']) || !empty($patients->getInvoice['deposit_amt'])) ?  $patients->getInvoice['grand_total_amt'] + $patients->getInvoice['deposit_amt'] : 0}}</td>
                <td>
                    @php
                        if($patients->is_final_invoice == 1) {
                            echo '<a href="'. url('indoor/'.encrypt($patients->id).'/invoiceedit') .'"><span class="badge is-bill badge-success">F.bill</span></a>';
                        }
                        elseif ($patients->is_invoice == 1) {
                            echo '<a href="'. url('indoor/'.encrypt($patients->id).'/invoiceedit') .'"><span class="badge is-bill badge-danger">E.bill</span></a>';
                        }
                        else {
                            echo '<a href="'. url('indoor/invoicecreate/'.encrypt($patients->id)) .'"><span class="badge is-bill new-bill">E.bill</span></a>';
                        }
                    @endphp
                </td>
                <td>{{ str_limit($patients->remark, $limit = 20, $end = '...') }}</td>
                <td><a class="deposit-link" data-toggle="modal" data-target="#depositModal" data-backdrop="static" data-keyboard="false" id="{{$patients->patient_id}}" data-id="{{ encrypt($patients->id) }}"><span class="badge is-bill deposit-border">Deposit</span></a></td>
                <td>
                    @php
                        if($patients->is_discharge_card == '1'){
                            echo '<a href="'. url('indoor/'.encrypt($patients->id).'/discardedit') .'"><span class="badge badge-info">Discharge Card</span></a>';
                        }
                        else {
                            echo '<a href="'. url('indoor/discardcreate/'.encrypt($patients->id)) .'"><span class="badge new-bill">Discharge Card</span></a>';
                        }
                    @endphp
                </td>
                <td>{{(!empty($patients->getPatientsDetails['hospital_doctor_id']) && $hospitalDoctor[$patients->getPatientsDetails['hospital_doctor_id']]) ? $hospitalDoctor[$patients->getPatientsDetails['hospital_doctor_id']] : ''}}</td>
            </tr>
        @empty
            <td colspan="13" class="text-center indoorpatient">No records available</td>
        @endforelse
    </tbody>
</table>
{{ $indoorData->links() }}
