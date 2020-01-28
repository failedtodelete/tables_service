<!DOCTYPE html>
<html class="loading" lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-textdirection="ltr">
<head>
    <meta charset="utf-8">
{{--    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">--}}
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Icons -->
    <link rel="apple-touch-icon" href="/assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/ico/favicon.ico">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">

    <!-- BEGIN: Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/weather-icons/climacons.min.css">
    <link rel="stylesheet" type="text/css" href="/assets/fonts/meteocons/style.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/charts/morris.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/charts/chartist.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/charts/chartist-plugin-tooltip.css">
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/forms/selects/select2.min.css">
    <!-- END: Vendor CSS -->

    <!-- BEGIN: Theme CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/components.css">
    <!-- END: Theme CSS -->

    <!-- BEGIN: Page CSS -->
    <link rel="stylesheet" type="text/css" href="/assets/css/core/menu/menu-types/vertical-menu-modern.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="/assets/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/pages/timeline.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/pages/dashboard-ecommerce.css">
    <!-- END: Page CSS -->

    @javascript('user', $user)
    @javascript('role', $role)
    @javascript('permissions', $permissions)

</head>
<body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <div id="app"></div>


    <!-- BEGIN: Vendor JS -->
    <script src="/assets/vendors/js/vendors.min.js"></script>
    <script src="/assets/vendors/js/ui/jquery.sticky.js"></script>

    <!-- BEGIN Vendor JS -->

    <!-- BEGIN: Theme JS -->
    <script src="/assets/js/core/app-menu.js"></script>
    <script src="/assets/js/core/app.js"></script>
    <!-- END: Theme JS -->

    <!-- BEGIN: Page Vendor JS -->
    {{--<script src="/assets/vendors/js/charts/chartist.min.js"></script>--}}
    {{--<script src="/assets/vendors/js/charts/chartist-plugin-tooltip.min.js"></script>--}}
    {{--<script src="/assets/vendors/js/charts/raphael-min.js"></script>--}}
    {{--<script src="/assets/vendors/js/charts/morris.min.js"></script>--}}
    {{--<script src="/assets/vendors/js/timeline/horizontal-timeline.js"></script>--}}
    <!-- END: Page Vendor JS -->

</body>
</html>
