<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('partials.header')
<body>
    <div class="page-wrapper chiller-theme toggled">
	    @include('partials.sidebar')
	    <main class="page-content">
		    <div class="container-fl" id="app">
	        	@yield('content')
	        </div>
    	</main>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>

    @include('sweetalert::alert')
    <script>
        @yield('scripts')
    </script>
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
</body>
</html>