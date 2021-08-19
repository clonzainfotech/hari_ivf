<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use View;
use Log;
use Carbon\Carbon;

class HomeController extends AdminController
{
    /**
    * Return on dashboard with all required data
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function dashboard() {
        $todayDate = \Carbon\Carbon::now()->format('Y-m-d');
        $hormonTotal = $appointmentChargesTotal = $incomeTotal = $expenseTotal = $balance = $profit = 0;
        $hormonTotal = $this->Hormon->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $todayDate)->sum('charge');
        $appointmentChargesTotal = $this->AppointmentCharges->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $todayDate)->sum('netamount');
        $incomeTotal = $this->IncomeManager->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $todayDate)->sum('amount');
        $expenseTotal = $this->ExpenseManager->where('date', $todayDate)->sum('amount');
        $balance = $hormonTotal + $appointmentChargesTotal + $incomeTotal - $expenseTotal;
        $profit = $hormonTotal + $appointmentChargesTotal + $incomeTotal;
        $inddorcount=$this->IndoorBook->where('is_direct_discharge',0)->where('type_id','>',0)->count();
        $categories = $this->Category->pluck('name', 'id');

        $patientIds = $this->Appointment->where('category_id', '=', 7)->pluck('patients_id')->toArray();
        $patientCount = $this->OpdPatients->whereNotIn('id', $patientIds)->count();
        // dd($patientCount);
        $category = $this->Category->where('id', '!=', 7)->get();
        $referenceDoctor = $this->ReferenceDoctor->count();

        $eventDays=Carbon::today()->format('Y-m-d');
        $event = $this->Event->where('end_date' ,'>=',$eventDays)->where('status',1)->orderBy(DB::raw("DATE_FORMAT(start_date,'%d-%m-%Y')"), 'asc')->get();
        // $event = $this->Event->orderBy(DB::raw("DATE_FORMAT(start_date,'%d-%m-%Y')"), 'asc')->get();

        if (count($category) > 0) {
            foreach ($category as $category) {
                $categoryId = $category->id;
                $categoryName = $category->name;
                $catArr = [];
                $catArr['catname'] = $categoryName;
                $catArr['catdata'] = $this->OpdPatients
                    ->where(function($query) use ($categoryId) {
                        $query->whereHas('getAppointments', function($query) use ($categoryId){
                            return $query->whereCategoryId($categoryId);
                        });
                    })->count();
                $categoryProgress[$categoryId] = $catArr;
            }
        } else {
            $categoryProgress=0;
        }

       $ancPatients = count($this->ANC
                             ->select('*')
                             ->groupBy('patients_id')
                             ->get());
        $iuiPatients = $this->IUI->count();
        $ivfPatients = $this->IVF->count();
        $total = $iuiPatients + $ivfPatients + $ancPatients;
       if($total != 0){
        $ancCount = ($ancPatients  / $total) * 100;
        $iuiCount = ($iuiPatients / $total) * 100;
        $ivfCount = ($ivfPatients / $total) * 100;
        }else{
            $ancCount= 0;
            $iuiCount= 0;
            $ivfCount= 0;
        }

        $reviewRoles = $this->ReviewRole->get();

        $reviewAvg = [];
        foreach ($reviewRoles as $role) {
            $roleId = $role->id;
            $reviewAvg[$role->name] = ($this->UserReview->where('role_id',$roleId)->groupBy('role_id')->avg('rate')*100)/5;
        }


        $patientsReports = $this->OpdPatients->select('city', DB::raw('COUNT(id) as patients'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('patients','desc')
            ->limit(9)
            ->get();
        $city =  $this->OpdPatients::select('city', DB::raw('COUNT(id) as patients'))
            ->groupBy('city')
            ->orderBy('patients','desc')
            ->limit(9)
            ->first();

        if(empty($city)){

        $cityMainArea=null;

        }else{
            $cityMainArea = $this->OpdPatients
            ->select('main_area',DB::raw('COUNT(id) as patients'))
            ->where('city','LIKE', '%'.$city->city .'%')
            ->groupby('main_area')
            ->orderBy('patients','desc')
            ->limit(9)
            ->get();
        }

        $newPatients = DB::table('patients')
            ->join('patients_category', 'patients_category.patients_id', '=', 'patients.id')
            ->select('patients.*','patients_category.category_id')
            ->where('patients_category.category_id',1)
            ->whereNotNull('patients.created_at')
            ->orderBy('patients.id', 'desc')
            ->groupBy('patients_category.patients_id')
            ->take(9)
            ->get();
        $newPatientData = $this->OpdPatients->paginate(100);

        // $appointmentData = collect($this->Appointment
        //     ->where('date', $todayDate)
        //     ->withCount([
        //         'getPatientsDetails' => function ($query) use ($todayDate) {
        //             $query->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $todayDate);
        //         }
        //     ])
        //     ->get());
        $appontmentCount = $this->Appointment->whereDate('date',$todayDate)->where('usg_status',0)->count();
        // $totalPatients = $appointmentData->sum('get_patients_details_count');
        $totalPatients = $this->OpdPatients->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $todayDate)->count();
        // $totalAppointments = count($appointmentData);
        $totalAppointments = $appontmentCount;
        $totalOpds = $this->AppointmentCharges->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $todayDate)->count();

        // chart data for income

        $income = $this->IncomeManager->select(
            DB::raw('sum(amount) as sums'),
            DB::raw("DATE_FORMAT(date,'%m') as monthKey")
        )
        ->whereYear('date', date('Y'))
        ->groupBy('monthKey')
        ->get();

        $incomeArray = [0,0,0,0,0,0,0,0,0,0,0,0];
        $incomeData = [];
        foreach($income as $order){
                $incomeArray[$order->monthKey-1] = $order->sums;
                $incomeData[] = $incomeArray;
        }

        // chart data for expence
        $expenses = $this->ExpenseManager->select(
                DB::raw('sum(amount) as sums'),
                DB::raw("DATE_FORMAT(date,'%m') as monthKey")
            )
            ->whereYear('date', date('Y'))
            ->groupBy('monthKey')
            ->get();


        $expense_array = [0,0,0,0,0,0,0,0,0,0,0,0];
        $expense_data = [];
        foreach($expenses as $expense){
               $expense_array[$expense->monthKey-1] = $expense->sums;
               $expense_data[] = $expense_array;
        }

        // chart data for iui
        $iui = $this->IUI->select(
            DB::raw('count(patients_id) as count'),
            DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('monthKey')
        ->get();

        $iuiArray = [0,0,0,0,0,0,0,0,0,0,0,0];
        $iuiData = [];
        foreach($iui as $order){
                $iuiArray[$order->monthKey-1] = $order->count;
                $iuiData[] = $iuiArray;
        }

        // chart data for anc
        $anc = $this->ANC->select(
            DB::raw('count(patients_id) as count'),
            DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('monthKey')
        ->get();

        $ancArray = [0,0,0,0,0,0,0,0,0,0,0,0];
        $ancData = [];
        foreach($anc as $order){
                $ancArray[$order->monthKey-1] = $order->count;
                $ancData[] = $ancArray;
        }

        // chart data for ivf
        $ivf = $this->IVF->select(
            DB::raw('count(patients_id) as count'),
            DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('monthKey')
        ->get();

        $ivfArray = [0,0,0,0,0,0,0,0,0,0,0,0];
        $ivfData = [];
        foreach($ivf as $order){
            $ivfArray[$order->monthKey-1] = $order->count;
            $ivfData[] = $ivfArray;
        }

        // data for iui avrage
        $iuidate = Carbon::now();
        $month=$iuidate->month;
        $iuiavg = $this->IUI->whereBetween('created_at', [$iuidate->setDate($iuidate->year, 1, 1)." 00:00:00", $iuidate=Carbon::now()->format('Y-m-d')." 23:59:59"])->count('patients_id')/$month;

         // data for ivf avrage
        $ivfdate = Carbon::now();
        $ivfavg = $this->IVF->whereBetween('created_at', [$ivfdate->setDate($ivfdate->year, 1, 1)." 00:00:00", $ivfdate=Carbon::now()->format('Y-m-d')." 23:59:59"])->count('patients_id')/$month;

         // data for anc avrage
        $ancdate = Carbon::now();
        $ancavg = $this->ANC->whereBetween('created_at', [$ancdate->setDate($ancdate->year, 1, 1)." 00:00:00", $ancdate=Carbon::now()->format('Y-m-d')." 23:59:59"])->count('patients_id')/$month;

        $now = Carbon::now();
        $month = $this->AppointmentCharges->whereYear('created_at', $now->year)->whereMonth('created_at', $now->month)->sum('netamount');
        $year = $this->AppointmentCharges->whereYear('created_at', $now->year)->sum('netamount');
        $day = $this->AppointmentCharges->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")'), $todayDate)->sum('netamount');
        $week = $this->AppointmentCharges->where('created_at', '>', Carbon::now()->startOfWeek())
            ->where('created_at', '<', Carbon::now()->endOfWeek())
            ->sum('netamount');
        $todayDates = \Carbon\Carbon::now()->format('Y-m-d');
        $doctorcount =  $user = $this->User->where('role','3')->where('status','1')->count();
        $usercount = $user = $this->User->whereIn('role', array(1,2,4,5,6,7,8))->where('status','1')->count();

        return view('admin.home.dashboard', compact('balance', 'profit', 'expenseTotal', 'totalAppointments',
            'totalPatients', 'totalOpds','incomeArray','expense_array','year','month','day','week','event','category', 'categories',
            'patientCount','categoryProgress','ancPatients','iuiPatients','ivfPatients','ancCount','iuiCount','ivfCount','referenceDoctor','reviewRoles','reviewAvg','newPatients',
            'newPatientData','city','patientsReports','cityMainArea','doctorcount','usercount','iuiArray','ancArray','ivfArray','iuiavg','ivfavg','ancavg','inddorcount'
        ));

    }

    /**
    * Search Patient data
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function searchPatientData(Request $request) {
        $code = $request->search_patient;
        $patientAppointments = $this->OpdPatients
            ->with([
                'getAppointments' => function ($query) use ($code) {
                    $query->orderBy('date', 'DESC');
                },
                'getAppointments.getPatientCategories',
                'getBookings' => function ($query) use ($code) {
                    $query->orderBy('doa_date', 'DESC');
                },
            ])
            ->whereCode($code)
            ->orWhere('mobile_number',$code)
            ->first();
         if ($patientAppointments == null) {
            return Redirect::route('dashboard')
                ->withInput()
                ->withErrors([
                    'search_patient' => 'Patient not found'
                ]);
        }
        $patientsid=$patientAppointments->id;
        $cName = [];
        $patientCategories = collect($this->PatientsCategory->where('patients_id',$patientsid)->groupBy('category_id')->get())->map(function($q) use(&$cName){
                                $cName[] = !empty($q->getCategories) ? ucfirst($q->getCategories['name']) : null;
                                return $cName;
                            });
        $cName = !empty($cName) ? implode(',',$cName) : null;
        if ($patientAppointments == null) {

            if (last(explode('/', url()->previous())) == 'search-patient-data') {
                return Redirect::route('dashboard')
                    ->withInput()
                    ->withErrors([
                        'search_patient' => 'Patient not found'
                    ]);
            }
            return Redirect::back()
                ->withInput()
                ->withErrors([
                    'search_patient' => 'Patient not found'
                ]);
        }

        $procedures = '';

        if (!empty($patientAppointments->getBookings[0]->procedure_id)) {
            $procedures = implode(', ', $this->IndoorProcedure
                ->whereIn('id', explode(',', $patientAppointments->getBookings[0]->procedure_id))
                ->pluck('name')
                ->toArray());
        }
               $userReview = DB::table('user_reviews')
                ->join('review_roles', 'user_reviews.role_id', '=', 'review_roles.id')
                ->select('role_id','rate','remark','review_roles.name')
                ->wherePatientId($patientsid)->where('user_reviews.status',1)
                ->get();


        $ancData = $this->AncHistory
            ->wherePatientsId($patientAppointments->id)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();

        return view('admin.home.patient_details', compact('code', 'patientAppointments', 'ancData', 'procedures','userReview','cName'));
    }

    /**
    * Get IUI, IVF and ANC appoitment
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function ancIuiIvf(Request $request){
        try{
            $doctor = $this->getDoctor();
            $patients = $this->getPatients();
            $referenceDoctor = $doctor['referenceDoctor'];
            $hospitalDoctor = $doctor['hospitalDoctor'];
            $categoryData = $this->Category->pluck('name','id');
            $categoryArray = [1,2,3,4,5,6];
            $category = collect($this->Category->whereStatus('1')->get())->map(function ($q) use($categoryArray){
                            if(in_array($q->id,$categoryArray)){
                                return $q->id;
                            }
                        })->toArray();
            $categoryId = array_values(array_filter($category));
            // $appointment = $this->Appointment->whereIn('category_id',$category);
            $nowDate = Carbon::now()->format('Y-m-d');
            $authUserId = Auth::user()->id;
            $appointment = $this->Appointment
                    ->where('is_procedure',0)
                    ->where('category_id', '!=', 7)
                    ->whereNotNull('arrival_time');
                    // ->where('seen_by',$authUserId);
                    // ->orderBy('date','ASC')
                    // ->orderBy('id','DESC');
            $patientId = $request->patient_id;
            if($patientId) {
                $appointment = $appointment->where(function($query) use($patientId) {
                    $query
                    ->whereHas('getPatientsDetails', function($query) use($patientId) {
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
            $categoryId = $request->categoryId;
            if($categoryId){
                $reportDatails['category'] = $this->Category->where('id',$categoryId)->value('name');
                $appointment = $appointment->WhereHas('categoryDetails', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            }
            $referenceDoctorId = $request->reference_doctor_id;
            $hospitalDoctorId = $request->hospital_doctor_id;
            if($referenceDoctorId){
                $appointment = $appointment->WhereHas('getPatientsDetails', function($query) use($referenceDoctorId){
                    $query->where('reference_doctor_id',$referenceDoctorId);
                });
            }
            if($hospitalDoctorId){
                $appointment = $appointment->WhereHas('getPatientsDetails', function($query) use($hospitalDoctorId){
                    $query->where('hospital_doctor_id',$hospitalDoctorId);
                });
            }

            if($request->date) {
                $date = explode("-",$request->date);
                $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                if($date){
                    $pId = $this->Appointment->whereBetween('date', [$startDate, $endDate])->pluck('patients_id','patients_id')->toArray();
                    $patients = $this->getPatients($pId);
                    $appointment = $appointment->whereBetween('date', [$startDate, $endDate]);
                }
            }
            // if($request->date && $startDate == $nowDate && $endDate == $nowDate){
            //     $appointment = $appointment->orderBy('time','asc')
            //                                 ->orderBy(DB::raw('ISNULL(arrival_time), arrival_time'), 'ASC')
            //                                 ->orderBy('date','ASC');
            // }else{
            //     $appointment = $appointment->orderBy('date','asc')
            //                                 ->orderBy('time','ASC');
            // }
            if($request->date && $startDate == $nowDate && $endDate == $nowDate){
                $appointment = $appointment
                                            ->orderBy(DB::raw('ISNULL(arrival_time), is_done'), 'ASC')
                                            ->orderBy(DB::raw('ISNULL(arrival_time), arrival_time'), 'ASC')
                                            // ->orderBy('is_done','ASC')
                                            ->orderBy('date','ASC');
            }else{
                $appointment = $appointment->orderBy('date','asc')
                                            ->orderBy('time','ASC');
            }

            if($request->ajax()){
                if($request->isprint == 1){
                    $appointment = $this->Appointment
                                    ->where('category_id', '!=', 7)
                                    ->orderBy('date','ASC')
                                    ->orderBy('id','DESC')->get();
                    $data['status'] = 2;
                    $data['appointmentData'] = View::make('admin.anc_iui_ivf.preview',compact('appointment'))->render();
                    return $data;
                }
                $appointment = $appointment->paginate(100);
                $patient_notification = $this->patientNotification->first();
                $data['status'] = 1;
                $data['patientsData'] = $patients;
                $data['pId'] = $request->patient_id;
                $data['appointmentData'] = View::make('admin.anc_iui_ivf.data',compact('patients','appointment','categoryArray','patient_notification'))->render();
                return $data;
            }
            return view('admin.anc_iui_ivf.index',compact('appointment','categoryData','referenceDoctor','doctor','hospitalDoctor','patients'));
        }catch(\Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    /**
    * Get Doctor list
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    private function getDoctor(){
        $referenceDoctor = $this->ReferenceDoctor->orderBy('name','ASC')->pluck('name','id');
        $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
        return['referenceDoctor'=>$referenceDoctor,'hospitalDoctor'=>$hospitalDoctor];
    }

    /**
    * Return PopUp detailof patient as category wise
    * @param  \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function getPatientPopUpDetail(Request $request)
    {
        $patients_id = decrypt($request->patients_id);
        $appoitmentDate = \Carbon\Carbon::parse($request->appoitmentDate)->format('Y-m-d');
        $opdPatient = $this->OpdPatients->find($patients_id);
        $planData = ['1'=>'Pick Up','2'=>'FET','3'=>'FET-OD','4'=>'FET-ED'];
        $typeOfData = [1=>'Primary',2=>'Secondary'];
        $data = '';
        if($request->category && in_array($request->category,[5,6,10,13]))
        {
            $ancHistory = $this->AncHistory->where('patients_id','=',$patients_id)
                ->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'<',$appoitmentDate)
                ->orderBy('id','desc')
                ->first();
            $lmp = '';
            $preg_week = '';
            $eddDate = '';
            $remark = '';
            $current_anc_id = null;
            $ancFirst = $this->ANC->where('patients_id',$patients_id)->orderBy('created_at','desc')->first();
            $mhData = !empty($ancFirst->m_h) ? json_decode($ancFirst->m_h) : null;
            $lmp = !empty($mhData->last_menstrual_date) ? $mhData->last_menstrual_date : null;
            $eddDate = !empty($mhData->edd) ? $mhData->edd : null;
            $current_anc_id = $ancFirst->id;
            if(empty($ancHistory))
            {
                $ancFirstH_o = !empty($ancFirst->h_o) ? json_decode($ancFirst->h_o) : null;
                $ancFirstO_e = !empty($ancFirst->o_e) ? json_decode($ancFirst->o_e) : null;
                $preg_week = !empty($ancFirstH_o->ho_details) ? $ancFirstH_o->ho_details : '';
                $remark = !empty($ancFirstO_e->remark) ? $ancFirstO_e->remark : null;
                $ancCreatedDate = $ancFirst->created_at;
            }
            else
            {
                $h_o = !empty($ancHistory->h_o) ? json_decode($ancHistory->h_o) : null;
                $o_e = !empty($ancHistory->o_e) ? json_decode($ancHistory->o_e) : null;
                $preg_week = !empty($h_o->ho_details) ? $h_o->ho_details : '';
                $remark = !empty($o_e->remark) ? $o_e->remark : null;
                $ancCreatedDate = $ancHistory->created_at;
            }
            // dd($preg_week);
            $ancAutoRemark = app('App\Http\Controllers\Admin\ANCController')->getAutoRemark($patients_id,$current_anc_id);;
            
            $html = '';
            
            if($ancAutoRemark && !empty($ancAutoRemark['blood_group']) &&  (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['blood_group_date'])))
            {
                $html =  $html . ' Blood Group : '.$ancAutoRemark['blood_group'];
            }
            
            if($ancAutoRemark && !empty($ancAutoRemark['hbsag']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['hbsag_date'])))
            {
                $html = $html.' HBSAG : '.$ancAutoRemark['hbsag'];
            }
            
            if($ancAutoRemark && !empty($ancAutoRemark['hiv']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['hiv_date'])))
            {
                $html = $html.' HIV : '.$ancAutoRemark['hiv'];
            }
            if($ancAutoRemark && !empty($ancAutoRemark['vdrl']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['vdrl_date'])))
            {
                $html = $html.' VDRL : '.$ancAutoRemark['vdrl'];
            }
            if($ancAutoRemark && !empty($ancAutoRemark['late_concept']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['late_concept_date'])))
            {
                $html = $html.' Late Conception : Yes';
            }
            if($ancAutoRemark && !empty($ancAutoRemark['cesarean']))
            {
                $html = $html.' Previous : '.$ancAutoRemark['cesarean']. ' - LSCS';
            }
            if($ancAutoRemark && !empty($ancAutoRemark['position']) && ($ancAutoRemark['position'] == 'breech' || $ancAutoRemark['position'] == 'transverse' || $ancAutoRemark['position'] == 'oblique'))
            {
                if(empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['position_date']))
                {
                    $html = $html.' Position : '.$ancAutoRemark['position'];
                }
            }
            if($ancAutoRemark && !empty($ancAutoRemark['liquor']) && ($ancAutoRemark['liquor'] == 'oligo' || $ancAutoRemark['liquor'] == 'poly') && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['liquor_date'])))
            {
                $html = $html.' Liquor : '.$ancAutoRemark['liquor'];
            }
            if($ancAutoRemark && !empty($ancAutoRemark['placenta']) && (empty($ancCreatedDate) || (!empty($ancCreatedDate) && $ancCreatedDate >= $ancAutoRemark['placenta_date'])))
            {
                $html = $html.' Placenta : '.$ancAutoRemark['placenta'];
            }
            $data = '<p><span class="font-bold candor-color">LMP Date : </span>'.\Carbon\Carbon::parse($lmp)->format('d M Y').'</p>
                    <p><span class="font-bold candor-color">EDD Date : </span>'.\Carbon\Carbon::parse($eddDate)->format('d M Y').'</p>
                    <p><span class="font-bold candor-color">Preg. Week : </span>'.$preg_week.'</p>
                    <p><span class="font-bold candor-color">Remark : </span>'.$html.'</p>
                    <p><span class="font-bold candor-color">Last Remark : </span>'.$remark.'</p>
                    <p><span class="font-bold candor-color">Ref. By : </span>'.$opdPatient->getReferenceDoctor['name'].'</p>';
        }
        if($request->category && in_array($request->category,[1,2]))
        {
            $ivf = $this->IVF->where('patients_id',$patients_id)->orderBy('id','desc')->first();
            $ml = '';
            $no_cycle = '';
            $plan_type = '';
            $remark = '';
            $package = '';
            $current_cycle = '';
            if($ivf)
            {
                $ohData = json_decode($ivf->o_h);
                $year = !empty($ohData->first_marriage_life) ? $ohData->first_marriage_life.' year' : (!empty($ohData->second_marriage_details) ? $ohData->second_marriage_details.' year': null);
                $ml = !empty($ohData->type_of_infertility) ? $typeOfData[$ohData->type_of_infertility].' / '.$year : 'Primary / '.$year;
            }
            $no_cycle = count($this->IvfHistory->where('patients_id',$patients_id)->WhereNull('description->skip_reason')->Where('description->is_transfer','=','yes')->groupBy('cycle_no')->get());
            // dd($no_cycle); 
            $currentHistory = $this->IvfHistory->where('patients_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'<',$appoitmentDate)->orderBy('id','desc')->first();
            $current_cycle = $no_cycle + 1;
            if($currentHistory)
            {
                $currentData = json_decode($currentHistory->description);
                $plan_type = $planData[$currentHistory->plan];
                $remark = !empty($currentData->remark) ? $currentData->remark : '';
            }
            $data = '<p><span class="font-bold candor-color">Marriage Life : </span>'.$ml.'</p>
            <p><span class="font-bold candor-color">No. Of Cycle : </span>'.$no_cycle.'</p>
            <p><span class="font-bold candor-color">Current Cycle : </span>'.$current_cycle.'</p>
            <p><span class="font-bold candor-color">Plan : </span>'.$plan_type.'</p>
            <p><span class="font-bold candor-color">Remark : </span>'.$remark.'</p>
            <p><span class="font-bold candor-color">Package : </span>'.'-'.'</p>';
        }
        if($request->category && in_array($request->category,[3,4]))
        {
            $iui = $this->IUI->where('patients_id',$patients_id)->orderBy('id','desc')->first();
            $ml = '';
            $no_cycle = '';
            $plan = '';
            $remark = '';
            $last_cycle_plan = '';
            $current_cycle = '';
            if($iui)
            {
                $ohData = json_decode($iui->o_h);
                $year = !empty($ohData->first_marriage_life) ? $ohData->first_marriage_life.' year' : (!empty($ohData->second_marriage_details) ? $ohData->second_marriage_details.' year': null);
                $ml = !empty($ohData->type_of_infertility) ? $typeOfData[$ohData->type_of_infertility].' / '.$year : 'Primary / '.$year;
                
            }
            $iuiFirstVisit = $this->IUI->where('patients_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'<',$appoitmentDate)->orderBy('id','desc')->first();
            if($iuiFirstVisit)
            {
                $iuiFirstVisitOh = json_decode($iuiFirstVisit->o_h);
                $remark = !empty($iuiFirstVisitOh->remark) ? $iuiFirstVisitOh->remark : '';
            }
            $no_cycle = count($this->IuiHistory->where('patients_id',$patients_id)->WhereNotNull('description->result')->groupBy('cycle_no')->get());
            // $current_cycle = $no_cycle + 1;
            $currentHistory = $this->IuiHistory->where('patients_id',$patients_id)->where(\DB::raw("(DATE_FORMAT(created_at,'%Y-%m-%d'))"),'<',$appoitmentDate)
            ->orderBy('id','desc')
            ->first();
            $iuiSecondVisit = $this->IuiHistory->where('patients_id',$patients_id)->where('visit',2)->orderBy('id','desc')->first();
            if($iuiSecondVisit)
            {
                $iuiSecondVisitData = json_decode($iuiSecondVisit->description);
                $current_cycle = $iuiSecondVisit->cycle_no;
                $lastCycle = $this->IuiHistory->where('patients_id',$patients_id)->where('visit',2)->where('cycle_no',($current_cycle-1))->first();
                if($lastCycle)
                {
                    $lastCycleVisitData = json_decode($lastCycle->description);
                    $last_cycle_plan = !empty($lastCycleVisitData->plan->plan_type) ? $lastCycleVisitData->plan->plan_type : null;
                }
            
                $plan = !empty($iuiSecondVisitData->plan->plan_type) ? $iuiSecondVisitData->plan->plan_type : null;
            }
            if($currentHistory)
            {
                $currentData = json_decode($currentHistory->description);
                $remark = !empty($currentData->remark) ? $currentData->remark : '';
            }
            $data = '<p><span class="font-bold candor-color">Marriage Life : </span>'.$ml.'</p>
            <p><span class="font-bold candor-color">No. Of Cycle : </span>'.$no_cycle.'</p>
            <p><span class="font-bold candor-color">Current Cycle : </span>'.$current_cycle.'</p>
            <p><span class="font-bold candor-color">Plan : </span>'.$plan.'</p>
            <p><span class="font-bold candor-color">Remark : </span>'.$remark.'</p>
            <p><span class="font-bold candor-color"> Last Cycle Plan: </span>'.$last_cycle_plan.'</p>';
        }
        
        return response()->json([
            'status'=>1,
            'data' => $data
        ]);                   
    }

}
