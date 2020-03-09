@extends('layouts.loginlay')

@section('title', 'Login Page')

@section('content')
    <div class="login-cover">
        <div class="login-cover-image" style="background-image: url(/images/nbo-bg1.jpg)" data-id="login-cover-image"></div>
        <div class="login-cover-bg"></div>
    </div>
    <div class="login login-v2" data-pageload-addclass="animated fadeIn" style="margin-top:  1%;">
        <!-- begin brand -->
        <div class="login-header">
            <div class="brand text-center">
              <img class="img-fluid rounded" style="height: 90px"  src="{{ asset('images/logo.png') }}">
            </div>
        </div>
        <div class="login-content">
            <div class="card">
              <div class="card-body text-center">
                <h4 class="card-title">Registration Status</h4>
                <hr>
                <p class="card-text">Registration has been disabled due to intergrity purposes</p>
                <hr>
                <a href="/login" class="btn btn-sm btn-danger">Back To Login</a>
              </div>
            </div>
        </div>
    </div>

@endsection
