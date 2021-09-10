<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'candor'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'https://app.candorivf.com/'),

    'asset_url' => env('ASSET_URL', 'https://app.candorivf.com/'),


    'collection_password' => env('COLLECTION_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Kolkata',

    // twilio
    'TWILIO_ACCOUNT_SID'=>"ACb9ed21c22eeac6dbde553b98680fa9e3",
    'TWILIO_AUTH_TOKEN'=>"e38c7727d15e3535179475562fe17dd3",

    'WASSENGER_TOKEN'=>"63308ad48c244bea4864f4ec2ded350ffbcb4f5d8bd6648ca63dd1ecb2173ba7c741b12b3dcc4869",

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,

        App\Providers\GoogleDriveServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
        'PDF' => Barryvdh\DomPDF\Facade::class,
    ],

    'sendAptToPatient'=>'Dear {{patient_name}}, You have appointment on {{apt_date}}',
    'sendAptToRefDoctor'=>'Dear {{reff_drname}}, Thank you for reffering patient {{patient_name}} for {{ot_name}} today',
    'sendDischargeCardToRefDoctor'=>'Dear {{reff_drname}}, Your reffer {{patient_fullname}} has addmited our hospital {{msg}} {{gender}} child on {{date}} at {{time}} with {{weight}} Thank You for reference and expecting your favourable supprt future. {{app_name}}',
    'sendOtpToPatients'=>'Dear Patient, {{otp}} is the your OTP for the login in to the RadhIVF system. Thank you, {{app_name}}',
    'sendAlrtOpdToDoctor'=>'Dear {{reff_drname}},Thank you for reffering patient {{patient_fullname}}, {{app_name}}',
    'sendRoomRegistrationDoctor'=>'Dear {{reff_drname}}, Thank you for reffering patient {{patient_fullname}} for {{procedure}} Today, {{app_name}}',
    'sendReferenceDoctor'=>'Dear {{reff_drname}}, Thank you for reffering patient {{patient_fullname}} for {{advise}} Today, Seen by {{dr_name}} Next Followup Date {{followUp}} {{app_name}}',

    'loader' => 'assets/images/Spin.gif',
    'hospitalname1'=>'Candor Hospital & Candor IVF Center',
    'hospitalname2'=>'Candor Hospital & Maternity Home',
    'doctor'=>'Dr. Jaydev Dhameliya',
    'stopNotification' => ['anc','ivf','iui'],
    'embroyologist_doctor' => "BHAVNA BORKHATARIA",
    'embroyologist_degree' => "M.SC., PH.D." ,

    'GOOGLE_CLIENT_ID' => '984779626869-fjht6mtsrqfla3ug8rmdvpn1n55gn509.apps.googleusercontent.com',
    'GOOGLE_CLIENT_SECRET' => 'eSold9YPOYmRmF_j13M1WVZo',
    'GOOGLE_REFRESH_TOKEN' => '1//04lMN1kpE8NfeCgYIARAAGAQSNwF-L9Ir_MfDBdhubB1CXFkX6moKG-x9Rcjm9AqWLU1baEFUUzPvjzW0xy67wI6-IXDLzGHO8yE',
    'GOOGLE_FOLDER_ID' => '1uK79a2YTAOW4CALT0eWgoDHtnkzkHnaX',
    'social_reference' => ['banner','camp','youtube','insta','facebook','pamplets','call'],

    'reject_apt_reason_en' => ["0"=>"We can't book right now as tomorrow OPD schedule is not planned yet. We contact you as soon as it planned or you can contact us on phone.",
                                "1"=>"Thank you for your booking an appointment with us. We Will assure you to take in consultation room with Dr no booked time if you come on time. Sorry for inconvenience in advance if we can't for that if any unavoidable circumstance will be there.",
                                "2"=>"Sorry, Your Appointment is already booked on this time"],
    'reject_apt_reason_gu' => ["0"=>"અમે હમણાં બુકિંગ કરી શકતા નથી કારણ કે આવતીકાલે ઓપીડીનું સમયપત્રક હજુ આયોજન નથી. અમે તમારી યોજના પ્રમાણે જ તમારો સંપર્ક કરીએ છીએ અથવા તમે ફોન પર અમારો સંપર્ક કરી શકો છો.",
                    "1"=>"અમારી સાથે એપોઇન્ટમેન્ટ બુક કરવા બદલ આભાર. જો તમે સમયસર આવો તો અમે તમને ડો.ની સાથે કન્સલ્ટેશન રૂમમાં લઈ જવાની ખાતરી આપીશું. જો કોઈ અનિવાર્ય સંજોગો હશે તો તે માટે અમે અગાઉથી અસુવિધા માટે માફ કરશો.",
                    "2"=>"માફ કરશો, તમારી નિમણૂક આ સમયે પહેલેથી જ બુક થઈ ગઈ છે."],
    'reject_apt_reason_hn' =>   ["0"=>"हम अभी बुकिंग नहीं कर सकते क्योंकि कल ओपीडी कार्यक्रम अभी तक योजनाबद्ध नहीं है। जैसे ही इसकी योजना होगी हम आपसे संपर्क करेंगे या आप हमसे फोन पर संपर्क कर सकते हैं।",
                        "1"=>"हमारे साथ अपॉइंटमेंट बुक करने के लिए धन्यवाद। यदि आप समय पर आते हैं तो हम आपको डॉ के साथ परामर्श कक्ष में कोई बुक समय नहीं लेने का आश्वासन देंगे। असुविधा के लिए अग्रिम खेद है यदि हम उसके लिए नहीं कर सकते हैं यदि कोई अपरिहार्य परिस्थिति होगी।",
                        "2"=>"क्षमा करें, इस समय आपका अपॉइंटमेंट पहले से ही बुक है"],             

    
];
