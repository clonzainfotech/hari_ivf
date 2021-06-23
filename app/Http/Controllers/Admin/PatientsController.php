<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Base\Admin\AdminController;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Validator;
use Session;
use View;
use Auth;
use Log;
use DB;


class PatientsController extends AdminController
{
    /**
    * Get Dose list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request) {
        try{
            $state = $this->getState()['state'];
            $doctor = $this->getDoctor();
            $referenceDoctor = $doctor['referenceDoctor'];
            $city = $this->City->pluck('name','name');
            $patients = collect($this->OpdPatients->orderBy('name','ASC')->pluck('name', 'id'))
                ->map(function ($query) {
                    $query = ucwords(strtolower($query));
                    return $query;
                });
            $patient = $this->OpdPatients->select("*",
                \DB::raw('
                    (CASE
                        WHEN gender = "1" THEN "Male"
                        WHEN gender = "2" THEN "Female"
                    END)
                    AS gender')
                )->orderBy('id','DESC');
            if($request->ajax()){
            
                // search text
                $patientId = $request->patient_id;

                if ($patientId) {
                    $patient = $patient->where('id', $patientId);
                }
                $referenceDoctorId = $request->reference_doctor_id;

                if($referenceDoctorId){
                    $patient = $patient->where('reference_doctor_id', $referenceDoctorId);
                }
                $search = $request->search;
                if($search){
                    $patient = $patient->where('mobile_number','LIKE',$search.'%');
                    }
                if($request->date){
                    $date = explode("-",$request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('m-d');
                    if($date){
                        $patient = $patient->whereBetween(DB::raw("(DATE_FORMAT(dob,'%m-%d'))"), [$startDate, $endDate]);

                    }
                }
                if($request->is_print == 1){
                    $patient = $this->OpdPatients->select("*",
                \DB::raw('
                    (CASE
                        WHEN gender = "1" THEN "Male"
                        WHEN gender = "2" THEN "Female"
                    END)
                    AS gender')
                )->orderBy('id','DESC')->get();
                    $data['patient'] = View::make('admin.appointment.patient.preview',compact('patients', 'patient', 'city', 'state', 'doctor', 'referenceDoctor'))->render();
                    $data['status'] = 2;
                    return response()->json($data);
                }
                $patient = $patient->paginate(100);
                $data['patient'] = View::make('admin.appointment.patient.data',compact('patients', 'patient', 'city', 'state', 'doctor', 'referenceDoctor'))->render();
                $data['status'] = 1;
                return response()->json($data);
            }
            return view('admin.appointment.patient.index', compact('patients', 'patient', 'city', 'state', 'doctor', 'referenceDoctor'));
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    /**
    * Return on appointment patient edit page
    * @param  \Illuminate\Http\Request $request,$id
    * @return \Illuminate\Http\Response
    */
    public function edit($id,Request $request) {
        try{
            $patientId = decrypt($id);
            $patient = $this->OpdPatients->find($patientId);
            $city = $this->City->pluck('name','name');
            $state = $this->getState()['state'];
            return view('admin.appointment.patient.edit', compact('patient','city','state'));
        }catch(Exception $e){
            abort(500);
        }
    }

    /**
    * Return on appointment patient create page
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function create(){
        try{
            $city = $this->City->pluck('name','name');
            $state = $this->getState()['state'];
            return view('admin.appointment.patient.edit', compact('city','state'));
        }catch(Exception $e){
            abort(500);
        }
    }
    
    /**
    * Update patient
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request,$id=null) {
        $patient = $this->OpdPatients;
        $patientId = null;
        if($id != null){
            $patientId = decrypt($id);
            $patient = $this->OpdPatients->find($patientId);
        }
        $this->validate($request,[
            'name' => 'required|min:2',
            'gender' => 'required',
            'state' => 'required',
            'mobile_number' => 'required|numeric|digits:10|unique:patients,mobile_number,'.$patientId,
            'dob' =>'nullable|before:' . date('Y-m-d')
        ]);
        try{
           
                $patient->name=$request->name;
                $patient->age=$request->age;
                $patient->months = $request->months;
                $patient->days = $request->days;
                $patient->gender=$request->gender;
                $patient->location=$request->loaction;
                $patient->residence=$request->residence;
                $patient->main_area=$request->main_area;
                $patient->city = $request->city_1;
                if($request->city_1 == 'Other'){
                    $city = $this->City;
                    $city->name = $request->city_2;
                    $city->save();
                    $patient->city = $request->city_2;
                }
                $patient->state=$request->state;
                $patient->mobile_number=$request->mobile_number;
                $patient->other_mobile_number=$request->other_mobile_number;
                $patient->email=$request->email;
                $patient->weight=$request->weight;
                $patient->height=$request->height;
                $patient->is_pregnant=$request->pregnant;
                $patient->occupation=$request->occupation;
                $patient->dob=$request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null;   
                $patient->save();
                if (!$request->code) {
                $generateCode = $this->generateCode($patient->id,$patient->name);
                $patient = $this->OpdPatients->whereId($patient->id)->first();
                $patient->code = $generateCode['code'];
                // if ($request->hasFile('image')) {
                //     $this->removeImage($patient->image);
                //     $image = $request->file('image');
                //     $profilePicture = $this->uploadImage($image, 'public/upload/patient');
                //     $patient->image = 'public/upload/patient/' . $profilePicture;
                // }
                $patient->save();
                $doctor = $this->getDoctor();
                $referenceDoctor = $doctor['referenceDoctor'];
                
            }
                return redirect('patient');
        }catch(Exception $e){
            log::Debug($e);
            abort(500);
        }
    }

    /**
    * Get patient report
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getPatientReport(Request $request)
    {
        try{
            $patientReportOpd = [];
            $patientReportHormon = [];

            $patient = $this->getPatients();
            $pMobileNumber = $this->OpdPatients->pluck('mobile_number','id')->toArray();
            if($request->ajax()){
                $patientId = $request->patient_id;
                $procedures = $this->IndoorProcedure->select('id', 'name')->get()->toArray();
                if($patientId){
                    $patientReportOpd = $this->Appointment
                                            ->wherePatientsId($patientId)
                                            ->orderBy('date', 'DESC')
                                            ->get();
                    $indoorDeposit = $this->IndoorDeposit
                                            ->wherePatientId($patientId)
                                            ->orderBy('created_at', 'DESC')
                                            ->get();
                    $patientReportOpd = $patientReportOpd->merge($indoorDeposit);
                    if($request->isprint == 1) {
                        return response()->json([
                            View::make('admin.report.patient.preview', compact('patient','procedures','patientReportOpd','patientReportHormon'))->render()
                        ]);
                    }
                }
                return response()->json([
                    View::make('admin.report.patient.data', compact('patientReportOpd','procedures','patientReportHormon', 'patient'))->render()
                ]);
            }
            return view('admin.report.patient.index',compact('patientReportOpd', 'patientReportHormon', 'patient','pMobileNumber'));
        }catch(Exception $e){
            abort(500);
        }
    }
    
     /**
    * Get state list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    private function getState(){
        $state = $this->State->pluck('name','id');
        return ['state'=>$state];
    }

    /**
    * Get docotr list
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    private function getDoctor(){
        $referenceDoctor = $this->ReferenceDoctor->orderBy('name','ASC')->pluck('name','id');

        return ['referenceDoctor' => $referenceDoctor];
    }
     /**
    * Generate patient code
    * @param  \Illuminate\Http\Request $patientsId, $name
    * @return \Illuminate\Http\Response
    */
     private function generateCode($patientsId, $name){
        $name = preg_replace('/\s+/', ' ', $name);
        $name = explode(' ', $name);
        $code = strtoupper($name[0][0]);

        $code .= (!empty($name[1])) ? strtoupper($name[1][0]) : 'R';
        $code .= (!empty($name[2])) ? strtoupper($name[2][0]) : 'R';

        $code .= $patientsId;
        $code = preg_replace('/[^A-Za-z0-9\-]/', 'R', $code);
        return ['code'=>$code];
    }
}
