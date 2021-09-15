
            <table class="table m-b-0 table-hover">
                <thead>
                    <tr>     
                        <th></th>        
                        <th>Sr.No</th>                          
                        <th>Name</th>                          
                        <th>Dose</th>                          
                        <th>Quantity</th>
                        <th>When to take</th>
                        <th>Frequency</th>
                        <th>Route</th>
                        <th>How many Days</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse($medicine as $value)
                    @php
                                $qty = (!empty($value['quantity'])) ? $value['quantity'] : 0;
                                $qty_2 = (!empty($value['quantity_2'])) ? $value['quantity_2'] : 0;
                                $qty_3 = (!empty($value['quantity_3'])) ? $value['quantity_3'] : 0;
                                $qty_4 = (!empty($value['quantity_4'])) ? $value['quantity_4'] : 0;
                            @endphp
                        <tr>
                            <td><input type="checkbox" class="sub_chk" data-id="{{($value->id)}}"></td>
                            <td>{{((($medicine->currentPage() - 1 ) * $medicine->perPage() ) + $loop->iteration) . '.'}}</td>
                            <td>{{$value['name']}}</td>
                            <td>{{$qty+$qty_2+$qty_3+$qty_4}}</td>
                            <td>{{$qty.'-'.$qty_2.'-'.$qty_3.'-'.$qty_4}} </td>
                            <td>{{!empty($value['medicine_status']) ? $mStatus[$value['medicine_status']] : null}}</td>
                            <td>{{!empty($value['dose']) ? $dose[$value['dose']] : null}}</td>
                            <td>{{!empty($value['medicine_time']) ? $mTime[$value['medicine_time']] : null}}</td>
                            <td>{{!empty($value['number']) ? $value['number'].' Days' : ''}} </td>
                            
                                {{-- @if(!empty($value['medicine_time']))
                                    @php
                                        $medicineTime = json_decode($value['medicine_time']);
                                        $data = [];
                                        if(!empty($medicineTime)){
                                            foreach($medicineTime as $mtimeData){
                                                $data[] = $mTime[$mtimeData];
                                            }
                                        }
                                    @endphp
                                    {{implode(',',$data)}}
                                @endif
                            </td> --}}
                            <td>
                                <a href="#" class="edit-medicine" data-toggle="modal" data-target="#medicine-modal" data-mid="{{encrypt($value->id)}}">
                                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini">
                                        <i class="zmdi zmdi-edit material-icons"></i>
                                    </button>
                                </a>
                                <a href="#" class="delete-medicine" data-id="{{encrypt($value->id)}}">
                                    <button class="btn btn-icon btn-neutral candor-color btn-icon-mini">
                                        <i class="zmdi zmdi-delete material-icons"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <td colspan='9' class="text-center">No records available</td>
                    @endforelse
                </tbody>
            </table>
    {{$medicine->links()}}