@if($reportDatails['type']==1)
    <table class="table m-b-0 table-hover font" id="category-report-table">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Code</th>
                <th>Patient Name</th>
                <th>Mobile</th>
                <th>Category</th>
                <th>Amount</th>
            </tr>
            @if($reportDatails['doctor'])
                <tr>
                    <th colspan="6">Doctor Name : {{$reportDatails['doctor']}}</th>
                </tr>
            @endif
            @if($reportDatails['category'])
                <tr>
                    <th colspan="6">Category Name : {{$reportDatails['category']}}</th>
                </tr>
            @endif
        </thead>
        <tbody>
            <td class="categorydata-loader" colspan="9">
                <div class="row">
                    <div class="page-loader-wrapper medicine-loader">
                        <div class="loader">
                            <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                        </div>
                    </div>
                </div>
            </td>
            @php
                $subTotal = 0;
            @endphp
            @forelse($categoryReport as $row)
                <tr class="categorydata">
                    <td>{{ ((($categoryReport->currentPage() - 1 ) * $categoryReport->perPage() ) + $loop->iteration) . '.' }}</td>
                    <td>{{ $row->getAppointment->getPatientsDetails['code']}}</td>
                    <td>{{strtoupper($row->getAppointment->getPatientsDetails['name'])}}</td>
                    <td>{{$row->getAppointment->getPatientsDetails['mobile_number']}}</td>
                    <td>{{ucfirst($row->getAppointment->categoryDetails['name'])}}</td>
                    <td>
                        <div class="amount">
                            {{$row->netamount}}
                            @php
                                $subTotal += $row->netamount;
                            @endphp
                        </div>
                    </td>
                </tr>
            @empty
                <td colspan="6" class="text-center categorydata">No records available</td>
            @endforelse
            @if ($reportDatails['total'] > 0)
                <tr class="categorydata">
                    <th class="bt-none" colspan="4"></th>
                    <th class="bt-none" colspan="1">Sub Total :</th>
                    <th class="grand-total-top-border">{{$subTotal}}</th>
                </tr>
                <tr class="categorydata">
                    <th class="bt-none" colspan="4"></th>
                    <th class="bt-none" colspan="1">Grand Total :</th>
                    <th class="grand-total-top-border">{{$reportDatails['total']}}</th>
                </tr>
            @endif
        </tbody>
    </table>
    {{$categoryReport->links()}}
@else
    <table class="table m-b-0 table-hover" id="category-report-table">
        <thead>
            <tr>
                <th>Category</th>
                <th>Total Patient</th>
            </tr>
            <tr>
                @if(!empty($reportDatails['doctor']))
                <th colspan="2">Doctor Name : {{$reportDatails['doctor']}}</th>
                @else
                <th colspan="2">OverAll Analysis</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if($reportDatails['category'])
                <tr>
                    <td>{{$reportDatails['category']}}</td>
                    <td>{{$reportDatails['count']}}</td>
                </tr>
            @else
                @if(!empty($reportDatails['allCategoryCount']))
                    <?php $total = 0;?>
                    @foreach($reportDatails['allCategoryCount'] as $row)
                        <?php
                            $total = $total+$row->totalAppointment;
                        ?>
                        <tr>
                            <td>{{$row->category_name}}</td>
                            <td>{{$row->totalAppointment}}</td>
                        </tr>
                    @endforeach
                    @if ($total > 0)
                        <tr class="categorydata">
                            <th class="bt-none">GrandTotal</th>
                            <th class="grand-total-top-border">{{$total}}</th>
                        </tr>
                    @endif
                @endif
            @endif
        </tbody>
    </table>
@endif
