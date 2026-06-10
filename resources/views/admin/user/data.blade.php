<table class="table m-b-0 table-hover" id="user-table">
    <thead>
        <tr>
            <th>Sr no</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($user as $row)
            @php
                $file = cdnUrl($row->profile_picture, 'public/images/default_user.png');
            @endphp
            <tr data-id="{{encrypt($row->id)}}">
                <td>{{(($user->currentPage() - 1 ) * $user->perPage() ) + $loop->iteration}}</td>
                <td><span class="list-icon"> <img  src="{{$file}}" style="width:50px; height: 50px; border-radius: 50%;" /></span>&nbsp&nbsp&nbsp<span class="list-name">{{ ucwords(strtolower($row->name)) }}</span></td>
                <td>{{$row->email}}</td>
                <td>{{$row->role}}</td>
                <td>{{$row->mobile_number ? $row->mobile_number : '-'}}</td>
                <td><span class="badge badge-{{$row->status == 'Active' ? 'success' : 'danger'}}">{{$row->status}}</td>
                <td>
                    <a href="#" class="a-color">
                        <button class="btn  btn-icon candor-color btn-neutral btn-icon-mini delete-user" data-id="{{$row->id}}">
                            <i class="zmdi zmdi-delete material-icons"></i>
                        </button>
                    </a>
                </td>
            </tr>
        @empty
            <td colspan='9' class="text-center">no records available </td>
        @endforelse
    </tbody>
</table>
{{$user->links()}}
