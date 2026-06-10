
<table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
        <tr>    
            <th>Sr.No</th>
            <th>Date</th>
            <th>Name</th>
            <th>Reference Doctor</th>
            <th>Reference Doctor PRO</th>
            <th>Category</th>
        </tr>
    </thead>
    <tbody>
        <td class="anc-loader" colspan="7">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($patients as $row)
            <tr>
                <td>{{ ((($patients->currentPage() - 1 ) * $patients->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>{{cdate($row['created_at'])->format('d-m-Y')}}</td>
               <td>{{ ucwords(strtolower($row->getPatientsDetails['name'])) }}</td>
                <td>{{$row->getPatientsDetails->getReferenceDoctor['name']}}</td>
                <td>{{$row->getPatientsDetails->getReferenceDoctorPro['name']}}</td>
                <td>{{ucfirst($row->categoryDetails['name'])}}</td>
            </tr>
        @empty
            <td colspan='5' class="text-center ancdata">No records available</td>
        @endforelse
    </tbody>
</table>
{{$patients->links()}}



<!-- <table class="table m-b-0 table-hover" id="appointment-table">
    <thead>
        <tr>    
            <th>Sr.No</th>
            <th>Date</th>
            <th>Name</th>
            <th>Reference Doctor</th>
            <th>Reference Doctor PRO</th>
            <th>Category</th>
        </tr>
    </thead>
    <tbody>
        <td class="anc-loader" colspan="7">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
        @forelse($patients as $row)
            <tr>
                <td>{{ ((($patients->currentPage() - 1 ) * $patients->perPage() ) + $loop->iteration) . '.' }}</td>
                <td>{{cdate($row['created_at'])->format('d-m-Y')}}</td>
               <td>{{ ucwords(strtolower($row->getPatientsDetails['name'])) }}</td>
                <td>{{$row->getPatientsDetails->getReferenceDoctor['name']}}</td>
                <td>{{$row->getPatientsDetails->getReferenceDoctorPro['name']}}</td>
                <td>{{ucfirst($row->categoryDetails['name'])}}</td>
            </tr>
        @empty
            <td colspan='5' class="text-center ancdata">No records available</td>
        @endforelse
    </tbody>
</table>
{{$patients->links()}} -->


                



                
