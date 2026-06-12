@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Subscriptions List') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{__('Dashboard')}} </a>
                    <span>/ {{__('Subscription')}}</span>
                </h2>
            </div>
            <div class="card">
                <div class="card-bodys">
                    <div class="premium-plan-container ">
                        <div class="premium-plan-content">
                        @foreach ($plans as $plan)
                                @php
                                    $activePlan = plan_data();
                                    $isFreePlan = $plan->subscriptionPrice == 0;
                                    $isPlanActivated = $activePlan != null;
                                    $business = auth()->user()->business ?? null;
                                    $notPurchaseAble = ($activePlan && $activePlan->plan_id == $plan->id && ($business && $business->will_expire > now()->addDays(7)))
                                        || ($business && $business->will_expire >= now()->addDays($plan->duration));
                                @endphp

                                <div class="plan-single-content">
                                    @if ((plan_data() ?? false) && plan_data()->plan_id == $plan->id)
                                    <div class="recommended-banner-container ">
                                        <div class="recommended-banner">
                                            <span>{{ __('Current Plan') }}</span>
                                          </div>
                                    </div>
                                    @endif
                                    <div class="d-flex align-items-center justify-content-center flex-column gap-3">
                                        <h3 class="pb-2 subscription-title">{{ $plan->subscriptionName }}</h3>
                                        <h6 class="pb-2">{{ $plan->duration }} {{ __('Days') }}</h6>
                                        <h1 class="pb-2 subscription-price">{{ currency_format(convert_money($plan->offerPrice ?? $plan->subscriptionPrice, business_currency()), currency : business_currency()) }} <span>
                                        @if ($plan->offerPrice)
                                            {{ currency_format(convert_money($plan->subscriptionPrice, business_currency()), currency: business_currency()) }}
                                        @endif
                                        </span></h1>

                                        @usercan('subscription.create')
                                        @if ($isFreePlan && $isPlanActivated || $notPurchaseAble)
                                            <button class="btn w-100 plan-buy-btn" disabled>
                                                {{ __('Buy Now')  }}
                                            </button>
                                        @else
                                            <a href="{{ route('payments-gateways.index', ['plan_id' => $plan->id, 'business_id' => auth()->user()->business_id]) }}" class="btn w-100 plan-buy-btn">
                                                {{ __('Buy Now') }}
                                            </a>
                                        @endif
                                        @endusercan

                                    </div>
                                    @foreach ($plan->features ?? [] as $item)
                                        <div class="d-flex align-items-center justify-content-between plans">
                                            <div class="d-flex align-items-center gap-2">
                                                <p>
                                                    <i class="fas {{ ($item['status'] ?? 0) == 1 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i>
                                                    {{ $item['feature'] ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if (moduleCheck('CustomDomainAddon'))
                                    <div class="d-flex align-items-center justify-content-between plans">
                                        <div class="d-flex align-items-center gap-2">
                                            <p><i class="fas {{ $plan->addon_domain_limit > 0 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i>
                                                {{ $plan->addon_domain_limit > 0 ? __('Addon Limit:') . ' ' . $plan->addon_domain_limit : __('Addon Domain Available?') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between plans">
                                        <div class="d-flex align-items-center gap-2">
                                            <p><i class="fas {{ $plan->subdomain_limit > 0 ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }} me-1"></i>
                                                {{ $plan->subdomain_limit > 0 ? __('Subdomain Limit:') . ' ' . $plan->subdomain_limit : __('Subdomain Available?') }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
