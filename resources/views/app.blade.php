<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia



    </body>
</html>
        <script type="text/javascript" src="{{ asset('layouts/bower_components/jquery/js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('layouts/bower_components/jquery-ui/js/jquery-ui.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('layouts/bower_components/popper.js/js/popper.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('layouts/bower_components/bootstrap/js/bootstrap.min.js') }}"></script>
        <!-- jquery slimscroll js -->
        <script type="text/javascript" src="{{ asset('layouts/bower_components/jquery-slimscroll/js/jquery.slimscroll.js') }}"></script>
        <!-- modernizr js -->
        <script type="text/javascript" src="{{ asset('layouts/bower_components/modernizr/js/modernizr.js') }}"></script>
        <script type="text/javascript" src="{{ asset('layouts/bower_components/modernizr/js/css-scrollbars.js') }}"></script>
        <!-- i18next.min.js -->
        <script type="text/javascript" src="{{ asset('layouts/bower_components/i18next/js/i18next.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('layouts/bower_components/i18next-xhr-backend/js/i18nextXHRBackend.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('layouts/bower_components/i18next-browser-languagedetector/js/i18nextBrowserLanguageDetector.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('layouts/bower_components/jquery-i18next/js/jquery-i18next.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('layouts/assets/js/common-pages.js') }}"></script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-23581568-13');
        </script>

