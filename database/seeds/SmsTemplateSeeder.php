<?php

use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sms_template')->insert([
            [
                'template' => 'Dear {{patient_name}},
                                You have appointment on {{apt_date}}.',
                'module' => 'sendAptToPatient',
                'status' => 1,
            ],
            [
                'template' => 'Dear {{reff_drname}},
                                Thank you for reffering patient {{patient_name}} for {{ot_name}} today',
                'module' => 'sendAptToRefDoctor',
                'status' => 1,
            ],
            [
                'template' => 'Dear {{reff_drname}},
                                    Your Reff. Pt. {{patient_fullname}} has admitted our hospital {{msg}} {{gender}} child wt {{weight}} kg at {{time}} on {{date}}.
                                    Thank you for Reference and expecting your favorable support in future.
                            From :- Civora Hospital & Maternity Home
                            0261-2548096',
                'module' => 'sendDischargeCardToRefDoctor',
                'status' => 1,
            ],
            [
                'template' => 'Dear Patient,
                                {{otp}} is the your OTP for the login in to the RadhIVF system. Thank you, CandorIVF',
                'module' => 'sendOtpToPatients',
                'status' => 1,
            ],
            [
                'template' => 'Dear {{reff_drname}},
                                Thank you for reffering patient {{patient_fullname}},
                                CandorIVF',
                'module' => 'sendAlrtOpdToDoctor',
                'status' => 1,
            ],
            
        ]);
    }
}
