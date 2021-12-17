<table class="table m-b-0 table-hover" id="report-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Date</th>
        <th>Name</th>
        <th>Category</th>
        <th>Advice Report</th>
    </tr>
    </thead>
    <tbody>
    @forelse($appointment as $row)
        <tr data-id="{{encrypt($row->id)}}">
            <td>{{ ((($appointment->currentPage() - 1 ) * $appointment->perPage() ) + $loop->iteration) . '.' }}</td>
            <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y')}}</td>
            <td><span class="list-name">{{ucwords(strtolower($row->getPatientsDetails['name']))}}</span></td>
            <td>{{$row->categoryDetails['name']}}</td>
            <td class="line-height">
                {{$row->advice_report}}
                    @if(isset($row->advice_report_male) && !empty($row->advice_report_male))
                        <br>Husbund Report : 
                        {{$row->advice_report_male}}
                    @endif
            </td>
            {{-- <td>
                <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini delete-bank" data-id="{{$row->id}}">
                        <i class="zmdi zmdi-delete material-icons"></i>
                    </button>
                </a>
                <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini editBank" data-id="{{encrypt($row->id)}}">
                        <i class="zmdi zmdi-edit material-icons"></i>
                    </button>
                </a>
            </td> --}}
        </tr>
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$appointment->links()}}
