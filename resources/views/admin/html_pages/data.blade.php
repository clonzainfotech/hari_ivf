<table class="table m-b-0 table-hover" id="html-page-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Title</th>
        <th>Slug</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse($html_page as $row)
        <tr data-id="{{encrypt($row->id)}}" class="">
            <td>{{ ((($html_page->currentPage() - 1 ) * $html_page->perPage() ) + $loop->iteration) . '.' }}</td>
            <td><span class="list-name">{{ ucfirst($row->title) }}</span></td>
            <td><span class="list-name">{{ $row->slug }}</span></td>
            <td>
                <a href="#" class="a-color">
                    <button class="btn btn-neutral candor-color btn-primary delete-html" data-id="{{encrypt($row->id)}}">Delete
                    </button>
                </a>
                <a href="#" class="a-color">
                    <button class="btn btn-primary btn-neutral candor-color view-html" data-id="{{$row->slug}}">View
                    </button>
                </a>
            </td>
        </tr>
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$html_page->links()}}
