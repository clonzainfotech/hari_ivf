<?php

namespace App\Http\Controllers\Api;

use App\Models\OpdPatients;
use Auth;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\Api\ApiController;

class HomeController extends ApiController
{
    private $apiToken;
    public function __construct()
    {
        // Unique Token
        parent::__construct();
        $this->apiToken = uniqid(base64_encode(\Illuminate\Support\Str::random(100)));
    }

    // user can login
    /**
    * Return appointment related detail
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function home(Request $request){
        $token = $request->header('Authorization');
        if($token) {
            // $patientData = $this->OpdPatients->where('token', $token)->first();
            $patientData = $this->PatientToken->where('token', $token)->first();

            if(!empty($patientData)) {
                $currentdate = Carbon::now()->format('Y-m-d');
                $msg = 'No appointment book';
                $patientId = $patientData->patients_id;
                $appointmentTime = null;
                $lastAppointment = $this->Appointment->where('patients_id', $patientId)->whereDate('date','>',$currentdate)->orderBy('date','DESC')->orderBy('id','DESC')->first();
                $lastAppointmentRequest = $this->AppointmentRequest->where('patients_id', $patientId)->where('is_book','!=',1)->whereDate('appointment_date','>',$currentdate)->orderBy('appointment_date','DESC')->orderBy('id','DESC')->first();
                $appointmentDate = $lastAppointment && $lastAppointment->date ? strtotime($lastAppointment->date) : 0;
                $appointmentRequestDate = $lastAppointmentRequest && $lastAppointmentRequest->appointment_date ? strtotime($lastAppointmentRequest->appointment_date) : 0;
                if($appointmentDate >= $appointmentRequestDate){
                    $status = 1;
                    if(strtotime(carbon::parse($lastAppointment ? $lastAppointment->updated_at : 0)->format('Y-m-d H:i:s')) > strtotime(carbon::parse($lastAppointmentRequest ? $lastAppointmentRequest->created_at : 0)->format('Y-m-d H:i:s'))){
                        $status = 1;
                    }else{
                        $status = 2;
                        if($lastAppointmentRequest){
                            if($lastAppointmentRequest->is_book == 2){
                                $msg = "Your appointment on ".Carbon::parse($lastAppointmentRequest->appointment_date)->format('d/m/Y').", ". ($lastAppointmentRequest->appointment_time ? Carbon::parse($lastAppointmentRequest->appointment_time)->format('H:i a') : '')." is rejected. Please select other time to book an appointment.";
                            }
                            if($lastAppointmentRequest->is_book == 0){
                                $msg = "Your appointment on ".Carbon::parse($lastAppointmentRequest->appointment_date)->format('d/m/Y').", ". ($lastAppointmentRequest->appointment_time ? Carbon::parse($lastAppointmentRequest->appointment_time)->format('H:i a') : '')." is pending. Kindly wait till hospital will approve.";
                            }
                        }
                    }
                    if($status == 1){
                        $msg = "Your appointment on ".Carbon::parse($lastAppointment->date)->format('d/m/Y')." , ".($lastAppointment->time ? Carbon::parse($lastAppointment->time)->format('H:i a') : '')." is approved. Please check in to the hospital at least 10 minutes before an appointment.";
                    }
                }else{
                    if($lastAppointmentRequest){
                        if($lastAppointmentRequest->is_book == 2){
                            $msg = "Your appointment on ".Carbon::parse($lastAppointmentRequest->appointment_date)->format('d/m/Y').", ". ($lastAppointmentRequest->appointment_time ? Carbon::parse($lastAppointmentRequest->appointment_time)->format('H:i a') : '')." is rejected. Please select other time to book an appointment.";
                        }
                        if($lastAppointmentRequest->is_book == 0){
                            $msg = "Your appointment on ".Carbon::parse($lastAppointmentRequest->appointment_date)->format('d/m/Y').", ". ($lastAppointmentRequest->appointment_time ? Carbon::parse($lastAppointmentRequest->appointment_time)->format('H:i a') : '')." is pending. Kindly wait till hospital will approve.";
                        }
                    }
                   
                }
                $today = Carbon::now()->format('Y-m-d');
                $eventData = collect($this->Event::select('id','title', 'event_picture', 'discription', 'venue','time','start_date', 'end_Date')->where('end_date','>',$today)->where('status',1)->get())->map(function($q){
                                $q->event_picture = $q->event_picture ? url($q->event_picture) : null;
                                return $q;            
                            });

                $feedback = $this->UserReview->where('patient_id',$patientId)->where('status',1)->first();
                $feedbackData = collect($this->UserReview->where('patient_id',$patientId)->where('status',1)->orderBy('id','desc')->get())->map(function($q){
                                    $q->name = $q->getReviewUser['name'];
                                    $q->patient_name = $q->getPatientsData['name'];
                                    $q->profile_picture = $q->getReviewUser['profile_picture'] ? url($q->getReviewUser['profile_picture']) : null;
                                    unset($q->getReviewUser,$q->getPatientsData);
                                    return $q;
                                });
                $feedbackStatus = 0;
                if(!empty($feedback)) {
                    $feedbackStatus = 1;
                }
                $systemSetting = systemSetting();
                $advertisementVideo = $systemSetting->adv_video;
                $advertisementVideo = $advertisementVideo ? url($advertisementVideo) : null;
                $ratingData = [];
                $ratingData['status'] = $feedbackStatus;
                $ratingData['data'] = $feedbackData;
                $success = [
                    'na' => $msg,
                    'event' => $eventData,
                    'advertisement_video' => $advertisementVideo,
                    'rating' => $ratingData
                ];
                return $this->sendResponse('Get pateint details sucessfully', $success);
            }
            return $this->sendError('User is not found');
        }
        return $this->sendError(__('auth.failed'), 401);
    }
    
    /**
    * Return list of youtube videos
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function hospital_tutorials()
    {
        $linkArray = [];
        $links = ['https://youtu.be/qBVkKecuMQU','https://youtu.be/WasGO4cj8uo','https://youtu.be/SjmVfp1hoQQ','https://youtu.be/UuDF7cefPQ0','https://youtu.be/GDV7ixCxoXw','https://youtu.be/udNZ1Me2otc',
        'https://youtu.be/uWSio_KqhP4','https://youtu.be/LLgP-Qumvdc','https://youtu.be/efu7j_YNgS8','https://youtu.be/Qd_RQu-YfTs','https://youtu.be/Xtgt9Mm0sQY','https://youtu.be/yOmaF1rWJAE','https://youtu.be/7Pf5uCzhu5M',
        'https://youtu.be/AYXglefWFVQ','https://youtu.be/Q4ttCv1zRvY','https://youtu.be/FBpaSaa7OA0','https://youtu.be/n3LgcqwUZ3Q','https://youtu.be/T3xoDMc0GI0','https://youtu.be/wzeCjqlhIXE','https://youtu.be/ET5bOxF3h7w',
        'https://youtu.be/O8kqM7h2qCg','https://youtu.be/eIMD5GcR17A','https://youtu.be/bxTRkOmllY8','https://youtu.be/ICezFyGgwGM','https://youtu.be/GAGthZnxXjU','https://youtu.be/ggVHEaxubI4','https://youtu.be/HDkfpN4AjuM',
        'https://youtu.be/mC2wqZwtBCM','https://youtu.be/qY9vcf1kZWg','https://youtu.be/kC3QRNo28k4','https://youtu.be/BPOLvb912xU','https://youtu.be/mwLIYUaUxbU','https://youtu.be/tRl7IMMBsmA','https://youtu.be/rTj8g8p0G_Y',
        'https://youtu.be/92vqRoZCNqg','https://youtu.be/aXrExELAV8Q','https://youtu.be/wsRbDvcQ8uA','https://youtu.be/9g2vv7nofKg','https://youtu.be/SynQx2D7B5U','https://youtu.be/FkjoHi_Kx4o','https://youtu.be/de0TQ6yFlzE',
        'https://youtu.be/aYBLitI_gM0','https://youtu.be/ee0BGJmtFKs','https://youtu.be/5dYtkwrnjss','https://youtu.be/3CeK4-Ob9Zc','https://youtu.be/7gy0Cq-wvDM','https://youtu.be/zN94U_hoK9E','https://youtu.be/D1jdZdGNPDI',
        'https://youtu.be/APdP2qeqhqo','https://youtu.be/tFvGnuZACCY','https://youtu.be/qfsNRzEPNvM','https://youtu.be/6YZDQgxufpw','https://youtu.be/qwO1YMcaPgA','https://youtu.be/aKcdt7bdqxQ','https://youtu.be/fb3RY80IZoc',
        'https://youtu.be/5CgYajQYw-Y','https://youtu.be/72I6wqM_EnU','https://youtu.be/TfEPLCEe9s8','https://youtu.be/GzD0V3Zx5MI','https://youtu.be/pzedpsEJA3o','https://youtu.be/6aWJ8z2M9r4',
        'https://youtu.be/RE_AeDlOx8c','https://youtu.be/TjhuiHFop2g','https://youtu.be/8ECIUOvt-4o','https://youtu.be/ifKiPAawUGI','https://youtu.be/K1eg-6mIAoE','https://youtu.be/gzPV0aeQQ_g',
        'https://youtu.be/igp0drtTOsk','https://youtu.be/4mYwBkg-RwY','https://youtu.be/8B4u8xXaR9Y','https://youtu.be/dPsOlBpAN1U','https://youtu.be/vhBqF6z-XyU','https://youtu.be/9YSnwlhmJIY',
        'https://youtu.be/t5gdEJ3D2-s','https://youtu.be/13yPs6K4zFE','https://youtu.be/35JvpF0iem8','https://youtu.be/uJGpg78yxnw','https://youtu.be/5IN72o2jdtY','https://youtu.be/OZw9L9juVzA',
        'https://youtu.be/rXL3ug_uLhE','https://youtu.be/0cRtXrNNReY','https://youtu.be/1sxYPm_Rek4','https://youtu.be/I_uHyIdyVv4','https://youtu.be/HaOtPSGcslU','https://youtu.be/X5g_VFu-WpE',
        'https://youtu.be/L0E1mY2FqYM','https://youtu.be/rSzeCW_pswo','https://youtu.be/a7gRKZxZ0f4','https://youtu.be/3SyQtrsPbD8','https://youtu.be/JmFmpPtbr30','https://youtu.be/TOkywlFywL8',
        'https://youtu.be/z4-XK2km9Tw','https://youtu.be/H1wq3fX5ztc','https://youtu.be/yjAA40FntS8','https://youtu.be/j1vuVcbnNA0','https://youtu.be/K32H6Etymg4','https://youtu.be/dW76M2khHv8',
        'https://youtu.be/sTxDXqcyfPs','https://youtu.be/LRCxA_QpJ9g','https://youtu.be/5pD5VXBy4Ck','https://youtu.be/Vir0Tg_EW4k','https://youtu.be/thYHW99FD6o','https://youtu.be/3an3bVQC9uE',
        'https://youtu.be/_KfT8BYQR9w','https://youtu.be/YE2UjfBO0Ck','https://youtu.be/7NgaJ9fvQo4','https://youtu.be/y7TZZaw-gE0','https://youtu.be/UQEl4C1JDUo','https://youtu.be/xqLgxnudFjY',
        'https://youtu.be/yVxssuqdkpc','https://youtu.be/qhnrmDEfdEw','https://youtu.be/rSJR1l8Zuw8','https://youtu.be/Fb7dZ1Q9X0Q','https://youtu.be/N2Qh7pU3rSs','https://youtu.be/I56Sh8L0k_U',
        'https://youtu.be/svOjeqdlbdE','https://youtu.be/2LtVidEkCbE','https://youtu.be/YcTGTAprNIM','https://youtu.be/Wc1CiJf7w4E','https://youtu.be/OQF8UpclW-s','https://youtu.be/kDjTxPTT0x8',
        'https://youtu.be/XHM-n8b3bPM','https://youtu.be/zpmLDn__Ibc','https://youtu.be/8XoIJNOcENs','https://youtu.be/1a9-q2-gdSQ','https://youtu.be/wiodpeRsgjE','https://youtu.be/EnHR6U8-fDc',
        'https://youtu.be/NvX36GTTRUY','https://youtu.be/yywen7Jk4uI'];
        foreach($links as $link)
        {
            $urlArray['url'] = $link;
            array_push($linkArray,$urlArray);
        }
        return $this->sendResponse('Get tutorials successfully', $linkArray);
    }
    /**
    * Return list of Question-Answer
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */

    public function getQAns()
    {
        $data = [];
        $dataValues = ["During pregnancy every morning I feel stomach upset and have urge to vomite. Is it normal? Should I take medicine?" => " It is Very  during the first trimester of pregnancy. Don’t worry it’s not going to harm you or your baby. if you experience this, you can Avoid morning water only and before getting out of bed do some exercise or stretching to circulate the blood, you can take dry snacks like biscuits, bread, puffed rice, khakhara and you feel better.  If you experience severe vomiting-nausea, you can take medicine from your  doctor. Or sometime  in severe conditionyou may needed to get admission and intravenous fluids and medicine for vomiting",
                    "When will I feel my baby move?"=>" usually u can feel  Baby movement  after completion of five month (20 week) of amenorrhea.  1st and In starting of 2ndtrimester baby is too small so you can't feel it, even after 20 weeks still you can consult your doctor and do ultrasound",
                    "I often feel feverish ,malaise  and headache during pregnancy. What should I do?"=>"during early pregnancy women can feel increase temperature  due to higher progesterone  and sometime suffering from headache, it is a normal but if temperature show high and continues than consult your gynec doctor and do reports and medicine as per doctor advised, Or you can take paracetamole.",
                    "why does pregnancy cause bloating abdomen, constipation and digestive issues? Can I take medicine?"=>" it all come down from hormones. Increased level of progesterone relaxed tissue all over body including GI tract relaxation slows down movement of your bowel cause gas, acidity constipation. You can take medicine for that but better to try first with dietary care during pregnancy like fibrous vegetable and food, warm water, walking after lunch and dinner, because it is improve your digestive system. Avoid spicy and oily food, over eating and late night eating.",
                    "Is physical intimacy (Sex) safe during pregnancy?  Can it harmful for the baby?"=>"It is ok to have sex during pregnancy. Your developing baby is protected by the amniotic fluid in your uterus, as well as by the strong muscles of the uterus itself. Sexual activity won't affect your baby, as long as you don't have complications such as preterm labor or placenta problems. You're at risk for preterm labor (contractions before 37 weeks of pregnancy) you having vaginal bleeding, discharge, or cramping without a known cause then sex really be said to be contraindicated.",
                    "what do you recommended for normal pregnancy aches and pains?"=>"there are many common discomforts associated with pregnancy. Many of this can be eased without medication. Some time it happen when baby's size increase. Also u feel some gastric illness, constipation and mild abdominal pain. It is not serious but if pain continuously increased then consult your gynec doctor.",
                    "why do some leady have really small baby bump  ? Is it normal?"=>"Yes, it is normal. Abdominal size and baby's growth are not interconnected. Baby growth depend on amniotic fluid and blood supply in your womb. Some woman are fatty that doesn't means her baby is healthy so don't worry about it. Baby bump also depends on abdominal muscle tone higher tone have less obvious baby bump",
                    "During pregnancy I have itching and black spots on my abdomen.is there any way to prevent this?"=>" it is called stretch mark, and it is totally normal for every pregnant women. it is happened when your tummy increased and that time skin tighten and stretched and it produce some black stretch marks and itching on abdomen. There’s no specific treatment to avoid or treat it. You can use lotion like bio-oil, other moisturizers  it help in skin nourishment.",
                    "Is it normal for me to have frequent white dischargeduring pregnancy?  Could it have caused less water aroundthe baby?"=>"One of the earliest signs of pregnancy is an increase in vaginal discharge, and this continues throughout pregnancy. Whena woman becomes pregnant, her vagina largely takes on a personality of its own.Normal vaginal discharge, known as leucorrhea, is thin, clear, or milky white, and mild smelling. You may want to wear an unscented panty liner. White discharge & liquor(amniotic fluid around baby) are different things. White discharge are sometimes curdy and also frequency of discharge is on/off and limited frequency. White discharge With itching at private part, burning at private part, smell in discharge than necessary to take medicine, otherwise it is normal. And also accompanied by redness or itching, or vulvar swelling than call your gynec doctor.",
                    "my weight is not increasing  in the current pregnancy, will it affect the weight of my baby?"=>" In general baby weight counted after complete 5 month of pregnancy, and baby weight not connected with the mother weight so don't worry.",
                    "Why does a doctor prescribe medicine during pregnancy?  Is it mandatory to take it.?"=>"Yes definitely, by the way that is not a routine medicine. It is called vitamins and minerals. During 1 to 3 month folic acid help to baby's brain development and after 3 to 9 month iron and calcium help to maintain baby's blood volume and development ofbaby's bone. This to components of nutrients are not enough in diet as per so many studies so it required.",
                    "What should i eat during pregnancy to  increase the weight of my baby.And maintain it's health?"=>"Balanced diet needed for baby's weight but it is necessary that food made only at home. second thing  is  make sure that only fresh food you are eating otherwise you are suffering from indigestion. You can eat Foods like dry fruits, yogurt, eggs and milk help the baby to gain weight. Eat for two is myth, actually eatas your body required, over eating or high calories with increase weight unnecessarily.",
                    "is it safe to travel while pregnant?"=>"Generally ,You can avoid travelling for three month of pregnancy. Because of in initial stage of pregnancy any kind of vigorous physical activity is dangerous for baby. If you arepregnant, thesafest time for you totravelis during thesecond trimester, provided you aren't experiencing any complications. If you arepregnantand consideringtravel, you must consult with your doctor, especially if yourpregnancyis high risk.",
                    "I am a pregnant women. Can I do housework?"=>" Yes, definitely that is good for your health and baby.In general, housework is a good way to stay active, and getting someexercise when you're pregnantwithout having to take time out for it. During pregnancy light activity maintain blood circulation in the body. So you feel happy, active, enthusiastic and that all things affect on your baby . But make sure you do not have any serious complain.",
                    "is it safe to exercise during pregnancy?"=>"yes, light exercise is good for health. Staying active during pregnancy keeps both you and your baby healthy and help to easepregnancy symptom like back pain, ease constipation.it keep bodyfeel fresh and active and also maintain body weight. You will need to be little extra careful and avoid exercise that are likely to lead to dehydration and overheating.",
                    "what if I have spotting during early pregnancy?"=>"many women who spot during early pregnancy assume that they have had a miscarriage , but that isn’t always a case . In fact,approximately 30%  of women will spot in the first trimester  go onto have a healthy pregnancy. If you do notice spotting notify your doctor.",
                    "how much weight should I gain during preganacy?"=>"most of doctor suggest gaining 10 to 12 kg during a single baby pregnancy. That is about 300 extra calories a day you shouldbe adding to your diet. While it may be tempting to load up on fastfood and ice cream, its important that these extra calories come from healthy food.",
                    "Do I need to change my beauty routine? What shouldI know about skincare/beauty routine during pregnancy?"=>"it depends, if your beauty routine includes prescription product then its okay to use it but you will also do well to avoid experimentation .your skin could be more sensitive during pregnancy so this is probably not a good time to try new products.",
                    "why do I feel so tired while pregnant?"=>"Simply put, youfeel tired because you're growing a baby. In addition to hormonal changes, physical and emotional changes also lower your energy levels and make youfeelfatigued. Some of these changes include: increased levels of estrogen and progesterone.",
                    "which kind of book do I read during pregnancy?"=>"you have to read spiritual books, story book, epic books like Ramayana,mahabharta,shiv puran as per indian culture. In short read the book which give you positive thoughts."];
        foreach($dataValues as $key => $value)
        {
            $dataArray['que'] = $key;
            $dataArray['ans'] = $value;
            array_push($data,$dataArray);
        }            
        return $this->sendResponse('Get Q-A list successfully', $data);
    }
    /**
    * Return Html Pages
    * @param  \Illuminate\Http\Request 
    * @return \Illuminate\Http\Response
    */
    public function getHtmlPages(Request $request)
    {
        $data = [];
        $dataValues = [];
        $html_pages = $this->HtmlPage->get();
        foreach($html_pages as $value)
        {
            $dataArray['slug'] = $value->slug;
            $dataArray['url'] = url('html-page/view/'.$value->slug);
            array_push($data,$dataArray);
        }            
        return $this->sendResponse('Get Html pages list successfully', $data);
    }
}
