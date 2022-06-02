<?php

namespace App\Http\Controllers\Api\DoctorApi;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Base\Api\ApiController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Models\Appointment;
use Validator;
use Carbon\Carbon;
use DB;

class PatientController extends ApiController
{
    /**
     * Get patient appointment
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function doctorpatient(Request $request)
    {
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $per_page = isset($request->per_page) ? $request->per_page : '';
        $page = isset($request->page) ? $request->page : '';

        if ($token && $UserData) {
            $patientList = collect($this->OpdPatients->select('id', 'code', 'name', 'dob', 'mobile_number', 'profile_picture', 'reference_doctor_id')->where('hospital_doctor_id', $UserData->user_id)->paginate($per_page, $page)->all())->map(function ($q) {
                $q->reference_doctor = $q->getReferenceDoctor['name'];
                $q->category = isset($q->lastDoneAppointmentData->categoryDetails['name']) ? $q->lastDoneAppointmentData->categoryDetails['name'] : '';
                unset($q->getReferenceDoctor, $q->lastDoneAppointmentData);
                return $q;
            });
            // dd($patientList);

            return $this->sendResponse('Your patients successfully get', $patientList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
     *Get patient appointment request list
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function PatientAppointmentRequest(Request $request)
    {

        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $opd_time = isset($request->opd) ? $request->opd : '';
        $doctor_id = isset($request->doctor_id) ? $request->doctor_id : '';
        // dd($doctor_id);
        if ($token && $UserData) {
            $appointmentRequestList = collect($this->AppointmentRequest
                ->select(
                    'id',
                    'patients_id',
                    'seen_by',
                    'appointment_date',
                    'appointment_time',
                    'created_at',
                    \DB::raw('(CASE
             WHEN is_book = "0" THEN "pending"
             WHEN is_book = "1" THEN "approve"
             WHEN is_book = "2" THEN "reject"
             END) AS is_book')
                )
                ->where('seen_by', $UserData->user_id)->get())->map(function ($q) use ($opd_time) {
                $q->doctor = $q->getSeenBy['name'];
                $q->patient = $q->getPatients['name'];
                $q->profile_picture = $q->getPatients['profile_picture'];
                $q->category = $q->lastAppointmentData->categoryDetails['name'];
                $q->time = (strtotime(\Carbon\Carbon::parse($q->appointment_time)->format('g:i')) > strtotime('9:00') &&   strtotime(\Carbon\Carbon::parse($q->appointment_time)->format('g:i')) < strtotime('12:00') ? 'morning' : 'evening');
                if ($opd_time == $q->time || $opd_time == null) {
                    unset($q->getPatients, $q->getSeenBy, $q->lastAppointmentData);
                    return $q;
                }
            });
            $appointmentList = $appointmentRequestList->filter(function ($value, $key) {
                return $value != null;
            });
            return $this->sendResponse('Your appointment request successfully get', $appointmentList);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    /**
     *Get today appointment list
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function doctortodaypatients(Request $request)
    {
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $opd_time = isset($request->opd) ? $request->opd : '';

        if ($token && $UserData) {
            $appointmentList = collect($this->Appointment->select('id', 'patients_id', 'category_id', 'date', 'time', 'seen_by')->where('seen_by', $UserData->user_id)->whereDate('created_at', Carbon::today())->get())->map(function ($q) use ($opd_time) {
                $q->doctor = $q->getSeenBy['name'];
                $q->mobile_number = $q->getSeenBy['mobile_number'];
                $q->profile_picture = $q->getPatientsDetails['profile_picture'];
                $q->patient_name = $q->getPatientsDetails['name'];
                $q->category = $q->categoryDetails['name'];
                $q->time = (strtotime(\Carbon\Carbon::parse($q->time)->format('g:i')) > strtotime('9:00') &&   strtotime(\Carbon\Carbon::parse($q->time)->format('g:i')) < strtotime('12:00') ? 'morning' : 'evening');
                if ($opd_time == $q->time || $opd_time == null) {
                    unset($q->getPatientsDetails, $q->getSeenBy, $q->categoryDetails);
                    return $q;
                }
            });
            $appointmentlist = $appointmentList->filter(function ($value, $key) {
                return $value != null;
            });
            return $this->sendResponse('Your Today appointment successfully get', $appointmentlist);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
    /**
     *Get Patient Details
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function patientDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $patientId = isset($request->patient_id) ? $request->patient_id : '';

        $appointmentData = $this->Appointment::select('id', 'date', 'time', 'created_by', 'is_done', 'category_id', 'appontment_request_id', 'arrival_time', 'is_procedure', DB::raw("DATE_FORMAT(date,'%Y') as yearKey"))
            ->where('patients_id', $patientId)
            ->orderBy('date', 'desc')
            ->get();
        $appointmentRequestData = $this->AppointmentRequest::select('id', 'appointment_date as date', 'appointment_time as time', 'is_book', DB::raw("DATE_FORMAT(appointment_date,'%Y') as yearKey"))
            ->where('patients_id', $patientId)
            ->where('is_book', '!=', 1)
            ->orderBy('date', 'desc')
            ->get();
        $data  = collect($appointmentData->merge($appointmentRequestData))->groupBy('date');

        $aData = [];
        $appointmentData = [];


        foreach ($data as $key => $value) {

            $value = $value[0];
            $utersWeek = null;
            $oldDate = null;
            $lastAppointment = $this->Appointment->where('patients_id', $patientId)->where('date', $value->date)->orderBy('id', 'DESC')->first();
            if ($lastAppointment) {
                $value->id = $lastAppointment->id;

                $categoryData = $lastAppointment->category_id;

                if ($lastAppointment->category_id == 5 || $lastAppointment->category_id == 6) {
                    $anc = $this->ANC->where('patients_id', $patientId)->orderBy('created_at', 'desc')->first();
                    if ($anc) {
                        $mhData = json_decode($anc->m_h);
                        $lmdDate = $mhData->last_menstrual_date;
                        $oldDate = \Carbon\Carbon::parse($lmdDate)->format('Y-m-d');
                    }
                }
            }
            $utersWeek =  \Carbon\Carbon::parse(!empty($oldDate) ? $oldDate : date('Y-m-d'))->diffInWeeks(\Carbon\Carbon::parse($value->date)->format('Y-m-d'));
            $value->category = !empty($lastAppointment) && $categoryData ? $lastAppointment['categoryDetails']['name'] : null;
            $value->category_id = !empty($lastAppointment) ? $categoryData : null;
            $currentDate = \Carbon\Carbon::now()->format('d-m-Y');
            $currentTime = \Carbon\Carbon::now()->format('H:i:s');
            $book = $value->is_book;
            $status = $book == 0 ? 'Pending' : 'Rejected';
            $value->reason = $value->remark;
            $value->oldDate = $oldDate;
            $value->time = \Carbon\Carbon::parse($value->time)->format('g:i') . ' ' . (strtotime(\Carbon\Carbon::parse($value->time)->format('g:i')) > strtotime('9:00') &&   strtotime(\Carbon\Carbon::parse($value->time)->format('g:i')) < strtotime('12:00') ? 'AM' : 'PM');
            if ($value->created_by) {
                if ($value->is_done == 0) {
                    $status = "Not Visited";
                    if (((strtotime($value->date . ' ' . $value->arrival_time) > strtotime($currentDate . ' ' . $currentTime)) && ($value->is_done == 0)) || (!$value->arrival_time && strtotime($value->date) >= strtotime($currentDate))) {
                        $status = "Approved";
                    }
                } else {
                    $status = "Visited";
                }
                if ($value->is_procedure == 1) {
                    $pickUp = $this->IvfPlanReport->where('patients_id', $patientId)->whereDate('created_at', \Carbon\Carbon::parse($value->date)->format('Y-m-d'))->first();
                    if (!empty($pickUp)) {
                        $status = "Visited";
                    }
                    // $transfer = $this->IvfTransferReport->where('patients_id',$patientId)->whereDate('craeted_at',$value->date)->first();
                }
            }
            $value->status = $status;
            unset($value->is_done, $value->is_procedure, $value->yearKey, $value->categoryDetails, $value->getPatientsDetails, $value->appontment_request_id, $value->is_book, $value->arrival_time, $value->created_by);

            $value->reason = $value->remark;
            $value->week = $utersWeek;
            array_push($appointmentData, $value);
        }

        if ($token && $UserData) {
            $patientdetails = $this->OpdPatients->select('id', 'code', 'name', 'profile_picture', 'dob', 'mobile_number', 'residence', 'location', 'other_mobile_number', 'weight')->where('hospital_doctor_id', $UserData->user_id)->where('id', $patientId)->first();
            $patientdetails->visit = $appointmentData;
            foreach ($appointmentData as $key) {
                $appid = $key->id;
                $key->url = $this->getappointmentvisit($appid);
                // dd($this->getappointmentvisit($appid));
            }
            return $this->sendResponse('Your Patient Details successfully get', $patientdetails);
        }
        return $this->sendError(__('auth.failed'), 401);
    }

    public function getappointmentvisit($appid)
    {
        $appointment = $this->Appointment->with('getPatientsDetails')->where('id', $appid)->first();
        $pId = $appointment->patients_id;

        if (!empty($appointment)) {
            $gender = 'Male';
            if ($appointment->getPatientsDetails['gender'] == 2) {
                $gender = 'female';
            }
            $result = 'Yes';
            if ($appointment->getPatientsDetails['is_pregnant'] == 2) {
                $result = 'No';
            }
            $categoryId = $appointment->category_id;

            $category = $this->Category->where('id', $appointment->category_id)->first();
            $categoryName = '';
            if (!empty($category)) {
                $categoryName = strtolower($category->name);
            }


            // $aptCharges = $this->AppointmentCharges->select('extra_field', 'consulting_charges', 'nst', 'cut', 'usg', 'ivf', 'procedure', 'dressing', 'total', 'discount', 'netamount')->where('appointment_id', $appid)->first();
            // $charges = null;
            // if (!empty($aptCharges) && $aptCharges->netamount >= 0) {
            //     $charges = $aptCharges;
            //     $extraField = unserialize($aptCharges->extra_field);
            //     if ($extraField) {
            //         $extraField1 = $extraField[0];
            //         $extraField2 = $extraField[1];
            //         if (!empty($extraField1[1])) {
            //             $aptCharges[$extraField1[0]] = $extraField1[1];
            //         }
            //         if (!empty($extraField2[1])) {
            //             $aptCharges[$extraField2[0]] = $extraField2[1];
            //         }
            //         unset($aptCharges->extra_field);
            //     }
            // }

            $madicineData = [];
            $reportsData = [];
            $aptCreatedDate = ($appointment->date);
            $url = [];
            if (!empty($categoryId)) {
                //  $patientCategory =  $this->checkCategory($categoryName);
                // $medicineTime = ['1' => 'Morning', '2' => 'Afternoon', '3' => 'Evening', '4' => 'Night'];
                // $medicine_time = ['1' => 'IV', '2' => 'IM', '3' => 'SC', "4" => 'Oral', "5" => 'P/V', "6" => "P/A"];
                // $dose = ["1" => "Daily", "2" => "Once a week", "3" => "Twice a week", "4" => "Stat", "5" => "SOS", "6" => "Alternate Day", "7" => "6 hourly", "8" => "8 hourly", "9" => "12 hourly", "10" => "24 hourly"];

                if (in_array($categoryId, [5, 6, 10, 13])) {
                    $anc = $this->ANC->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->where('patients_id', $pId)->first();
                    // $madicine_status = ["1" => "After Meal", "2" => "Empty Stomach", "3" => "Instead of menstruation space"];

                    if (!empty($anc)) {
                        // $madicineData = null;
                        // $url[] = url('get-anc-report?date='.$aptCreatedDate.'&patient_id='.encrypt($appointment->patients_id));
                        // if (!empty($anc->treatment)) {
                        //     $treatment = json_decode($anc->treatment, true);
                        //     unset($treatment["medicinedata"]);
                        //     $madicines = $treatment;
                        //     foreach ($madicines as $madicine) {
                        //         if (!empty($madicine['dose'])) {
                        //             $madicine['dose'] = $dose[$madicine['dose']];
                        //         }
                        //         if (!empty($madicine['medicine_status'])) {
                        //             $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                        //         }
                        //         if (isset($madicine['medicine_time']) && is_array($madicine['medicine_time'])) {
                        //             $medicineTimeData = !empty($madicine['medicine_time']) ? $madicine['medicine_time'] : [];
                        //             unset($madicine['medicine_time']);
                        //             foreach ($medicineTimeData as $key => $row) {
                        //                 $madicineTime[] = $medicineTime[$row];
                        //             }
                        //             $madicine['medicine_time'] = !empty($madicineTime) ? implode(',', $madicineTime) : null;
                        //         } else {
                        //             $madicine['medicine_time'] = !empty($madicine['medicine_time']) && isset($medicine_time[$madicine['medicine_time']]) ? $medicine_time[$madicine['medicine_time']] : null;
                        //         }

                        //         $madicineData[] = $madicine;
                        //     }
                        // }
                        // $reportsArr = null;
                        // if (!empty($anc->o_e)) {
                        //     $reportsArr = json_decode($anc->o_e, true);
                        // }
                        // $reportsData[] = $reportsArr;

                        $patient_id = encrypt($appointment->patients_id);
                        // $url = url('ancdata/'.$patient_id);
                        $url[] = url('get-anc-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id));
                    } else {
                        $ancHistory = $this->AncHistory->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->first();
                        if (!empty($ancHistory)) {
                            // $madicineData = null;
                            // if (!empty($anc->treatment)) {
                            //     $treatment = json_decode($ancHistory->treatment, true);
                            //     unset($treatment["medicinedata"]);
                            //     $madicines = $treatment;
                            //     foreach ($madicines as $madicine) {
                            //         if (!empty($madicine['dose'])) {
                            //             $madicine['dose'] = $dose[$madicine['dose']];
                            //         }
                            //         if (!empty($madicine['medicine_status'])) {
                            //             $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                            //         }
                            //         if (is_array($madicine['medicine_time'])) {
                            //             $medicineTimeData = !empty($madicine['medicine_time']) ? $madicine['medicine_time'] : [];
                            //             unset($madicine['medicine_time']);
                            //             foreach ($medicineTimeData as $key => $row) {
                            //                 $madicineTime[] = $medicineTime[$row];
                            //             }
                            //             $madicine['medicine_time'] = !empty($madicineTime) ? implode(',', $madicineTime) : null;
                            //         } else {
                            //             $madicine['medicine_time'] = !empty($madicine['medicine_time']) && isset($medicine_time[$madicine['medicine_time']]) ? $medicine_time[$madicine['medicine_time']] : null;
                            //         }
                            //         $madicineData[] = $madicine;
                            //     }
                            // }
                            $reportsArr = null;
                            if (!empty($anc->o_e)) {
                                $reportsArr = json_decode($anc->o_e, true);
                            }
                            // $reportsData[] = $reportsArr;
                            $patient_id = encrypt($appointment->patients_id);
                            // $url = url('anchistorydata/'.$patient_id.'/'.$aptCreatedDate);
                            $url[] = url('get-anc-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id));
                        } else {
                            // $madicineData = null;
                            $reportsData = null;
                            // $url=[];
                        }
                    }
                }
                if (in_array($categoryId, [3, 4])) {
                    $madicine_status = ["1" => "After Meal", "2" => "Empty Stomach", "3" => "Instead of menstruation space"];
                    $iui = $this->IUI->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->first();
                    if (!empty($iui)) {
                        $madicineData = null;
                        $url[] = url('get-iui-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id));
                        // if (!empty($iui->treatment)) {
                        //     $treatment = json_decode($iui->treatment, true);
                        //     unset($treatment["medicinedata"]);
                        //     $madicines = $treatment;
                        //     unset($treatment["medicine_time"]);
                        //     if (!empty($madicines)) {
                        //         foreach ($madicines as $madicine) {
                        //             $madicineTime = [];
                        //             if (!empty($madicine['dose'])) {
                        //                 $madicine['dose'] = $dose[$madicine['dose']];
                        //             }
                        //             if (!empty($madicine['medicine_status'])) {
                        //                 $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                        //             }
                        //             if (isset($madicine['medicine_time']) && is_array($madicine['medicine_time'])) {
                        //                 $medicineTimeData = !empty($madicine['medicine_time']) ? $madicine['medicine_time'] : [];
                        //                 unset($madicine['medicine_time']);
                        //                 foreach ($medicineTimeData as $key => $row) {
                        //                     $madicineTime[] = $medicineTime[$row];
                        //                 }
                        //                 $madicine['medicine_time'] = !empty($madicineTime) ? implode(',', $madicineTime) : null;
                        //             } else {
                        //                 $madicine['medicine_time'] = !empty($madicine['medicine_time']) && isset($medicine_time[$madicine['medicine_time']]) ? $medicine_time[$madicine['medicine_time']] : null;
                        //             }
                        //             $madicineData[] = $madicine;
                        //         }
                        //     }
                        // }
                        // $reportsArr = null;
                        // if (!empty($iui->o_e)) {
                        //     $reportsArr = json_decode($iui->o_e, true);
                        // }
                        // $reportsData[] = $reportsArr;
                    }
                    // else{
                    $iuiHistory = $this->IuiHistory->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->first();
                    if (!empty($iuiHistory)) {
                        // $madicineData = null;
                        // if (!empty($iui->treatment)) {
                        //     $treatment = json_decode($iuiHistory->treatment, true);
                        //     unset($treatment["medicinedata"]);
                        //     $madicines = $treatment;
                        //     if (!empty($madicines)) {
                        //         foreach ($madicines as $madicine) {
                        //             if (!empty($madicine['dose'])) {
                        //                 $madicine['dose'] = $dose[$madicine['dose']];
                        //             }
                        //             if (!empty($madicine['medicine_status'])) {
                        //                 $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                        //             }
                        //             if (isset($madicine['medicine_time']) && is_array($madicine['medicine_time'])) {
                        //                 $medicineTimeData = !empty($madicine['medicine_time']) ? $madicine['medicine_time'] : [];
                        //                 unset($madicine['medicine_time']);
                        //                 foreach ($medicineTimeData as $key => $row) {
                        //                     $madicineTime[] = $medicineTime[$row];
                        //                 }
                        //                 $madicine['medicine_time'] = !empty($madicineTime) ? implode(',', $madicineTime) : null;
                        //             } else {
                        //                 $madicine['medicine_time'] = !empty($madicine['medicine_time']) && isset($medicine_time[$madicine['medicine_time']]) ? $medicine_time[$madicine['medicine_time']] : null;
                        //             }
                        //             $madicineData[] = $madicine;
                        //         }
                        //     }
                        // }
                        // $reportsArr = null;
                        // if (!empty($iui->o_e)) {
                        //     $reportsArr = json_decode($iui->o_e, true);
                        // }
                        // $reportsData[] = $reportsArr;
                        $url[] = url('get-iui-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id) . '&is_history=1');
                    }
                    $iuiReport = $this->IUIReport->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->first();
                    if (!empty($iuiReport)) {
                        $url[] = url('get-iui-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id) . '&is_iuiReport=1');
                    }
                    $iuiExtraVisit = $this->IuiExtraVisit->where('patient_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->first();
                    if (!empty($iuiExtraVisit)) {
                        $url[] = url('get-iui-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id) . '&is_extraVisit=1');
                    } else {
                        // $url = [];
                        $madicineData = null;
                        $reportsData = null;
                    }
                    // }
                }
                if (in_array($categoryId, [1, 2])) {
                    $ivf = $this->IVF->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->first();
                    $madicine_status = ["1" => "After Meal", "2" => "Empty Stomach", "3" => "Instead of menstruation space"];

                    if (!empty($ivf)) {
                        // $madicineData = null;
                        // if (!empty($ivf->treatment)) {
                        //     $treatment = json_decode($ivf->treatment, true);
                        //     unset($treatment["medicinedata"]);
                        //     $madicines = $treatment;
                        //     if (!empty($madicines)) {
                        //         foreach ($madicines as $madicine) {
                        //             if (!empty($madicine['dose'])) {
                        //                 $madicine['dose'] = $dose[$madicine['dose']];
                        //             }
                        //             if (!empty($madicine['medicine_status'])) {
                        //                 $madicine['medicine_status'] = $madicine_status[$madicine['medicine_status']];
                        //             }
                        //             $madicineData[] = $madicine;
                        //         }
                        //     }
                        // }
                        // if (!empty($ivf->o_e)) {
                        //     $reportsArr = json_decode($ivf->o_e, true);
                        // }
                        $url[] = url('get-ivf-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id));
                        // $reportsData[] = $reportsArr;
                    }
                    // else {
                    //PickUp report
                    $ivfPlanReport = $this->IvfPlanReport->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->first();
                    if ($ivfPlanReport) {
                        $url[] = url('get-ivf-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id) . '&is_history=1&is_pickup=1');
                    }
                    $ivfAllHistory = $this->IvfHistory->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->get();
                    if (!empty($ivfAllHistory)) {
                        foreach ($ivfAllHistory as $ivfHistory) {
                            $madicineData = null;
                            $reportsArr = null;
                            $reportsData[] = $reportsArr;
                            $historyData = json_decode($ivfHistory->description);
                            $collectionData = !empty($historyData->collection) ? $historyData->collection : [];
                            //Transfer Report
                            if (in_array('transfer', $collectionData)) {
                                $url[] = url('get-ivf-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id) . '&is_history=1&is_trasnfer=1');
                            } else {
                                $url[] = url('get-ivf-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id) . '&is_history=1&cycle_no=' . encrypt($ivfHistory->cycle_no));
                            }
                        }
                    }

                    $ivfExtraVisit = $this->IvfExtraVisit->where('patient_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->first();
                    if (!empty($ivfExtraVisit)) {
                        $url[] = url('get-ivf-report?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id) . '&is_extraVisit=1');
                    } else {
                        $madicineData = null;
                        $reportsData = null;
                    }
                    // }
                }
                if (in_array($categoryId, [17, 18])) {
                    $gynec = $this->Gynec->where('patients_id', $appointment->patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"), $aptCreatedDate)->where('patients_id', $pId)->first();
                    $madicine_status = ["1" => "After Meal", "2" => "Empty Stomach", "3" => "Instead of menstruation space"];

                    if (!empty($gynec)) {
                        $madicineData = null;

                        $patient_id = encrypt($appointment->patients_id);
                        // $url = url('ancdata/'.$patient_id);
                        $url[] = url('get-gynec-details?date=' . $aptCreatedDate . '&patient_id=' . encrypt($appointment->patients_id));
                    }
                }
            }
            return  $url;
        }
    }

    /**
     *Get Appointment Patient Details
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function appointmentpatientDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }
        $token = $request->header('Authorization');
        $UserData = $this->UserToken->where('token', $token)->first();
        $patients_id = isset($request->patient_id) ? $request->patient_id : '';

        if ($token && $UserData) {
            $appointment = $this->Appointment->select('id', 'patients_id', 'category_id', 'date', 'time', 'remark')->where('patients_id', $patients_id)->latest()->take(1)->first();
            $appointment->profile_picture = $appointment->getPatientsDetails['profile_picture'];
            $appointment->patient_name = $appointment->getPatientsDetails['name'];
            $appointment->mobile_number = $appointment->getPatientsDetails['mobile_number'];
            $appointment->date_of_birth = $appointment->getPatientsDetails['dob'];
            $appointment->last_visit = $appointment->lastAppointmentData['date'];
            $appointment->age = $appointment->getPatientsDetails['age'];
            $appointment->weight = $appointment->getPatientsDetails['weight'];
            $appointment->category = $appointment->categoryDetails['name'];
            unset($appointment->categoryDetails, $appointment->getPatientsDetails, $appointment->lastAppointmentData);

            return $this->sendResponse('Your Appointment Patient Details successfully get', $appointment);
        }
        return $this->sendError(__('auth.failed'), 401);
    }
}