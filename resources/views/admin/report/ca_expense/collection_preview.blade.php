@php
    $j = $total = $totalLeft = $totalRight = 0;
    $count = count($data);
@endphp
@if ($count > 0)

    @for ($i = 0; $i < $count; $i++)
        <tr>
            <td class="data-font seperator">{{ ($j = $i + 1) . '.'}}</td>
            <td class="data-font seperator">{{ cdate($data[$i]['date'])->format('d-m-Y')}}</td>
            <td class="data-font seperator">{{ strtoupper($data[$i]['get_patients_details']['name']) }}</td>
            <td class="data-font seperator">
                @php
                    echo ($data[$i]['get_appointment_charges']['consulting_charges'] > 0) ? 'Consulting <br />' : null;
                    $extraField1 = '';
                    $extraField2 = '';
                    if ($data[$i]['get_appointment_charges']['extra_field'] != null) {
                        $extraField = unserialize($data[$i]['get_appointment_charges']['extra_field']);
                        $extraField1 = $extraField[0];
                        echo !empty($extraField1[0]) ? $extraField1[0] . '<br />' : null;
                        $extraField2 = $extraField[1];
                        echo !empty($extraField2[0]) ? $extraField2[0] . '<br />' : null;
                    }
                    echo ($data[$i]['get_appointment_charges']['nst'] > 0) ? 'NST <br />' : null;
                    echo ($data[$i]['get_appointment_charges']['cut'] > 0) ? 'CUT <br />' : null;
                    echo ($data[$i]['get_appointment_charges']['dressing'] > 0) ? 'Dressing <br />' : null;
                    echo ($data[$i]['get_appointment_charges']['ivf'] > 0) ? 'IVF <br />' : null;
                    if ($data[$i]['get_appointment_charges']['charge_types'] == null) {
                        echo '-';
                    }
                @endphp
            </td>
            <td class="data-font seperator">
                <div class="amount">
                    @php
                        if ($data[$i]['get_appointment_charges']['total'] > 0) {
                            echo ($data[$i]['get_appointment_charges']['consulting_charges'] > 0) ? $data[$i]['get_appointment_charges']['consulting_charges'] . '<br />' : null;
                            echo (!empty($extraField1) && ($extraField1[1] > 0)) ? $extraField1[1] . '<br />' : null;
                            echo (!empty($extraField2) && ($extraField2[1] > 0)) ? $extraField2[1] . '<br />' : null;
                            echo ($data[$i]['get_appointment_charges']['nst'] > 0) ? $data[$i]['get_appointment_charges']['nst'] . '<br />' : null;
                            echo ($data[$i]['get_appointment_charges']['cut'] > 0) ? $data[$i]['get_appointment_charges']['cut'] . '<br />' : null;
                            echo ($data[$i]['get_appointment_charges']['dressing'] > 0) ? $data[$i]['get_appointment_charges']['dressing'] . '<br />' : null;
                            echo ($data[$i]['get_appointment_charges']['ivf'] > 0) ? $data[$i]['get_appointment_charges']['ivf'] . '<br />' : null;
                            $totalLeft += $data[$i]['get_appointment_charges']['total'] - $data[$i]['get_appointment_charges']['usg'];
                        } else {
                            echo '0';
                            $totalLeft += 0;
                        }
                    @endphp
                </div>
            </td>
            <td class="expense-icon data-font seperator">{{$data[$i]['get_appointment_charges']['txt_amount']}}</td>
            <td class="expense-icon data-font seperator">{{$data[$i]['get_appointment_charges']['invoice_no']}}</td>
            <td class="expense-icon data-font seperator">{{(isset($bank_details[$data[$i]['get_appointment_charges']['bank_id']])) ? $bank_details[$data[$i]['get_appointment_charges']['bank_id']] : ''}}</td>
            <td class="expense-icon data-font seperator">{{$data[$i]['get_appointment_charges']['detail']}}</td>
            {{-- @if ($count > 8)
                @php
                    $i++;
                @endphp
            @endif
            @if ($i < $count && $count > 8)
                <td class="data-font seperator">{{ ($j = $i + 1) . '.'}}</td>
                <td class="data-font seperator">{{ cdate($data[$i]['date'])->format('d-m-Y')}}</td>
                <td class="data-font seperator">{{ strtoupper(@$data[$i]['get_patients_details']['name']) }}</td>
                <td class="data-font seperator">
                    @php

                        echo ($data[$i]['get_appointment_charges']['consulting_charges'] > 0) ? 'Consulting <br />' : null;
                        $extraField1 = '';
                        $extraField2 = '';
                        if ($data[$i]['get_appointment_charges']['extra_field'] != null) {
                            $extraField = unserialize($data[$i]['get_appointment_charges']['extra_field']);
                            $extraField1 = $extraField[0];
                            echo !empty($extraField1[0]) ? $extraField1[0] . '<br />' : null;
                            $extraField2 = $extraField[1];
                            echo !empty($extraField2[0]) ? $extraField2[0] . '<br />' : null;
                        }
                        echo ($data[$i]['get_appointment_charges']['nst'] > 0) ? 'NST <br />' : null;
                        echo ($data[$i]['get_appointment_charges']['cut'] > 0) ? 'CUT <br />' : null;
                        echo ($data[$i]['get_appointment_charges']['dressing'] > 0) ? 'Dressing <br />' : null;
                        echo ($data[$i]['get_appointment_charges']['ivf'] > 0) ? 'IVF <br />' : null;
                        if($data[$i]['get_appointment_charges']['charge_types'] == null) {
                            echo '-';
                        }
                    @endphp
                </td>
                <td class="data-font seperator">
                    <div class="amount">
                        @php
                            if ($data[$i]['get_appointment_charges']['total'] > 0) {
                                echo ($data[$i]['get_appointment_charges']['consulting_charges'] > 0) ? $data[$i]['get_appointment_charges']['consulting_charges'] . '<br />' : null;
                                echo (!empty($extraField1) && ($extraField1[1] > 0)) ? $extraField1[1] . '<br />' : null;
                                echo (!empty($extraField2) && ($extraField2[1] > 0)) ? $extraField2[1] . '<br />' : null;
                                echo ($data[$i]['get_appointment_charges']['nst'] > 0) ? $data[$i]['get_appointment_charges']['nst'] . '<br />' : null;
                                echo ($data[$i]['get_appointment_charges']['cut'] > 0) ? $data[$i]['get_appointment_charges']['cut'] . '<br />' : null;
                                echo ($data[$i]['get_appointment_charges']['dressing'] > 0) ? $data[$i]['get_appointment_charges']['dressing'] . '<br />' : null;
                                echo ($data[$i]['get_appointment_charges']['ivf'] > 0) ? $data[$i]['get_appointment_charges']['ivf'] . '<br />' : null;
                                $totalRight += $data[$i]['get_appointment_charges']['total'] - $data[$i]['get_appointment_charges']['usg'];
                            } else {
                                echo '0';
                                $totalRight += 0;
                            }
                        @endphp
                    </div>
                </td>
                <td class="expense-icon data-font seperator">{{$data[$i]['get_appointment_charges']['txt_amount']}}</td>
                <td class="expense-icon data-font seperator">{{$data[$i]['get_appointment_charges']['invoice_no']}}</td>
                <td class="expense-icon data-font seperator">{{(isset($bank_details[$data[$i]['get_appointment_charges']['bank_id']])) ? $bank_details[$data[$i]['get_appointment_charges']['bank_id']] : ''}}</td>
                <td class="expense-icon data-font seperator">{{$data[$i]['get_appointment_charges']['detail']}}</td>
            @endif --}}
        </tr>
    @endfor
    @php
        $total = $totalLeft + $totalRight;
    @endphp
    <tr>
        <td class="bt-none" colspan="3"></td>
        <th class="bt-none" colspan="1">Total :</th>
        <th class="top-border-first">{{ $total }}</th>
    </tr>
@else
    <td colspan='9' class="text-center">No records available</td>
@endif
@php
    Session(['total' => $total]);
@endphp
