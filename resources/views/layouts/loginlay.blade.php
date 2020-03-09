<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kahaki') }}</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/theme.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('css/kahaki.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('css/ionicons/dist/css/ionicons.min.css') }}" rel="stylesheet">
    
    <script src="{{ asset('js/kahaki/pace.js') }}"></script>
    <script type="text/javascript"></script>
    <style type="text/css">
        scroll-behavior: smooth;
    </style>
</head>

<body>
    <div class="page-wrapper chiller-theme toggled">
	    <main class="page-content">
		    <div class="container-fl">
	        	@yield('content')
	        </div>
    	</main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    @include('sweetalert::alert')
    <script>
        @yield('scripts')
    </script>
</body>
</html>
