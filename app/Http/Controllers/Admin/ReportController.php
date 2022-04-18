<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use App\Models\AppointmentCharges;
use Carbon\Carbon;
use App\Models\Category;
use Exception;
use Validator;
use View;
use Log;
use DB;
use Excel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReportController extends AdminController
{
    /**
    * Return on report index
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        try{
            if ($request->ajax()) {
                $report = $this->AppointmentCharges->select("*",
                    \DB::raw('(CASE
                                    WHEN payment_mode = "1" THEN "Swipe"
                                    WHEN payment_mode = "2" THEN "Cash"
                                    WHEN payment_mode = "3" THEN "Cheque"
                                    WHEN payment_mode = "4" THEN "UPI"
                                    WHEN payment_mode = "5" THEN "NEFT"
                                    END) AS payment_mode'))->orderBy('id', 'desc');
                $search = $request->report_days;
                $date = '';
                if ($search) {
                    if($search==1)
                    {
                        $date = \Carbon\Carbon::now()->isoFormat('Do MMM Y, dddd');
                        $report = $report->WhereHas('getAppointment', function ($query) use ($search) {
                            $query->whereDay('date','=',\Carbon\Carbon::now()->format('d'));
                        });
                    }elseif($search==2)
                    {
                        $date = \Carbon\Carbon::now()->isoFormat('MMMM, G');
                        $report = $report->WhereHas('getAppointment', function ($query) use ($search) {
                            $query->whereMonth('date','=',\Carbon\Carbon::now()->format('m'));
                        });
                    }elseif($search==3)
                    {
                        $date = 'Year ' . \Carbon\Carbon::now()->isoFormat('G');
                        $report = $report->WhereHas('getAppointment', function ($query) use ($search) {
                            $query->whereYear('date','=',\Carbon\Carbon::now()->format('Y'));
                        });
                    }
                } else {
                    $fromdate = $request->fromdate;
                    $todate = $request->todate;

                    $date = carbon::parse($fromdate)->isoFormat('Do MMM Y, dddd');

                    if($fromdate!=$todate)
                    {
                        $date = carbon::parse($fromdate)->isoFormat('Do MMM Y, dddd').' - '.carbon::parse($todate)->isoFormat('Do MMM Y, dddd');
                    }

                    if ($fromdate || $todate) {
                        $report = $report->WhereHas('getAppointment', function ($query) use ($fromdate, $todate) {
                            $query->whereBetween('date', [$fromdate, $todate]);
                        });
                    }
                }
                $netAmountCount = $report->sum('netamount');
                if($request->isprint==1){
                    $report = $report->get();
                    return response()->json([
                        View::make('admin.report.preview', compact('report','date', 'netAmountCount'))->render()
                    ]);
                }

                $report = $report->paginate(100);
                return response()->json([
                    View::make('admin.report.data', compact('report', 'netAmountCount'))->render()
                ]);
            }
            return view('admin.report.index');
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on report category index
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getCategoryReport(Request $request)
    {
        try{
            $doctor = $this->getDoctor();
            $hospitalDoctor = $doctor['hospitalDoctor'];
            $category = $this->Category->pluck('name','id');
            $reportDatails = [];
            $reportDatails['category'] = '';
            $reportDatails['doctor'] = '';
            $allCategoryCount = [];
            if($request->ajax()){


                $categoryReport = $this->AppointmentCharges->orderBy('id','DESC');

                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $categoryReport = $categoryReport->WhereHas('getAppointment', function ($query) use ($fromdate, $todate) {
                        $query->whereBetween('date', [$fromdate, $todate]);
                    });
                    if($request->reportType == 2)
                    {
                        $hospitalDoctorId = $request->hospital_doctor_id;
                        $allCategoryCount = $this->AppointmentCharges
                                        ->leftJoin('appointments','appointment_charges.appointment_id','appointments.id')
                                        ->leftJoin('patients','appointments.patients_id','patients.id')
                                        ->leftJoin('category','appointments.category_id','category.id')
                                        ->whereBetween('appointments.date',[$fromdate, $todate])
                                        ->WhereNull('appointments.deleted_at')
                                        ->select('appointment_charges.*','appointments.category_id','category.name as category_name',DB::raw('count(appointments.category_id) as totalAppointment'))
                                        ->groupBy('appointments.category_id');
                        if($hospitalDoctorId)
                        {
                            $allCategoryCount = $allCategoryCount->where(function($query) use($hospitalDoctorId) {
                                $query->whereHas('getAppointment.getPatientsDetails', function($query) use($hospitalDoctorId) {
                                    $query->where('hospital_doctor_id', $hospitalDoctorId);
                                });
                            });
                        }
                        $allCategoryCount = $allCategoryCount->get();
                    }
                }
                $categoryId = $request->categoryId;
                if($categoryId){
                    $reportDatails['category'] = $this->Category->where('id',$categoryId)->value('name');
                    $categoryReport = $categoryReport->WhereHas('getAppointment', function ($query) use ($categoryId) {
                        $query->where('category_id', $categoryId);
                    });
                }

                $hospitalDoctorId = $request->hospital_doctor_id;
                if($hospitalDoctorId){
                    $reportDatails['doctor'] =  $this->User->where('id',$hospitalDoctorId)->value('name');
                    $categoryReport = $categoryReport->WhereHas('getAppointment.getPatientsDetails', function($query) use($hospitalDoctorId){
                        $query->where('hospital_doctor_id',$hospitalDoctorId);
                    });
                }

                if($request->isprint==1){
                    $categoryReport = $categoryReport->get();
                    $reportDatails['total'] = $categoryReport->sum('netamount');
                    $reportDatails['count'] = $categoryReport->count();
                    $reportDatails['allCategoryCount'] = $allCategoryCount;
                    $reportDatails['type'] = $request->reportType;
                    return response()->json([
                        View::make('admin.report.category.preview', compact('categoryReport','reportDatails','category'))->render()
                    ]);
                }

                $reportDatails['total'] = $categoryReport->sum('netamount');
                $reportDatails['count'] = $categoryReport->count();
                $reportDatails['allCategoryCount'] = $allCategoryCount;
                $categoryReport = $categoryReport->paginate(100);
                $reportDatails['type'] = $request->reportType;
                return response()->json([
                    View::make('admin.report.category.data',compact('categoryReport','reportDatails','category'))->render()
                ]);
            }
            return view('admin.report.category.index',compact('category','hospitalDoctor'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }


    /**
    * Return on report index
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function referenceDoctorProReport(Request $request){
        $infertilityReport = null;
        $referenceDoctorPro = $this->ReferenceDoctorPro->pluck('name','id');
        $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
        $appointment = $this->getPatients();
        // $OpdPatients_category = $this->OpdPatients_category;
        // $appointment = $this->OpdPatients->whereNotIn('reference_doctor_id',[3,500,90,107,452,471,489,1,32,387,30]);
        // $patients = $this->Appointment->whereNotIn('category_id',[2,4,6,9,11,13,18,25])->where('is_procedure',0);

        $patients = $this->Appointment->rightJoin('patients','patients.id','=','appointments.patients_id')->whereNotIn('patients.reference_doctor_id',[1,"null"])->
        whereNotIn('appointments.category_id',[2,4,6,9,11,13,18,25]);
        if($request->ajax()){

            $patientId = $request->patient_id;
            if($patientId) {
                $patients = $patients->where(function($query) use($patientId) {
                    $query
                        ->orWhereHas('getPatientsDetails', function($query) use($patientId) {
                            $query->where('id', $patientId);
                        });
                });
            }

            $refProId = $request->ref_pro_doctorid;
            if($refProId){
                $patients= $patients->where('patients.reference_doctor_pro_id',$refProId);
            }
            $refDocId = $request->ref_doc_id;
            if($refDocId){
                $patients= $patients->where('patients.reference_doctor_id',$refDocId);
            }

            $iuiReport = $this->IndoorDeposit
                ->whereNotIn('reference_doctor_id', [1,12,32])
                ->where([
                    ['charge_type', '=', 3],
                    ['case_type', '=', 'Credit'],
                ])
                ->orderBy('id', 'DESC');

            $fromdate = $request->fromdate;
            $todate = $request->todate;
            if($fromdate || $todate){
                $fromdate = $fromdate;
                $todate = $todate;
                $patients = $patients->whereBetween(\DB::raw('DATE(patients.created_at)'), [$fromdate, $todate]);
                $iuiReport = $iuiReport->whereBetween('patients.date', [$fromdate . ' 00:00:00', $todate. ' 23:59:59']);
            }

            if($request->isprint==1){
                $patients = $patients->get();
                return response()->json([
                    View::make('admin.report.ref_pro_doctor.preview',compact('patients'))->render()
                ]);
            }
            $patients = $patients->paginate(50);
            $data['status'] = 1;
            $data['report_data'] = View::make('admin.report.ref_pro_doctor.data',compact('patients','referenceDoctorPro','referenceDoctor'))->render();
            return $data;
        }
        return view('admin.report.ref_pro_doctor.index',compact('patients','appointment','referenceDoctorPro','referenceDoctor'));
    }

    /**
    * Return on cut report index
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getCutReport(Request $request)
    {
        try{
            $doctor = collect($this->ReferenceDoctor->whereNotIn('id', [1, 3, 12, 32])->pluck('name', 'id'))
                ->map(function ($query) {
                    $query = ucwords(strtolower($query));
                    return $query;
                });
            $reportDatails = [];
            $reportDatails['doctor'] = '';
            if($request->ajax()){
                $cutReport = $this->AppointmentCharges->where(function($query) {
                    $query->whereHas('getAppointment.getPatientsDetails', function($query) {
                        $query->whereNotIn('reference_doctor_id', [1, 3, 12, 32]);
                    });
                })
                    ->orderBy('id','DESC');

                $iuiReport = $this->IndoorDeposit
                    ->whereNotIn('reference_doctor_id', [1, 3, 12, 32])
                    ->where([
                        ['charge_type', '=', 3],
                        ['case_type', '=', 'Credit'],
                    ])
                    ->orderBy('id', 'DESC');
                $indoorBook = $this->IndoorBook->with('getInvoice')->where(function($query) {
                    $query->whereHas('getPatientsDetails', function($query) {
                        $query->whereNotIn('reference_doctor_id', [1, 3, 12, 32]);
                    });
                })->where('is_final_invoice',1)->whereNotNull('final_invoice_date')->orderBy('id','DESC');

                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $cutReport = $cutReport->whereHas('getAppointment', function ($query) use ($fromdate, $todate) {
                        $query->whereBetween('date', [$fromdate, $todate]);
                    });
                    $iuiReport = $iuiReport->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate. ' 23:59:59']);
                    $indoorBook = $indoorBook->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate. ' 23:59:59']);

                }

                $referenceDoctorId = $request->reference_doctor_id;

                if($referenceDoctorId){
                    $reportDatails['doctor'] =  $this->ReferenceDoctor->where('id',$referenceDoctorId)->value('name');
                    $cutReport = $cutReport->where(function($query) use ($referenceDoctorId) {
                        $query->whereHas('getAppointment.getPatientsDetails', function($query)  use ($referenceDoctorId) {
                            $query->where('reference_doctor_id', $referenceDoctorId);
                        });
                    });
                    $iuiReport = $iuiReport->where('reference_doctor_id', $referenceDoctorId);
                    $indoorBook = $indoorBook->where(function($query) use ($referenceDoctorId) {
                        $query->whereHas('getPatientsDetails', function($query)  use ($referenceDoctorId) {
                            $query->where('reference_doctor_id', $referenceDoctorId);
                        });
                    });
                }

                $cutReport = collect($cutReport->get())
                    ->map(function ($query) {
                        $query->reference_doctor_id = $query->getAppointment->getPatientsDetails['reference_doctor_id'];
                        $query->date = $query->getAppointment['date'];
                        $query->reference_doctor_name = $query->getAppointment->getPatientsDetails->getReferenceDoctor['name'];
                        return $query;
                    });
                $iuiReport = collect($iuiReport->get())
                    ->map(function ($query) {
                        $query->reference_doctor_name = $query->getReferenceDoctors['name'];
                        $query->date = $query->created_at;
                        return $query;
                    });
                $indoorBook = collect($indoorBook->get())
                    ->map(function ($query) {
                        $query->reference_doctor_id = $query->getPatientsDetails['reference_doctor_id'];
                        $query->reference_doctor_name = $query->getPatientsDetails->getReferenceDoctor['name'];
                        $procedureName = implode(', ', $this->IndoorProcedure
                            ->whereIn('id', explode(',', $query->procedure_id))
                            ->pluck('name')
                            ->toArray());
                        $query->procedure_name = $procedureName;
                        $query->date = $query->final_invoice_date;
                        return $query;
                    });
                $cutReport = collect($cutReport)->merge($iuiReport);
                $cutReport = $cutReport->merge($indoorBook);
                $cutReport = $cutReport->sortby('date');
                // $cutReport = $cutReport->groupBy('getReferenceDoctors.name');
                $cutReport = $cutReport->groupBy('reference_doctor_name');
                if($request->isprint==1){

                    return response()->json([
                        View::make('admin.report.cut.preview', compact('cutReport'))->render()
                    ]);
                }

                return response()->json([
                    View::make('admin.report.cut.data',compact('cutReport'))->render()
                ]);
            }
            return view('admin.report.cut.index',compact('doctor'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    /**
    * Get refernce doctor report
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getRefDoctorReport(Request $request)
    {
        try{
            $doctor = collect($this->ReferenceDoctor->whereNotIn('id', [1, 12, 32])->pluck('name', 'id'))
                ->map(function ($query) {
                    $query = ucwords(strtolower($query));
                    return $query;
                });

            $reportDatails = [];
            $reportDatails['doctor'] = '';
            $category = $this->Category->pluck('name','id');
            $charge_type = !empty($request->type) ? $request->type : null;
            if($request->ajax()){

                // $refDoctorReport = $this->AppointmentCharges->where(function($query) {
                //     $query->whereHas('getAppointment.getPatientsDetails', function($query) {
                //         $query->whereNotIn('reference_doctor_id', [1,12,32]);
                //     });
                // })
                //     ->orderBy('id', 'DESC');


                //OPD
                //charge type 5 and 6 is for  new patients category and old patients category
                if(empty($charge_type) || $charge_type == 1 || $charge_type == 5 || $charge_type == 6)
                {

                    $refDoctorReport = $this->AppointmentCharges->where(function($query) {
                        $query->whereHas('getAppointment.getPatientsDetails', function($query) {
                            $query->whereNotIn('reference_doctor_id', [1,12,32]);
                        });
                    })
                        ->orderBy('id', 'DESC');
                    // $iuiReport = $this->IndoorDeposit
                    // ->whereNotIn('reference_doctor_id', [1,12,32])
                    // ->whereIn('charge_type', [1,2,3])
                    // ->where([
                    //     ['case_type', '=', 'Credit'],
                    // ])
                    // ->orderBy('id', 'DESC');
                }
                //INDOOR
                if(!empty($charge_type) && $charge_type == 4)
                {
                    $refDoctorReport = $this->IndoorBook
                    ->whereIsFinalInvoice(1)
                    ->whereNotNull('final_invoice_date')
                    ->with([
                        'getInvoice',
                        'getPatientsDetails'
                    ])->where(function($query) {
                        $query->whereHas('getPatientsDetails', function($query) {
                            $query->whereNotIn('reference_doctor_id', [1,12,32]);
                        });
                    })
                    ->orderBy('id', 'DESC');
                }
                // //IPD(invoice)
                // if(!empty($charge_type) && $charge_type == 2)
                // {

                //     $iuiReport = $this->IndoorBook
                //     ->whereIsFinalInvoice(1)
                //     ->whereNotNull('final_invoice_date')
                //     ->with([
                //         'getInvoice',
                //         'getPatientsDetails'
                //     ])
                //     ->orderBy('id', 'DESC');
                // }

                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $fromdate = $fromdate;
                    $todate = $todate;
                    $refDoctorReport = $refDoctorReport->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    // $iuiReport = $iuiReport->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate. ' 23:59:59']);
                }

                $categoryId = !empty($request->categoryId) ? [$request->categoryId] : [];
                if(!empty($charge_type) && $charge_type == 5)//new category patient
                {

                    $categoryId = [1,3,5,8,10];
                }
                if(!empty($charge_type) && $charge_type == 6)//oldcategory patient
                {

                    $categoryId = [2,4,6,9,13];
                }

                if(count($categoryId) > 0){
                    $reportDatails['category'] = $this->Category->where('id',$categoryId)->value('name');
                    $refDoctorReport =$refDoctorReport->WhereHas('getAppointment', function ($query) use ($categoryId) {
                        $query->whereIn('category_id', $categoryId);
                    });
                }
                $categoryReport = $refDoctorReport->get();

                $referenceDoctorId = $request->reference_doctor_id;

                if($referenceDoctorId)
                {//opd
                    
                    if($charge_type == 4) // IPD
                    {
                        $refDoctorReport = $refDoctorReport->where(function($query) use ($referenceDoctorId) {
                            $query->whereHas('getPatientsDetails', function($query)  use ($referenceDoctorId) {
                                $query->where('reference_doctor_id', $referenceDoctorId);
                            });
                        });
                    }
                    else
                    {
                        $refDoctorReport = $refDoctorReport->where(function($query) use ($referenceDoctorId) {
                            $query->whereHas('getAppointment.getPatientsDetails', function($query)  use ($referenceDoctorId) {
                                $query->where('reference_doctor_id', $referenceDoctorId);
                            });
                        });
                    }
                }
                if($charge_type == 4) // IPD
                {
                    $refDoctorReport = collect($refDoctorReport->get())
                    ->map(function ($query) {
                        $query->reference_doctor_id = $query->getPatientsDetails['reference_doctor_id'];
                        $query->reference_doctor_name = $query->getPatientsDetails->getReferenceDoctor['name'];
                        $procedureName = implode(', ', $this->IndoorProcedure
                            ->whereIn('id', explode(',', $query->procedure_id))
                            ->pluck('name')
                            ->toArray());
                        $query->procedure_name = $procedureName;
                        return $query;
                    });
                }
                else//opd
                {
                    $refDoctorReport = collect($refDoctorReport->get())
                    ->map(function ($query) {
                        $query->reference_doctor_id = $query->getAppointment->getPatientsDetails['reference_doctor_id'];
                        $query->reference_doctor_name = $query->getAppointment->getPatientsDetails->getReferenceDoctor['name'];
                        return $query;
                    });
                }
                $refDoctorReport = $refDoctorReport->groupBy('reference_doctor_name');
                if($request->isprint==1){
                    return response()->json([
                        View::make('admin.report.refdoctor.preview', compact('refDoctorReport','reportDatails'))->render()
                    ]);
                }

                $data['status'] = 1;
                $data['report_data'] = View::make('admin.report.refdoctor.data',compact('refDoctorReport','reportDatails','categoryReport'))->render();
                return $data;

            }
            return view('admin.report.refdoctor.index',compact('doctor','category'));
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    /**
    * Return on New collection report with filtering
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getCollectionReport(Request $request)
    {
        try{
            $doctor = $this->getDoctor();
            $referenceDoctor = $doctor['referenceDoctor'];
            $reportDatails = [];
            $reportDatails['doctor'] = '';
            if($request->ajax() || $request->isexport == 1){
                $paymentType = $request->payment_type;
                $collectionReport = $this->AppointmentCharges->orderBy('id','DESC');

                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $fromdate = carbon::parse($fromdate)->format('Y-m-d');
                    $todate = carbon::parse($todate)->format('Y-m-d');
                    $collectionReport = $collectionReport->WhereHas('getAppointment', function ($query) use ($fromdate, $todate) {
                        $query->whereBetween('date', [$fromdate, $todate]);
                    });
                }

                $referenceDoctorId = $request->reference_doctor_id;
                if($referenceDoctorId){
                    $reportDatails['doctor'] =  $this->ReferenceDoctor->where('id',$referenceDoctorId)->value('name');
                    $collectionReport = $collectionReport->WhereHas('getAppointment.getPatientsDetails', function($query) use($referenceDoctorId){
                        $query->where('reference_doctor_id',$referenceDoctorId);
                    });
                }
                // USG
                $usg = $this->Appointment->whereHas('getAppointmentCharges',function($q) use($paymentType){
                    $q->where('usg', '!=', null)
                        ->where('payment_mode',$paymentType);
                })->orderBy('id', 'desc');
                // Hormon
                $hormon = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->where([['charge_type', '=', 1],['case_type', '=', 'Credit'],['payment_type', '=', $paymentType]])->orderBy('id', 'desc');
                // IUI
                $iui = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->where([['charge_type', '=', 3],['case_type', '=', 'Credit'],['payment_type', '=', $paymentType]])->orderBy('id', 'desc');
                // IVF
                $ivf = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->where([['charge_type', '=', 2],['case_type', '=', 'Credit'],['payment_type', '=', $paymentType]])->orderBy('id', 'desc');
                // Main Collection Data Card
                $mainDataCash = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) use($paymentType) {$query->where([
                        ['payment_mode', '=', $paymentType],
                        ['category_id', '!=', 7],
                    ])
                        ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);})
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');

                $mainDataCard = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {
                        $query->where([
                            ['payment_mode', '=', 1],
                            ['category_id', '!=', 7],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                    })
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                $mainDataCheque = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {
                        $query->where([
                            ['payment_mode', '=', 3],
                            ['category_id', '!=', 7],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                    })
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                $mainDataUPI = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {
                        $query->where([
                            ['payment_mode', '=', 4],
                            ['category_id', '!=', 7],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                    })
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                $mainDataNEFT = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {
                        $query->where([
                            ['payment_mode', '=', 5],
                            ['category_id', '!=', 7],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                    })
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');

                if($fromdate || $todate){
                    $usg = $usg->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $hormon = $hormon->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $iui = $iui->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $ivf = $ivf->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);

                    $mainDataCash = $mainDataCash->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataCard = $mainDataCard->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataCheque = $mainDataCheque->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataCheque = $mainDataCheque->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataUPI = $mainDataUPI->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataNEFT = $mainDataNEFT->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                }

                $mainDataCash  = $mainDataCash->get();
                $mainDataCash = $mainDataCash->groupBy('category_id')->toArray();

                // $ivfCash = (!empty($mainDataCash[1]) ? $mainDataCash[1] : []) + (!empty($mainDataCash[2]) ? $mainDataCash[2] : []);
                // $iuiCash = (!empty($mainDataCash[3]) ? $mainDataCash[3] : []) + (!empty($mainDataCash[4]) ? $mainDataCash[4] : []);
                // $ancCash = (!empty($mainDataCash[5]) ? $mainDataCash[5] : []) + (!empty($mainDataCash[6]) ? $mainDataCash[6] : []);
                $ivfCash = array_merge(!empty($mainDataCash[1]) ? $mainDataCash[1] : [],!empty($mainDataCash[2]) ? $mainDataCash[2] : []);
                $iuiCash = array_merge(!empty($mainDataCash[3]) ? $mainDataCash[3] : [],!empty($mainDataCash[4]) ? $mainDataCash[4] : []);
                $ancCash = array_merge(!empty($mainDataCash[5]) ? $mainDataCash[5] : [],!empty($mainDataCash[6]) ? $mainDataCash[6] : []);
                $gynecCash = array_merge(!empty($mainDataCash[17]) ? $mainDataCash[17] : [],!empty($mainDataCash[18]) ? $mainDataCash[18] : []);
                // $newOldCash = array_merge(!empty($mainDataCash[8]) ? $mainDataCash[8] : [],!empty($mainDataCash[9]) ? $mainDataCash[9] : []);
                $mainDataCard  = $mainDataCard->get();
                $mainDataCard = $mainDataCard->groupBy('category_id')->toArray();
                // $ivfCard = (!empty($mainDataCard[1]) ? $mainDataCard[1] : []) + (!empty($mainDataCard[2]) ? $mainDataCard[2] : []);
                // $iuiCard = (!empty($mainDataCard[3]) ? $mainDataCard[3] : []) + (!empty($mainDataCard[4]) ? $mainDataCard[4] : []);
                // $ancCard = (!empty($mainDataCard[5]) ? $mainDataCard[5] : []) + (!empty($mainDataCard[6]) ? $mainDataCard[6] : []);
                $ivfCard = array_merge(!empty($mainDataCard[1]) ? $mainDataCard[1] : [],!empty($mainDataCard[2]) ? $mainDataCard[2] : []);
                $iuiCard = array_merge(!empty($mainDataCard[3]) ? $mainDataCard[3] : [],!empty($mainDataCard[4]) ? $mainDataCard[4] : []);
                $ancCard = array_merge(!empty($mainDataCard[5]) ? $mainDataCard[5] : [],!empty($mainDataCard[6]) ? $mainDataCard[6] : []);
                $gynecCard = array_merge(!empty($mainDataCard[17]) ? $mainDataCard[17] : [],!empty($mainDataCard[18]) ? $mainDataCard[18] : []);
                // $newOldCard = array_merge(!empty($mainDataCard[8]) ? $mainDataCard[8] : [],!empty($mainDataCard[9]) ? $mainDataCard[9] : []);

                $mainDataCheque  = $mainDataCheque->get();
                $mainDataCheque = $mainDataCheque->groupBy('category_id')->toArray();

                $mainDataUPI  = $mainDataUPI->get();
                $mainDataUPI = $mainDataUPI->groupBy('category_id')->toArray();

                $mainDataNEFT  = $mainDataNEFT->get();
                $mainDataNEFT = $mainDataNEFT->groupBy('category_id')->toArray();

                // Cheque
                $ivfCheque = array_merge(!empty($mainDataCheque[1]) ? $mainDataCheque[1] : [],!empty($mainDataCheque[2]) ? $mainDataCheque[2] : []);
                $iuiCheque = array_merge(!empty($mainDataCheque[3]) ? $mainDataCheque[3] : [],!empty($mainDataCheque[4]) ? $mainDataCheque[4] : []);
                $ancCheque = array_merge(!empty($mainDataCheque[5]) ? $mainDataCheque[5] : [],!empty($mainDataCheque[6]) ? $mainDataCheque[6] : []);
                $gynecCheque = array_merge(!empty($mainDataCheque[17]) ? $mainDataCheque[17] : [],!empty($mainDataCheque[18]) ? $mainDataCheque[18] : []);

                // UPI
                $ivfUPI = array_merge(!empty($mainDataUPI[1]) ? $mainDataUPI[1] : [],!empty($mainDataUPI[2]) ? $mainDataUPI[2] : []);
                $iuiUPI = array_merge(!empty($mainDataUPI[3]) ? $mainDataUPI[3] : [],!empty($mainDataUPI[4]) ? $mainDataUPI[4] : []);
                $ancUPI = array_merge(!empty($mainDataUPI[5]) ? $mainDataUPI[5] : [],!empty($mainDataUPI[6]) ? $mainDataUPI[6] : []);
                $gynecUPI = array_merge(!empty($mainDataUPI[17]) ? $mainDataUPI[17] : [],!empty($mainDataUPI[18]) ? $mainDataUPI[18] : []);

                // NEFT
                $ivfNEFT = array_merge(!empty($mainDataNEFT[1]) ? $mainDataNEFT[1] : [],!empty($mainDataNEFT[2]) ? $mainDataNEFT[2] : []);
                $iuiNEFT = array_merge(!empty($mainDataNEFT[3]) ? $mainDataNEFT[3] : [],!empty($mainDataNEFT[4]) ? $mainDataNEFT[4] : []);
                $ancNEFT = array_merge(!empty($mainDataNEFT[5]) ? $mainDataNEFT[5] : [],!empty($mainDataNEFT[6]) ? $mainDataNEFT[6] : []);
                $gynecNEFT = array_merge(!empty($mainDataNEFT[17]) ? $mainDataNEFT[17] : [],!empty($mainDataNEFT[18]) ? $mainDataNEFT[18] : []);


                $indoor = $this->IndoorBook
                    ->whereIsFinalInvoice(1)
                    ->whereNotNull('final_invoice_date')
                    ->with([
                        'getInvoice',
                        'getPatientsDetails'
                    ])
                    ->orderBy('id', 'DESC');

                $indoorCaseDeposit = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType($paymentType)->with('getPatients')->orderBy('id', 'DESC');
                $indoorCardDeposit = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType('1')->with('getPatients')->orderBy('id', 'DESC');
                $indoorChequeDeposit = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType('3')->with('getPatients')->orderBy('id', 'DESC');
                $indoorUPIDeposit = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType('4')->with('getPatients')->orderBy('id', 'DESC');
                $indoorNEFTDeposit = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType('5')->with('getPatients')->orderBy('id', 'DESC');
                $indoorDebit = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereCaseTypeAndChargeType('Debit', 4)->with('getPatients')->orderBy('id', 'DESC');

                if($fromdate || $todate) {
                    $indoor = $indoor->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorCaseDeposit = $indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorCardDeposit = $indoorCardDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorChequeDeposit = $indoorChequeDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorUPIDeposit = $indoorUPIDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorNEFTDeposit = $indoorNEFTDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorDebit = $indoorDebit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                }
                $indoorCaseDeposit = $indoorCaseDeposit->get()->toArray();
                $indoorCardDeposit = $indoorCardDeposit->get()->toArray();
                $indoorChequeDeposit = $indoorChequeDeposit->get()->toArray();
                $indoorUPIDeposit = $indoorUPIDeposit->get()->toArray();
                $indoorNEFTDeposit = $indoorNEFTDeposit->get()->toArray();
                $indoorDebit = $indoorDebit->get()->toArray();
                $indoor = $indoor->get()->toArray();

                $indoorCash = collect($indoor)->where('get_invoice.payment_mode', '=', $paymentType)->all();
                $indoorCash = array_slice($indoorCash, 0);

                $indoorCheque = collect($indoor)->where('get_invoice.payment_mode', '=', 3)->all();
                $indoorCheque = array_slice($indoorCheque, 0);

                $indoorUPI = collect($indoor)->where('get_invoice.payment_mode', '=', 4)->all();
                $indoorUPI = array_slice($indoorUPI, 0);

                $indoorNEFT = collect($indoor)->where('get_invoice.payment_mode', 5)->all();
                $indoorNEFT = array_slice($indoorNEFT, 0);

                $indoorCard = collect($indoor)->where('get_invoice.payment_mode', 1)->all();
                $indoorCard = array_slice($indoorCard, 0);

                $procedures = $this->IndoorProcedure->select('id', 'name')->get()->toArray();
                $paymentMethodValueData = [1=>2,2=>1,3=>3,4=>4,5=>5];
                $incomePaymentType = $paymentMethodValueData[$paymentType];
                $incomeCategory = $this->ExpenseCategory->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereType('1')->whereStatus('1')->pluck('id','id');
                
                $income = $this->IncomeManager->whereIn('income_category',$incomeCategory)->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                )
                    ->orderBy('id', 'desc');
                
                // expense
                $expenceCategory = $this->ExpenseCategory->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereType('2')->whereStatus('1')->pluck('id','id');
                $expense = $this->ExpenseManager->whereIn('expense_category',$expenceCategory)->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                )
                    ->orderBy('id', 'desc');

                //pediatric Total income and expense
                $category = $this->ExpenseCategory->where('is_pediatric',1)->whereType('1')->whereStatus('1')->pluck('id','id');
                $pediatric_income = $this->IncomeManager->whereIn('income_category',$category)->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                    )
                    ->orderBy('id', 'desc');
                $expenseCategory = $this->ExpenseCategory->where('is_pediatric',1)->whereType('2')->whereStatus('1')->pluck('id','id');
                $pediatric_expense = $this->ExpenseManager->whereIn('expense_category',$expenseCategory)->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                    )
                ->orderBy('id', 'desc');
                $pediatric_indoorBook = $this->IndoorBook->with('getInvoice')->where('is_pediatric_patient',1)->where('is_final_invoice',1)->whereNotNull('final_invoice_date');
                $pediatric_indoorBook = $pediatric_indoorBook->where(function($query) use($paymentType){
                    $query->whereHas('getInvoice', function($query) use($paymentType) {
                        $query->where('payment_mode', $paymentType);
                    });
                });
                $pediatric_indoorCaseDeposit = $this->IndoorDeposit->where('is_pediatric',1)->whereCaseTypeAndChargeType('Credit', 4)->where('payment_type',$paymentType)->with('getPatients')->orderBy('id', 'DESC');


                //medicare Total income and expense
                $category = $this->ExpenseCategory->where('is_medicare',1)->whereType('1')->whereStatus('1')->pluck('id','id');
                $medicare_income = $this->IncomeManager->whereIn('income_category',$category)->where('payment_method',$incomePaymentType)->select("*",
                \DB::raw('
                    (CASE
                        WHEN payment_method = "1" THEN "Cash"
                        WHEN payment_method = "2" THEN "Swipe"
                        WHEN payment_method = "3" THEN "Cheque"
                        WHEN payment_method = "4" THEN "UPI"
                        WHEN payment_method = "4" THEN "NEFT"
                    END)
                    AS payment_mode')
                )
                ->orderBy('id', 'desc');
                $expenseCategory = $this->ExpenseCategory->where('is_medicare',1)->whereType('2')->whereStatus('1')->pluck('id','id');
                $medicare_expense = $this->ExpenseManager->whereIn('expense_category',$expenseCategory)->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                    )
                ->orderBy('id', 'desc');
                $medicare_indoorBook = $this->IndoorBook->with('getInvoice')->where('is_medicare_patient',1)->where('is_final_invoice',1)->whereNotNull('final_invoice_date');
                $medicare_indoorBook = $medicare_indoorBook->where(function($query) use($paymentType){
                    $query->whereHas('getInvoice', function($query) use($paymentType) {
                        $query->where('payment_mode', $paymentType);
                    });
                });
                $medicare_indoorCaseDeposit = $this->IndoorDeposit->where('is_medicare',1)->whereCaseTypeAndChargeType('Credit', 4)->where('payment_type',$paymentType)->with('getPatients')->orderBy('id', 'DESC');

                if($fromdate || $todate){
                    $income = $income->whereBetween('date', [$fromdate, $todate]);
                    $expense = $expense->whereBetween('date', [$fromdate, $todate]);

                    //pediatric Total income and expense
                    $pediatric_income = $pediatric_income->whereBetween('date', [$fromdate, $todate]);
                    $pediatric_expense = $pediatric_expense->whereBetween('date', [$fromdate, $todate]);
                    $pediatric_indoorBook = $pediatric_indoorBook->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $pediatric_indoorCaseDeposit = $pediatric_indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    //medicare Total income and expense
                    $medicare_income = $medicare_income->whereBetween('date', [$fromdate, $todate]);
                    $medicare_expense = $medicare_expense->whereBetween('date', [$fromdate, $todate]);
                    $medicare_indoorBook = $medicare_indoorBook->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $medicare_indoorCaseDeposit = $medicare_indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                }
                // $usgGrandTotal = $usg->sum('usg');
                $incomeGrandTotal = $income->sum('amount');
                $expenseGrandTotal = $expense->sum('amount');

                $pediatric_income = $pediatric_income->sum('amount') + $pediatric_indoorBook->get()->sum('getInvoice.grand_total_amt')+$pediatric_indoorCaseDeposit->sum('amount');
                $pediatric_expense = $pediatric_expense->sum('amount');

                $medicare_income = $medicare_income->sum('amount') + $medicare_indoorBook->get()->sum('getInvoice.grand_total_amt')+$medicare_indoorCaseDeposit->sum('amount');
                $medicare_expense = $medicare_expense->sum('amount');

                if($request->isprint==1){
                    $usg = $usg->get();
                    $hormon = $hormon->get();
                    $iui = $iui->get();
                    $ivf = $ivf->get();
                    $income = $income->get();
                    $expense = $expense->get();
                    $collectionReport = $collectionReport->get();
                    $reportDatails['total'] = $collectionReport->sum('netamount');
                    $reportDatails['count'] = $collectionReport->count();

                    return response()->json([
                        View::make('admin.report.collection.preview',compact('pediatric_income','pediatric_expense','medicare_income','medicare_expense','indoorNEFT','indoorUPI','indoorCheque','indoorNEFTDeposit','indoorUPIDeposit','indoorCheque','indoorChequeDeposit','ivfNEFT','iuiNEFT','ancNEFT','gynecNEFT','ivfUPI','iuiUPI','ancUPI','gynecUPI','ivfCheque','iuiCheque','ancCheque','gynecCheque','collectionReport','reportDatails', 'expense', 'expenseGrandTotal', 'hormon', 'iui', 'ivf', 'income', 'incomeGrandTotal', 'usg', 'indoorCash', 'indoorCard', 'indoorCaseDeposit','indoorCardDeposit','ivfCash', 'iuiCash', 'ancCash', 'ivfCard','gynecCash','gynecCard','iuiCard', 'ancCard', 'procedures','indoorDebit'))->render()
                    ]);
                }
                $collectionReport = $collectionReport->paginate(100);
                $reportDatails['total'] = $collectionReport->sum('netamount');
                $reportDatails['count'] = $collectionReport->count();

                $usg = $usg->paginate(100, ['*'], 'usg');
                $hormon = $hormon->paginate(100, ['*'], 'hormon');
                $iui = $iui->paginate(100, ['*'], 'iui');
                $ivf = $ivf->paginate(100, ['*'], 'ivf');
                $income = $income->paginate(100, ['*'], 'income');
                $expense = $expense->paginate(100, ['*'], 'expense');

                return response()->json([
                    View::make('admin.report.collection.data',compact('pediatric_income','pediatric_expense','medicare_income','medicare_expense','indoorNEFT','indoorUPI','indoorCheque','indoorNEFTDeposit','indoorUPIDeposit','indoorCheque','indoorChequeDeposit','ivfNEFT','iuiNEFT','ancNEFT','gynecNEFT','ivfUPI','iuiUPI','ancUPI','gynecUPI','ivfCheque','iuiCheque','ancCheque','gynecCheque','collectionReport','reportDatails', 'expense', 'expenseGrandTotal', 'hormon', 'iui', 'ivf', 'income', 'incomeGrandTotal', 'usg', 'indoorCash', 'indoorCard', 'indoorCaseDeposit','indoorCardDeposit','ivfCash', 'iuiCash', 'ancCash', 'ivfCard','gynecCash','gynecCard', 'iuiCard', 'ancCard', 'procedures','indoorDebit'))->render()
                ]);
            }
            return view('admin.report.collection.index',compact('referenceDoctor'));
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }
    /**
    * Get doctor report
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    private function getDoctor(){
        $referenceDoctor = $this->ReferenceDoctor->pluck('name','id');
        $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
        return['referenceDoctor'=>$referenceDoctor,'hospitalDoctor'=>$hospitalDoctor];
    }

    // ivf payment report
    public function ivfPaymentReport(Request $request){
        try{
            $patients = $this->getPatients();
            if($request->ajax()){
                $ivfPayment = $this->IvfPayment->whereNotNull('package')->orderBy('id','DESC');
                $patients = $this->getPatients();
                if($request->date){
                    $date = explode("-",$request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $startDate = $startDate.' '.'00:00:00';
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    $endDate = Carbon::parse($endDate)->addDays(1)->format('Y-m-d');
                    $endDate = $endDate.' '.'00:00:00';
                    if($date){
                        $ivfPayment = $ivfPayment->whereBetween('created_at', [$startDate, $endDate]);
                    }
                }
                // dd($ivfPayment->get());
                $patientId = $request->patient_id;
                if($patientId){
                    $ivfPayment = $ivfPayment->where('patients_id',$patientId);
                }
                if($request->isprint){
                    $ivfPayment = $ivfPayment->get();
                    $data['status'] = 2;
                    $data['ivfpayment'] = View::make('admin.report.ivf_payment.preview',compact('ivfPayment'))->render();
                    return $data;
                }
                $ivfPayment = $ivfPayment->paginate(100);
                $data['status'] = 1;
                $data['ivfpayment'] = View::make('admin.report.ivf_payment.data',compact('ivfPayment'))->render();
                return $data;
            }
            return view('admin.report.ivf_payment.index',compact('patients'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    /**
    * Return on ivf remaining payment
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function ivfRemainigPayment(Request $request){
        try{
            $ivfPayment = $this->IvfPayment->whereNotNull('package')->orderBy('id','DESC');
            $patients = $this->getPatients();
            if($request->ajax()){

                if($request->date){
                    $date = explode("-",$request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    if($date){
                        $ivfPayment = $ivfPayment->whereBetween('remaining_date', [$startDate, $endDate]);
                    }
                }
                /*
                $patientId = $request->patient_id;
                if($patientId){
                    $ivfPayment = $ivfPayment->where('patients_id',$patientId);
                }*/
                if($request->isprint){
                    $ivfPayment = $ivfPayment->get();
                    $data['status'] = 2;
                    $data['ivfpayment'] = View::make('admin.report.ivf_payment.preview',compact('ivfPayment'))->render();
                    return $data;
                }
                $ivfPayment = $ivfPayment->paginate(100);
                $data['status'] = 1;
                $data['ivfpayment'] = View::make('admin.report.ivf_payment.remaining_data',compact('ivfPayment','patients'))->render();
                return $data;
            }
            return view('admin.report.ivf_payment.remaining_payment',compact('patients'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    public function remaining_payment(Request $request)
    {
        return view("admin.report.ivf_payment.remaining_payment");
    }

    /**
    * Get IVF payment report detail
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getIvfPaymentReport($paymentId){
        try{
            $ipId = decrypt($paymentId);
            $paymentData = $this->IvfPayment->find($ipId);
            return ['status'=>1,'data'=>$paymentData];
        }catch(Exception $e){
            return ['status'=>0];
        }
    }

    // update
    public function updateIvfPaymentReport(Request $request){
        try{
            $id = decrypt($request->id);
            $ivfPaymentData = $this->IvfPayment->find($id);
            $ivfPaymentData->cycle_no = $request->cycle_no;
            $ivfPaymentData->payment = $request->payment;
            $ivfPaymentData->package = $request->package;
            $ivfPaymentData->payment_type = $request->payment_type;
            // $ivfPaymentData->time = $request->time;
            $ivfPaymentData->condition = $request->condition;
            $ivfPaymentData->save();
            return['status'=>1,'data'=>$ivfPaymentData];
        }catch(Exception $e){
            log::debug($e);
            return['status'=>0];
        }
    }

    // generate infertility report base on IUI and IVF module and also print for IVF package
    public function infertilityReport(Request $request){
        $infertilityReport = null;
        $patients = $this->getPatients();
        // $appointment = $this->Appointment->whereIn('category_id',[1,2,3,4])->where('is_procedure',0)->get();
            // $appointment = $this->IvfHistory->where('cycle_status',1)->groupBy('patients_id')->having(DB::raw('count(patients_id)'),'=',1);
            // $appointment = $this->IVF->groupBy('patients_id')->having(DB::raw('count(patients_id)'),'=',1);
            // if($request->report_type == 2)
            // {
            //     // $appointment = $this->IuiHistory->where('cycle_status',1)->groupBy('patients_id')->having(DB::raw('count(patients_id)'),'=',1);
            //     $appointment = $this->IUI->groupBy('patients_id')->having(DB::raw('count(patients_id)'),'=',1);
            // }

        if($request->ajax()){
            $patientStatus = $request->patient_status;
            $type = $request->report_type;
            if($request->package_id){
                $ivfPayment = $this->IvfPayment->find(decrypt($request->package_id));
                return response()->json([
                    'status' => 2,
                    'infertility_data' => View::make('admin.ivf.payment_preview', compact('ivfPayment'))->render()
                ]);
            }
            $appointment = ($type == 2) ? $this->IuiHistory->where('visit',2)->where('cycle_status',1)->groupBy('patients_id')->having(DB::raw('count(patients_id)'),'=',1) : $this->IvfHistory->where('visit',2)->where('cycle_status',1)->groupBy('patients_id')->having(DB::raw('count(patients_id)'),'=',1);
            if($patientStatus == 2)
            {
                $appointment = ($type == 2) ? $this->IUI->doesnthave('getPatientsDetails.getIuiHistory')->groupBy('patients_id')->having(DB::raw('count(patients_id)'),'=',1) : $this->IVF->doesnthave('getPatientsDetails.getIvfHistory')->groupBy('patients_id')->having(DB::raw('count(patients_id)'),'=',1);
            }
            $patientId = $request->patient_id;
            if($patientId) {
                $appointment = $appointment->where(function($query) use($patientId) {
                    $query->orWhereHas('getPatientsDetails', function($query) use($patientId) {
                            $query->where('id', $patientId);
                        });
                });
            }
            $search = $request->search;
            if($search){
                $appointment = $appointment->where(function($query) use($search) {
                    $query
                        ->orWhereHas('getPatientsDetails', function($query) use($search) {
                            $query->where('mobile_number','LIKE',$search.'%');
                        });
                });
            }
            $patientStatus = $request->patient_status;
            // if($patientStatus)
            // {
            //     if($patientStatus == 1){
            //         $appointment = $appointment->where(function($query){
            //             $query->has('getPatientsDetails.getIuiHistory')->orHas('getPatientsDetails.getIuiHistory');
            //         });
            //     }else{
            //         $appointment = $appointment->where(function($query){
            //             $query->doesnthave('getPatientsDetails.getIui')->doesnthave('getPatientsDetails.getIvf');
            //         });
            //     }
            // }
            if($request->date){
                $date = explode("-",$request->date);
                $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d').' 00:00:00';
                $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d').' 23:59:59';
                if($date){
                    $patientId = $appointment->pluck('patients_id','patients_id')->toArray();
                    // $patientsData = $this->getPatients($pId);
                    $appointment = $appointment->whereIn('patients_id',$patientId)->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
            if($request->isprint == 1){
                $appointment = $appointment->orderBy('id','DESC')->get();
                $data['status'] = 2;
                $data['infertility_data'] = View::make('admin.report.infertility.preview',compact('appointment'))->render();
                return $data;
            }
            $appointment = $appointment->orderBy('id','DESC')->paginate(50);
            $data['status'] = 1;
            $data['infertility_data'] = View::make('admin.report.infertility.data',compact('appointment'))->render();
            return $data;
        }
        return view('admin.report.infertility.index',compact('patients'));
    }


    // remark appointment generate
    public function remarkAppointment(Request $request){
        try{
            $patients = $this->OpdPatients->where('is_approved',1)->pluck('name','id')->toArray();
            if($request->ajax()){
                $appointment = $this->Appointment->whereNotNull('remark');
                $patientId = $request->patient_id;
                if($patientId){
                    $appointment = $appointment->where('patients_id',$patientId);
                }
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $fromdate = carbon::parse($fromdate)->format('Y-m-d');
                    $todate = carbon::parse($todate)->format('Y-m-d');
                    $appointment = $appointment->whereBetween('date',[$fromdate,$todate]);
                }
                $search = $request->search;
                if($search){
                    $appointment = $appointment->where('remark','LIKE','%'.$search.'%');
                }
                if($request->isprint == 1){
                    $appointment = $appointment->orderBy('id','DESC')->get();
                    $data['status'] = 2;
                    $data['remark_appointment'] = View::make('admin.report.remark_appointment.preview',compact('appointment'))->render();
                    return $data;
                }
                $appointment = $appointment->orderBy('id','DESC')->paginate(50);
                $data['status'] = 1;
                $data['remark_appointment'] = View::make('admin.report.remark_appointment.data',compact('appointment'))->render();
                return $data;
            }
            return view('admin.report.remark_appointment.index',compact('patients'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    //ca expense report
    public function getCaExpenseReport(Request $request)
    {
        try{
            $doctor = $this->getDoctor();
            $referenceDoctor = $doctor['referenceDoctor'];
            $reportDatails = [];
            $reportDatails['doctor'] = '';
            $bank_details = $this->BankDetail->pluck('name','id');
            if($request->ajax() || $request->isexport == 1){
                $paymentType = $request->payment_type;
                $bank_id = $request->bank_id;
                $collectionReport = $this->AppointmentCharges->orderBy('id','DESC');
                if(!empty($bank_id))
                {
                    $collectionReport = $this->AppointmentCharges->where('bank_id',$bank_id)->orderBy('id','DESC');
                }

                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $fromdate = carbon::parse($fromdate)->format('Y-m-d');
                    $todate = carbon::parse($todate)->format('Y-m-d');
                    $collectionReport = $collectionReport->WhereHas('getAppointment', function ($query) use ($fromdate, $todate) {
                        $query->whereBetween('date', [$fromdate, $todate]);
                    });
                }

                $referenceDoctorId = $request->reference_doctor_id;
                if($referenceDoctorId){
                    $reportDatails['doctor'] =  $this->ReferenceDoctor->where('id',$referenceDoctorId)->value('name');
                    $collectionReport = $collectionReport->WhereHas('getAppointment.getPatientsDetails', function($query) use($referenceDoctorId){
                        $query->where('reference_doctor_id',$referenceDoctorId);
                    });
                }
                // USG
                $usg = $this->Appointment->whereHas('getAppointmentCharges',function($q) use($paymentType){
                    $q->where('usg', '!=', null)
                        ->where('payment_mode',$paymentType);
                })->orderBy('id', 'desc');
                if(!empty($bank_id))
                {
                    $usg = $this->Appointment->whereHas('getAppointmentCharges',function($q) use($paymentType,$bank_id){
                        $q->where('usg', '!=', null)
                            ->where('payment_mode',$paymentType)
                            ->where('bank_id',$bank_id);
                    })->orderBy('id', 'desc');
                }
                // Hormon
                $hormon = $this->IndoorDeposit->where([['charge_type', '=', 1],['case_type', '=', 'Credit'],['payment_type', '=', $paymentType]])->orderBy('id', 'desc');
                // IUI
                $iui = $this->IndoorDeposit->where([['charge_type', '=', 3],['case_type', '=', 'Credit'],['payment_type', '=', $paymentType]])->orderBy('id', 'desc');
                // IVF
                $ivf = $this->IndoorDeposit->where([['charge_type', '=', 2],['case_type', '=', 'Credit'],['payment_type', '=', $paymentType]])->orderBy('id', 'desc');
                // Main Collection Data Card

                $mainDataCash = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) use($paymentType) {$query->where([
                        ['payment_mode', '=', $paymentType],
                        ['category_id', '!=', 7],
                    ])
                        ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);})
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                    if(!empty($bank_id))
                    {
                        $mainDataCash = $this->Appointment
                        ->whereHas('getAppointmentCharges', function($query) use($paymentType,$bank_id) {$query->where([
                            ['payment_mode', '=', $paymentType],
                            ['category_id', '!=', 7],
                            ['bank_id','=',$bank_id],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);})
                        ->with([
                            'getPatientsDetails',
                            'getAppointmentCharges',
                        ])
                        ->orderBy('id', 'desc');

                    }
                $mainDataCard = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {
                        $query->where([
                            ['payment_mode', '=', 1],
                            ['category_id', '!=', 7],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                    })
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                    if(!empty($bank_id))
                    {
                        $mainDataCard = $this->Appointment
                        ->whereHas('getAppointmentCharges', function($query) use($bank_id) {
                            $query->where([
                                ['payment_mode', '=', 1],
                                ['category_id', '!=', 7],
                                ['bank_id','=', $bank_id],
                            ])
                                ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                        })
                        ->with([
                            'getPatientsDetails',
                            'getAppointmentCharges',
                        ])
                        ->orderBy('id', 'desc');

                    }
                $mainDataCheque = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {
                        $query->where([
                            ['payment_mode', '=', 3],
                            ['category_id', '!=', 7],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                    })
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                    if(!empty($bank_id))
                    {
                        $mainDataCheque = $this->Appointment
                        ->whereHas('getAppointmentCharges', function($query) use($bank_id) {
                            $query->where([
                                ['payment_mode', '=', 3],
                                ['category_id', '!=', 7],
                                ['bank_id','=',$bank_id],
                            ])
                                ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                        })
                        ->with([
                            'getPatientsDetails',
                            'getAppointmentCharges',
                        ])
                        ->orderBy('id', 'desc');
                    }
                $mainDataUPI = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {
                        $query->where([
                            ['payment_mode', '=', 4],
                            ['category_id', '!=', 7],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                    })
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                    if(!empty($bank_id))
                    {
                        $mainDataUPI = $this->Appointment
                        ->whereHas('getAppointmentCharges', function($query) use($bank_id) {
                            $query->where([
                                ['payment_mode', '=', 4],
                                ['category_id', '!=', 7],
                                ['bank_id','=',$bank_id],
                            ])
                                ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                        })
                        ->with([
                            'getPatientsDetails',
                            'getAppointmentCharges',
                        ])
                        ->orderBy('id', 'desc');
                    }
                $mainDataNEFT = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {
                        $query->where([
                            ['payment_mode', '=', 5],
                            ['category_id', '!=', 7],
                        ])
                            ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                    })
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                    if(!empty($bank_id))
                    {
                        $mainDataNEFT = $this->Appointment
                        ->whereHas('getAppointmentCharges', function($query) use($bank_id) {
                            $query->where([
                                ['payment_mode', '=', 5],
                                ['category_id', '!=', 7],
                                ['bank_id','=',$bank_id],
                            ])
                                ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);
                        })
                        ->with([
                            'getPatientsDetails',
                            'getAppointmentCharges',
                        ])
                        ->orderBy('id', 'desc');
                    }
                if(!empty($bank_id))
                {
                    $hormon = $hormon->where('bank_id',$bank_id);
                    $iui = $iui->where('bank_id',$bank_id);
                    $ivf = $ivf->where('bank_id',$bank_id);
                }
                if($fromdate || $todate){
                    $usg = $usg->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $hormon = $hormon->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $iui = $iui->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $ivf = $ivf->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);

                    $mainDataCash = $mainDataCash->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataCard = $mainDataCard->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataCheque = $mainDataCheque->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataCheque = $mainDataCheque->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataUPI = $mainDataUPI->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $mainDataNEFT = $mainDataNEFT->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                }

                $mainDataCash  = $mainDataCash->get();
                $mainDataCash = $mainDataCash->groupBy('category_id')->toArray();

                // $ivfCash = (!empty($mainDataCash[1]) ? $mainDataCash[1] : []) + (!empty($mainDataCash[2]) ? $mainDataCash[2] : []);
                // $iuiCash = (!empty($mainDataCash[3]) ? $mainDataCash[3] : []) + (!empty($mainDataCash[4]) ? $mainDataCash[4] : []);
                // $ancCash = (!empty($mainDataCash[5]) ? $mainDataCash[5] : []) + (!empty($mainDataCash[6]) ? $mainDataCash[6] : []);
                $ivfCash = array_merge(!empty($mainDataCash[1]) ? $mainDataCash[1] : [],!empty($mainDataCash[2]) ? $mainDataCash[2] : []);
                $iuiCash = array_merge(!empty($mainDataCash[3]) ? $mainDataCash[3] : [],!empty($mainDataCash[4]) ? $mainDataCash[4] : []);
                $ancCash = array_merge(!empty($mainDataCash[5]) ? $mainDataCash[5] : [],!empty($mainDataCash[6]) ? $mainDataCash[6] : []);
                $gynecCash = array_merge(!empty($mainDataCash[17]) ? $mainDataCash[17] : [],!empty($mainDataCash[18]) ? $mainDataCash[18] : []);
                // $newOldCash = array_merge(!empty($mainDataCash[8]) ? $mainDataCash[8] : [],!empty($mainDataCash[9]) ? $mainDataCash[9] : []);
                $mainDataCard  = $mainDataCard->get();
                $mainDataCard = $mainDataCard->groupBy('category_id')->toArray();
                // $ivfCard = (!empty($mainDataCard[1]) ? $mainDataCard[1] : []) + (!empty($mainDataCard[2]) ? $mainDataCard[2] : []);
                // $iuiCard = (!empty($mainDataCard[3]) ? $mainDataCard[3] : []) + (!empty($mainDataCard[4]) ? $mainDataCard[4] : []);
                // $ancCard = (!empty($mainDataCard[5]) ? $mainDataCard[5] : []) + (!empty($mainDataCard[6]) ? $mainDataCard[6] : []);
                $ivfCard = array_merge(!empty($mainDataCard[1]) ? $mainDataCard[1] : [],!empty($mainDataCard[2]) ? $mainDataCard[2] : []);
                $iuiCard = array_merge(!empty($mainDataCard[3]) ? $mainDataCard[3] : [],!empty($mainDataCard[4]) ? $mainDataCard[4] : []);
                $ancCard = array_merge(!empty($mainDataCard[5]) ? $mainDataCard[5] : [],!empty($mainDataCard[6]) ? $mainDataCard[6] : []);
                $gynecCard = array_merge(!empty($mainDataCard[17]) ? $mainDataCard[17] : [],!empty($mainDataCard[18]) ? $mainDataCard[18] : []);
                // $newOldCard = array_merge(!empty($mainDataCard[8]) ? $mainDataCard[8] : [],!empty($mainDataCard[9]) ? $mainDataCard[9] : []);

                $mainDataCheque  = $mainDataCheque->get();
                $mainDataCheque = $mainDataCheque->groupBy('category_id')->toArray();

                $mainDataUPI  = $mainDataUPI->get();
                $mainDataUPI = $mainDataUPI->groupBy('category_id')->toArray();

                $mainDataNEFT  = $mainDataNEFT->get();
                $mainDataNEFT = $mainDataNEFT->groupBy('category_id')->toArray();

                // Cheque
                $ivfCheque = array_merge(!empty($mainDataCheque[1]) ? $mainDataCheque[1] : [],!empty($mainDataCheque[2]) ? $mainDataCheque[2] : []);
                $iuiCheque = array_merge(!empty($mainDataCheque[3]) ? $mainDataCheque[3] : [],!empty($mainDataCheque[4]) ? $mainDataCheque[4] : []);
                $ancCheque = array_merge(!empty($mainDataCheque[5]) ? $mainDataCheque[5] : [],!empty($mainDataCheque[6]) ? $mainDataCheque[6] : []);
                $gynecCheque = array_merge(!empty($mainDataCheque[17]) ? $mainDataCheque[17] : [],!empty($mainDataCheque[18]) ? $mainDataCheque[18] : []);

                // UPI
                $ivfUPI = array_merge(!empty($mainDataUPI[1]) ? $mainDataUPI[1] : [],!empty($mainDataUPI[2]) ? $mainDataUPI[2] : []);
                $iuiUPI = array_merge(!empty($mainDataUPI[3]) ? $mainDataUPI[3] : [],!empty($mainDataUPI[4]) ? $mainDataUPI[4] : []);
                $ancUPI = array_merge(!empty($mainDataUPI[5]) ? $mainDataUPI[5] : [],!empty($mainDataUPI[6]) ? $mainDataUPI[6] : []);
                $gynecUPI = array_merge(!empty($mainDataUPI[17]) ? $mainDataUPI[17] : [],!empty($mainDataUPI[18]) ? $mainDataUPI[18] : []);

                // NEFT
                $ivfNEFT = array_merge(!empty($mainDataNEFT[1]) ? $mainDataNEFT[1] : [],!empty($mainDataNEFT[2]) ? $mainDataNEFT[2] : []);
                $iuiNEFT = array_merge(!empty($mainDataNEFT[3]) ? $mainDataNEFT[3] : [],!empty($mainDataNEFT[4]) ? $mainDataNEFT[4] : []);
                $ancNEFT = array_merge(!empty($mainDataNEFT[5]) ? $mainDataNEFT[5] : [],!empty($mainDataNEFT[6]) ? $mainDataNEFT[6] : []);
                $gynecNEFT = array_merge(!empty($mainDataNEFT[17]) ? $mainDataNEFT[17] : [],!empty($mainDataNEFT[18]) ? $mainDataNEFT[18] : []);


                $indoor = $this->IndoorBook
                    ->whereIsFinalInvoice(1)
                    ->whereNotNull('final_invoice_date')
                    ->with([
                        'getInvoice',
                        'getPatientsDetails'
                    ])
                    ->orderBy('id', 'DESC');


                $indoorCaseDeposit = $this->IndoorDeposit->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType($paymentType)->with('getPatients')->orderBy('id', 'DESC');
                $indoorCardDeposit = $this->IndoorDeposit->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType('1')->with('getPatients')->orderBy('id', 'DESC');
                $indoorChequeDeposit = $this->IndoorDeposit->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType('3')->with('getPatients')->orderBy('id', 'DESC');
                $indoorUPIDeposit = $this->IndoorDeposit->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType('4')->with('getPatients')->orderBy('id', 'DESC');
                $indoorNEFTDeposit = $this->IndoorDeposit->whereCaseTypeAndChargeType('Credit', 4)->wherePaymentType('5')->with('getPatients')->orderBy('id', 'DESC');
                $indoorDebit = $this->IndoorDeposit->whereCaseTypeAndChargeType('Debit', 4)->with('getPatients')->orderBy('id', 'DESC');
                if(!empty($bank_id))
                {
                    $indoorCaseDeposit = $indoorCaseDeposit->whereBankId($bank_id);
                    $indoorCardDeposit = $indoorCardDeposit->whereBankId($bank_id);
                    $indoorChequeDeposit = $indoorChequeDeposit->whereBankId($bank_id);
                    $indoorUPIDeposit = $indoorUPIDeposit->whereBankId($bank_id);
                    $indoorNEFTDeposit = $indoorNEFTDeposit->whereBankId($bank_id);
                    $indoorDebit = $indoorDebit->whereBankId($bank_id);
                }
                if($fromdate || $todate) {
                    $indoor = $indoor->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorCaseDeposit = $indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorCardDeposit = $indoorCardDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorChequeDeposit = $indoorChequeDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorUPIDeposit = $indoorUPIDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorNEFTDeposit = $indoorNEFTDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorDebit = $indoorDebit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                }

                $indoorCaseDeposit = $indoorCaseDeposit->get()->toArray();
                $indoorCardDeposit = $indoorCardDeposit->get()->toArray();
                $indoorChequeDeposit = $indoorChequeDeposit->get()->toArray();
                $indoorUPIDeposit = $indoorUPIDeposit->get()->toArray();
                $indoorNEFTDeposit = $indoorNEFTDeposit->get()->toArray();
                $indoorDebit = $indoorDebit->get()->toArray();
                $indoor = $indoor->get()->toArray();

                if(!empty($bank_id))
                {
                    $indoor = collect($indoor)->where('get_invoice.bank_id', '=', $bank_id);
                    // $indoorCheque = collect($indoor)->where('get_invoice.bank_id', '=', $bank_id);
                    // $indoorUPI = collect($indoor)->where('get_invoice.bank_id', '=', $bank_id);
                    // $indoorNEFT = collect($indoor)->where('get_invoice.bank_id', '=', $bank_id);
                    // $indoorCard = collect($indoor)->where('get_invoice.bank_id', '=', $bank_id);

                }
                $indoorCash = collect($indoor)->where('get_invoice.payment_mode', '=', $paymentType)->all();
                $indoorCash = array_slice($indoorCash, 0);

                $indoorCheque = collect($indoor)->where('get_invoice.payment_mode', '=', 3)->all();
                $indoorCheque = array_slice($indoorCheque, 0);

                $indoorUPI = collect($indoor)->where('get_invoice.payment_mode', '=', 4)->all();
                $indoorUPI = array_slice($indoorUPI, 0);

                $indoorNEFT = collect($indoor)->where('get_invoice.payment_mode', 5)->all();
                $indoorNEFT = array_slice($indoorNEFT, 0);

                $indoorCard = collect($indoor)->where('get_invoice.payment_mode', 1)->all();
                $indoorCard = array_slice($indoorCard, 0);

                $procedures = $this->IndoorProcedure->select('id', 'name')->get()->toArray();
                $paymentMethodValueData = [1=>2,2=>1,3=>3,4=>4,5=>5];
                $incomePaymentType = $paymentMethodValueData[$paymentType];
                $income = $this->IncomeManager->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                )
                    ->orderBy('id', 'desc');
                if($fromdate || $todate){
                    $income = $income->whereBetween('date', [$fromdate, $todate]);
                }
                if(!empty($bank_id))
                {
                    $income = $income->where('bank_id', $bank_id);
                }
                // expense
                $expense = $this->ExpenseManager->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                )
                    ->orderBy('id', 'desc');

                if($fromdate || $todate){
                    $expense = $expense->whereBetween('date', [$fromdate, $todate]);
                }
                if(!empty($bank_id))
                {
                    $expense = $expense->where('bank_id', $bank_id);
                }
                // $usgGrandTotal = $usg->sum('usg');
                $incomeGrandTotal = $income->sum('amount');
                $expenseGrandTotal = $expense->sum('amount');

                if($request->isprint==1){
                    $usg = $usg->get();
                    $hormon = $hormon->get();
                    $iui = $iui->get();
                    $ivf = $ivf->get();
                    $income = $income->get();
                    $expense = $expense->get();
                    $collectionReport = $collectionReport->get();
                    $reportDatails['total'] = $collectionReport->sum('netamount');
                    $reportDatails['count'] = $collectionReport->count();

                    return response()->json([
                        View::make('admin.report.ca_expense.preview',compact('indoorNEFT','indoorUPI','indoorCheque','indoorNEFTDeposit','indoorUPIDeposit','indoorCheque','indoorChequeDeposit','ivfNEFT','iuiNEFT','ancNEFT','gynecNEFT','ivfUPI','iuiUPI','ancUPI','gynecUPI','ivfCheque','iuiCheque','ancCheque','gynecCheque','collectionReport','reportDatails', 'expense', 'expenseGrandTotal', 'hormon', 'iui', 'ivf', 'income', 'incomeGrandTotal', 'usg', 'indoorCash', 'indoorCard', 'indoorCaseDeposit','indoorCardDeposit','ivfCash', 'iuiCash', 'ancCash', 'ivfCard','gynecCash','gynecCard','iuiCard', 'ancCard', 'procedures','indoorDebit','bank_details','paymentType'))->render()
                    ]);
                }
                $collectionReport = $collectionReport->paginate(100);
                $reportDatails['total'] = $collectionReport->sum('netamount');
                $reportDatails['count'] = $collectionReport->count();

                $usg = $usg->paginate(100, ['*'], 'usg');
                $hormon = $hormon->paginate(100, ['*'], 'hormon');
                $iui = $iui->paginate(100, ['*'], 'iui');
                $ivf = $ivf->paginate(100, ['*'], 'ivf');
                $income = $income->paginate(100, ['*'], 'income');
                $expense = $expense->paginate(100, ['*'], 'expense');
                $bank_details = $this->BankDetail->pluck('name','id');
                return response()->json([
                    View::make('admin.report.ca_expense.data',compact('indoorNEFT','indoorUPI','indoorCheque','indoorNEFTDeposit','indoorUPIDeposit','indoorCheque','indoorChequeDeposit','ivfNEFT','iuiNEFT','ancNEFT','gynecNEFT','ivfUPI','iuiUPI','ancUPI','gynecUPI','ivfCheque','iuiCheque','ancCheque','gynecCheque','collectionReport','reportDatails', 'expense', 'expenseGrandTotal', 'hormon', 'iui', 'ivf', 'income', 'incomeGrandTotal', 'usg', 'indoorCash', 'indoorCard', 'indoorCaseDeposit','indoorCardDeposit','ivfCash', 'iuiCash', 'ancCash', 'ivfCard','gynecCash','gynecCard', 'iuiCard', 'ancCard', 'procedures','indoorDebit','bank_details'))->render()
                ]);
            }
            return view('admin.report.ca_expense.index',compact('referenceDoctor','bank_details'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
    /**
    * Store CA expense
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function storeCaExpense(Request $request)
    {
        try{
            if($request->ajax()){
                $table = $request->ca_expense_table;
                $id = $request->ca_expense_row_id;
                $txt_amount = $request->txt_amount;
                $bank_id = $request->bank_id;
                $invoice_no = $request->invoice_no;
                $detail = $request->detail;
                $ca_expense_data = $this->$table->where('id',$id)->first();
                $ca_expense_data->txt_amount = $txt_amount;
                $ca_expense_data->invoice_no = $invoice_no;
                $ca_expense_data->bank_id = $bank_id;
                $ca_expense_data->detail = $detail;
                $ca_expense_data->save();
                if($ca_expense_data)
                {
                    $data['status'] = 1;
                }
                else
                {
                    $data['status'] = 2;

                }
                return $data;
            }

        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
    /**
    * Get CA expense detail
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getCaExpense(Request $request)
    {
        try{
            if($request->ajax()){
                $table = $request->ca_expense_table;
                $id = $request->ca_expense_row_id;
                $ca_expense_data = $this->$table->where('id',$id)->first();
                $data['status'] = 1;
                $data['data'] = $ca_expense_data;
            }
            else
            {
                $data['status'] = 2;
            }
            return $data;
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
    /**
    * Get All collection report (swipe, cash,NEFT, UPI...)
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getAllCollectionReport(Request $request)
    {
        try{
            $doctor = $this->getDoctor();
            $referenceDoctor = $doctor['referenceDoctor'];
            $reportDatails = [];
            $reportDatails['doctor'] = '';
            if($request->ajax() || $request->isexport == 1){
                $paymentType = $request->payment_type;
                $collectionReport = $this->AppointmentCharges->orderBy('id','DESC');

                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    // $month = carbon::parse($fromdate)->format('Y-m-d');
                    $fromdate = carbon::parse($fromdate)->format('Y-m-d');
                    $todate = carbon::parse($todate)->format('Y-m-d');
                    $collectionReport = $collectionReport->WhereHas('getAppointment', function ($query) use ($fromdate, $todate) {
                        $query->whereBetween('date', [$fromdate, $todate]);
                    });
                }
                // dd($collectionReport->get()->toArray());
                // USG
                $usg = $this->Appointment->whereHas('getAppointmentCharges',function($q) {
                    $q->where('usg', '!=', null);
                })->orderBy('id', 'desc');
                // Hormon
                $hormon = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->where([['charge_type', '=', 1],['case_type', '=', 'Credit']])->orderBy('id', 'desc');
                // IUI
                $iui = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->where([['charge_type', '=', 3],['case_type', '=', 'Credit']])->orderBy('id', 'desc');
                // IVF
                $ivf = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->where([['charge_type', '=', 2],['case_type', '=', 'Credit']])->orderBy('id', 'desc');
                // Main Collection Data Card
                $mainDataCash = $this->Appointment
                    ->whereHas('getAppointmentCharges', function($query) {$query->where([
                        ['category_id', '!=', 7],
                    ])
                        ->select(['id','appointment_id','consulting_charges','extra_field','nst','cut','usg','dressing','ivf','charge_types','total','payment_mode','created_at']);})
                    ->with([
                        'getPatientsDetails',
                        'getAppointmentCharges',
                    ])
                    ->orderBy('id', 'desc');
                if($fromdate || $todate)
                {
                    $usg = $usg->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $hormon = $hormon->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $iui = $iui->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $ivf = $ivf->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);

                    $mainDataCash = $mainDataCash->whereBetween('date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);

                }

                $mainDataCash  = $mainDataCash->get();
                $mainDataCash = $mainDataCash->groupBy('category_id')->toArray();

                $ivfCash = array_merge(!empty($mainDataCash[1]) ? $mainDataCash[1] : [],!empty($mainDataCash[2]) ? $mainDataCash[2] : []);
                $iuiCash = array_merge(!empty($mainDataCash[3]) ? $mainDataCash[3] : [],!empty($mainDataCash[4]) ? $mainDataCash[4] : []);
                $ancCash = array_merge(!empty($mainDataCash[5]) ? $mainDataCash[5] : [],!empty($mainDataCash[6]) ? $mainDataCash[6] : []);
                $gynecCash = array_merge(!empty($mainDataCash[17]) ? $mainDataCash[17] : [],!empty($mainDataCash[18]) ? $mainDataCash[18] : []);


                $indoor = $this->IndoorBook
                    ->whereIsFinalInvoice(1)
                    ->whereNotNull('final_invoice_date')
                    ->with([
                        'getInvoice',
                        'getPatientsDetails'
                    ])
                    ->orderBy('id', 'DESC');


                $indoorCaseDeposit = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereCaseTypeAndChargeType('Credit', 4)->with('getPatients')->orderBy('id', 'DESC');
                $indoorDebit = $this->IndoorDeposit->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereCaseTypeAndChargeType('Debit', 4)->with('getPatients')->orderBy('id', 'DESC');

                if($fromdate || $todate) {
                    $indoor = $indoor->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorCaseDeposit = $indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorDebit = $indoorDebit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                }
                $indoorCaseDeposit = $indoorCaseDeposit->get()->toArray();
                $indoorDebit = $indoorDebit->get()->toArray();
                $indoor = $indoor->get()->toArray();

                // $indoorCash = collect($indoor)->where('get_invoice.payment_mode', '=', $paymentType)->all();
                $indoorCash = collect($indoor)->all();
                $indoorCash = array_slice($indoorCash, 0);

                $procedures = $this->IndoorProcedure->select('id', 'name')->get()->toArray();
                $paymentMethodValueData = [1=>2,2=>1,3=>3,4=>4,5=>5];
                $incomeCategory = $this->ExpenseCategory->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereType('1')->whereStatus('1')->pluck('id','id');
                $income = $this->IncomeManager->whereIn('income_category',$incomeCategory)->orderBy('id', 'desc');
               
                // expense
                $expenceCategory = $this->ExpenseCategory->where('is_pediatric','!=',1)->where('is_medicare','!=',1)->whereType('2')->whereStatus('1')->pluck('id','id');
                $expense = $this->ExpenseManager->whereIn('expense_category',$expenceCategory)->orderBy('id', 'desc');

                //pediatric Total income and expense
                $category = $this->ExpenseCategory->where('is_pediatric',1)->whereType('1')->whereStatus('1')->pluck('id','id');
                $pediatric_income = $this->IncomeManager->whereIn('income_category',$category);
                $expenseCategory = $this->ExpenseCategory->where('is_pediatric',1)->whereType('2')->whereStatus('1')->pluck('id','id');
                $pediatric_expense = $this->ExpenseManager->whereIn('expense_category',$expenseCategory);
                $pediatric_indoorBook = $this->IndoorBook->with('getInvoice')->where('is_pediatric_patient',1)->where('is_final_invoice',1)->whereNotNull('final_invoice_date');
                $pediatric_indoorCaseDeposit = $this->IndoorDeposit->where('is_pediatric',1)->whereCaseTypeAndChargeType('Credit', 4)->with('getPatients')->orderBy('id', 'DESC');

                //medicare Total income and expense
                $category = $this->ExpenseCategory->where('is_medicare',1)->whereType('1')->whereStatus('1')->pluck('id','id');
                $medicare_income = $this->IncomeManager->whereIn('income_category',$category);
                $expenseCategory = $this->ExpenseCategory->where('is_medicare',1)->whereType('2')->whereStatus('1')->pluck('id','id');
                $medicare_expense = $this->ExpenseManager->whereIn('expense_category',$expenseCategory);
                $medicare_indoorBook = $this->IndoorBook->with('getInvoice')->where('is_medicare_patient',1)->where('is_final_invoice',1)->whereNotNull('final_invoice_date');
                $medicare_indoorCaseDeposit = $this->IndoorDeposit->where('is_medicare',1)->whereCaseTypeAndChargeType('Credit', 4)->with('getPatients')->orderBy('id', 'DESC');

                if($fromdate || $todate){
                    $expense = $expense->whereBetween('date', [$fromdate, $todate]);
                    $income = $income->whereBetween('date', [$fromdate, $todate]);

                    //pediatric Total income and expense
                    $pediatric_income = $pediatric_income->whereBetween('date', [$fromdate, $todate]);
                    $pediatric_expense = $pediatric_expense->whereBetween('date', [$fromdate, $todate]);
                    $pediatric_indoorBook = $pediatric_indoorBook->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $pediatric_indoorCaseDeposit = $pediatric_indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    //medicare Total income and expense
                    $medicare_income = $medicare_income->whereBetween('date', [$fromdate, $todate]);
                    $medicare_expense = $medicare_expense->whereBetween('date', [$fromdate, $todate]);
                    $medicare_indoorBook = $medicare_indoorBook->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $medicare_indoorCaseDeposit = $medicare_indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    
                }
                // $usgGrandTotal = $usg->sum('usg');
                $incomeGrandTotal = $income->sum('amount');
                $expenseGrandTotal = $expense->sum('amount');

                $pediatric_income = $pediatric_income->sum('amount') + $pediatric_indoorBook->get()->sum('getInvoice.grand_total_amt') + $pediatric_indoorCaseDeposit->sum('amount');
                $pediatric_expense = $pediatric_expense->sum('amount');

                $medicare_income = $medicare_income->sum('amount') + $medicare_indoorBook->get()->sum('getInvoice.grand_total_amt') + $medicare_indoorCaseDeposit->sum('amount');
                $medicare_expense = $medicare_expense->sum('amount');

                if($request->isprint==1){
                    $usg = $usg->get();
                    $hormon = $hormon->get();
                    $iui = $iui->get();
                    $ivf = $ivf->get();
                    $income = $income->get();
                    $expense = $expense->get();
                    $collectionReport = $collectionReport->get();
                    $reportDatails['total'] = $collectionReport->sum('netamount');
                    $reportDatails['count'] = $collectionReport->count();

                    return response()->json([
                        View::make('admin.report.collection.all_collection_preview',compact('pediatric_income','pediatric_expense','medicare_income','medicare_expense','collectionReport','reportDatails', 'expense', 'expenseGrandTotal', 'hormon', 'iui', 'ivf', 'income', 'incomeGrandTotal', 'usg', 'indoorCash', 'indoorCaseDeposit','ivfCash', 'iuiCash', 'ancCash','gynecCash','procedures','indoorDebit'))->render()
                    ]);
                }
                $collectionReport = $collectionReport->get();
                $reportDatails['total'] = $collectionReport->sum('netamount');
                $reportDatails['count'] = $collectionReport->count();

                $usg = $usg->get();
                $hormon = $hormon->get();
                $iui = $iui->get();
                $ivf = $ivf->get();
                $income = $income->get();
                $expense = $expense->get();

                return response()->json([
                    View::make('admin.report.collection.all_collection_data',compact('pediatric_income','pediatric_expense','medicare_income','medicare_expense','collectionReport','reportDatails', 'expense', 'expenseGrandTotal', 'hormon', 'iui', 'ivf', 'income', 'incomeGrandTotal', 'usg', 'indoorCash', 'indoorCaseDeposit','ivfCash', 'iuiCash', 'ancCash','gynecCash','procedures','indoorDebit'))->render()
                ]);
            }
            return view('admin.report.collection.index',compact('referenceDoctor'));
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }
    /**
    * Get Hormon Collection Report
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getHormonInjectionReport(Request $request)
    {
        try{
            $injection = $this->InjectionCharge->pluck('name','name');
            $patients = $this->OpdPatients->where('is_approved',1)->pluck('name','id')->toArray();
            if($request->ajax()){
                $injManager = $this->InjectionManager;
                if(!empty($request->inj))
                {
                    $injManager = $injManager->where('injection',$request->inj)->orderBy('id', 'DESC');
                }
                if(!empty($request->patients))
                {
                    $patientId = $request->patients;
                    $injManager = $injManager->where(function($query) use($patientId){
                        $query->whereHas('getPatientsDetails', function($query) use($patientId) {
                            $query->where('id', $patientId);
                        });
                    });
                }
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $fromdate = $fromdate;
                    $todate = $todate;
                    $injManager = $injManager->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                }
                $injManager = collect($injManager->get());
                $injManager = $injManager->groupBy('injection');
                // dd($injManager);
                if($request->isprint==1){
                    return response()->json([
                        View::make('admin.report.hormon_injection.preview', compact('injManager'))->render()
                    ]);
                }

                $data['status'] = 1;
                $data['report_data'] = View::make('admin.report.hormon_injection.data',compact('injManager'))->render();
                return $data;
            }
            return view('admin.report.hormon_injection.index',compact('injection','patients'));
        }catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
    /**
    * Get Pediatric Collection Report
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getPedCollection(Request $request)
    {
        try{
            if($request->ajax())
            {
                $paymentType = $request->payment_type;
                //opd
                
                $category = $this->ExpenseCategory->where('is_pediatric',1)->whereType('1')->whereStatus('1')->pluck('id','id');
                $income = $this->IncomeManager->whereIn('income_category',$category);
                $expenseCategory = $this->ExpenseCategory->where('is_pediatric',1)->whereType('2')->whereStatus('1')->pluck('id','id');
                $expense = $this->ExpenseManager->whereIn('expense_category',$expenseCategory);
                //ipd
                $indoorBook = $this->IndoorBook->with('getInvoice')->where('is_pediatric_patient',1)->where('is_final_invoice',1)->whereNotNull('final_invoice_date')->orderBy('id','DESC');
                $indoorCaseDeposit = $this->IndoorDeposit->where('is_pediatric',1)->whereCaseTypeAndChargeType('Credit', 4)->with('getPatients')->orderBy('id', 'DESC');
                
                if(!empty($paymentType) || $paymentType != 0)
                {
                    $indoorCaseDeposit = $indoorCaseDeposit->where('payment_type',$paymentType);
                    $paymentMethodValueData = [1=>2,2=>1,3=>3,4=>4,5=>5];
                    $incomePaymentType = $paymentMethodValueData[$paymentType];
                    $income = $this->IncomeManager->whereIn('income_category',$category)->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                    )->orderBy('id', 'desc');
                    $expense = $this->ExpenseManager->whereIn('expense_category',$expenseCategory)->where('payment_method',$incomePaymentType)->select("*",
                        \DB::raw('
                            (CASE
                                WHEN payment_method = "1" THEN "Cash"
                                WHEN payment_method = "2" THEN "Swipe"
                                WHEN payment_method = "3" THEN "Cheque"
                                WHEN payment_method = "4" THEN "UPI"
                                WHEN payment_method = "4" THEN "NEFT"
                            END)
                            AS payment_mode')
                            )->orderBy('id', 'desc');
                    $indoorBook = $indoorBook->where(function($query) use($paymentType){
                            $query->whereHas('getInvoice', function($query) use($paymentType) {
                                $query->where('payment_mode', $paymentType);
                            });
                        });
                }
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $fromdate = $fromdate;
                    $todate = $todate;
                    $expense = $expense->whereBetween('date', [$fromdate, $todate]);
                    $income = $income->whereBetween('date', [$fromdate, $todate]);
                    $indoorBook = $indoorBook->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorCaseDeposit = $indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    
                }
                $income = collect($income->get())->map(function ($query){
                    $query->income_category = $query->getExpenseCategory['name'];
                    return $query;
                });
                $income = $income->groupBy('income_category');

                $expense = collect($expense->get())->map(function ($query){
                    $query->expense_category = $query->getExpenseCategory['name'];
                    return $query;
                });
                $expense = $expense->groupBy('expense_category');
                $indoorBook = collect($indoorBook->get())
                    ->map(function ($query) {
                        $procedureName = implode(', ', $this->IndoorProcedure
                            ->whereIn('id', explode(',', $query->procedure_id))
                            ->pluck('name')
                            ->toArray());
                        $query->procedure_name = $procedureName;
                        $query->date = $query->final_invoice_date;
                        return $query;
                    });
                $indoorCaseDeposit = collect($indoorCaseDeposit->get())
                    ->map(function ($query) {
                        $procedureName = implode(', ', $this->IndoorProcedure
                            ->whereIn('id', explode(',', $query->procedure_id))
                            ->pluck('name')
                            ->toArray());
                        $query->procedure_name = $procedureName;
                        // $query->date = $query->final_invoice_date;
                        return $query;
                    });
                if($request->isprint == 1)
                {
                    $data['status'] = 1;
                    $data['report_data'] = View::make('admin.report.pediatric.preview',compact('income','expense','indoorBook','indoorCaseDeposit'))->render();
                    return $data;
                }
                $data['status'] = 1;
                $data['report_data'] = View::make('admin.report.pediatric.data',compact('income','expense','indoorBook','indoorCaseDeposit'))->render();
                return $data;
            }
            return view('admin.report.pediatric.index');
        }
        catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }

    public function analysisReport(Request $request) {
        try{
            $data_total = $this->Appointment->whereHas('getAppointmentCharges')->where('is_done',1)->whereIn('category_id',[3,4])->groupBy('patients_id')->orderBy('id','DESC')->get();
            $total_consive = $this->IuiHistory::where('visit', 4 )->whereHas('getPatientsDetails')->where('description->result','like', '%consive%')->groupBy('patients_id')->orderBy('id','DESC')->get();
            $total_fail = $this->IuiHistory::where('visit', 4 )->whereHas('getPatientsDetails')->where('description->result','like', '%fail%')->groupBy('patients_id')->orderBy('id','DESC')->get();
            // \DB::enableQueryLog();
            $data_pending_result = $this->IuiHistory->where(function($query){
                $query->whereHas('lastAppointmentData', function($query) {
                            return $query->whereIn('category_id',[3,4]);
                        });
                })->whereIn('iui_history.id', function($query){
                $query->selectRaw('max(`id`)')
                    ->from('iui_history')
                    ->groupBy('iui_history.patients_id');
                })
            ->select('iui_history.*');
            // dd(\DB::getQueryLog());
            $pending_result = collect($data_pending_result->orderBy('iui_history.created_at', 'desc')->get())
                ->map(function ($query) {
                    $description = json_decode($query->description,true);
                    if(($query->visit == 3 || $query->visit == 4) && (isset($description['ovalution']) && $description['ovalution'] == 'yes' ) || (isset($description['upt_type']) && $description['upt_type'] == 'weak_positive'))
                    {
                        return $query;
                    }
                })->pluck('patients_id','patients_id');               
            if($request->ajax())
            {
                $data_plan_type = [];
                $injection_type = '';         
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                $follicle = $request->follicle;
                $data_newIUI = $this->Appointment->whereHas('getAppointmentCharges')->where('is_done',1)->whereIn('category_id',[3])->groupBy('patients_id')->orderBy('id','DESC');
                $data_oldinf = $this->IuiHistory->groupBy('patients_id')->whereHas('getPatientsDetails');
                $data_fail = $this->IuiHistory::where('visit', 4 )->whereHas('getPatientsDetails')->where('description->result','like', '%fail%')->orderBy('id','DESC');
                $data_consive = $this->IuiHistory::where('visit', 4 )->whereHas('getPatientsDetails')->where('description->result','like', '%consive%')->orderBy('id','DESC');
                // $data_dropIds = $this->Appointment->select('*',\DB::raw('max(id) as appointment_id'))->where('is_done',1)->groupBy('patients_id')->orderBy('id','DESC')->pluck('appointment_id','appointment_id')->toArray();
                $data_dropIds = $this->Appointment->orderBy('created_at','desc')->where('is_done',1)->get()->unique('patients_id')->pluck('appointment_id','appointment_id')->toArray();
                $data_drop = $this->Appointment->whereIn('id',$data_dropIds)->where('date', '<', (new \Carbon\Carbon)->submonths(1))->whereIn('category_id',[3,4]);
                $data_continue = $this->IuiHistory->orderBy('created_at','desc')->where(function($query){
                    $query->whereHas('lastAppointmentData', function($query) {
                                return $query->whereIn('category_id',[3,4]);
                            });
                })->get()->unique('patients_id');
                $data_skip = $this->IuiHistory->whereHas('getPatientsDetails')->where('description->skip_cycle','yes')->where(function($query){
                    $query->whereHas('lastAppointmentData', function($query) {
                        return $query->whereCategoryId(4);
                    });
                })->orderBy('id','DESC');
                $pending_result = collect($data_pending_result->orderBy('iui_history.created_at', 'desc')->get())
                    ->map(function ($query) {
                    $description = json_decode($query->description,true);
                    if(($query->visit == 3 || $query->visit == 4) && ((isset($description['ovalution']) && $description['ovalution'] == 'yes' ) || (isset($description['upt_type']) && $description['upt_type'] == 'weak_positive')))
                    {
                        return $query;
                    }
                })->pluck('patients_id','patients_id');
                if(!empty($request->injection_type) || !empty($request->plan_type))
                {
                    $data_plan_type = $this->IuiHistory::where('visit', 2 )->whereHas('getPatientsDetails');
                    if(!empty($request->injection_type))
                    {
                        $injection_type = $this->Injection->where('category',1)->where('id',$request->injection_type)->value('name');
                        $data_plan_type = $data_plan_type->where('description','like', '%'.$injection_type.'%');
                    }
                    if(!empty($request->plan_type))
                    {
                        $plan_type = $this->Injection->where('category',1)->where('id',$request->plan_type)->value('type');
                        $data_plan_type = $data_plan_type->where('description','like', '%'.$plan_type.'%');
                    }
                    if($fromdate || $todate)
                    {
                        $fromdate = $fromdate;
                        $todate = $todate;
                        $data_plan_type = $data_plan_type->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    }
                    $data_plan_type = $data_plan_type->groupBy('patients_id')->orderBy('id','DESC')->pluck('patients_id','patients_id')->toArray();
                }
                
                if($fromdate || $todate){
                    $fromdate = $fromdate;
                    $todate = $todate;
                    $data_newIUI = $data_newIUI->whereBetween(\DB::raw('DATE(date)'), [$fromdate, $todate]);
                    $data_oldinf = $data_oldinf->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    $data_continue = $this->IuiHistory->orderBy('created_at','desc')->where(function($query){
                        $query->whereHas('lastAppointmentData', function($query) {
                                    return $query->whereIn('category_id',[3,4]);
                                });
                    })->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate])->get()->unique('patients_id');
                    // $data_continue = $data_continue->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    $pending_result = collect($data_pending_result->orderBy('iui_history.created_at', 'desc')->get())
                    ->map(function ($query) use($fromdate, $todate) {
                        $description = json_decode($query->description,true);
                        return $query->cycle_status == 1 && isset($description['new_follow_up']) && (carbon::parse($description['new_follow_up'])->format('Y-m-d') >= $fromdate && carbon::parse($description['new_follow_up'])->format('Y-m-d') <= $todate) && ($query->visit == 3 || $query->visit == 4) && ((isset($description['ovalution']) && $description['ovalution'] == 'yes' ) || (isset($description['upt_type']) && $description['upt_type'] == 'weak_positive')) ? $query : [];
                    })->pluck('patients_id','patients_id')->toArray();
                    // dd(array_filter($pending_result));
                }
                
                if(!empty($request->injection_type) || !empty($request->plan_type))
                {
                    $injection_type = $this->Injection->where('category',1)->where('id',$request->injection_type)->value('name');
                    $plan_type = $this->Injection->where('category',1)->where('id',$request->plan_type)->value('type');
                     // plan is given in january  then consider result(conceive/fail/skip) in january
                    $data_fail_Id = collect($data_fail->get())->map(function($value) use($injection_type,$fromdate,$todate,$plan_type){
                        $description = !empty($value->getIuiSecondVisitCycleWise()) && !empty($value->getIuiSecondVisitCycleWise()->description) ? json_decode($value->getIuiSecondVisitCycleWise()->description,true) : null;
                        $plan_given_date = !empty($value->getIuiSecondVisitCycleWise()->created_at) && carbon::parse($value->getIuiSecondVisitCycleWise()->created_at)->format('Y-m-d') >= $fromdate && carbon::parse($value->getIuiSecondVisitCycleWise()->created_at)->format('Y-m-d') <= $todate ? 1 : 0;
                        if(!empty($injection_type) && empty($plan_type))
                        {
                            return !empty($description) && isset($description['plan']['agenet'][0]) && $description['plan']['agenet'][0] == $injection_type &&  $plan_given_date == 1 ? $value : [];
                        }
                        if(!empty($plan_type) && empty($injection_type))
                        {
                            return !empty($description) && isset($description['plan']['plan_type']) && $description['plan']['plan_type'] == $plan_type &&  $plan_given_date == 1 ? $value : [];
                        }
                        if(!empty($plan_type) && !empty($injection_type))
                        {
                            return !empty($description) && isset($description['plan']['plan_type']) && $description['plan']['plan_type'] == $plan_type && isset($description['plan']['agenet'][0]) && $description['plan']['agenet'][0] == $injection_type &&  $plan_given_date == 1 ? $value : [];
                        }
                        
                    })->pluck('patients_id','patients_id');
                    $data_fail = $data_fail->whereIn('patients_id',$data_fail_Id);  

                    $data_consive_Id = collect($data_consive->get())->map(function($value) use($injection_type,$fromdate,$todate,$plan_type){
                        

                        $description = !empty($value->getIuiSecondVisitCycleWise()) && !empty($value->getIuiSecondVisitCycleWise()->description) ? json_decode($value->getIuiSecondVisitCycleWise()->description,true) : null;
                        $plan_given_date = !empty($value->getIuiSecondVisitCycleWise()->created_at) && carbon::parse($value->getIuiSecondVisitCycleWise()->created_at)->format('Y-m-d') >= $fromdate && carbon::parse($value->getIuiSecondVisitCycleWise()->created_at)->format('Y-m-d') <= $todate ? 1 : 0;
                        
                        if(!empty($injection_type) && empty($plan_type))
                        {
                            return !empty($description) && isset($description['plan']['agenet'][0]) && $description['plan']['agenet'][0] == $injection_type &&  $plan_given_date == 1 ? $value : [];
                        }
                        if(!empty($plan_type) && empty($injection_type))
                        {
                            return !empty($description) && isset($description['plan']['plan_type']) && $description['plan']['plan_type'] == $plan_type &&  $plan_given_date == 1 ? $value : [];
                        }
                        if(!empty($plan_type) && !empty($injection_type))
                        { 
                            return !empty($description) && isset($description['plan']['plan_type']) && $description['plan']['plan_type'] == $plan_type && isset($description['plan']['agenet'][0]) && $description['plan']['agenet'][0] == $injection_type &&  $plan_given_date == 1 ? $value : [];
                        }
                    })->pluck('patients_id','patients_id');
                    $data_consive = $data_consive->whereIn('patients_id',$data_consive_Id);
                    
                    //continue with selected plan(not skip and not fillup result visit)
                    $data_continue_Id = collect($data_continue)->map(function($value) use($injection_type,$fromdate,$todate,$plan_type){
                        $description = !empty($value->getIuiSecondVisitCycleWise()) && !empty($value->getIuiSecondVisitCycleWise()->description) ? json_decode($value->getIuiSecondVisitCycleWise()->description,true) : null;
                        if(!empty($injection_type) && empty($plan_type))
                        {
                            return !empty($description) && isset($description['plan']['agenet'][0]) && $description['plan']['agenet'][0] == $injection_type && empty($value->getIuiForthVisitCycleWise()) && empty($value->checkIuiHistorySkip()) ? $value : [];
                        }
                        if(!empty($plan_type) && empty($injection_type))
                        {
                            return !empty($description) && isset($description['plan']['plan_type']) && $description['plan']['plan_type'] == $plan_type && empty($value->getIuiForthVisitCycleWise()) && empty($value->checkIuiHistorySkip()) ? $value : [];
                        }
                        if(!empty($injection_type) && !empty($plan_type))
                        {
                            return !empty($description) && isset($description['plan']['agenet'][0]) && $description['plan']['agenet'][0] == $injection_type && isset($description['plan']['plan_type']) && $description['plan']['plan_type'] == $plan_type && empty($value->getIuiForthVisitCycleWise()) && empty($value->checkIuiHistorySkip()) ? $value : [];
                        }
                    })->pluck('patients_id','patients_id');
                    $data_continue = $this->IuiHistory->whereIn('patients_id',$data_continue_Id);

                    $data_skip_Id = collect($data_skip->get())->map(function($value) use($injection_type,$fromdate,$todate,$plan_type){
                        $description = !empty($value->getIuiSecondVisitCycleWise()) && !empty($value->getIuiSecondVisitCycleWise()->description) ? json_decode($value->getIuiSecondVisitCycleWise()->description,true) : null;
                        $plan_given_date = !empty($value->getIuiSecondVisitCycleWise()->created_at) && carbon::parse($value->getIuiSecondVisitCycleWise()->created_at)->format('Y-m-d') >= $fromdate && carbon::parse($value->getIuiSecondVisitCycleWise()->created_at)->format('Y-m-d') <= $todate ? 1 : 0;
                        if(!empty($injection_type) && empty($plan_type))
                        {
                            return !empty($description)&& isset($description['plan']['agenet'][0]) && $description['plan']['agenet'][0] == $injection_type &&  $plan_given_date == 1 ? $value : [];
                        }
                        if(!empty($plan_type) && empty($injection_type))
                        {
                            return !empty($description) && isset($description['plan']['plan_type']) && $description['plan']['plan_type'] == $plan_type &&  $plan_given_date == 1 ? $value : [];
                        }
                        if(!empty($injection_type) && !empty($plan_type))
                        {
                            return !empty($description) && isset($description['plan']['agenet'][0]) && $description['plan']['agenet'][0] == $injection_type && isset($description['plan']['plan_type']) && $description['plan']['plan_type'] == $plan_type && $plan_given_date == 1  ? $value : [];
                        }
                    })->pluck('patients_id','patients_id');
                    $data_skip = $data_skip->whereIn('patients_id',$data_skip_Id); 

                    $pending_result = collect($data_pending_result->orderBy('iui_history.created_at', 'desc')->get())
                        ->map(function ($value) use($fromdate, $todate,$injection_type,$plan_type) {
                            $description = json_decode($value->description,true);
                            $second_visit_data = !empty($value->getIuiSecondVisitCycleWise()) && !empty($value->getIuiSecondVisitCycleWise()->description) ? json_decode($value->getIuiSecondVisitCycleWise()->description,true) : null;
                            $is_result = $value->cycle_status == 1 && isset($description['new_follow_up']) && (carbon::parse($description['new_follow_up'])->format('Y-m-d') >= $fromdate && carbon::parse($description['new_follow_up'])->format('Y-m-d') <= $todate) && ($value->visit == 3 || $value->visit == 4) && ((isset($description['ovalution']) && $description['ovalution'] == 'yes' ) || (isset($description['upt_type']) && $description['upt_type'] == 'weak_positive')) ? 1 : 0;
                            
                            if(!empty($injection_type) && empty($plan_type))
                            {
                                return !empty($second_visit_data) && isset($second_visit_data['plan']['agenet'][0]) && $second_visit_data['plan']['agenet'][0] == $injection_type &&  $is_result == 1 ? $value : [];
                            }
                            if(!empty($plan_type) && empty($injection_type))
                            {
                                return !empty($second_visit_data) && isset($second_visit_data['plan']['plan_type']) && $second_visit_data['plan']['plan_type'] == $plan_type &&  $is_result == 1 ? $value : [];
                            }
                            if(!empty($injection_type) && !empty($plan_type))
                            {
                                return !empty($second_visit_data) && isset($second_visit_data['plan']['agenet'][0]) && $second_visit_data['plan']['agenet'][0] == $injection_type && isset($second_visit_data['plan']['plan_type']) && $second_visit_data['plan']['plan_type'] == $plan_type && $is_result == 1  ? $value : [];
                            }
                        })->pluck('patients_id','patients_id')->toArray();
                }
                else
                {
                    $data_drop = $data_drop->whereBetween(\DB::raw('DATE(date)'), [$fromdate, $todate]);
                    $data_consive = $data_consive->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    $data_fail = $data_fail->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    $data_skip = $data_skip->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                }
                $data_newIUI = $data_newIUI->pluck('patients_id','patients_id')->toArray();
                $data_oldinf = $data_oldinf->pluck('patients_id','patients_id')->toArray();
                $data_fail = $data_fail->pluck('patients_id','patients_id')->toArray();
                $data_drop = $data_drop->pluck('patients_id','patients_id')->toArray();
                $data_consive = $data_consive->pluck('patients_id','patients_id')->toArray();
                $data_continue = $data_continue->pluck('patients_id','patients_id')->toArray();
                $data_skip = $data_skip->pluck('patients_id','patients_id')->toArray();
                $patients = $this->OpdPatients;
                $key = $request->key;
                // if($key == 'total')
                // {
                //     $patients = $patients->whereIn('id',$data_total);
                // }
                if($key == 'new-inf')
                {
                    $patients = $patients->whereIn('id',$data_newIUI);
                }
                if($key == 'old-inf')
                {
                    $old_patients = array_diff($data_oldinf,$data_newIUI);
                    // dd()
                    $patients = $patients->whereIn('id',$data_oldinf)->whereNotIn('id',$data_newIUI);
                }
                if($key == 'fail-inf')
                {
                    $patients = $patients->whereIn('id',$data_fail);
                }
                if($key == 'plan-inf')
                {
                    $patients = $patients->whereIn('id',$data_plan_type);
                }
                if($key == 'drop-inf')
                {
                    // $continue_ids = array_diff($data_total,$data_drop);
                    $patients = $patients->whereIn('id',$data_drop);
                }
                if($key == 'consive-inf')
                {
                    $patients = $patients->whereIn('id',$data_consive);
                }
                if($key == 'continue-inf')
                {
                    // $continue_ids = ($data['total'] - $data['drop']) > 0 ? array_diff($data_total,$data_drop) : [];
                    $patients = $patients->whereIn('id',$data_continue);
                }
                if($key == 'skip-inf')
                {
                    $patients = $patients->whereIn('id',$data_skip);
                }
                if($key == 'pending-result')
                {
                    $patients = $patients->whereIn('id',array_filter($pending_result));
                }
                if(!empty($request->search))
                {
                    $search = $request->search;
                    $patients = $patients->where('name','LIKE',$search.'%')->orWhere('mobile_number','LIKE',$search.'%');
                }
                if(!empty($request->age))
                {
                    // $age = $request->age;
                    $age = explode("-",$request->age);
                    $startAge = $age[0];
                    $endAge = $age[1];
                    // $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    $patients = $patients->whereBetween('age', [$startAge, $endAge]);
                }
                $data['newinf'] = count($data_newIUI);
                $data['oldinf'] =  count(array_diff($data_oldinf,$data_newIUI));
                $data['fail'] = count($data_fail);
                $data['plan_type'] = count($data_plan_type);
                $data['drop'] = count($data_drop);
                $data['consive'] = count($data_consive);
                $data['continue'] = count($data_continue);
                $data['skip'] = count($data_skip);
                $data['pending_result'] = count(array_filter($pending_result));
                $data['patients'] = $patients->get();
                $data['status'] = 1;
                $data['report_data'] = View::make('admin.report.analysis.data',compact('data'))->render();
                return $data;
            }
            $total_IUI = count($data_total);
            $total_consive = count($total_consive);
            $total_fail = count($total_fail);
            $data_pending_result = count($pending_result);


            $planType = $this->Injection->where('category',1)->groupBy('type')->pluck('type','id');
            $planData = $this->Injection->where('category',1)->pluck('name','id');

            return view('admin.report.analysis.index',compact('planType','planData','total_IUI','total_consive','total_fail','data_pending_result'));
        }
        catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
     /**
    * Get Medicare Collection Report
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getmedicareCollection(Request $request)
    {
        try{
            if($request->ajax())
            {
                $paymentType = $request->payment_type;
                //opd
                
                $category = $this->ExpenseCategory->where('is_medicare',1)->whereType('1')->whereStatus('1')->pluck('id','id');
                $income = $this->IncomeManager->whereIn('income_category',$category);
                $expenseCategory = $this->ExpenseCategory->where('is_medicare',1)->whereType('2')->whereStatus('1')->pluck('id','id');
                $expense = $this->ExpenseManager->whereIn('expense_category',$expenseCategory);
                //ipd
                $indoorBook = $this->IndoorBook->with('getInvoice')->where('is_medicare_patient',1)->where('is_final_invoice',1)->whereNotNull('final_invoice_date')->orderBy('id','DESC');
                $indoorCaseDeposit = $this->IndoorDeposit->where('is_medicare',1)->whereCaseTypeAndChargeType('Credit', 4)->with('getPatients')->orderBy('id', 'DESC');
                
                if(!empty($paymentType) || $paymentType != 0)
                {
                    $indoorCaseDeposit = $indoorCaseDeposit->where('payment_type',$paymentType);
                    $paymentMethodValueData = [1=>2,2=>1,3=>3,4=>4,5=>5];
                    $incomePaymentType = $paymentMethodValueData[$paymentType];
                    $income = $this->IncomeManager->whereIn('income_category',$category)->where('payment_method',$incomePaymentType)->select("*",
                    \DB::raw('
                        (CASE
                            WHEN payment_method = "1" THEN "Cash"
                            WHEN payment_method = "2" THEN "Swipe"
                            WHEN payment_method = "3" THEN "Cheque"
                            WHEN payment_method = "4" THEN "UPI"
                            WHEN payment_method = "4" THEN "NEFT"
                        END)
                        AS payment_mode')
                    )->orderBy('id', 'desc');
                    $expense = $this->ExpenseManager->whereIn('expense_category',$expenseCategory)->where('payment_method',$incomePaymentType)->select("*",
                        \DB::raw('
                            (CASE
                                WHEN payment_method = "1" THEN "Cash"
                                WHEN payment_method = "2" THEN "Swipe"
                                WHEN payment_method = "3" THEN "Cheque"
                                WHEN payment_method = "4" THEN "UPI"
                                WHEN payment_method = "4" THEN "NEFT"
                            END)
                            AS payment_mode')
                            )->orderBy('id', 'desc');
                    $indoorBook = $indoorBook->where(function($query) use($paymentType){
                            $query->whereHas('getInvoice', function($query) use($paymentType) {
                                $query->where('payment_mode', $paymentType);
                            });
                        });
                }
                $fromdate = $request->fromdate;
                $todate = $request->todate;
                if($fromdate || $todate){
                    $fromdate = $fromdate;
                    $todate = $todate;
                    $expense = $expense->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    $income = $income->whereBetween(\DB::raw('DATE(created_at)'), [$fromdate, $todate]);
                    $indoorBook = $indoorBook->whereBetween('final_invoice_date', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                    $indoorCaseDeposit = $indoorCaseDeposit->whereBetween('created_at', [$fromdate . ' 00:00:00', $todate . ' 23:59:59']);
                }
                $income = collect($income->get())->map(function ($query){
                    $query->income_category = $query->getExpenseCategory['name'];
                    return $query;
                });
                $income = $income->groupBy('income_category');

                $expense = collect($expense->get())->map(function ($query){
                    $query->expense_category = $query->getExpenseCategory['name'];
                    return $query;
                });
                $expense = $expense->groupBy('expense_category');
                $indoorBook = collect($indoorBook->get())
                    ->map(function ($query) {
                        $procedureName = implode(', ', $this->IndoorProcedure
                            ->whereIn('id', explode(',', $query->procedure_id))
                            ->pluck('name')
                            ->toArray());
                        $query->procedure_name = $procedureName;
                        $query->date = $query->final_invoice_date;
                        return $query;
                    });
                    $indoorCaseDeposit = collect($indoorCaseDeposit->get())
                    ->map(function ($query) {
                        $procedureName = implode(', ', $this->IndoorProcedure
                            ->whereIn('id', explode(',', $query->procedure_id))
                            ->pluck('name')
                            ->toArray());
                        $query->procedure_name = $procedureName;
                        // $query->date = $query->final_invoice_date;
                        return $query;
                    });
                if($request->isprint == 1)
                {
                    $data['status'] = 1;
                    $data['report_data'] = View::make('admin.report.medicare.preview',compact('income','expense','indoorBook','indoorCaseDeposit'))->render();
                    return $data;
                }
                $data['status'] = 1;
                $data['report_data'] = View::make('admin.report.medicare.data',compact('income','expense','indoorBook','indoorCaseDeposit'))->render();
                return $data;
            }
            return view('admin.report.medicare.index');
        }
        catch(Exception $e){
            log::debug($e);
            abort(500);
        }
    }
}
