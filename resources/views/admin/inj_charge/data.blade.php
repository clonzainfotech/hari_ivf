<table class="table m-b-0 table-hover" id="injection-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Name</th>
        <th>Type</th>
        <th>Net Price</th>
        <th>MRP</th>
        <th>Stock</th>
        <th>Stock Type</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @php
        $type= ["1"=>'Hormon','2'=>'IVF','3'=>'IUI'];  
        $qty_type = ["1"=>'QTY','2'=>'ML'];  
    @endphp   
    @forelse($injection as $row)
        
        <tr data-id="{{encrypt($row->id)}}" class="">
            <td>{{ ((($injection->currentPage() - 1 ) * $injection->perPage() ) + $loop->iteration) . '.' }}</td>
            <td><span class="list-name">{{ ucfirst($row->name) }}</span></td>
            <td><span class="list-name">{{ isset($type[$row->type]) ? $type[$row->type] : '-' }}</span></td>
            <td><span class="list-name">{{ $row->net_price }}</span></td>
            <td><span class="list-name">{{ $row->mrp }}</span></td>
            <td><span class="list-name">{{ $row->quantity }}</span></td>
            <td><span class="list-name">{{isset($qty_type[$row->qty_type]) ? $qty_type[$row->qty_type] : '-'}}</span></td>
            <td>
                <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini injection-edit" data-id="{{encrypt($row->id)}}">
                        <i class="zmdi zmdi-edit material-icons"></i>
                    </button>
                </a>
                {{-- <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini injection-delete" data-id="{{encrypt($row->id)}}">
                        <i class="zmdi zmdi-delete material-icons"></i>
                    </button>
                </a> --}}
                
            </td>
        </tr>
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$injection->links()}}
