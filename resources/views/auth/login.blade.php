@extends('layouts.loginlay')

@section('title', 'Login Page')

@section('content')
    <div class="login-cover">
        <div class="login-cover-image" style="background-image: url(/images/nbo-bg1.jpg)" data-id="login-cover-image"></div>
        <div class="login-cover-bg"></div>
    </div>
    <!-- begin login -->
    <div class="login login-v2" data-pageload-addclass="animated fadeIn">
        <!-- begin brand -->
        <div class="login-header">
            <div class="brand text-center">
              <img class="img-fluid rounded" style="height: 90px"  src="{{ asset('images/logo.png') }}">
            </div>
        </div>
        <!-- end brand -->
        <!-- begin login-content -->
        <div class="login-content">
            <form  class="margin-bottom-0" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <input name="email" id="email" type="email" class="form-control form-control-lg bg-light text-dark @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address" autofocus>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                              <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <input type="password" class="form-control form-control-lg bg-light text-dark " placeholder="Password" id="password" name="password" required />
                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="checkbox checkbox-css m-b-20 text-center">
                    <input type="checkbox" id="remember_checkbox" /> 
                    <label for="remember_checkbox">
                        Remember Me
                    </label>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">  
                        <div class="login-buttons">
                            <button type="submit" class="btn btn-success btn-block btn-lg btn-sm">Sign me in</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="login-buttons">
                            <a href="{{ route('register') }}" class="btn btn-warning btn-block btn-lg btn-sm">Register</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- end login-content -->
    </div>
    <!-- end login -->

@endsection

@section('scripts')

@endsection

