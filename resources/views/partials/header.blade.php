<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ optional(Auth::user())->id }}">
    <meta name="moduleName" content="{{$moduleName ?? '0'}}">
    <meta name="objectId" content="{{$showModuleData['id'] ?? '0'}}">

    <meta name="description" content="{{$description ?? 'Kahaki CRM'}}">
    <meta name="keywords" content="{{$keywords ?? 'Kahaki CRM'}}">

    <title>{{ config('app.name', 'Kahaki') }}</title>


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('css/fullcalendar.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('css/ionicons/dist/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/kahaki.css') }}" id="theme" rel="stylesheet">
    <script src="{{ asset('js/kahaki/pace.js') }}"></script>
    <script type="text/javascript"></script>
    <style type="text/css">
        scroll-behavior: smooth;
    </style>
</head>

