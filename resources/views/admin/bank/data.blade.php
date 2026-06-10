<table class="table m-b-0 table-hover" id="bank-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Name</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse($bank_detail as $row)
        <tr data-id="{{encrypt($row->id)}}">
            <td>{{ ((($bank_detail->currentPage() - 1 ) * $bank_detail->perPage() ) + $loop->iteration) . '.' }}</td>
            <td><span class="list-name">{{ ucfirst($row->name) }}</span></td>
            <td>{{ cdate($row->created_at)->format('d-m-Y h:i A') }}</td>
            <td>
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
            </td>
        </tr>
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$bank_detail->links()}}
