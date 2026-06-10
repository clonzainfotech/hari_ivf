<table class="table table-striped m-b-0" id="bookpatient-table">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Code</th>
            <th>Patient Name</th>
            <th>Room Type</th>
            <th>Room No</th>
            <th>Bed No</th>
            <th>Surgery</th>
            <th>DOA</th>
            <th>DOD</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    @forelse($indoorData as $patients)
        <tr data-id="{{encrypt($patients->id)}}" >
            @php
                $discharge = $patients->is_discharge_card;
                if($discharge == 1) {
                    $dischargeCard = "discharge";
                }
                else {
                    $dischargeCard = "";
                }
            @endphp
            <td class="patient-srno {{ $dischargeCard }}">
                @php
                    $invoice = $patients->is_invoice;
                    if($invoice == 0) {
                        $isinvoice = 'invoice';
                    }
                    else {
                        $isinvoice = '';
                    }
                @endphp
                <div class="inline">{{ ((($indoorData->currentPage() - 1 ) * $indoorData->perPage() ) + $loop->iteration) . '.' }}</div>
            </td>
            <td> {{ $patients->getPatientsDetails['code'] }} </td>
            <td> {{ ucwords(strtolower($patients->getPatientsDetails['name'])) }} </td>
            <td>{{ $patients->getRoomType['name'] }}</td>
            <td>{{ $patients->room_id }}</td>
            <td>{{ $patients->getRoom['room_no'] }}</td>
            <td></td>
            <td>{{ (!empty($patients->doa_date)) ? cdate($patients->doa_date)->format('d-m-Y') : '-' }}</td>
            <td>{{ (!empty($patients->dod_date)) ? cdate($patients->dod_date)->format('d-m-Y') : '-' }}</td>
            <td>
                <div class="header custom-dropdown">
                    <ul class="header-dropdown">
                        <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    @if($discharge == 0)
                                        <a href="{{URL::to('indoor/discardcreate/'.encrypt($patients->id))}}" class="dischargecard" >Discharge Card</a>
                                    @else
                                        <a href="{{URL::to('indoor/'.encrypt($patients->id) .'/discardedit/')}}" class="dischargecard" >Discharge Card</a>
                                    @endif
                                </li> |
                                <li>
                                    @if($invoice == 0)
                                        <a href="{{URL::to('indoor/invoicecreate/'.encrypt($patients->id))}}" class="invoice-link">Invoice</a>
                                    @else
                                        <a href="{{URL::to('indoor/'.encrypt($patients->id) .'/invoiceedit/')}}" class="invoice-link" >Invoice</a>
                                    @endif
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
    @empty
        <td colspan="10" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{ $indoorData->links() }}
