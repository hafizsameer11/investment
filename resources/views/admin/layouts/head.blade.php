<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Core Mining - Admin Panel</title>
        <meta content="Core Mining Admin Dashboard - Manage your mining platform" name="description" />
        <meta content="Core Mining" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- Favicon -->
        <link rel="icon" type="image/jpeg" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="Core Mining - Admin Panel">
        <meta property="og:description" content="Core Mining Admin Dashboard - Manage your mining platform">
        <meta property="og:image" content="{{ asset('assets/dashboard/images/meta/logo.jfif') }}">
        <meta property="og:type" content="website">

        <!-- jvectormap -->
        <link href="{{ asset('assets/admin/plugins/jvectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/admin/plugins/fullcalendar/vanillaCalendar.css') }}" rel="stylesheet" type="text/css"  />
        <link href="{{ asset('assets/admin/plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet" type="text/css" media="screen">
        
        <link href="{{ asset('assets/admin/plugins/morris/morris.css') }}" rel="stylesheet">

        <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" type="text/css">

        <style>
            /* Hide breadcrumbs on all admin pages */
            .breadcrumb,
            .page-title-box .breadcrumb,
            .btn-group .breadcrumb {
                display: none !important;
            }
        </style>

        @stack('styles')

    </head>