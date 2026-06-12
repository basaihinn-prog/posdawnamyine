@extends('layouts.auth.app')

@section('title')
    {{ __('Reset Password') }}
@endsection

@section('main_content')
<div class="footer position-relative">
    <div class="mybazar-login-section">
        <div class="mybazar-login-wrapper">
            <div class="login-wrapper">
                <div class="login-header">
                    <h4>{{ get_option('general')['name'] ?? '' }}</h4>
                </div>
                <div class="login-body w-100">
                     <div class="footer-logo w-100  ">
                                <img src="{{ asset(get_option('general')['login_page_logo'] ?? 'assets/images/login/login-logo.svg') }}" alt="">
                            </div>
                    <h2>{{ __('Set Up New Password') }}</h2>
                    <h6>{{ __('Reset your password and log in your account') }}</h6>
                    <form action="{{ route('password.store') }}" method="post" class="ajaxform_instant_reload">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <input type="hidden" name="email" value="{{ $request->email }}">

                        <div class="input-group">
                             <span class="input-icon"><img src="{{ asset('assets/images/icons/lock.svg') }}" alt="img"></span>
                            <span class="hide-pass">
                                <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                                <img src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                            </span>
                            <input type="password" id="password" name="password" class="form-control" placeholder="{{ __('New Password') }}">
                        </div>
                        <div class="input-group">
                         <span class="input-icon"><img src="{{ asset('assets/images/icons/lock.svg') }}" alt="img"></span>
                            <span class="hide-pass">
                                <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                                <img src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}">
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

@push('js')
<script src="{{ asset('assets/js/auth.js') }}"></script>
@endpush
