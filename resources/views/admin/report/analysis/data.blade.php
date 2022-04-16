
<div class="row m-0 clearfix dashboard">
    <div class="col-sm-2">
        <div class="card iui-box" data-key='new-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">NEW INF</p>
                        <h4 class="number mt-0 mb-0">{{ $data['newinf']  }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card iui-box" data-key='old-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">OLD INF</p>
                        <h4 class="number mt-0 mb-0">{{ $data['oldinf'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card iui-box" data-key='plan-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted plan-type">Selected Plan Wise</p>
                        <h4 class="number mt-0 mb-0">{{$data['plan_type']}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card iui-box" data-key='continue-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Continue</p>
                        <h4 class="number mt-0 mb-0">{{ $data['continue'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card iui-box" data-key='drop-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Drop</p>
                        <h4 class="number mt-0 mb-0">{{ $data['drop'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card iui-box" data-key='skip-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Skip</p>
                        <h4 class="number mt-0 mb-0">{{ $data['skip'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- <div class="col-sm-2">
        <div class="card iui-box" data-key='ltz-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">LTZ</p>
                        <h4 class="number mt-0 mb-0">{{ $data['ltz'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="col-sm-2">
        <div class="card iui-box" data-key='consive-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Consive</p>
                        <h4 class="number mt-0 mb-0">{{ $data['consive'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card iui-box" data-key='fail-inf'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Fail</p>
                        <h4 class="number mt-0 mb-0">{{ $data['fail'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card iui-box" data-key='pending-result'>
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">Pending Result</p>
                        <h4 class="number mt-0 mb-0">{{ $data['pending_result'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<table class="table" id="myTable">
    <thead>
        <tr>
            <th>No</th>
            <th>Code</th>
            <th>Name</th>
            <th>Age</th>
            <th>Mobile</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <td class="reportdata-loader" colspan="6">
            <div class="row">
                <div class="page-loader-wrapper medicine-loader report-loader">
                    <div class="loader">
                        <div class="m-t-30"><img src="{{url(config('app.loader'))}}" width="48" height="48" alt="Oreo"></div>
                    </div>
                </div>
            </div>
        </td>
    @forelse($data['patients'] as $index => $patient)
        <tr>
            <td>{{$index+1}}</td>
            <td>{{$patient->code}}</td>
            <td>{{ucWords($patient->name)}}</td>
            <td>{{$patient->age.' Year'}}</td>
            <td>{{$patient->mobile_number}}</td>
            <td><a href="{{URL::to('patient-history/'.encrypt($patient->id))}}" target="_blank" class="btn btn-primary">View History</a></td>
        </tr>
     @empty
        <td colspan='6' class="text-center reportdata">No records available</td>
        @endforelse
    </tbody>
</table>
{{-- <script>
    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script> --}}
