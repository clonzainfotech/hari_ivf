<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\Admin\AdminController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;

class IndoorPatientController extends AdminController
{
    /**
    * Return on indoorpatient index page with patient and indoor data
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
        public function index(Request $request) {
            $indoorData = $this->IndoorBook;
            $indoorType = $this->IndoorType->where('status',1)->pluck('name', 'id');
            $procedures = $this->IndoorProcedure->pluck('name', 'id');
            $patients = $this->getPatients();
            $referenceDoctors = collect($this->ReferenceDoctor->orderBy('name','ASC')->pluck('name', 'id'))
                ->map(function ($query) {
                    $query = ucwords(strtolower($query));
                    return $query;
                });
                $hospitalDoctor = $this->User->whereRole('3')->whereStatus('1')->pluck('name','id')->toArray();
            if ($request->ajax()) {
                $patientId = $request->patient_id;
                $roomType = $request->room_type;
                $referenceDoctorId = $request->reference_doctor;
                if ($patientId) {
                    $indoorData = $indoorData->where(function($query) use($patientId) {
                    $query->whereHas('getPatientsDetails', function($query) use($patientId) {
                        $query->where('id', $patientId);
                    });
                });

                }
                if ($roomType) {
                    $indoorData = $indoorData->where(function($query) use($roomType) {
                        $query->whereHas('getRoomType', function($query) use($roomType) {
                            $query->where('id', $roomType);
                        });
                    });
                }
                if($referenceDoctorId)
                {
                    // dd($referenceDoctorId);
                    $indoorData = $indoorData->where(function($query) use($referenceDoctorId) {
                        $query->whereHas('getPatientsDetails', function($query) use($referenceDoctorId) {
                            $query->where('reference_doctor_id', $referenceDoctorId);
                        });
                    });
                }
                if($request->procudure_search)
                {
                    $procedure_search = $request->procudure_search;
                    $indoorData = $indoorData->where(function($query) use($procedure_search) {
                            $query->where('procedure_id', $procedure_search);
                    });
                }
                $search = $request->search;
                if($search){
                    $indoorData = $indoorData->where(function($query) use($search) {
                        $query
                        ->orWhereHas('getPatientsDetails', function($query) use($search) {
                            $query->where('mobile_number','LIKE',$search.'%');
                        });
                    });
                }

                if ($request->date) {
                    $date = explode('-', $request->date);
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($date[0]))->format('Y-m-d');
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($date[1]))->format('Y-m-d');
                    if ($date) {
                        $indoorData = $indoorData->whereBetween('doa_date', [$startDate, $endDate]);
                    }
                }

                if ($request->is_print == 1) {
                    $indoorData = $indoorData->get();
                    foreach ($indoorData as $data) {
                        $procedureName = implode(', ', $this->IndoorProcedure
                            ->whereIn('id', explode(',', $data['procedure_id']))
                            ->pluck('name')
                            ->toArray());
                        $data->procedure_name = $procedureName;
                    }
                    return response()->json([
                        View::make('admin.indoor.indoorpatient.preview', compact('indoorData'))->render()
                    ]);                    
                }
                $indoorData = $indoorData->paginate(100);
                foreach ($indoorData as $data) {
                    $procedureName = implode(', ', $this->IndoorProcedure
                        ->whereIn('id', explode(',', $data['procedure_id']))
                        ->pluck('name')
                        ->toArray());
                        
                    $data->procedurename = $procedureName;
                }

                $data['indoorData'] = View('admin.indoor.indoorpatient.data',compact('indoorData','hospitalDoctor'))->render();
                return $data;
            }
            return view('admin.indoor.indoorpatient.index',compact('patients', 'indoorType','procedures','referenceDoctors'));
        }
}
