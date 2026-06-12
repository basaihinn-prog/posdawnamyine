@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Settings') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                  <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                      <span>/ {{__('Settings')}}</span>
                  </h2>
              </div>
            <div class="card shadow-sm">
                <div class="card-bodys">
                    <ul class="nav nav-tabs " id="settingsTab" role="tablist">
                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link active" id="all-tab" data-bs-toggle="tab"
                                data-bs-target="#all" type="button" role="tab">
                                {{__('All Settings')}}
                            </button>
                        </li>

                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="general-tab" data-bs-toggle="tab"
                                data-bs-target="#general" type="button" role="tab">
                                {{__('General')}}
                            </button>
                        </li>

                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#role" type="button" role="tab">
                                {{__('Role & Permission')}}
                            </button>
                        </li>

                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#Currencies" type="button" role="tab">
                                {{__('Currencies')}}
                            </button>
                        </li>

                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#Payment" type="button" role="tab">
                                {{__('Payment Type')}}
                            </button>
                        </li>
                        @if(moduleCheck('RestaurantOnlineStore'))
                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#payment_gateway" type="button" role="tab">
                                {{__('Payment Gateway')}}
                            </button>
                        </li>
                        @endif
                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#reservation" type="button" role="tab">
                                {{__('Reservation')}}
                            </button>
                        </li>
                        @if(moduleCheck('RestaurantOnlineStore'))
                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#customer_order_type" type="button" role="tab">
                                {{__('Customer Order Types')}}
                            </button>
                        </li>
                        @endif
                        @if(moduleCheck('RestaurantOnlineStore'))
                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#delivery_charge" type="button" role="tab">
                                {{__('Delivery Charge')}}
                            </button>
                        </li>
                        @endif
                        @if(moduleCheck('RestaurantOnlineStore'))
                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#discount" type="button" role="tab">
                                {{__('Discount')}}
                            </button>
                        </li>
                        @endif
                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link" id="role-tab" data-bs-toggle="tab"
                                data-bs-target="#cancel_reason" type="button" role="tab">
                                {{__('Cancel Reason')}}
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="settingsTabContent">
                        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                            <div class="settings-box-container">
                                @usercan('generalSetting.view')
                                <div>
                                    <a href="{{ route('business.settings.index') }}" class="text-decoration-none text-dark">
                                        <div class=" setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.5 12C15.5 13.933 13.933 15.5 12 15.5C10.067 15.5 8.5 13.933 8.5 12C8.5 10.067 10.067 8.5 12 8.5C13.933 8.5 15.5 10.067 15.5 12Z"
                                                            stroke="#FC8019" stroke-width="1.5" />
                                                        <path
                                                            d="M21.011 14.0949C21.5329 13.9542 21.7939 13.8838 21.8969 13.7492C22 13.6147 22 13.3982 22 12.9653V11.0316C22 10.5987 22 10.3822 21.8969 10.2477C21.7938 10.1131 21.5329 10.0427 21.011 9.90194C19.0606 9.37595 17.8399 7.33687 18.3433 5.39923C18.4817 4.86635 18.5509 4.59992 18.4848 4.44365C18.4187 4.28738 18.2291 4.1797 17.8497 3.96432L16.125 2.98509C15.7528 2.77375 15.5667 2.66808 15.3997 2.69058C15.2326 2.71308 15.0442 2.90109 14.6672 3.27709C13.208 4.73284 10.7936 4.73278 9.33434 3.277C8.95743 2.90099 8.76898 2.71299 8.60193 2.69048C8.43489 2.66798 8.24877 2.77365 7.87653 2.98499L6.15184 3.96423C5.77253 4.17959 5.58287 4.28727 5.51678 4.44351C5.45068 4.59976 5.51987 4.86623 5.65825 5.39916C6.16137 7.33686 4.93972 9.37599 2.98902 9.90196C2.46712 10.0427 2.20617 10.1131 2.10308 10.2476C2 10.3822 2 10.5987 2 11.0316V12.9653C2 13.3982 2 13.6147 2.10308 13.7492C2.20615 13.8838 2.46711 13.9542 2.98902 14.0949C4.9394 14.6209 6.16008 16.66 5.65672 18.5976C5.51829 19.1305 5.44907 19.3969 5.51516 19.5532C5.58126 19.7095 5.77092 19.8172 6.15025 20.0325L7.87495 21.0118C8.24721 21.2231 8.43334 21.3288 8.6004 21.3063C8.76746 21.2838 8.95588 21.0957 9.33271 20.7197C10.7927 19.2628 13.2088 19.2627 14.6689 20.7196C15.0457 21.0957 15.2341 21.2837 15.4012 21.3062C15.5682 21.3287 15.7544 21.223 16.1266 21.0117L17.8513 20.0324C18.2307 19.8171 18.4204 19.7094 18.4864 19.5531C18.5525 19.3968 18.4833 19.1304 18.3448 18.5975C17.8412 16.66 19.0609 14.621 21.011 14.0949Z"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('General Settings')}}</h6>
                                                    <small class="text-muted d-block">{{__('Configure the fundamental information of the site.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan

                                @usercan('notification.view')
                                <div>
                                    <a href="{{ route('business.notifications.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.49235 11.491C5.41887 12.887 5.50334 14.373 4.25611 15.3084C3.67562 15.7438 3.33398 16.427 3.33398 17.1527C3.33398 18.1508 4.11578 19 5.13398 19H19.534C20.5522 19 21.334 18.1508 21.334 17.1527C21.334 16.427 20.9924 15.7438 20.4119 15.3084C19.1646 14.373 19.2491 12.887 19.1756 11.491C18.9841 7.85223 15.9778 5 12.334 5C8.69015 5 5.68386 7.85222 5.49235 11.491Z"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M10.834 3.125C10.834 3.95343 11.5056 5 12.334 5C13.1624 5 13.834 3.95343 13.834 3.125C13.834 2.29657 13.1624 2 12.334 2C11.5056 2 10.834 2.29657 10.834 3.125Z"
                                                            stroke="#FC8019" stroke-width="1.5" />
                                                        <path
                                                            d="M15.334 19C15.334 20.6569 13.9909 22 12.334 22C10.6771 22 9.33398 20.6569 9.33398 19"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Notifications')}}</h6>
                                                    <small class="text-muted d-block">{{__('Control and configure overall notification systems')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan

                                @usercan('currency.view')
                                <div>
                                    <a href="{{ route('business.currencies.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M3.16602 12C3.16602 7.77027 3.16602 5.6554 4.36399 4.25276C4.5341 4.05358 4.7196 3.86808 4.91878 3.69797C6.32142 2.5 8.43629 2.5 12.666 2.5C16.8957 2.5 19.0106 2.5 20.4132 3.69797C20.6124 3.86808 20.7979 4.05358 20.968 4.25276C22.166 5.6554 22.166 7.77027 22.166 12C22.166 16.2297 22.166 18.3446 20.968 19.7472C20.7979 19.9464 20.6124 20.1319 20.4132 20.302C19.0106 21.5 16.8957 21.5 12.666 21.5C8.43629 21.5 6.32142 21.5 4.91878 20.302C4.7196 20.1319 4.5341 19.9464 4.36399 19.7472C3.16602 18.3446 3.16602 16.2297 3.16602 12Z"
                                                            stroke="#FC8019" stroke-width="1.5" />
                                                        <path
                                                            d="M15.3762 10.063C15.2771 9.30039 14.4014 8.06817 12.8268 8.06814C10.9972 8.06811 10.2274 9.08141 10.0712 9.58806C9.82746 10.2657 9.8762 11.659 12.0207 11.8109C14.7014 12.0009 15.7753 12.3174 15.6387 13.958C15.502 15.5985 14.0077 15.953 12.8268 15.9149C11.6458 15.877 9.71365 15.3344 9.63867 13.8752M12.6394 7V8.07177M12.6394 15.9051V16.9999"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Currencies')}}</h6>
                                                    <small class="text-muted d-block">{{__('View and update currency settings')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan

                                @usercan('rolePermission.view')
                                <div>
                                    <a href="{{ route('business.roles.index') }}" class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M11.5 14.0116C9.45338 13.9164 7.38334 14.4064 5.57757 15.4816C4.1628 16.324 0.453366 18.0441 2.71266 20.1966C3.81631 21.248 5.04549 22 6.59087 22H12"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M15.5 6.5C15.5 8.98528 13.4853 11 11 11C8.51472 11 6.5 8.98528 6.5 6.5C6.5 4.01472 8.51472 2 11 2C13.4853 2 15.5 4.01472 15.5 6.5Z"
                                                            stroke="#FC8019" stroke-width="1.5" />
                                                        <path
                                                            d="M18 20.7143V22M18 20.7143C16.8432 20.7143 15.8241 20.1461 15.2263 19.2833M18 20.7143C19.1568 20.7143 20.1759 20.1461 20.7737 19.2833M15.2263 19.2833L14.0004 20.0714M15.2263 19.2833C14.8728 18.773 14.6667 18.1597 14.6667 17.5C14.6667 16.8403 14.8727 16.2271 15.2262 15.7169M20.7737 19.2833L21.9996 20.0714M20.7737 19.2833C21.1272 18.773 21.3333 18.1597 21.3333 17.5C21.3333 16.8403 21.1273 16.2271 20.7738 15.7169M18 14.2857C19.1569 14.2857 20.1761 14.854 20.7738 15.7169M18 14.2857C16.8431 14.2857 15.8239 14.854 15.2262 15.7169M18 14.2857V13M20.7738 15.7169L22 14.9286M15.2262 15.7169L14 14.9286"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('User Role')}}</h6>
                                                    <small class="text-muted d-block">{{__('Add new users, Provide role and Permission')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan

                                @usercan('paymentMethod.view')
                                <div>
                                    <a href="{{ route('business.payment-types.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M13.5 15H6C4.11438 15 3.17157 15 2.58579 14.4142C2 13.8284 2 12.8856 2 11V7C2 5.11438 2 4.17157 2.58579 3.58579C3.17157 3 4.11438 3 6 3H18C19.8856 3 20.8284 3 21.4142 3.58579C22 4.17157 22 5.11438 22 7V12C22 12.9319 22 13.3978 21.8478 13.7654C21.6448 14.2554 21.2554 14.6448 20.7654 14.8478C20.3978 15 19.9319 15 19 15"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M14 9C14 10.1045 13.1046 11 12 11C10.8954 11 10 10.1045 10 9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9Z"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M13 17C13 15.3431 14.3431 14 16 14V12C16 10.3431 17.3431 9 19 9V14.5C19 16.8346 19 18.0019 18.5277 18.8856C18.1548 19.5833 17.5833 20.1548 16.8856 20.5277C16.0019 21 14.8346 21 12.5 21H12C10.1362 21 9.20435 21 8.46927 20.6955C7.48915 20.2895 6.71046 19.5108 6.30448 18.5307C6 17.7956 6 16.8638 6 15"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Payment Type')}}</h6>
                                                    <small class="text-muted d-block">{{__('Manage payment method for purchase.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan
                                @if(moduleCheck('RestaurantOnlineStore'))
                                <div>
                                    <a href="{{ route('business.payment-gateway.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.01733 14C4.2169 14 6.00001 15.7831 6.00001 17.9827" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.00001 4.01562C6.00001 6.21519 4.2169 7.9983 2.01733 7.9983" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M18 4.01562C18 6.19595 19.769 7.96706 21.9423 7.9979" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M22 11V10C22 7.17157 22 5.75736 21.1213 4.87868C20.2426 4 18.8284 4 16 4H8C5.17157 4 3.75736 4 2.87868 4.87868C2 5.75736 2 7.17157 2 10V12C2 14.8284 2 16.2426 2.87868 17.1213C3.75736 18 5.17157 18 8 18H11" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15 11C15 12.6569 13.6569 14 12 14C10.3431 14 9 12.6569 9 11C9 9.34315 10.3431 8 12 8C13.6569 8 15 9.34315 15 11Z" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M14 18C14 18 15 18 16 20C16 20 19.1765 15 22 14" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>

                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Payment Gateway')}}</h6>
                                                    <small class="text-muted d-block">{{__('View and update payment gateway.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                <div>
                                <a href="{{ route('business.reservation-settting.index') }}"
                                    class="text-decoration-none text-dark">
                                    <div class="setting-box">
                                        <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                            <div class="settings-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16 2V6M8 2V6" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M13 4H11C7.22876 4 5.34315 4 4.17157 5.17157C3 6.34315 3 8.22876 3 12V14C3 17.7712 3 19.6569 4.17157 20.8284C5.34315 22 7.22876 22 11 22H13C16.7712 22 18.6569 22 19.8284 20.8284C21 19.6569 21 17.7712 21 14V12C21 8.22876 21 6.34315 19.8284 5.17157C18.6569 4 16.7712 4 13 4Z" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M3 10H21" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M11 14H16M8 14H8.00898M13 18H8M16 18H15.991" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h6 class="">{{__('Reservation')}}</h6>
                                                <small class="text-muted d-block">{{__('Manage your reservation settings.')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>

                                @if(moduleCheck('RestaurantOnlineStore'))
                                <div>
                                <a href="{{ route('business.order-type-setting.index') }}"
                                    class="text-decoration-none text-dark">
                                    <div class="setting-box">
                                        <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                            <div class="settings-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 16L16.7201 15.2733C19.4486 15.046 20.0611 14.45 20.3635 11.7289L21 6" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M6 6H22" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M6 22C7.10457 22 8 21.1046 8 20C8 18.8954 7.10457 18 6 18C4.89543 18 4 18.8954 4 20C4 21.1046 4.89543 22 6 22Z" stroke="#FC8019" stroke-width="1.5"/>
                                                <path d="M17 22C18.1046 22 19 21.1046 19 20C19 18.8954 18.1046 18 17 18C15.8954 18 15 18.8954 15 20C15 21.1046 15.8954 22 17 22Z" stroke="#FC8019" stroke-width="1.5"/>
                                                <path d="M8 20H15" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                <path d="M2 2H2.966C3.91068 2 4.73414 2.62459 4.96326 3.51493L7.93852 15.0765C8.08887 15.6608 7.9602 16.2797 7.58824 16.7616L6.63213 18" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h6 class="">{{__('Customer Order Types')}}</h6>
                                                <small class="text-muted d-block">{{__('View and update Customer Order Types.')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                @endif

                                @if(moduleCheck('RestaurantOnlineStore'))
                                <div>
                                <a href="{{ route('business.delivery-charge.index') }}"
                                    class="text-decoration-none text-dark">
                                    <div class="setting-box">
                                        <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                            <div class="settings-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M13.5 15H6C4.11438 15 3.17157 15 2.58579 14.4142C2 13.8284 2 12.8856 2 11V7C2 5.11438 2 4.17157 2.58579 3.58579C3.17157 3 4.11438 3 6 3H18C19.8856 3 20.8284 3 21.4142 3.58579C22 4.17157 22 5.11438 22 7V12C22 12.9319 22 13.3978 21.8478 13.7654C21.6448 14.2554 21.2554 14.6448 20.7654 14.8478C20.3978 15 19.9319 15 19 15"
                                                        stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M14 9C14 10.1045 13.1046 11 12 11C10.8954 11 10 10.1045 10 9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9Z"
                                                        stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M13 17C13 15.3431 14.3431 14 16 14V12C16 10.3431 17.3431 9 19 9V14.5C19 16.8346 19 18.0019 18.5277 18.8856C18.1548 19.5833 17.5833 20.1548 16.8856 20.5277C16.0019 21 14.8346 21 12.5 21H12C10.1362 21 9.20435 21 8.46927 20.6955C7.48915 20.2895 6.71046 19.5108 6.30448 18.5307C6 17.7956 6 16.8638 6 15"
                                                        stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h6 class="">{{__('Delivery Charge')}}</h6>
                                                <small class="text-muted d-block">{{__('View and update Delivery Charge.')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                @endif

                                @if(moduleCheck('RestaurantOnlineStore'))
                                <div>
                                <a href="{{ route('business.discount.index') }}"
                                    class="text-decoration-none text-dark">
                                    <div class="setting-box">
                                        <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                            <div class="settings-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M13.5 15H6C4.11438 15 3.17157 15 2.58579 14.4142C2 13.8284 2 12.8856 2 11V7C2 5.11438 2 4.17157 2.58579 3.58579C3.17157 3 4.11438 3 6 3H18C19.8856 3 20.8284 3 21.4142 3.58579C22 4.17157 22 5.11438 22 7V12C22 12.9319 22 13.3978 21.8478 13.7654C21.6448 14.2554 21.2554 14.6448 20.7654 14.8478C20.3978 15 19.9319 15 19 15"
                                                        stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M14 9C14 10.1045 13.1046 11 12 11C10.8954 11 10 10.1045 10 9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9Z"
                                                        stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M13 17C13 15.3431 14.3431 14 16 14V12C16 10.3431 17.3431 9 19 9V14.5C19 16.8346 19 18.0019 18.5277 18.8856C18.1548 19.5833 17.5833 20.1548 16.8856 20.5277C16.0019 21 14.8346 21 12.5 21H12C10.1362 21 9.20435 21 8.46927 20.6955C7.48915 20.2895 6.71046 19.5108 6.30448 18.5307C6 17.7956 6 16.8638 6 15"
                                                        stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h6 class="">{{__('Discount')}}</h6>
                                                <small class="text-muted d-block">{{__('View and update discount.')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                                @endif
                                
                                @usercan('cancelReason.view')
                                <div>
                                    <a href="{{ route('business.cancel-reasons.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6 2.5H14L18.5 7V21.5H6C4.61929 21.5 3.5 20.3807 3.5 19V5C3.5 3.61929 4.61929 2.5 6 2.5Z" stroke="#FC8019" stroke-width="1.5" stroke-linejoin="round"/>
                                                        <path d="M14 2.5V7H18.5" stroke="#FC8019" stroke-width="1.5" stroke-linejoin="round"/>
                                                        <path d="M9.5 11.5L14.5 16.5M14.5 11.5L9.5 16.5" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                        <path d="M7.5 18H13.5" stroke="#FC8019" stroke-width="1.2" stroke-linecap="round"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Cancel Reason')}}</h6>
                                                    <small class="text-muted d-block">{{__('Store and update cancel reason.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan
                            </div>
                        </div>

                        <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <div class="settings-box-container">
                                @usercan('generalSetting.view')
                                <div>
                                    <a href="{{ route('business.settings.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class=" setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.5 12C15.5 13.933 13.933 15.5 12 15.5C10.067 15.5 8.5 13.933 8.5 12C8.5 10.067 10.067 8.5 12 8.5C13.933 8.5 15.5 10.067 15.5 12Z"
                                                            stroke="#FC8019" stroke-width="1.5" />
                                                        <path
                                                            d="M21.011 14.0949C21.5329 13.9542 21.7939 13.8838 21.8969 13.7492C22 13.6147 22 13.3982 22 12.9653V11.0316C22 10.5987 22 10.3822 21.8969 10.2477C21.7938 10.1131 21.5329 10.0427 21.011 9.90194C19.0606 9.37595 17.8399 7.33687 18.3433 5.39923C18.4817 4.86635 18.5509 4.59992 18.4848 4.44365C18.4187 4.28738 18.2291 4.1797 17.8497 3.96432L16.125 2.98509C15.7528 2.77375 15.5667 2.66808 15.3997 2.69058C15.2326 2.71308 15.0442 2.90109 14.6672 3.27709C13.208 4.73284 10.7936 4.73278 9.33434 3.277C8.95743 2.90099 8.76898 2.71299 8.60193 2.69048C8.43489 2.66798 8.24877 2.77365 7.87653 2.98499L6.15184 3.96423C5.77253 4.17959 5.58287 4.28727 5.51678 4.44351C5.45068 4.59976 5.51987 4.86623 5.65825 5.39916C6.16137 7.33686 4.93972 9.37599 2.98902 9.90196C2.46712 10.0427 2.20617 10.1131 2.10308 10.2476C2 10.3822 2 10.5987 2 11.0316V12.9653C2 13.3982 2 13.6147 2.10308 13.7492C2.20615 13.8838 2.46711 13.9542 2.98902 14.0949C4.9394 14.6209 6.16008 16.66 5.65672 18.5976C5.51829 19.1305 5.44907 19.3969 5.51516 19.5532C5.58126 19.7095 5.77092 19.8172 6.15025 20.0325L7.87495 21.0118C8.24721 21.2231 8.43334 21.3288 8.6004 21.3063C8.76746 21.2838 8.95588 21.0957 9.33271 20.7197C10.7927 19.2628 13.2088 19.2627 14.6689 20.7196C15.0457 21.0957 15.2341 21.2837 15.4012 21.3062C15.5682 21.3287 15.7544 21.223 16.1266 21.0117L17.8513 20.0324C18.2307 19.8171 18.4204 19.7094 18.4864 19.5531C18.5525 19.3968 18.4833 19.1304 18.3448 18.5975C17.8412 16.66 19.0609 14.621 21.011 14.0949Z"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('General Settings')}}</h6>
                                                    <small class="text-muted d-block">{{__('Configure the fundamental information of the site.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan
                            </div>
                        </div>
                        <div class="tab-pane fade" id="role" role="tabpanel" aria-labelledby="invoice-tab">
                            <div class="">
                                @usercan('rolePermission.view')
                                <div class="settings-box-container">
                                    <a href="{{ route('business.roles.index') }}" class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M11.5 14.0116C9.45338 13.9164 7.38334 14.4064 5.57757 15.4816C4.1628 16.324 0.453366 18.0441 2.71266 20.1966C3.81631 21.248 5.04549 22 6.59087 22H12"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M15.5 6.5C15.5 8.98528 13.4853 11 11 11C8.51472 11 6.5 8.98528 6.5 6.5C6.5 4.01472 8.51472 2 11 2C13.4853 2 15.5 4.01472 15.5 6.5Z"
                                                            stroke="#FC8019" stroke-width="1.5" />
                                                        <path
                                                            d="M18 20.7143V22M18 20.7143C16.8432 20.7143 15.8241 20.1461 15.2263 19.2833M18 20.7143C19.1568 20.7143 20.1759 20.1461 20.7737 19.2833M15.2263 19.2833L14.0004 20.0714M15.2263 19.2833C14.8728 18.773 14.6667 18.1597 14.6667 17.5C14.6667 16.8403 14.8727 16.2271 15.2262 15.7169M20.7737 19.2833L21.9996 20.0714M20.7737 19.2833C21.1272 18.773 21.3333 18.1597 21.3333 17.5C21.3333 16.8403 21.1273 16.2271 20.7738 15.7169M18 14.2857C19.1569 14.2857 20.1761 14.854 20.7738 15.7169M18 14.2857C16.8431 14.2857 15.8239 14.854 15.2262 15.7169M18 14.2857V13M20.7738 15.7169L22 14.9286M15.2262 15.7169L14 14.9286"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('User Role')}}</h6>
                                                    <small class="text-muted d-block">{{__('Add new users, Provide role and Permission')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan
                            </div>
                        </div>

                        <div class="tab-pane fade" id="Currencies" role="tabpanel" aria-labelledby="invoice-tab">
                            <div>
                                @usercan('currency.view')
                                <div class="settings-box-container">
                                     <a href="{{ route('business.currencies.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M3.16602 12C3.16602 7.77027 3.16602 5.6554 4.36399 4.25276C4.5341 4.05358 4.7196 3.86808 4.91878 3.69797C6.32142 2.5 8.43629 2.5 12.666 2.5C16.8957 2.5 19.0106 2.5 20.4132 3.69797C20.6124 3.86808 20.7979 4.05358 20.968 4.25276C22.166 5.6554 22.166 7.77027 22.166 12C22.166 16.2297 22.166 18.3446 20.968 19.7472C20.7979 19.9464 20.6124 20.1319 20.4132 20.302C19.0106 21.5 16.8957 21.5 12.666 21.5C8.43629 21.5 6.32142 21.5 4.91878 20.302C4.7196 20.1319 4.5341 19.9464 4.36399 19.7472C3.16602 18.3446 3.16602 16.2297 3.16602 12Z"
                                                            stroke="#FC8019" stroke-width="1.5" />
                                                        <path
                                                            d="M15.3762 10.063C15.2771 9.30039 14.4014 8.06817 12.8268 8.06814C10.9972 8.06811 10.2274 9.08141 10.0712 9.58806C9.82746 10.2657 9.8762 11.659 12.0207 11.8109C14.7014 12.0009 15.7753 12.3174 15.6387 13.958C15.502 15.5985 14.0077 15.953 12.8268 15.9149C11.6458 15.877 9.71365 15.3344 9.63867 13.8752M12.6394 7V8.07177M12.6394 15.9051V16.9999"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Currencies')}}</h6>
                                                    <small class="text-muted d-block">{{__('View and update currency settings')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan
                            </div>
                        </div>

                        <div class="tab-pane fade" id="Payment" role="tabpanel" aria-labelledby="invoice-tab">
                            <div>
                                @usercan('paymentMethod.view')
                                <div class="settings-box-container">
                                         <a href="{{ route('business.payment-types.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M13.5 15H6C4.11438 15 3.17157 15 2.58579 14.4142C2 13.8284 2 12.8856 2 11V7C2 5.11438 2 4.17157 2.58579 3.58579C3.17157 3 4.11438 3 6 3H18C19.8856 3 20.8284 3 21.4142 3.58579C22 4.17157 22 5.11438 22 7V12C22 12.9319 22 13.3978 21.8478 13.7654C21.6448 14.2554 21.2554 14.6448 20.7654 14.8478C20.3978 15 19.9319 15 19 15"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M14 9C14 10.1045 13.1046 11 12 11C10.8954 11 10 10.1045 10 9C10 7.89543 10.8954 7 12 7C13.1046 7 14 7.89543 14 9Z"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M13 17C13 15.3431 14.3431 14 16 14V12C16 10.3431 17.3431 9 19 9V14.5C19 16.8346 19 18.0019 18.5277 18.8856C18.1548 19.5833 17.5833 20.1548 16.8856 20.5277C16.0019 21 14.8346 21 12.5 21H12C10.1362 21 9.20435 21 8.46927 20.6955C7.48915 20.2895 6.71046 19.5108 6.30448 18.5307C6 17.7956 6 16.8638 6 15"
                                                            stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Payment Type')}}</h6>
                                                    <small class="text-muted d-block">{{__('Manage payment method for purchase.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan
                            </div>
                        </div>
                        <div class="tab-pane fade" id="payment_gateway" role="tabpanel" aria-labelledby="invoice-tab">
                            <div>
                                <div class="settings-box-container">
                                         <a href="{{ route('business.payment-gateway.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.01733 14C4.2169 14 6.00001 15.7831 6.00001 17.9827" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.00001 4.01562C6.00001 6.21519 4.2169 7.9983 2.01733 7.9983" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M18 4.01562C18 6.19595 19.769 7.96706 21.9423 7.9979" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M22 11V10C22 7.17157 22 5.75736 21.1213 4.87868C20.2426 4 18.8284 4 16 4H8C5.17157 4 3.75736 4 2.87868 4.87868C2 5.75736 2 7.17157 2 10V12C2 14.8284 2 16.2426 2.87868 17.1213C3.75736 18 5.17157 18 8 18H11" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M15 11C15 12.6569 13.6569 14 12 14C10.3431 14 9 12.6569 9 11C9 9.34315 10.3431 8 12 8C13.6569 8 15 9.34315 15 11Z" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M14 18C14 18 15 18 16 20C16 20 19.1765 15 22 14" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Payment Gateway')}}</h6>
                                                    <small class="text-muted d-block">{{__('View and update payment gateway.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="reservation" role="tabpanel" aria-labelledby="invoice-tab">
                            <div>
                                <div class="settings-box-container">
                                         <a href="{{ route('business.reservation-settting.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M16 2V6M8 2V6" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M13 4H11C7.22876 4 5.34315 4 4.17157 5.17157C3 6.34315 3 8.22876 3 12V14C3 17.7712 3 19.6569 4.17157 20.8284C5.34315 22 7.22876 22 11 22H13C16.7712 22 18.6569 22 19.8284 20.8284C21 19.6569 21 17.7712 21 14V12C21 8.22876 21 6.34315 19.8284 5.17157C18.6569 4 16.7712 4 13 4Z" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M3 10H21" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M11 14H16M8 14H8.00898M13 18H8M16 18H15.991" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Reservation')}}</h6>
                                                    <small class="text-muted d-block">{{__('Manage your reservation settings.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="customer_order_type" role="tabpanel" aria-labelledby="invoice-tab">
                            <div>
                                <div class="settings-box-container">
                                         <a href="{{ route('business.order-type-setting.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 16L16.7201 15.2733C19.4486 15.046 20.0611 14.45 20.3635 11.7289L21 6" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M6 6H22" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M6 22C7.10457 22 8 21.1046 8 20C8 18.8954 7.10457 18 6 18C4.89543 18 4 18.8954 4 20C4 21.1046 4.89543 22 6 22Z" stroke="#FC8019" stroke-width="1.5"/>
                                                    <path d="M17 22C18.1046 22 19 21.1046 19 20C19 18.8954 18.1046 18 17 18C15.8954 18 15 18.8954 15 20C15 21.1046 15.8954 22 17 22Z" stroke="#FC8019" stroke-width="1.5"/>
                                                    <path d="M8 20H15" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                    <path d="M2 2H2.966C3.91068 2 4.73414 2.62459 4.96326 3.51493L7.93852 15.0765C8.08887 15.6608 7.9602 16.2797 7.58824 16.7616L6.63213 18" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Customer Order Types')}}</h6>
                                                    <small class="text-muted d-block">{{__('View and update Customer Order Types.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="delivery_charge" role="tabpanel" aria-labelledby="invoice-tab">
                            <div>
                                <div class="settings-box-container">
                                         <a href="{{ route('business.delivery-charge.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" stroke="#FC8019" stroke-width="1.5" stroke-linejoin="round"/>
                                                    <path d="M12.0078 10.5092C11.1794 10.5092 10.5078 11.1808 10.5078 12.0092C10.5078 12.8376 11.1794 13.5092 12.0078 13.5092C12.8362 13.5092 13.5078 12.8376 13.5078 12.0092C13.5078 11.1808 12.8362 10.5092 12.0078 10.5092ZM12.0078 10.5092V7M15.0147 15.0208L13.0661 13.0722" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Delivery Charge')}}</h6>
                                                    <small class="text-muted d-block">{{__('View and update Delivery Charge.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="discount" role="tabpanel" aria-labelledby="invoice-tab">
                            <div>
                                <div class="settings-box-container">
                                         <a href="{{ route('business.discount.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.5 12C2.5 7.52166 2.5 5.28249 3.89124 3.89124C5.28249 2.5 7.52166 2.5 12 2.5C16.4783 2.5 18.7175 2.5 20.1088 3.89124C21.5 5.28249 21.5 7.52166 21.5 12C21.5 16.4783 21.5 18.7175 20.1088 20.1088C18.7175 21.5 16.4783 21.5 12 21.5C7.52166 21.5 5.28249 21.5 3.89124 20.1088C2.5 18.7175 2.5 16.4783 2.5 12Z" stroke="#FC8019" stroke-width="1.5" stroke-linejoin="round"/>
                                                    <path d="M12.0078 10.5092C11.1794 10.5092 10.5078 11.1808 10.5078 12.0092C10.5078 12.8376 11.1794 13.5092 12.0078 13.5092C12.8362 13.5092 13.5078 12.8376 13.5078 12.0092C13.5078 11.1808 12.8362 10.5092 12.0078 10.5092ZM12.0078 10.5092V7M15.0147 15.0208L13.0661 13.0722" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Discount')}}</h6>
                                                    <small class="text-muted d-block">{{__('View and update discount.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="cancel_reason" role="tabpanel" aria-labelledby="invoice-tab">
                            <div>
                                @usercan('cancelReason.view')
                                <div class="settings-box-container">
                                         <a href="{{ route('business.cancel-reasons.index') }}"
                                        class="text-decoration-none text-dark">
                                        <div class="setting-box">
                                            <div class="d-flex align-items-center jusitfy-content-center gap-3">
                                                <div class="settings-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6 2.5H14L18.5 7V21.5H6C4.61929 21.5 3.5 20.3807 3.5 19V5C3.5 3.61929 4.61929 2.5 6 2.5Z" stroke="#FC8019" stroke-width="1.5" stroke-linejoin="round"/>
                                                        <path d="M14 2.5V7H18.5" stroke="#FC8019" stroke-width="1.5" stroke-linejoin="round"/>
                                                        <path d="M9.5 11.5L14.5 16.5M14.5 11.5L9.5 16.5" stroke="#FC8019" stroke-width="1.5" stroke-linecap="round"/>
                                                        <path d="M7.5 18H13.5" stroke="#FC8019" stroke-width="1.2" stroke-linecap="round"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h6 class="">{{__('Cancel Reason')}}</h6>
                                                    <small class="text-muted d-block">{{__('Store and update cancel reason.')}}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endusercan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
