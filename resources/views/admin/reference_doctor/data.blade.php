@php
    $type = ['1'=>'Offline','2'=>'Online'];
@endphp
<table class="table m-b-0 table-hover" id="Reference-table">
        <thead>
            <tr>
                <th>Sr no</th>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Address</th>
                <th>Date</th>
                <th>Reference Type</th>
                <th>Lead</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <td class="refbydata-loader" colspan="9">
                <div class="row">
                    <div class="page-loader-wrapper medicine-loader">
                        <div class="loader">
                            <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                        </div>
                    </div>
                </div> 
            </td>
            @forelse($referenceDoctor as $row)
                <tr class="refbydata" data-id="{{encrypt($row->id)}}">
                    <td>{{(($referenceDoctor->currentPage() - 1 ) * $referenceDoctor->perPage() ) + $loop->iteration}}</td>
                    <td><span class="list-name">{{ucwords(strtolower($row->name))}}</span></td>
                    <td><span class="list-name">{{!empty($row->mobile_number) ? $row->mobile_number : '-'}}</span></td>
                    <td><div class="list-name text-wrap">{{!empty($row->address) ? $row->address : '-'}}</div></td>
                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A') }}</td>
                    <td><span class="list-name">{{isset($type[$row->reference_type]) ? $type[$row->reference_type] : ''}}</span></td>
                    <td><span class="list-name">{{$row->is_lead == 1 ?'Yes' : 'No'}}</span></td>
                    <td>
                        <a href="#" class="a-color">
                            <button class="btn  btn-icon  btn-neutral candor-color btn-icon-mini delete-reference-doctor" data-id="{{encrypt($row->id)}}">
                                <i class="zmdi zmdi-delete material-icons"></i>
                            </button>
                        </a>
                    </td>
                </tr>
            @empty
                <td colspan='8' class="text-center refbydata">No records available</td>
            @endforelse
        </tbody>
    </table>
    {{$referenceDoctor->links()}}

