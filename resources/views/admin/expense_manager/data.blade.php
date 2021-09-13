@php
    $total = 0;
@endphp
<table class="table m-b-0 table-hover" id="expense-table">
    <thead>
        <tr>
            <th>Expense Date</th>
            <th>Expense Category</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Given For</th>
            <th>Notes</th>
            <th>Added by</th>
            <th>Added on</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($expense as $row)
        @php
            $total = $total + $row->amount;
        @endphp
            <tr data-id="{{encrypt($row->id)}}">
                <td>{{\Carbon\Carbon::parse($row->date)->format('d-m-Y')}}</td>
                <td>{{ucfirst($row->getExpenseCategory['name'])}}</td>
                <td>{{$row->amount}}</td>
                <td>{{$row->payment_method}}</td>
                <td>{{$row->given_for}}</td>
                <td>{{$row->note}}</td>
                <td>{{ ucwords(strtolower($row->getUser['name'])) }}</td>
                <td>{{$row->created_at}}</td>
                <td>
                    <a href="#" class="a-color">
                        <button class="btn  btn-icon  btn-neutral candor-color btn-icon-mini delete-expense" data-id="{{$row->id}}">
                            <i class="zmdi zmdi-delete material-icons"></i>
                        </button>
                    </a>
                </td>
            </tr>
        @empty
            <td colspan='8' class="text-center">No records available</td>
        @endforelse
        <tr>
            <td colspan=""></td>
            <td class="font-bold">Total : </td>
            <td class="font-bold">{{$total}}</td>
            <td colspan="6"></td>
        </tr>
    </tbody>
</table>
<ul class="pagination pagination-primary m-b-0 deletebutton">{{$expense->links()}}
                    </ul>
