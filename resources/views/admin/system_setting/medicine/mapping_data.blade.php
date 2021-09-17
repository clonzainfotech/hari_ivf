@forelse($complaintMedicines as $key=>$row)
    <div class="{{'card ho-co-medicines-data ho-co-medicines-data-'.$complaintMedicines[$key][0]['type_id']}}">
        <div class="body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-5">
                        <h5>{{$hoco[$key]}}</h5>
                    </div>
                    <div class="col-md-7"></div>
                    <div class="col-md-3">
                    </div>
                </div>    
                <div class="row">
                </div>
                <br>
                <table class="table m-b-0 table-hover">
                    <thead>
                        <tr>             
                            <th>Sr.No</th>                          
                            <th>Name</th>                          
                            <th>Medicine Status</th>
                            <th>Dose</th>
                            <th>Number</th>
                            <th>Quantity</th>
                            <th>Medicine Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                            $medicine_time = ['1'=>'IV','2'=>'IM','3'=>'SC',"4"=>'Oral',"5"=>'P/V',"6"=>"P/A"];
                        @endphp
                        @forelse($row as $value)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$value->getMedicinesData['name']}}</td>
                                <td>{{!empty($value->getMedicinesData['medicine_status']) ? $mStatus[$value->getMedicinesData['medicine_status']] : null}}</td>
                                <td>{{!empty($value->getMedicinesData['dose']) ? $dose[$value->getMedicinesData['dose']] : null}}</td>
                                <td>{{$value->getMedicinesData['number']}}</td>
                                <td>@php
                                    $mData = [0,0,0,0];

                                        if(@$value->getMedicinesData['quantity']>0) {
                                            $mData[0] = $value->getMedicinesData['quantity'];
                                        }
                                        if(@$value->getMedicinesData['quantity_2']>0) {
                                            $mData[1] = $value->getMedicinesData['quantity_2'];
                                        }
                                        if(@$value->getMedicinesData['quantity_3']>0) {
                                            $mData[2] = $value->getMedicinesData['quantity_3'];
                                        }
                                        if(@$value->getMedicinesData['quantity_4']>0) {
                                            $mData[3] = $value->getMedicinesData['quantity_4'];
                                        }
                                        $mData = implode('-',$mData);
                                    @endphp
                                    {{$mData}}
                                </td>
                                <td>
                                    {{-- @if(!empty($value->getMedicinesData['medicine_time'])) --}}
                                        {{-- @php
                                            $medicineTime = json_decode($value->getMedicinesData['medicine_time']);
                                            $data = [];
                                            foreach($medicineTime as $mtimeData){
                                                $data[] = $mTime[$mtimeData];
                                            }
                                        @endphp --}}
                                        {{isset($medicine_time[$value->getMedicinesData['medicine_time']]) ? $medicine_time[$value->getMedicinesData['medicine_time']] : '-'}}
                                    {{-- @endif --}}
                                </td>
                                <td>
                                    <a href="#" class="delete-medicine" data-id="{{encrypt($value->id)}}">
                                        <button class="btn btn-icon btn-neutral candor-color btn-icon-mini">
                                            <i class="zmdi zmdi-delete material-icons"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <td colspan='7' class="text-center">No records available</td>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@empty
    <div class="card category-data">
        <div class="body">
            <div class="col-md-12">
                <div class="row">
                    No records available
                </div>
            </div>
        </div>
    </div>
@endforelse