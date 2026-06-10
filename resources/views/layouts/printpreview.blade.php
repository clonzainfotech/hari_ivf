<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $systemSetting = systemSetting();
    $html_favicon = isset($systemSetting->html_favicon) && !empty($systemSetting->html_favicon) ? $systemSetting->html_favicon : 'favicon.ico';
    $header_logo = isset($systemSetting->header_logo) && !empty($systemSetting->header_logo) ? $systemSetting->header_logo : null;
    $title = isset($systemSetting->html_title) && !empty($systemSetting->html_title) ? $systemSetting->html_title : null;
    $primary = isset($systemSetting->primary) && !empty($systemSetting->primary) ? $systemSetting->primary : null;
    $secondary = isset($systemSetting->secondary) && !empty($systemSetting->secondary) ? $systemSetting->secondary : null;
    $link = isset($systemSetting->link) && !empty($systemSetting->link) ? $systemSetting->link : null;
    $before_visits = isset($systemSetting->before_visits) && !empty($systemSetting->before_visits) ? $systemSetting->before_visits : null;
    $after_visits = isset($systemSetting->after_visits) && !empty($systemSetting->after_visits) ? $systemSetting->after_visits : null;
    $unpaid_opd = isset($systemSetting->unpaid_opd) && !empty($systemSetting->unpaid_opd) ? $systemSetting->unpaid_opd : null;
    
    $footer_1 = isset($systemSetting->footer_1) && !empty($systemSetting->footer_1) ? $systemSetting->footer_1 : null;
    $footer_2 = isset($systemSetting->footer_2) && !empty($systemSetting->footer_2) ? $systemSetting->footer_2 : null;
    $docter_1 = isset($systemSetting->docter_1) && !empty($systemSetting->docter_1) ? $systemSetting->docter_1 : null;
    $docter_2 = isset($systemSetting->docter_2) && !empty($systemSetting->docter_2) ? $systemSetting->docter_2 : null;
    $water_mark = isset($systemSetting->water_mark) && !empty($systemSetting->water_mark) ? $systemSetting->water_mark : null;
    ?>

    <style type="text/css">
        .invoice-receipt {
            font-family: 'Montserrat', Arial, Tahoma, sans-serif;
            width: 100%;
        }
    
        .invoice-receipt-th {
            line-height: 15px;
            font-size: 18px;
            font-weight: 900;
            height:25px;
        }
        .invoice-receipt{
            background-color: <?php echo $primary ?> !important;
            -webkit-print-color-adjust: exact;
            }
            .title{
                color: <?php echo $primary ?> !important;
                font-weight: 600;
                -webkit-print-color-adjust: exact;
            }
            h4{
                color: <?php echo $primary ?> !important;
                text-align:end ;
                font-weight: bold!important;
                font-size: 15px!important;
            }
            .doctor_name
            {
                font-weight: bold;
                color: <?php echo $primary ?> !important;
                font-size: 14px;
                line-height: 20px;
            }
            h1{
                font-size: 30px!important;
            }
            .system-setting-logo{
                height: 72px !important;
            }
            .preview{
                height: 3px !important;
            }
            p{
            color: <?php echo $primary ?> !important;
            font-weight: bolder;
            font-size: 18px;
            text-align:center;
            }
            .font-bold
            {
                font-weight: bold;
            }
            .font-17
            {
                font-size: 17px;
            }
            .font-22
            {
                font-size: 20px !important;
            }
            
            .copyright>p{
                font-size: 26px; 
                font-family: auto;
            }
            
            .copyright>h4{
                text-align: center;
            }
            .watermark{
                background-repeat: no-repeat;
                background-position: center;
                -webkit-print-color-adjust: exact;
                position: relative;
            /* background-image:url({{url('images/' . $water_mark)}}); */
            }
            .watermark:before {
            position: absolute;
            content: '';
            width: 600px;
            height: 100%;
            background-repeat: no-repeat;
            background-size: contain;
            top: 42%;
            left: 0;
            right: 0;
            margin: auto;
            z-index: 9;
            opacity: 0.2;
            background-image: url({{url('images/' . $water_mark)}});
        }
        .study-report-table
        {
            margin-top: 20px;
        }
        @media(max-width: 991px) {
            .dr-name h4 {
                text-align: center
            }
            .watermark:before{
                width: auto;
            }
        }
    </style>
    {{-- <link rel="stylesheet" href="{{url('assets/css/themes.css')}}"> --}}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        {{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> --}}
    </head>
    @php
    // $url = ($data == null) ? 'system-create' : 'system-update';
    @endphp
    
    <!-- ======= header ======= -->
      <header id="header" class="fixed-top header-inner-pages">
        <div class="container d-flex align-items-center justify-content-between">
            <div class="invoice-receipt" cellpadding="0" cellspacing="0">
                <div class="invoice-receipt-th text-center"></div>
            </div>
            <div class="row" style="padding: 20px;">
                <div class="col-xs-2 col-md-2"> @if (!empty($html_favicon))
                    
                        <img src="{{url('assets/' . $html_favicon)}}"  class="system-setting-favicon"/>
                   
                    @endif
                </div>
                <div class="col-xs-10 col-md-6">
                    <div class="row">
                        <div class="col-md-12 text-center">         
                            <div>       
                                <h1 class="title">Radha Hospital</h1>
                                <h1 class="title" style="text-align: center !important; margin:0px !important;">&</h1>
                                @if (!empty($header_logo))
                                    <img src="{{url('images/' . $header_logo)}}" class="system-setting-logo"/>
                                @endif
                            </div>
                        </div>
                    </div>     
                </div>
                <div class="col-xs-12 col-md-4 text-center dr-name">
                    <h4>
                        @php
                            if(!empty($docter_1))
                            {
                                $docter_1 = explode('/',$docter_1);
                                foreach($docter_1 as $key => $doctor)
                                {
                                    $bold = ($key == 0) ? 'font-bold font-17' : '';
                                    echo '<span class="doctor_name '.$bold.'">'.$doctor.'</span><br>';
                                }
                            }
                        @endphp
                        </h4>
                    <h4>
                        @php
                        if(!empty($docter_2))
                        {
                            $docter_2 = explode('/',$docter_2);
                            foreach($docter_2 as $key => $doctor)
                            {
                                $bold = ($key == 0) ? 'font-bold font-17' : '';
                                echo '<span class="doctor_name '.$bold.'">'.$doctor.'</span><br>';
                            }
                        }
                    @endphp</h4>
                </div>
            </div>
           
            <div class="invoice-receipt preview" >
                <div class="invoice-receipt-th text-center"></div>
            </div>
        </div>
    </header><!-- End Header -->
      
    
    <!-- ======= main ======= -->
      <main id="main">
       
        <div class="container">
             {{-- <div class="watermark"><img src="{{url('images/' . $water_mark)}}"></div> --}}
            @yield('content')
        </div>
     </main><!-- End main -->
    
    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-newsletter">
          <div class="container">
                <p>{{$footer_1}}</p>
                <div class="invoice-receipt preview" >
                    <div class="invoice-receipt-th text-center"></div>
                </div>
          </div>
        </div>
        <div class="container" style="padding: 10px;">
            <div class="copyright">
                  <p>{{$footer_2}}</p>
                    @if(!empty($hospitalAddress) && count($hospitalAddress) > 0	)
                        @foreach ($hospitalAddress as $row)
                                <h4>
                                    {{$row->address}} {{$row->mobile}}
                                </h4>
                        @endforeach
                    @endif
            </div>
        </div>
        <div class="footer-newsletter">
            <div class="container">
                  <div class="invoice-receipt" >
                      <div class="invoice-receipt-th text-center"></div>
                  </div>
              </div>
          </div>
    </footer><!-- End Footer -->
    
      <div id="preloader"></div> 
    
      