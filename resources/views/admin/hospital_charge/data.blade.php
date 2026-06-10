<table class="table m-b-0 table-hover" id="charge-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Title</th>
        <th>Charge</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse($charge as $row)
        <tr data-id="{{encrypt($row->id)}}" class="">
            <td>{{ ((($charge->currentPage() - 1 ) * $charge->perPage() ) + $loop->iteration) . '.' }}</td>
            <td><span class="list-name">{{ ucfirst($row->title) }}</span></td>
            <td><span class="list-name">{{ $row->charge }}</span></td>
            <td>{{ cdate($row->created_at)->format('d-m-Y h:i A') }}</td>
            <td>
                <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini delete-charge" data-id="{{$row->id}}">
                        <i class="zmdi zmdi-delete material-icons"></i>
                    </button>
                </a>
                <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini editCharge" data-id="{{encrypt($row->id)}}">
                        <i class="zmdi zmdi-edit material-icons"></i>
                    </button>
                </a>
            </td>
        </tr>
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$charge->links()}}
