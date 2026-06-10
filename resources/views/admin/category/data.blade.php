<table class="table m-b-0 table-hover" id="category-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Name</th>
        <th>Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse($category as $row)
        <tr data-id="{{encrypt($row->id)}}" class="{{in_array($row->id, [1,2,3,4,5,6,7,8,9,10]) ? 'main-category' : ''}}">
            <td>{{ ((($category->currentPage() - 1 ) * $category->perPage() ) + $loop->iteration) . '.' }}</td>
            <td><span class="list-name">{{ ucfirst($row->name) }}</span></td>
            <td>{{ cdate($row->created_at)->format('d-m-Y h:i A') }}</td>
            <td><span class="badge badge-{{$row->status == 'Active' ? 'success' : 'danger'}}">{{$row->status}}</td>
            <td>
                @if(!in_array($row->id, [1,2,3,4,5,6,7,8,9,10]))
                    <a href="#" class="a-color">
                        <button class="btn btn-icon btn-neutral candor-color btn-icon-mini delete-category" data-id="{{$row->id}}">
                            <i class="zmdi zmdi-delete material-icons"></i>
                        </button>
                    </a>
                @endif
            </td>
        </tr>
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$category->links()}}
