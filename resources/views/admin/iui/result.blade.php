@extends('layouts.main')
@section('parentPageTitle', 'IUI')
@section('title', 'Result IUI')
@section('content')
    <div class="row clearfix iui-result-data">
        @forelse($iuiHistoryData as $key=>$row)
            @php
                $class = $key == \Carbon\Carbon::now()->format('Y-m-d') ? 'red-border' : null;
            @endphp
            <div class="col-sm-12 col-md-6 col-lg-2">
                <div class="{{'result-card p-3 '.$class}}">
                    <div class="row">
                        <div class="text-center follow-date">
                            {{cdate($key)->format('d M Y')}}
                        </div>
                        @for($i=0; $i<count($row); $i++)
                            <div class="patients-name text-center">
                                {{ucwords(strtolower($row[$i]['get_patients_info']['name'] ?? ''))}}
                            </div>
                        @endfor
                        <br>
                        <div class="total-iui-patients text-right w-100 mr-2">{{'Total :' .count($row)}}</div >
                    </div>
                </div>
            </div>
        @empty
            <div class="col-sm-12 col-md-6 col-lg-12 text-center">No records available</div>
        @endforelse
    </div>
@endsection
