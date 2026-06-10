@extends('layouts.main')
@section('parentPageTitle', 'Patient')
@section('title', 'Patient Details')

@section('content')
    <div class="row clearfix profile-page">
        <div class="col-md-12">
            <div class="card patients-list">
                <div class="header">
                    <h2>
                        <strong>Patient Profile Page</strong>
                    </h2>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12 col-sm-12">
            <div class="card member-card patient-profile">
                <div class="header">
                    <h4 class="m-t-10">{{ ucwords(strtolower($patientAppointments['name'])) }}</h4>
                </div>
                <hr />
                <div class="member-img">
                    <img src="{{URL::to('images/default_user.png')}}" class="rounded-circle" alt="profile-image">
                </div>
                <div class="body">
                    <strong>Occupation</strong>
                    <p>
                        {{ (!empty($patientAppointments['occupation'])) ? ucwords(strtolower($patientAppointments['occupation'])) : '-' }}
                    </p>

                    <hr>
                    <strong>Email ID</strong>
                    <p>
                        -
                    </p>
                    <hr>
                    <strong>Phone</strong>
                    <p>
                        +91 {{ $patientAppointments['mobile_number'] }}
                        @if (!empty($patientAppointments['other_mobile_number']))
                            <br>
                            +91 {{ $patientAppointments['other_mobile_number'] }}
                        @endif
                    </p>
                    <hr>
                    <strong>Address</strong>
                    <address>
                        {{ ucwords(strtolower(str_replace(',', '', $patientAppointments['residence']) . ', ' . str_replace(',', '', $patientAppointments['main_area']) . ', ' . str_replace(',', '', $patientAppointments['city']))) }}
                    </address>
                </div>
            </div>
            <div class="card">
                <div class="body">
                    <div class="last-appointment">
                        <strong>Last Appointment</strong>
                        <br>

                            @foreach($patientAppointments->getAppointments as $row)
                                @if (\Carbon\Carbon::parse($row->date) < \Carbon\Carbon::now())
                                    <small class="text-muted">
                                        {{ (!empty($row)) ? \Carbon\Carbon::parse($row->date)->englishDayOfWeek : null }}
                                    </small>
                                    <p>
                                        {{ (!empty($row)) ? \Carbon\Carbon::parse($row->date)->format('d M Y') : null }}
                                    </p>
                                    @break
                                @endif
                            @endforeach
                        </small>
                        <hr>
                        @if ($patientAppointments->getAppointments->count() > 0)
                            <strong>Categories</strong>
                            <br>
                            {{-- @php $categories = [] @endphp --}}
                            {{-- @foreach ($patientAppointments->getAppointments as $patientAppointment)
                                @foreach ($patientAppointment->getPatientCategories as $row)
                                    @php
                                        array_push($categories, ucfirst($row->getCategories['name']))
                                    @endphp
                                @endforeach
                            @endforeach --}}
                            {{$cName}}
                            <hr>
                        @endif
                    </div>

                    <div class="last-indoor">
                        <strong>Indoor</strong>
                        @if (count($patientAppointments->getBookings) > 0)
                            <br>
                            <small class="text-muted">

                                {{  (!empty($patientAppointments->getBookings[0]['doa_date'])) ? 'DOA : ' . \Carbon\Carbon::parse($patientAppointments->getBookings[0]['doa_date'])->englishDayOfWeek . ', ' . \Carbon\Carbon::parse($patientAppointments->getBookings[0]['doa_date'])->format('d M Y'): null }}
                            </small>
                            <br />
                            <small class="text-muted">
                                {{  (!empty($patientAppointments->getBookings[0]['dod_date'])) ? 'DOD : ' . \Carbon\Carbon::parse($patientAppointments->getBookings[0]['dod_date'])->englishDayOfWeek . ', ' . \Carbon\Carbon::parse($patientAppointments->getBookings[0]['dod_date'])->format('d M Y') : null }}
                            </small>
                            <p>
                                {{ $patientAppointments->getBookings[0]->getRoomType['name'] . ', Room no. '. $patientAppointments->getBookings[0]->getRoom['room_no'] }}
                            </p>

                        @endif
                        <hr />
                    </div>
                    <div class="reviews">

                        <strong>Reviews</strong>
                        <br>
                        @forelse($userReview as $review)
                        <small class="text-muted">{{$review->name}}</small>
                        <p>
                           @for ($i = 0; $i < 5; ++$i)
                                <i class="zmdi zmdi-star{{ $review->rate <= $i ? '-outline' : '' }}" aria-hidden="true"></i>
                            @endfor
                        </p>
                        <hr>
                        @empty
                        <span>No Review Rating</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="tab-content">
                    <div class="tab-pane body active" id="about">

                        <strong>Patient Details</strong>
                        <br>
                        <hr>

                        <ul class="list-unstyled">
                            <li>
                                <p><strong>Code: </strong> {{ $patientAppointments->code }}</p>
                            </li>
                            <li>
                                <p><strong>Age: </strong> {{ $patientAppointments->age }}</p>
                            </li>
                            <li>
                                <p><strong>Weight: </strong> {{ (!empty($patientAppointments->weight)) ? $patientAppointments->weight : null }}</p>
                            </li>
                            <li>
                                <p><strong>Hospital Doctor: </strong> {{ (!empty($patientAppointments->getHospitalDoctor['name'])) ? ucwords(strtolower($patientAppointments->getHospitalDoctor['name'])) : null }}</p>
                            </li>
                            <li>
                            <p><strong>Reference Doctor: </strong> <a href="{{URL::to('reference-doctor/'.encrypt($patientAppointments->getReferenceDoctor['id']).'/edit')}}" class="link-color">{{  ucwords(strtolower($patientAppointments->getReferenceDoctor['name']))  }}</a></p>
                            </li>
                            <li>
                                <p><strong>Reference Doctor Mobile: </strong>  +91 {{ $patientAppointments->getReferenceDoctor['mobile_number'] }}</p>
                            </li>
                        </ul>
                        <hr />


                        @if (!empty($procedures))
                            <strong>Surgeries: </strong>
                            {{ $procedures }}
                        @endif

                    </div>
                    <div class="tab-pane body" id="Account">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Current Password">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="New Password">
                        </div>
                        <button class="btn btn-info btn-round">Save Changes</button>
                        <hr>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="First Name">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Last Name">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="City">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="E-mail">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Country">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group m-b-10">
                                    <textarea rows="4" class="form-control no-resize" placeholder="Address Line 1"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="checkbox">
                                    <input id="procheck2" type="checkbox">
                                    <label for="procheck2">New task notifications</label>
                                </div>
                                <div class="checkbox">
                                    <input id="procheck3" type="checkbox">
                                    <label for="procheck3">New friend request notifications</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-round">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" id="timeline">
                <div class="body">
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Appointments</strong>
                            <br>
                            <br>
                            <div class="timeline-body">
                                <div class="timeline m-border">

                                    @foreach ($patientAppointments->getAppointments as $row)
                                        @php
                                            $colorClass = '';
                                            if ($row->date == \Carbon\Carbon::now()->format('d M Y')) {
                                                $colorClass = '';
                                            } else if ($row->getAppointmentCharges != null) {
                                                $colorClass =  'border-info';
                                            } else {
                                                $colorClass = 'border-danger';
                                            }
                                        @endphp
                                        <div class="timeline-item {{ $colorClass }}">
                                            <div class="item-content">
                                                <div class="text-small">
                                                    <a href="{{ URL::to('appointment/' . encrypt($row->id) . '/edit')}}" class="link-color">
                                                        {{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}
                                                        @if (!empty($row->time))
                                                            {{ \Carbon\Carbon::parse($row->time)->format('h:i A') }}
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        <p>{{ (!empty($row->categoryDetails['name'])) ? ucfirst($row->categoryDetails['name']) : null }}</p>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <p>{{ isset($row->getAppointmentCharges['netamount']) ? 'OPD: ₹ ' . $row->getAppointmentCharges['netamount'] : null }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-script')
    <script type="text/javascript">
        $('.anc-date').on('click', function(){
            var id = $(this).data('id');
            var date = $(this).data('date');
            localStorage.setItem('anc_date', date)
            window.location.href = '{{URL::to("anc/history")}}/' + id;
        });
    </script>
@stop
