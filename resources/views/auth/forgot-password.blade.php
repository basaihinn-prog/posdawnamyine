@extends('layouts.auth.app')

@section('title')
    {{ __('Forget Password') }}
@endsection

@section('main_content')
<div class="footer">
    <div class="mybazar-login-section">
        <div class="mybazar-login-wrapper">
            <div class="login-wrapper">
                 <div class="footer-logo w-100  ">
                    <img src="{{ asset(get_option('general')['login_page_logo'] ?? 'assets/images/login/login-logo.svg') }}" alt="">
                </div>
                <div class="login-header">
                    <h4>{{ get_option('general')['name'] ?? '' }}</h4>
                </div>
                <div class="login-body w-100">
                    <h2>{{ __('Forgot Password') }}</h2>
                    <h6>{{ __('Enter your email address and we will send you reset link') }}</h6>
                    <form method="POST" action="{{ route('password.email') }}" class="ajaxform">
                        @csrf
                         <input type="hidden" name="type" value="{{ request('type') }}">
                        <div class="input-group">
                            <span><img src="{{ asset('assets/images/icons/email.svg') }}" alt="img"></span>
                            <input type="email" name="email" class="form-control" placeholder="{{ __('Enter your Email') }}">
                        </div>

                        <button type="submit" class="btn login-btn submit-btn">{{ __('Continue') }}</button>
                    </form>

                </div>
            </div>
            <div class="login-img">
                <img src="{{ asset(get_option('general')['login_page_img'] ?? 'assets/images/login/login-img.svg') }}" alt="">
            </div>
        </div>
    </div>
</div>
@endsection

