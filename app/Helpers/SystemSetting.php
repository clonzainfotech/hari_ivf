<?php
    use App\Models\OvaryDetail;
    use App\Models\DurationData;
    use App\Models\Appointment;
    use App\User;
    
    function systemSetting() {
        $systemSetting = DB::table('system_setting')->orderBy('id', 'DESC')->first();
        return $systemSetting;
    }

    function hospitalAddress(){
        $hAddress = DB::table('hospital_addresses')->first();
        return $hAddress;
    }

    function addOvaryAbnormalData($data,$type){
        $abnormalData = [];
        $ovaryData = OvaryDetail::where('type',$type)->pluck('name','name')->toArray();
        $diffrent = array_diff($data,$ovaryData);
        foreach($diffrent as $row){
            $oData = new OvaryDetail;
            $oData->type = $type;
            $oData->name = $row;
            $oData->save();
        }
        return true;
    }

    function durationData($type,$data){
        $durationData = [];
        $durationData = DurationData::where('type',$type)->pluck('name','name')->toArray();
        $diffrent = array_diff($data,$durationData);
        foreach($diffrent as $row){
            $durationData = new DurationData;
            $durationData->type = $type;
            $durationData->name = $row;
            $durationData->save();
        }
        return true;
    }

    function getDurationOfData($type){
        $durationData = DurationData::whereType($type)->pluck('name','name')->toArray();
        return['data'=>$durationData];
    }

    function addOrdinalNumberSuffix($num) {
        if(!in_array(($num % 100),array(11,12,13))){
            switch ($num % 10) {
                // Handle 1st, 2nd, 3rd
                case 1:  return $num.'st';
                case 2:  return $num.'nd';
                case 3:  return $num.'rd';
            }
        }
        return $num.'th';
    }

    function getAppointmentCount($type){
        $date = date('Y-m-d');
        $appontmentCount = Appointment::whereDate('date',$date);
        switch($type) {
            case 1:
                // anc
                $categoryId = ['5','6'];
                break;
            case 2:
                // ivf
                $categoryId = ['1','2'];
                break;
            case 3:
                // iui
                $categoryId = ['3','4'];
                break;
            case 4:
                // appointment
                $categoryId = [];
                break;
        }
        if($type != 4){
            $appontmentCount = $appontmentCount->where('is_done',0)->whereIn('category_id',$categoryId);
        }else{
            $appontmentCount = $appontmentCount->where('usg_status',0);
        }
        $appontmentCount = $appontmentCount->count();
        return ['appointmentCount'=>$appontmentCount];
    }
    function getSelfBookingCount()
    {
        $now = date('Y-m-d');
        $selfBookingCount = DB::table('patients_signup')->whereDate('created_at',$now)->count();
        return $selfBookingCount;
    }

    function getANCNumberToWOrd($number){
        $word = null;
        switch ($number) {
            case 1:
                $word = 'st';
                break;
            case 2:
                $word = 'nd';
                break;
            case 3:
                $word = 'rd';
                break;
            case 4:
                $word = 'th';
                break;
        }
        return ['word'=>$word];
    }

    function getSeenByDoctor($id){
        return User::find($id);
    }

    function checkData($value,$patientsInvestigation){
        $investigationData = !empty($patientsInvestigation->investigation_data) ? $patientsInvestigation->investigation_data : [];
        if(!empty($investigationData) && in_array($value,$investigationData)){
            return true;
        }
        return false;
    }  
    function getCity()
    {
        $city = DB::table('city')->orderBy('name', 'ASC')->pluck('name','name')->toArray();
        return $city;
    } 
    function getState()
    {
        $city = DB::table('state')->orderBy('name', 'ASC')->pluck('name','id')->toArray();
        return $city;
    } 
?>