@php
    $systemSetting = systemSetting();
@endphp
<aside id="leftsidebar" class="sidebar">
    <ul class="nav nav-tabs">
        <li class="user-log-name">
            <div class="user-info">
               <div class="list-icon mt-3">
                    @php
                        $logo = isset($systemSetting->header_logo) && !empty($systemSetting->header_logo) ? $systemSetting->header_logo : 'logo.svg';
                        $width = isset($systemSetting->header_logo_width) && !empty($systemSetting->header_logo_width) ? $systemSetting->header_logo_width : 'auto';
                        $height = isset($systemSetting->header_logo_height) && !empty($systemSetting->header_logo_height) ? $systemSetting->header_logo_height : 'auto';
                        $alt = isset($systemSetting->alt) && !empty($systemSetting->alt) ? $systemSetting->alt : '';
                        $htmlTitle = isset($systemSetting->html_title) && !empty($systemSetting->html_title) ? $systemSetting->html_title : null;
                        $ancCount = getAppointmentCount(1)['appointmentCount'];
                        $ivfCount = getAppointmentCount(2)['appointmentCount'];
                        $iuiCount = getAppointmentCount(3)['appointmentCount'];
                        $appointmentCount = getAppointmentCount(4)['appointmentCount'];
                    @endphp
                    <div>
                        <a href="{{URL::to('/dashboard')}}" style="margin-bottom: 15px;">
                            <img src="{{ url('public/images/' . $logo)}}" alt="{{ $alt }}" width="{{$width}}" height="{{$height}}"/>
                        </a>
                    </div>
                    <div class="detail">
                        @if($htmlTitle == 'null')
                            <a href="{{URL::to('/dashboard')}}"><h4>{{ $htmlTitle }}</h4></a>
                        @endif
                    </div>
                </div>
            </div>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="dashboard">
            <div class="menu">
                <ul class="list sidemenu_list">

                    {{--reception--}}
                    @if(in_array(Auth::user()->role,[1,2]))
                    <li class="{{Request::segment(1) === 'appointment' || Request::segment(1) === 'appointment-create'|| Request::segment(1) === 'appointment-request' || Request::segment(1) === 'donor' || Request::segment(1) === 'hormon' || Request::segment(1) === 'usg-appointment' ? 'active open' : null }}">
                        <a href="javascript:void(0);"  class="menu-toggle waves-effect waves-block">
                        <span>RECEPTION</span></a>
                        <ul class="ml-menu" style="display: none;">
                            <li class="{{(Request::segment(1) === 'appointment' && Request::segment(2) == '') || Request::segment(1) === 'appointment-create' ? 'sub active open' : null }}">
                                <a href="{{URL::to('appointment')}}"><span>APPOINTMENTS ({{$appointmentCount}})</span>&nbsp;</a>
                            </li>
                            <li class="{{ Request::segment(1) === 'appointment' && Request::segment(2) != '' ? 'sub active open' : null }}">
                                <a href="{{URL::to('appointment/'.encrypt('usg'))}}"><span>USG APPOINTMENTS</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'appointment-request' ? 'sub active open' : null }}">
                                <a href="{{URL::to('appointment-request')}}"><span>Online APT REQUESTS</span></a>
                            </li>
                            <li class="{{ Request::segment(1) === 'hormon' ? 'sub active open' : null }}">
                                <a href="{{URL::to('hormon')}}"><span>OPD Collection</span></a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    {{--visit--}}
                    @if(in_array(Auth::user()->role,[1,3,6,7,8]))
                    <li class="visit {{ Request::segment(1) === 'anc-iui-ivf' || (Request::segment(1) === 'report' && !empty(Request::segment(2))) || Request::segment(1) === 'anc' || Request::segment(1) === 'iui' || Request::segment(1) === 'ivf' || Request::segment(1) === 'call-reminder' || Request::segment(1) === 'iui-result' || Request::segment(1) === 'get-anc-report' ? 'active open' : null }}">
                        <a href="javascript:void(0);"  class="menu-toggle waves-effect waves-block">
                        <span>VISIT</span></a>
                        <ul class="ml-menu" style="display: none;">
                            <li class="{{ Request::is('anc-iui-ivf*') == 'anc-iui-ivf*' ? 'sub active open' : null }}">
                                <a href="{{URL::to('anc-iui-ivf')}}">
                                    <span>APPOINTMENTS</span>
                                </a>
                            </li>
                            <li class="{{ Request::segment(1) === 'anc' || Request::segment(1) === 'get-anc-report' ? 'sub active open' : null }}"><a href="{{URL::to('anc')}}">
                                <span>ANC ({{$ancCount}})</span>&nbsp;</a></li>
                            <li class="{{ Request::segment(1) === 'ivf' ? 'sub active open' : null }}"><a href="{{URL::to('ivf')}}">
                                <span>IVF ({{$ivfCount}})</span>&nbsp;</a></li>
                            <li class="{{ Request::segment(1) === 'iui' ? 'sub active open' : null }}"><a href="{{URL::to('iui')}}">
                                    <span>INFERTILITY ({{$iuiCount}})</span>&nbsp;</a></li>
                            <li class="{{ Request::segment(1) === 'call-reminder' ? 'sub active open' : null }}"><a href="{{URL::to('call-reminder')}}"><span>IUI Call Reminder</span></a></li>
                            <li class="{{ Request::segment(1) === 'iui-result' ? 'sub active open' : null }}"><a href="{{URL::to('iui-result')}}"><span>IUI Result</span></a></li>
                        </ul>
                    </li>
                    @endif

                    {{--patient--}}
                    @if(in_array(Auth::user()->role,[1,2,3]))
                    <li class="{{ Request::segment(1) === 'patient' ? 'sub active open' : null }}"><a href="{{URL::to('patient')}}"><span>Patient</span></a></li>
                    @endif

                    {{--indoor--}}
                    @if(in_array(Auth::user()->role,[1,2,3]))
                    <li class="{{ Request::segment(1) === 'indoor'  || Request::segment(1) === 'patient-detail' ? 'active open' : null }}">
                        <a href="javascript:void(0);"  class="menu-toggle waves-effect waves-block">
                        <span>indoor</span></a>
                        <ul class="ml-menu" style="display: none;">
                            <li class="{{ Request::segment(1) === 'indoor' ? 'sub active open' : null }}"><a href="{{URL::to('indoor')}}"><span>Details</span></a></li>
                            <li class="{{ Request::segment(1) === 'patient-detail' ? 'sub active open' : null }}"><a href="{{URL::to('patient-detail')}}"><span>Summary</span></a></li>
                        </ul>
                    </li>
                    @endif

                    {{--report--}}
                    @if(in_array(Auth::user()->role,[1,2,4]))
                    <li class="{{ (Request::segment(1) === 'report' && empty(Request::segment(2)))  || Request::segment(1) === 'category-report' || Request::segment(1) == 'ref-pro-doctor-report' || Request::segment(1) == 'remark-appointment-report' || Request::segment(1) === 'cut-report' || Request::segment(1) === 'ivf-payment-report'|| Request::segment(1) == 'infertility-report' || Request::segment(1) === 'ref-doctor-report' || Request::segment(1) === 'collection-report' || Request::segment(1) === 'patient-report' || Request::segment(1) == 'edd-patient' || Request::segment(1) === 'sms-manager' || Request::segment(1) === 'ca-expense-report' ? 'active open' : null || Request::segment(1) === 'hormon-inj-report' ? 'active open' : null }}">
                        <a href="javascript:void(0);"  class="menu-toggle waves-effect waves-block">
                        <span>reports</span></a>
                        <ul class="ml-menu" style="display: none;">
                            <li class="{{ Request::segment(1) === 'report' && empty(Request::segment(2)) ? 'sub active open' : null }}"><a href="{{URL::to('report')}}" class=" waves-effect waves-block"><span>OPD 3C</span></a></li>
                            <li class="{{ Request::segment(1) === 'category-report' ? 'sub active open' : null }}"><a href="{{URL::to('category-report')}}" class=" waves-effect waves-block"><span>Category</span></a></li>
                            <li class="{{ Request::segment(1) === 'cut-report' ? 'sub active open' : null }}"><a href="{{URL::to('cut-report')}}" class=" waves-effect waves-block"><span>CUT</span></a></li>
                            <li class="{{ Request::segment(1) === 'ref-doctor-report' ? 'sub active open' : null }}"><a href="{{URL::to('ref-doctor-report')}}" class="waves-effect waves-block"><span>Ref. Doctor</span></a></li>
                            <li class="{{ Request::segment(1) === 'ref-pro-doctor-report' ? 'sub active open' : null }}"><a href="{{URL::to('ref-pro-doctor-report')}}" class="waves-effect waves-block"><span>Ref. Pro Doctor</span></a></li>
                            <li class="{{ Request::segment(1) === 'remark-appointment-report' ? 'sub active open' : null }}"><a href="{{URL::to('remark-appointment-report')}}" class="waves-effect waves-block"><span>Remark Report</span></a></li>
                            <li class="{{ Request::segment(1) === 'collection-report' ? 'sub active open' : null }}"><a class="waves-effect waves-block collection-report-modal"><span>New Collection</span></a></li>
                            <li class="{{ Request::segment(1) === 'hormon-inj-report' ? 'sub active open' : null }}"><a href="{{URL::to('hormon-inj-report')}}" class="waves-effect waves-block"><span>Hormon Injection</span></a></li>
                            <li class="{{ Request::segment(1) === 'patient-report' ? 'sub active open' : null }}"><a href="{{URL::to('patient-report')}}" class=" waves-effect waves-block"><span>patient</span></a></li>
                            <li class="{{ Request::segment(1) === 'sms-manager' ? 'sub active open' : null }}"><a href="{{URL::to('sms-manager')}}"><span>SMS </span></a></li>
                            <li class="{{ Request::segment(1) === 'ivf-payment-report' ? 'sub active open' : null }}"><a href="{{URL::to('ivf-payment-report')}}" class=" waves-effect waves-block"><span>IVF Payment</span></a></li>
                            <li class="{{ Request::segment(1) === 'edd-patient' ? 'sub active open' : null }}"><a href="{{URL::to('edd-patient')}}" class=" waves-effect waves-block"><span>EDD Patients</span></a></li>
                            <li class="{{ Request::segment(1) === 'infertility-report' ? 'sub active open' : null }}"><a href="{{URL::to('infertility-report')}}" class=" waves-effect waves-block"><span>Infertility Report</span></a></li>
                            <li class="{{ Request::segment(1) === 'ca-expense-report' ? 'sub active open' : null }}"><a href="{{URL::to('ca-expense-report')}}"class="waves-effect waves-block"><span>CA Expense</span></a></li>
                        </ul>
                    </li>
                    @endif

                    {{--medical--}}
                    @if(in_array(Auth::user()->role,[1,5]))
                    <li class="{{ Request::segment('1') == 'medical' || Request::segment(1) === 'get-medicine'? 'sub active open' : null }}">
                        <a href="{{URL::to('medical')}}">
                            <span>Medical</span>
                        </a>
                    </li>
                    @endif

                    {{--user--}}
                    @if(in_array(Auth::user()->role,[1]))
                    <li class="{{ Request::is('user*') == 'user' ? 'sub active open' : null }}">
                        <a href="{{URL::to('user')}}">
                            <span>Users</span></a>
                    </li>
                    @endif

                    {{--system--}}
                    @if(in_array(Auth::user()->role,[1,2]))
                    <li class="{{ Request::segment('1') == 'reference-doctor' || Request::segment('1') == 'reference-doctor-pro' || Request::segment('1') == 'category' || Request::segment('1') == 'indoorsetting' || Request::segment('1') == 'systemsetting' || Request::segment('1') == 'medicines-mapping' || Request::segment('1') == 'medicines-setting' ||  Request::segment('1') == 'injection' ? 'active open' : null || Request::segment('1') == 'charge' ? 'active open' : null || Request::segment('1') == 'inj-charge' ? 'active open' : null}}">
                        <a href="javascript:void(0);"  class="menu-toggle waves-effect waves-block">
                        {{--<i class="material-icons">hotel</i>--}}
                            <span>system</span></a>
                        <ul class="ml-menu" style="display: none;">
                            <li class="{{ Request::segment(1) === 'category' ? 'sub active open' : null }}"><a href="{{URL::to('category')}}"><span>Category</span></a></li>
                            <li class="{{ Request::segment(1) === 'reference-doctor' ? 'sub active open' : null }}"><a href="{{URL::to('reference-doctor')}}"><span>Reference By</span></a></li>
                            <li class="{{ Request::segment(1) === 'reference-doctor-pro' ? 'sub active open' : null }}"><a href="{{URL::to('reference-doctor-pro')}}"><span>Reference By Pro</span></a></li>
                            <li class="{{ Request::segment(1) === 'indoorsetting' ? 'sub active open' : null }}"><a href="{{URL::to('indoorsetting')}}"><span>Indoor</span></a></li>
                            <li class="{{ Request::segment(1) === 'systemsetting' ? 'sub active open' : null }}"><a href="{{URL::to('systemsetting')}}"><span>System</span></a></li>
                            <li class="{{ Request::segment(1) === 'medicines-setting' || Request::segment(1) === 'medicines-mapping' ? 'sub active open' : null }}"><a href="{{URL::to('medicines-setting')}}"><span>Medicine</span></a></li>
                            <li class="{{ Request::segment(1) === 'injection' || Request::segment(1) === 'injection/*' ? 'sub active open' : null }}"><a href="{{URL::to('injection')}}"><span>Plan</span></a></li>
                            <li class="{{ Request::segment(1) === 'charge' || Request::segment(1) === 'charge/*' ? 'sub active open' : null }}"><a href="{{URL::to('charge')}}"><span>Hospital Charges</span></a></li>
                            <li class="{{ Request::segment(1) === 'inj-charge' || Request::segment(1) === 'inj-charge/*' ? 'sub active open' : null }}"><a href="{{URL::to('inj-charge')}}"><span>INJ. Charges</span></a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</aside>
