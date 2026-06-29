<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use App\User;
use Carbon\Carbon;
use Log;

class SmsManager extends BaseModel
{
    protected $table = 'sms_manager';

    public function getReffDoctor()
    {
        return $this->belongsTo('App\Models\ReferenceDoctor', 'receiver_id', 'id');
    }
    public function getPatientsDetails()
    {
        return $this->belongsTo('App\Models\OpdPatients', 'patients_id');
    }

    public static function sendAptToPatient($appointmentId)
    {

        $module = __FUNCTION__;
        $smsData = [];
        $smsData['module'] = $module;
        $smsData['templateid'] = '';
        $smsData['message'] = '';

        // $template = self::getTemplate($module);
        $smsData['templateid'] = 0;
        $smsData['message'] = config('app.' . $smsData['module']);

        $appointment = Appointment::with('getPatientsDetails')
            ->whereId($appointmentId)
            ->first();

        $getPatientName = $appointment->getPatientsDetails['name'];
        $getAptDate = $appointment->date;

        $smsData['message'] = self::replaceMessage('{{patient_name}}', $getPatientName, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{apt_date}}', $getAptDate, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{app_name}}', config('app.name'), $smsData['message']);

        $smsData['mobile'] = $appointment->getPatientsDetails['mobile_number'];

        self::startToSendSms($smsData);
        return true;
    }

    public static function sendCustomMessage($mobile, $templateId, $message)
    {

        $module = 'sendCustomMessage';
        $smsData = [];
        $smsData['module'] = $module;
        $smsData['templateid'] = $templateId;
        $smsData['message'] = preg_replace('/[^A-Za-z0-9 ]/', '', $message);
        $smsData['mobile'] = $mobile;
        self::startToSendSms($smsData);
        return true;
    }
    public static function sendAlrtOpdToDoctor($patientid)
    {
        $module = __FUNCTION__;
        $smsData = [];
        $smsData['module'] = $module;
        $smsData['templateid'] = '';
        $smsData['message'] = '';

        $template = self::getTemplate($module);
        $smsData['templateid'] = 0;
        $smsData['message'] = config('app.' . $smsData['module']);
        if (is_array($template)) {
            $smsData['templateid'] = $template['id'];
            $smsData['message'] = $template['template'];
        }

        $patient = OpdPatients::with('getReferenceDoctor')
            ->whereId($patientid)
            ->first();

        $getPatientName = $patient->name;
        $getDoctorName = $patient->getReferenceDoctor['name'];

        $smsData['message'] = self::replaceMessage('{{patient_fullname}}', $getPatientName, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{reff_drname}}', $getDoctorName, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{app_name}}', config('app.name'), $smsData['message']);
        $smsData['receiver_id'] = $patient->reference_doctor_id;
        $smsData['mobile'] = $patient->getReferenceDoctor['mobile_number'];
        self::startToSendSms($smsData);
        return true;
    }

    public static function sendDischargeCardToRefDoctor($bookingId)
    {
        log::debug('inIf');
        $module = __FUNCTION__;
        $smsData = [];
        $smsData['module'] = $module;
        $smsData['templateid'] = '';
        $smsData['message'] = '';

        // $template = self::getTemplate($module);
        $smsData['templateid'] = 0;
        $smsData['message'] = config('app.' . $smsData['module']);

        // getStringBetween($str,$from,$to)
        $dischargeData = IndoorDischargeCard::with('getIndoorBook')
            ->whereBookingId($bookingId)
            ->orderBy('id', 'DESC')
            ->first();
        $pId = explode(',', $dischargeData->getIndoorBook->procedure_id);
        $msg = 'deliverd a';
        if (in_array('2', $pId)) {
            $msg = 'and LSCS done';
        }
        $sNotes = $dischargeData->surgical_note;
        $gender = null;
        $weight = null;
        $time = null;
        $date = null;
        if ($sNotes) {
            $sNotes = strtolower($sNotes);
            $var = explode('on ', $sNotes);
            $date = !empty($var[1]) ? $var[1] : null;
            if (strpos($sNotes, 'male') !== false) {
                $gender = "Male";
            } else {
                $gender = "Female";
            }
            $weight = self::getStringBetween($sNotes, 'wt', 'kg');
            $time = self::getStringBetween($sNotes, 'at', 'on');
        }
        $getPatientName = $dischargeData->getIndoorBook->getPatientsDetails['name'];
        $getDoctorName = $dischargeData->getIndoorBook->getPatientsDetails->getReferenceDoctor['name'];
        $getDischargeDate = $dischargeData->getIndoorBook->dod_date;
        $smsData['message'] = self::replaceMessage('{{patient_fullname}}', $getPatientName, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{reff_drname}}', $getDoctorName, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{msg}}', $msg, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{gender}}', $gender, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{date}}', $date, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{weight}}', $weight, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{time}}', $time, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{app_name}}', config('app.name'), $smsData['message']);
        $smsData['mobile'] = $dischargeData->getIndoorBook->getPatientsDetails->getReferenceDoctor['mobile_number'];
        self::startToSendSms($smsData);
        return true;
    }

    public function sendRoomRegistration($bookId)
    {
        $module = 'sendRoomRegistrationDoctor';
        $smsData = [];
        $smsData['module'] = $module;
        $smsData['templateid'] = '';
        $smsData['message'] = '';

        // $template = self::getTemplate($module);
        $smsData['templateid'] = 0;
        $smsData['message'] = config('app.' . $smsData['module']);

        $indoorBook = IndoorBook::find($bookId);
        $procedureName = implode(',', IndoorProcedure::whereIn('id', explode(',', $indoorBook->procedure_id))->pluck('name', 'name')->toArray());
        $procedureName = str_replace('+', ',', $procedureName);
        $procedureName = str_replace(',,', ',', $procedureName);
        $smsData['message'] = self::replaceMessage('{{patient_fullname}}', $indoorBook->getPatientsDetails['name'], $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{reff_drname}}', $indoorBook->getPatientsDetails->getReferenceDoctor['name'], $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{procedure}}', $procedureName, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{app_name}}', config('app.name'), $smsData['message']);
        $smsData['receiver_id'] = $indoorBook->getPatientsDetails->reference_doctor_id;
        $smsData['mobile'] = $indoorBook->getPatientsDetails->getReferenceDoctor['mobile_number'];

        self::startToSendSms($smsData);
    }

    public static function sendReferenceDoctor($advise, $seenBy, $followUp, $pId)
    {
        $module = 'sendReferenceDoctor';
        $smsData = [];
        $smsData['module'] = $module;
        $smsData['templateid'] = '';
        $smsData['message'] = '';
        // $template = self::getTemplate($module);
        $smsData['templateid'] = 0;
        $smsData['message'] = config('app.' . $smsData['module']);

        $patient = OpdPatients::whereId($pId)->first();
        $refDr = ReferenceDoctor::whereId($patient->reference_doctor_id)->first();
        $followUp = $followUp ? $followUp : '-';
        if (!empty($refDr->name)) {
            $smsData['message'] = self::replaceMessage('{{patient_fullname}}', $patient->name, $smsData['message']);
            $smsData['message'] = self::replaceMessage('{{reff_drname}}', $refDr->name, $smsData['message']);
            $smsData['message'] = self::replaceMessage('{{advise}}', $advise, $smsData['message']);
            $smsData['message'] = self::replaceMessage('{{dr_name}}', $seenBy, $smsData['message']);
            $smsData['message'] = self::replaceMessage('{{followUp}}', $followUp, $smsData['message']);
            $smsData['message'] = self::replaceMessage('{{app_name}}', config('app.name'), $smsData['message']);
            $smsData['receiver_id'] = $refDr->id;
            $smsData['mobile'] = $refDr['mobile_number'];
            self::startToSendSms($smsData);
        }
    }

    public static function sendOtpToPatients($userId, $mobile_no, $type = null)
    {
        $module = __FUNCTION__;
        $smsData = [];
        $smsData['module'] = $module;
        $smsData['message'] = '';

        // $template = self::getTemplate($module);
        $smsData['templateid'] = 0;
        $smsData['message'] = config('app.' . $smsData['module']);

        if ($type == 'user') {
            $user = User::whereMobileNumber($mobile_no)->first();
            $getotp = $user->verification_code;
            $mobile_no = $user->mobile_number;
        } else {
            $patients = OpdPatients::whereMobileNumber($mobile_no)->first();
            //for app register patients
            if (!$patients) {
                $patients = PatientSignup::whereMobileNumber($mobile_no)->first();
            }

            $getotp = $patients->otp;
            $mobile_no = $patients->mobile_number;
        }

        $smsData['message'] = self::replaceMessage('{{otp}}', $getotp, $smsData['message']);
        $smsData['message'] = self::replaceMessage('{{app_name}}', config('app.name'), $smsData['message']);
        $smsData['mobile'] = $mobile_no;

        self::startToSendSms($smsData);
        return true;
    }

    public function sendDischargeMsgToDoctor($mobile, $msg)
    {
        $smsData = [];
        $smsData['module'] = 'sendDischargeCardToRefDoctor';
        $smsData['message'] = $msg;
        $smsData['templateid'] = 0;
        $smsData['mobile'] = !empty($mobile) ? $mobile : null;
        self::startToSendSms($smsData);
    }

    public static function startToSendSms($smsData)
    {
        // if (!empty($smsData['mobile'])) {

        //     $message = urlencode($smsData['message']);
        //     $smsData['send_message'] = $message;
        //     $smsid = self::beforeSendMessage($smsData);
        //     if ($smsid > 0) {
        //         self::sendSms($smsid,$smsData['mobile'], $message);
        //         return true;
        //     }
        // }
        return true;
    }

    public static function sendSms($smsid, $mobile, $message)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $url = env("TXTLCL_URL", "http://websms.anandkrupapublicity.com/api/push.json?");
            $sender = env("TXTLCL_SENDER", "JRADHA");
            $route = env("TXTLCL_ROUTE", "trans_dnd");
            $apiKey = env("TXTLCL_API_KEY", "600565fbbf506");

            $sendSmsData = [];
            $sendSmsData['smsid'] = $smsid;
            $sendSmsData['status'] = 2;
            $sendSmsData['parts'] = 0;
            $sendSmsData['messageid'] = 0;

            if (!empty($url) && !empty($sender) && !empty($mobile) && !empty($message)) {
                $response = $client->request('GET', $url, [
                    'query' => [
                        'apikey' => $apiKey,
                        'route' => $route,
                        'sender' => $sender,
                        'mobileno' => $mobile,
                        'text' => str_replace(["+", "%2C%0A", "%2C%0D%0A", "%0D%0A"], " ", $message),
                    ]
                ]);
                $sendSmsData['status'] = 1;
                $sendSmsData['messageid'] = 0;
                self::afterSendMessage($sendSmsData, '');
                return true;
            }
            self::afterSendMessage($sendSmsData, 'Empty fields are not allowed');
            return true;
        } catch (\Exception $e) {
            log::debug($e);
            self::afterSendMessage($sendSmsData, 'error');
            return true;
        }
    }

    public static function beforeSendMessage($smsData)
    {
        if ($smsData['mobile'] && $smsData['mobile'] > 0) {
            $sms = new SmsManager;
            $sms->message = $smsData['message'];
            $sms->mobile_number = $smsData['mobile'];
            $sms->module = $smsData['module'];
            $sms->template_id = !empty($smsData['templateid']) ? $smsData['templateid'] : null;
            $sms->receiver_id = !empty($smsData['receiver_id']) ? $smsData['receiver_id'] : null;
            $sms->save();
            return $sms->id;
        }
        return 0;
    }

    public static function afterSendMessage($sendSmsData, $remark = '')
    {
        $smsId = $sendSmsData['smsid'];
        $sms = SmsManager::whereId($smsId)->first();
        if ($sms) {
            $sms->status = $sendSmsData['status'];
            $sms->message_id = $sendSmsData['messageid'];
            $sms->send_message = $sendSmsData['parts'];
            $sms->remark = $remark;
            $sms->save();
        }
        return true;
    }

    public static function getTemplate($module)
    {

        if (!empty($module)) {
            $getTemplateData = SmsTemplate::whereModule($module)->first();
            // dd($get)
            if ($getTemplateData && ($getTemplateData->id) > 0 && !empty($getTemplateData->template)) {
                $tempData = [];
                $tempData['id'] = $getTemplateData->id;
                $tempData['template'] = $getTemplateData->template;
                return $tempData;
            }
        }
        return false;
    }

    public static function replaceMessage($find, $replace = '', $sms)
    {
        if (!empty($find) && !empty($replace)) {
            if ($find == '{{patient_name}}') {
                $patientFullName = explode(' ', $replace);
                $replace = ucwords(strtolower($patientFullName[0]));
            }

            if (($find == '{{apt_date}}') || ($find == '{{date_of_discharge}}')) {
                $replace = Carbon::parse($replace)->format('d-m-Y');
            }

            if ($find == '{{surgical_note}}') {
                $replace = preg_replace('/[^A-Za-z0-9 ]/', '', $replace);
            }

            if ($find == '{{reff_drname}}' || $find == '{{patient_fullname}}') {
                $replace = ucwords(strtolower($replace));
            }

            if ($find == '{{app_name}}') {
                $replace = ucwords(strtolower($replace));
            }
            return str_replace($find, $replace, $sms);
        }
        return $sms;
    }

    public static function getStringBetween($str, $from, $to)
    {
        $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
        return substr($sub, 0, strpos($sub, $to));
    }
}
