<table class="table m-b-0 table-hover" id="plan-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Plan</th>
        <th>Catgeory</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        @php
            $category = ['1'=>'IUI','2'=>'IVF'];
        @endphp
    @forelse($planList as $row)
        
        <tr data-id="{{encrypt($row->id)}}" class="">
            <td>{{ ((($planList->currentPage() - 1 ) * $planList->perPage() ) + $loop->iteration) . '.' }}</td>
            <td><span class="list-name">{{ ucfirst($row->type) }}</span></td>
            <td><span class="list-name">{{isset($category[$row->category]) ? $category[$row->category] : ''}}</span></td>
            <td>
                <a href="#" class="a-color">
                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini plan-edit" data-id="{{encrypt($row->id)}}">
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
{{$planList->links()}}
