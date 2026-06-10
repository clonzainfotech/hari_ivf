
    <table class="table m-b-0 table-hover" id="donor-table">
        <thead>
            <tr>
                <th>Appointment</th>
                <th>Code</th>
                <th>Name</th>
                <th>Arrival</th>
                <th>Mobile</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <td class="donor-loader" colspan="6">
                <div class="row">
                    <div class="page-loader-wrapper medicine-loader">
                        <div class="loader">
                            <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                        </div>
                    </div>
                </div>
            </td>
            @forelse($donor as $row)
                <tr class="donordata" data-id="{{ encrypt($row->id)}}">
                
                    <td>
                        <div>
                            {{ !empty($row->time) ? cdate($row->date)->format('d-m-Y') . ' ' .  cdate($row->time)->format('h:i') : cdate($row->date)->format('d-m-Y') }}
                        </div>
                    </td>
                    <td>{{$row->getPatientsDetails['code'] }}</td>
                    <td>
                        <div class="text-wrraping-name">
                            {{ ucwords(strtolower($row->getPatientsDetails['name'])) }}
                        </div>
                    </td>
                    <td>
                        {{ ($row->arrival_time == null) ? '-' : date('h:i', strtotime($row->arrival_time)) }}
                    </td>
                    <td>{{$row->getPatientsDetails['mobile_number']}}</td>
                    <td>
                        <a href="#" class="a-color">
                            <button class="btn btn-icon btn-neutral candor-color btn-icon-mini delete-donor-appointment" data-id="{{ encrypt($row->id) }}">
                                <i class="zmdi zmdi-delete material-icons"></i>
                            </button>
                        </a>
                    </td>
                    {{-- <td>
                        <span class="list-icon">
                            @php $images = explode(', ', $row->getDonorDetails['aadhar_image']); @endphp
                            @foreach($images as $aadharImage)
                                <img  src="{{$aadharImage }}" style="width:50px; height: 50px; border-radius: 50%;" />
                            @endforeach
                        </span>
                    </td> --}}
                    {{-- <td>
                        {{ ucwords(strtolower($row->getPatientsDetails['name'])) }}
                    </td> --}}
                    {{-- <td>
                        {{ !empty($row->arrival_time) ? cdate($row->arrival_time)->format('H:i A') : '-' }}
                    </td>
                    <td>
                        {{ !empty($row->getPatientsDetails['mobile_number']) ? $row->getPatientsDetails['mobile_number'] : '-' }}
                    </td> --}}
                    
                </tr>
            @empty
                <td colspan='6' class="text-center">No records available</td>
            @endforelse
        </tbody>
    </table>
    {{$donor->links()}}
    