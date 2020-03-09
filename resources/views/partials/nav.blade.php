<div class="header navbar navbar-default mt-n4 pl-n3">
		<div class="navbar-header kahaki-navbar">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="/" class="navbar-brand"><h3> kahaki CRM </h3></a>
		</div>
		<div class="collapse navbar-collapse align-top" id="header-navbar">
			<ul class="nav navbar-nav navbar-right">
<!--				<li class="nav-item"><a class="nav-link active" href="/">Home </a></li> -->
				@guest
				<li class="nav-item dropdown">
					<a class="nav-link" href="#" data-toggle="dropdown"><i class=" ion-ios-keypad"></i> Sign In <b class="caret"></b> </a>
					<div class="dropdown-menu dropdown-menu-left animated fadeInDown">
						<a class="dropdown-item" href="{{ route('login') }}">{{ __('Login') }}</a>
						 @if (Route::has('register'))
						 <a class="dropdown-item" href="{{ route('register') }}">{{ __('Register') }}</a>
						 @endif
					</div>
				</li>
				@endguest
				@auth
				<li class="nav-item dropdown" style="height: 125px">
					<a class="nav-link" href="#" data-toggle="dropdown"><i class=" ion-ios-keypad"></i> {{ Auth::user()->fname }} {{ Auth::user()->lname }} <b class="caret"></b> </a>
					<div class="dropdown-menu dropdown-menu-left animated fadeInDown">
						 <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> {{ __('Logout') }}
                          </a>

                            <form id="logout-form" action="/login" method="POST" style="display: none;">
                                @csrf
                            </form>
					</div>
				</li>
				@endauth
			</ul>
		</div>
</div>

