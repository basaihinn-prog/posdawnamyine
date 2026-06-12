<!DOCTYPE html>
@if (app()->getLocale() == 'ar')
<html lang="ar" dir="rtl">
@else
<html lang="en" dir="auto">
@endif
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="{{__('IE=edge')}}">
    <meta name="viewport" content="{{__('width=device-width, initial-scale=1.0')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title') @yield('title') | @endif {{ get_option('general')['title'] ?? config('app.name') }}</title>
    @include('layouts.partials.css')
</head>
<body>

<!-- Side Bar Start -->
@include('restaurantwebaddon::layouts.partials.side-bar')
<!-- Side Bar End -->
<div class="section-container">
    <!-- header start -->
    @include('restaurantwebaddon::layouts.partials.header')
    <!-- header end -->
    <!-- erp-state-overview-section start -->

    @yield('main_content')
    @if (!request()->routeIs('business.sales.create', 'business.sales.edit', 'business.quotations.edit', 'business.quotations.convert-sale'))
    @include('restaurantwebaddon::layouts.partials.footer')
    @endif
    <!-- erp-state-overview-section end -->
    @stack('modal')
</div>

<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="orderToast"
         class="toast align-items-center border-0"
         role="alert"
         aria-live="assertive"
         aria-atomic="true">

        <div class="toast-body d-flex align-items-center gap-2">
            <span class="toast-icon">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M25.6667 14C25.6667 7.55672 20.4433 2.33337 14 2.33337C7.55672 2.33337 2.33337 7.55672 2.33337 14C2.33337 20.4433 7.55672 25.6667 14 25.6667C20.4433 25.6667 25.6667 20.4433 25.6667 14Z" stroke="#00932C" stroke-width="1.5"/>
                <path d="M9.33337 14.875C9.33337 14.875 11.2 15.9396 12.1334 17.5C12.1334 17.5 14.9334 11.375 18.6667 9.33337" stroke="#00932C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <span class="fw-semibold text-dark">
                New Order Received
            </span>
        </div>
    </div>
</div>
@php
    $notification_sound = get_business_option('business-settings')['notification_sound'] ?? '';
@endphp
<input type="hidden" id="notification-sound-path" value="{{  asset($notification_sound ?? '') }}">

@include('restaurantwebaddon::layouts.partials.script')
</body>
</html>
