<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> <!-- Favicon-->
        <title>@yield('title') - {{ config('app.name') }}</title>
        <meta name="description" content="@yield('meta_description', config('app.name'))">
        <meta name="author" content="@yield('meta_author', config('app.name'))">
        @yield('meta')
        {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
        @stack('before-styles')
        <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Custom Css -->
        <link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/authentication.css') }}">
        <link rel="stylesheet" href="{{asset('assets/css/color_skins.css') }}">
        <link rel="stylesheet" href="{{asset('assets/css/themes.css') }}">
        @stack('after-styles')
    </head>
    <?php 
        $setting = !empty($_GET['theme']) ? $_GET['theme'] : '';
        $theme = "theme-cyan";
        $menu = "";
        if ($setting == 'p') {
            $theme = "theme-purple";
        } else if ($setting == 'b') {
            $theme = "theme-blue";
        } else if ($setting == 'g') {
            $theme = "theme-green";
        } else if ($setting == 'o') {
            $theme = "theme-orange";
        } else if ($setting == 'bl') {
            $theme = "theme-blush";
        } else {
            $theme = "theme-cyan";
        }
    
        $systemSetting = systemSetting();
        $primary = isset($systemSetting->primary) && !empty($systemSetting->primary) ? $systemSetting->primary : null;

    ?>
    <style>
        .footer, .page-loader-wrapper p{
            color: <?php echo $primary ?> !important;
        }
    </style>
    <body class="<?= $theme ?> authentication sidebar-collapse">
        <nav class="navbar navbar-expand-lg fixed-top navbar-transparent">
            <div class="container">        
                <div class="navbar-translate n_logo">
                    <a class="navbar-brand" href="javascript:void(0);" title="" target="_blank"></a>
                    <button class="navbar-toggler" type="button">
                        <span class="navbar-toggler-bar bar1"></span>
                        <span class="navbar-toggler-bar bar2"></span>
                        <span class="navbar-toggler-bar bar3"></span>
                    </button>
                </div>
            </div>
        </nav>
        <div class="page-header">
            <div class="page-header-image">
            </div>
            @yield('content')
            <footer class="footer">
                <div class="container">
                    <div class="">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>,
                        <span>{{ !empty($systemSetting->footer_copyright) ? $systemSetting->footer_copyright : null }} Designed by UNIKWORK</span>
                    </div>
                </div>
            </footer>
        </div>
        <!-- Scripts -->
        @stack('before-scripts')
        <script src="{{ asset('assets/bundles/libscripts.bundle.js') }}"></script>    
        <script src="{{ asset('assets/bundles/vendorscripts.bundle.js') }}"></script>
        
        @stack('after-scripts')
    </body>
</html>
