<?php
    use App\Models\OvaryDetail;
    use App\Models\DurationData;
    use App\Models\Appointment;
    use App\Models\AppointmentRequest;
    use App\Models\IndoorDeposit;
    use App\Models\IndoorBook;
    use App\Models\IvfHistory;
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
        $selfBookingCount = DB::table('patients')->whereDate('created_at',$now)->where('is_approved',0)->count();
        return $selfBookingCount;
    }

    function getOnlineAppointmentCount()
    {
        $now = date('Y-m-d');
        $onlineAppointmentCount = AppointmentRequest::where('is_book',0)->whereDate('appointment_date','>=',$now);
        $onlineAppointmentCount = $onlineAppointmentCount->WhereHas('getPatients', function ($query) {
            $query->where('is_approved', 1);
        })->count();
        return $onlineAppointmentCount;
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
    /**
     * return total IPD and OPD income
     */
    function getPatientsTotalIncome($patientId)
    {
        $patientReportOpd = Appointment::whereHas('getAppointmentCharges')->wherePatientsId($patientId)->get()->sum('getAppointmentCharges.total');
        $indoorDeposit = IndoorDeposit::where('case_type','Credit')->wherePatientId($patientId)->get()->sum('amount');
        $indoorBook = IndoorBook ::whereHas('getInvoice')->whereIsFinalInvoice(1)->whereNotNull('final_invoice_date')->wherePatientId($patientId)->get()->sum('getInvoice.amount');
        
        return $patientReportOpd + $indoorDeposit + $indoorBook;
    }
    /**
     * return total IPD and OPD income
     */
    function getIvfHystrocopyDate($cycle_no,$plan,$pId)
    {
        $pId = decrypt($pId);
        $ivfHistory = IvfHistory::where('cycle_no',$cycle_no)->where('plan',$plan)->wherePatientsId($pId)->whereJsonContains('investigation->hystroscopy->type','yes')->first();

        return $ivfHistory;
    }

    /**
     * Resolve an uploaded-image path to its CDN URL.
     *
     * Stored values are relative paths like "public/upload/event/123.jpg"; the
     * bytes live on the DigitalOcean Spaces CDN, so we prefix the configured
     * CDN base. Placeholders stay local; already-absolute values pass through.
     *
     * @param  string|null $path     Stored path, e.g. "public/upload/event/123.jpg"
     * @param  string|null $default  Local fallback when $path is empty (null = "")
     * @return string
     */
    function cdnUrl($path, $default = 'public/images/default_user.png')
    {
        // Empty -> fall back to the default (resolved the same way; null = "").
        if (empty($path)) {
            return is_null($default) ? '' : cdnUrl($default, null);
        }
        // Already an absolute URL (http/https/protocol-relative/data:) -> leave as-is.
        if (preg_match('#^(https?:)?//#i', $path) || \Illuminate\Support\Str::startsWith($path, 'data:')) {
            return $path;
        }
        $base = rtrim(config('filesystems.cdn_url'), '/');
        // Already points at the CDN host -> don't double-prefix.
        if (\Illuminate\Support\Str::contains($path, parse_url($base, PHP_URL_HOST))) {
            return $path;
        }
        // Uploaded files (public/upload/...) live on the CDN; everything else
        // (public/images placeholders, assets) is a LOCAL static file.
        if (\Illuminate\Support\Str::startsWith(ltrim($path, '/'), 'public/upload/')) {
            return $base . '/' . ltrim($path, '/');
        }
        // Local static asset. The web root is already public/, so a leading
        // "public/" (e.g. public/images/default_user.png) would 404 — strip it.
        $local = \Illuminate\Support\Str::startsWith($path, 'public/')
            ? \Illuminate\Support\Str::after($path, 'public/')
            : $path;
        return url($local);
    }

    /**
     * Best-effort MIME type from a file path's EXTENSION.
     *
     * Replaces mime_content_type()/is_file() detection for files that live on
     * the CDN (not the local disk), so report galleries can still tell PDFs
     * from images. Returns '' for unknown/empty.
     */
    function imageMimeFromExt($path)
    {
        if (empty($path)) {
            return '';
        }
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            return 'application/pdf';
        }
        if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
            return 'image/' . $ext;
        }
        return '';
    }
?>