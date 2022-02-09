<?php
$planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
?>
<table class="table m-b-0 table-hover" id="category-table">
    <thead>
    <tr>
        <th>Sr No</th>
        <th>Code</th>
        <th>Name</th>
        <th>Cycle No</th>
        <th>Plan</th>
        <th>Mobile Number</th>
        <th>Transfer Date</th>
        <th>Result Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse($ivfResultReview as $row)
        <tr data-id="{{encrypt($row->id)}}" class="">
            <td>{{ ((($ivfResultReview->currentPage() - 1 ) * $ivfResultReview->perPage() ) + $loop->iteration) . '.' }}</td>
            <td>{{ ucfirst($row->getPatients->code) }}</td>
            <td><span class="list-name">{{ ucfirst($row->getPatients->name) }}</span></td>
            <td>{{ $row->cycle_no}}</td>
            <td>{{ isset($planData[$row->plan]) ? $planData[$row->plan] : '-'}}</td>
            <td>{{ $row->getPatients->mobile_number.', '.$row->getPatients->other_mobile_number}}</td>
            <td>{{ !empty($row->getTransferDate()) ? $row->getTransferDate() : ''}}</td>
            <td>{{ !empty($row->getResult()) ? \Carbon\Carbon::parse($row->getResult()['follow_up'])->format('d-M-Y') : (!empty($row->getTransferDate()) ? \Carbon\Carbon::parse($row->getTransferDate())->addDays(14)->format('d-M-Y') : null)}}</td>
            {{-- <td>{{ !empty($row->getTransferDate()) ? \Carbon\Carbon::parse($row->getTransferDate())->addDays(14)->format('d-M-Y') : null}}</td> --}}
            <td><a href="{{URL::to('ivf-result-review/'.encrypt($row->patients_id).'/'.$row->plan.'/'.$row->cycle_no)}}" target="_blank" class="btn btn-primary mr-1 btn-sm">view</a></td>
        </tr>
    @empty
        <td colspan="5" class="text-center">No records available</td>
    @endforelse
    </tbody>
</table>
{{$ivfResultReview->links()}}
