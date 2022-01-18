
<div class="row m-0 clearfix dashboard">
    <div class="col-sm-2">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">TOTAL</p>
                        <h4 class="number mt-0 mb-0">{{ $data ['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card">
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
        <div class="card">
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
        <div class="card">
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
        <div class="card">
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
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">CC</p>
                        <h4 class="number mt-0 mb-0">{{ $data['cc'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-12">
                        <p class="text-muted">LTZ</p>
                        <h4 class="number mt-0 mb-0">{{ $data['ltz'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-2">
        <div class="card">
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
        <div class="card">
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
</div>
<div class="row">
    <div class="col-md-4">
        <input type="text" name="" class="form-control mb-3" value="" id="myInput" placeholder="Search by name and mobile">
    </div>
</div>
<table class="table" id="myTable">
    <thead>
        <tr>
            <th>
                No
            </th>
            <th>
                Name
            </th>
            <th>
                Mobile
            </th>
        </tr>
    </thead>
    <tbody>
    @foreach($data['patients'] as $index => $patient)
        <tr>
            <td>{{$index+1}}</td>
            <td>{{$patient->name}}</td>
            <td> {{$patient->mobile_number}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<script>
    $(document).ready(function(){
        $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
