@php
    $number = $total = $dataLeft = $dataRight = 0;
    $dataCount = count($data);
@endphp
@if ($dataCount > 0)
    @foreach ($data as $key => $value)
        @php
            if ($key > 0) {
                $number++;
            }
        @endphp

        @if ($number < $dataCount)
            <tr>
                <td>{{ ($j = $number + 1) . '.'}}</td>
                <td>{{ cdate($data[$number]['date'])->format('d-m-Y')}}</td>
                <td>{{ strtoupper($data[$number]['get_patients_details']['name']) }}</td>
                <td>
                    @php
                        echo ($data[$number]['get_appointment_charges']['consulting_charges'] > 0) ? 'Consulting <br />' : null;
                        $extraField1 = '';
                        $extraField2 = '';
                        if ($data[$number]['get_appointment_charges']['extra_field'] != null) {
                            $extraField = unserialize($data[$number]['get_appointment_charges']['extra_field']);
                            $extraField1 = $extraField[0];
                            echo !empty($extraField1[0]) ? $extraField1[0] . '<br />' : null;
                            $extraField2 = $extraField[1];
                            echo !empty($extraField2[0]) ? $extraField2[0] . '<br />' : null;
                        }
                        echo ($data[$number]['get_appointment_charges']['nst'] > 0) ? 'NST <br />' : null;
                        echo ($data[$number]['get_appointment_charges']['cut'] > 0) ? 'CUT <br />' : null;
                        echo ($data[$number]['get_appointment_charges']['dressing'] > 0) ? 'Dressing <br />' : null;
                        echo ($data[$number]['get_appointment_charges']['ivf'] > 0) ? 'IVF <br />' : null;
                        if ($data[$number]['get_appointment_charges']['charge_types'] == null) {
                            echo '-';
                        }
                    @endphp
                </td>
                <td>
                    <div class="amount">
                        @php
                            if ($data[$number]['get_appointment_charges']['total'] > 0) {
                                echo ($data[$number]['get_appointment_charges']['consulting_charges'] > 0) ? $data[$number]['get_appointment_charges']['consulting_charges'] . '<br />' : null;
                                echo (!empty($extraField1) && ($extraField1[1] > 0)) ? $extraField1[1] . '<br />' : null;
                                echo (!empty($extraField2) && ($extraField2[1] > 0)) ? $extraField2[1] . '<br />' : null;
                                echo ($data[$number]['get_appointment_charges']['nst'] > 0) ? $data[$number]['get_appointment_charges']['nst'] . '<br />' : null;
                                echo ($data[$number]['get_appointment_charges']['cut'] > 0) ? $data[$number]['get_appointment_charges']['cut'] . '<br />' : null;
                                echo ($data[$number]['get_appointment_charges']['dressing'] > 0) ? $data[$number]['get_appointment_charges']['dressing'] . '<br />' : null;
                                echo ($data[$number]['get_appointment_charges']['ivf'] > 0) ? $data[$number]['get_appointment_charges']['ivf'] . '<br />' : null;

                                $dataLeft += $data[$number]['get_appointment_charges']['total'] - $data[$number]['get_appointment_charges']['usg'];
                            } else {
                                echo '0';
                                $dataLeft += 0;
                            }
                        @endphp
                    </div>
                </td>
                <td class="expense-icon">{{$data[$number]['get_appointment_charges']['txt_amount']}}</td>
                <td class="expense-icon">{{$data[$number]['get_appointment_charges']['invoice_no']}}</td>
                <td class="expense-icon">{{(isset($bank_details[$data[$number]['get_appointment_charges']['bank_id']])) ? $bank_details[$data[$number]['get_appointment_charges']['bank_id']] : ''}}</td>
                <td class="expense-icon">{{$data[$number]['get_appointment_charges']['detail']}}</td>
                <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$data[$number]['get_appointment_charges']['id']}}" data-class="AppointmentCharges">edit</i></td>
                @php
                    if ($dataCount > 8) {
                        $number++;
                    }
                @endphp

                @if ($dataCount > 8 && $number < $dataCount)
                    <td>{{ ($j = $number + 1) . '.'}}</td>
                    <td>{{ cdate($data[$number]['date'])->format('d-m-Y')}}</td>
                    <td>{{ strtoupper(@$data[$number]['get_patients_details']['name']) }}</td>
                    <td>
                        @php
                            echo ($data[$number]['get_appointment_charges']['consulting_charges'] > 0) ? 'Consulting <br />' : null;
                            $extraField1 = '';
                            $extraField2 = '';
                            if($data[$number]['get_appointment_charges']['extra_field'] != null) {
                                $extraField = unserialize($data[$number]['get_appointment_charges']['extra_field']);
                                $extraField1 = $extraField[0];
                                echo !empty($extraField1[0]) ? $extraField1[0] . '<br />' : null;
                                $extraField2 = $extraField[1];
                                echo !empty($extraField2[0]) ? $extraField2[0] . '<br />' : null;
                            }
                            echo ($data[$number]['get_appointment_charges']['nst'] > 0) ? 'NST <br />' : null;
                            echo ($data[$number]['get_appointment_charges']['cut'] > 0) ? 'CUT <br />' : null;
                            echo ($data[$number]['get_appointment_charges']['dressing'] > 0) ? 'Dressing <br />' : null;
                            echo ($data[$number]['get_appointment_charges']['ivf'] > 0) ? 'IVF <br />' : null;
                            if($data[$number]['get_appointment_charges']['charge_types'] == null) {
                                echo '-';
                            }
                        @endphp
                    </td>
                    <td>
                        <div class="amount">
                            @php
                                if ($data[$number]['get_appointment_charges']['total'] > 0) {
                                    echo ($data[$number]['get_appointment_charges']['consulting_charges'] > 0) ? $data[$number]['get_appointment_charges']['consulting_charges'] . '<br />' : null;
                                    echo (!empty($extraField1) && ($extraField1[1] > 0)) ? $extraField1[1] . '<br />' : null;
                                    echo (!empty($extraField2) && ($extraField2[1] > 0)) ? $extraField2[1] . '<br />' : null;
                                    echo ($data[$number]['get_appointment_charges']['nst'] > 0) ? $data[$number]['get_appointment_charges']['nst'] . '<br />' : null;
                                    echo ($data[$number]['get_appointment_charges']['cut'] > 0) ? $data[$number]['get_appointment_charges']['cut'] . '<br />' : null;
                                    echo ($data[$number]['get_appointment_charges']['dressing'] > 0) ? $data[$number]['get_appointment_charges']['dressing'] . '<br />' : null;
                                    echo ($data[$number]['get_appointment_charges']['ivf'] > 0) ? $data[$number]['get_appointment_charges']['ivf'] . '<br />' : null;
                                    $dataRight += $data[$number]['get_appointment_charges']['total'] - $data[$number]['get_appointment_charges']['usg'];
                                } else {
                                    echo '0';
                                    $dataRight += 0;
                                }
                            @endphp
                        </div>
                    </td>
                    <td class="expense-icon">{{$data[$number]['get_appointment_charges']['txt_amount']}}</td>
                    <td class="expense-icon">{{$data[$number]['get_appointment_charges']['invoice_no']}}</td>
                    <td class="expense-icon">{{(isset($bank_details[$data[$number]['get_appointment_charges']['bank_id']])) ? $bank_details[$data[$number]['get_appointment_charges']['bank_id']] : ''}}</td>
                    <td class="expense-icon">{{$data[$number]['get_appointment_charges']['detail']}}</td>
                    <td class="expense-icon"><i class="material-icons expense-modal pencil-icon a-time" data-id="{{$data[$number]['get_appointment_charges']['id']}}" data-class="AppointmentCharges">edit</i></td>
                @endif
            </tr>
        @endif
    @endforeach
    @php

        $total = $dataLeft + $dataRight;

    @endphp
    <tr class="bt-none">
        <td class="bt-none" colspan="3"></td>
        <th class="bt-none" >Total :</th>
        <th class="grand-total-top-border"> {{$total}}</th>
    </tr>
@else
    <td colspan='12' class="text-center">No records available</td>
@endif
@php
    Session(['total' => $total]);
@endphp
