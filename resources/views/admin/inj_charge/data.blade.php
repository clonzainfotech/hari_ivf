<table class="table m-b-0 table-hover" id="injection-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Name</th>
        <th>Net Price</th>
        <th>MRP</th>
        <th>Stock</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        
    @forelse($injection as $row)
        
        <tr data-id="{{encrypt($row->id)}}" class="">
            <td>{{ ((($injection->currentPage() - 1 ) * $injection->perPage() ) + $loop->iteration) . '.' }}</td>
            <td><span class="list-name">{{ ucfirst($row->name) }}</span></td>
            <td><span class="list-name">{{ ucfirst($row->net_price) }}</span></td>
            <td><span class="list-name">{{ ucfirst($row->mrp) }}</span></td>
            <td><span class="list-name">{{ ucfirst($row->stock) }}</span></td>
            <td>
                <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini injection-edit" data-id="{{encrypt($row->id)}}">
                        <i class="zmdi zmdi-edit material-icons"></i>
                    </button>
                </a>
                <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini injection-delete" data-id="{{encrypt($row->id)}}">
                        <i class="zmdi zmdi-delete material-icons"></i>
                    </button>
                </a>
                
            </td>
        </tr>
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$injection->links()}}
