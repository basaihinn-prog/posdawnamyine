@extends('layouts.auth.app')

@section('title')
    {{ __('Login') }}
@endsection

@section('main_content')
    <div class="loging-header">
        <div class="footer-logo">
            <img src="{{ asset(get_option('general')['login_page_logo'] ?? 'assets/images/login/login-logo.svg') }}" alt="logo">
        </div>

        @if (moduleCheck('RestaurantOnlineStore'))
        @php
        $business = get_business();
        @endphp
        <div class="login-stor-btn">
            <a href="{{ route('onlineStore.index', ['restaurant_slug' => $business->slug ]) }}">Online Store</a>
        </div>
        @endif
    </div>
    <div class="footer position-relative">
        <div class="mybazar-login-section ">
            <div class="mybazar-login-wrapper ">
                <div class="login-wrapper">
                    <div class="login-body w-100">
                        <h2 class="login-title">{{ __('Welcome to') }} {{ __(env('APP_NAME')) }}</h2>
                        <form method="POST" action="{{ route('login') }}" class="ajaxform_instant_reload">
                            @csrf
                            <input type="hidden" name="fcm_token" id="fcm_token">
                            <div class="input-group ">
                                <span class="input-icon"><img src="{{ asset('assets/images/icons/email.svg') }}" alt="img"></span>
                                <input type="text" name="email" class="form-control w-100 dynamictext" placeholder="{{ __('Enter your Email') }}">
                            </div>

                            <div class="input-group">
                                <span class="input-icon"><img src="{{ asset('assets/images/icons/lock.svg') }}" alt="img"></span>

                                <span class="hide-pass">
                                    <img src="{{ asset('assets/images/icons/show.svg') }}" alt="img">
                                    <img src="{{ asset('assets/images/icons/Hide.svg') }}" alt="img">
                                </span>
                                <input type="password" name="password" class="form-control w-100 password" placeholder="{{ __('Password') }}">
                            </div>

                            <div class="mt-lg-3 mb-0 forget-password">
                                <label class="custom-control-label">
                                    <input type="checkbox" name="remember" class="custom-control-input">
                                    <span>{{ __('Remember me') }}</span>
                                </label>
                                <a href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                            </div>

                            <button type="submit" class="btn login-btn submit-btn">{{ __('Log In') }}</button>


                            {{-- <div class="d-flex justify-content-between">
                                <a class="backhome" href="{{ url('/') }}">{{ __('Back Home') }}</a>
                                <a class="backhome" data-bs-toggle="modal" data-bs-target="#registration-modal">{{ __('Create an Account') }}</a>
                            </div> --}}
                        </form>
                    </div>
                </div>
                <div class="login-img">
                    <img src="{{ asset(get_option('general')['login_page_img'] ?? 'assets/images/login/login-img.svg') }}"
                        alt="">
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" data-model="Login" id="auth">
@endsection

@push('modal')
    @include('web.components.signup')
@endpush

@push('js')
    <script src="{{ asset('assets/js/auth.js') }}?v={{ time() }}"></script>
@endpush
