@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Dashboard') }}
@endsection

@section('main_content')
    <div class="container-fluid m-h-100">
        @if (env('DEMO_MODE') && auth()->user()->business_id == 1)
        <div class="alert alert-dismissible fade show text-center mb-3 text-white" role="alert" style="background: linear-gradient(270deg, #ff7db8 0%, #ee2a7b 100%)">
            <b class="text-light">Note:</b> This is a demo account — data resets every hour for this account only. Some of module are disabled in this account, to get full access please please create your own account.
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="admin-dashboard">
            <div class="gpt-dashboard-card admin-gpt-dashboard grid-4">
                <div class="admin-dashboard-couter-box">
                    <div class="admin-content-side">
                        <p class="stat-title">{{ __('Total Orders') }}</p>
                        {{-- generate form js  --}}
                        <p class="admin-content-num" id="total_order">0</p>
                        <p id="order_update"></p>
                    </div>
                    <div class="admin-icons-1">
                       <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.33331 2.66665C1.33331 1.93027 1.93027 1.33331 2.66665 1.33331H5.99998C6.64594 1.33331 7.19899 1.79635 7.31255 2.43226L10.6459 21.0989C10.6963 21.3816 10.6543 21.6728 10.5259 21.9296L9.19255 24.5962C8.86323 25.2549 8.06233 25.5218 7.4037 25.1925C6.74506 24.8632 6.47809 24.0624 6.80741 23.4037L7.94302 21.1325L4.88365 3.99998H2.66665C1.93027 3.99998 1.33331 3.40302 1.33331 2.66665Z" fill="#4946FF"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8 25.3334C7.26363 25.3334 6.66667 25.9303 6.66667 26.6667C6.66667 27.4031 7.26363 28 8 28C8.73637 28 9.33333 27.4031 9.33333 26.6667C9.33333 25.9303 8.73637 25.3334 8 25.3334ZM4 26.6667C4 24.4575 5.79087 22.6667 8 22.6667C10.2091 22.6667 12 24.4575 12 26.6667C12 28.8759 10.2091 30.6667 8 30.6667C5.79087 30.6667 4 28.8759 4 26.6667Z" fill="#4946FF"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M21.3333 25.3334C20.5969 25.3334 20 25.9303 20 26.6667C20 27.4031 20.5969 28 21.3333 28C22.0697 28 22.6666 27.4031 22.6666 26.6667C22.6666 25.9303 22.0697 25.3334 21.3333 25.3334ZM17.3333 26.6667C17.3333 24.4575 19.1241 22.6667 21.3333 22.6667C23.5425 22.6667 25.3333 24.4575 25.3333 26.6667C25.3333 28.8759 23.5425 30.6667 21.3333 30.6667C19.1241 30.6667 17.3333 28.8759 17.3333 26.6667Z" fill="#4946FF"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.33331 26.6666C9.33331 25.9302 9.93027 25.3333 10.6666 25.3333H18.6666C19.403 25.3333 20 25.9302 20 26.6666C20 27.403 19.403 28 18.6666 28H10.6666C9.93027 28 9.33331 27.403 9.33331 26.6666Z" fill="#4946FF"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M22.187 2.97576C22.7527 3.44718 22.8291 4.28793 22.3576 4.85364L15.691 12.8536C15.4512 13.1414 15.1014 13.315 14.7271 13.332C14.3528 13.349 13.9888 13.2078 13.7239 12.9429L11.0572 10.2762C10.5365 9.7555 10.5365 8.91128 11.0572 8.39058C11.5779 7.86988 12.4221 7.86988 12.9428 8.39058L14.5771 10.0248L20.3091 3.14648C20.7804 2.58077 21.6212 2.50434 22.187 2.97576Z" fill="#4946FF"/>
                        <path d="M16.2304 5.33331L14.4606 7.45702L14.1684 7.16489C12.9709 5.96727 11.0292 5.96727 9.83156 7.16489C8.63396 8.36249 8.63396 10.3042 9.83156 11.5018L12.4982 14.1685C13.1075 14.7777 13.945 15.1025 14.8056 15.0636C15.6664 15.0245 16.471 14.6252 17.0226 13.9632L23.6892 5.96325C23.8535 5.76607 23.989 5.55438 24.0958 5.33331H28C28.7364 5.33331 29.3334 5.93027 29.3334 6.66665C29.3334 7.40302 28.7364 7.99998 28 7.99998H27.4656L25.654 19.2917C25.5847 19.7241 25.2422 20.061 24.8087 20.1232L9.47538 22.3232C8.94309 22.3996 8.44569 22.0413 8.34949 21.5122L5.68282 6.84553C5.62978 6.55379 5.70894 6.25355 5.89894 6.0259C6.08894 5.79825 6.37017 5.66665 6.66669 5.66665V5.33331H16.2304Z" fill="#4946FF"/>
                        </svg>
                    </div>
                </div>

                <div class="admin-dashboard-couter-box">
                    <div class="admin-content-side">
                        <p class="stat-title">{{ __('Total Item') }}</p>
                        {{-- generate form js  --}}
                        <p class="admin-content-num" id="total_items">0</p>
                        <p id="item_update"></p>

                    </div>
                    <div class="admin-icons-2">
                       <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2 10.973L14.0405 17.4429C14.1701 17.5126 14.3061 17.5629 14.4444 17.5948V30.1428L2.85873 23.7615C2.32646 23.4683 2 22.9354 2 22.3598V10.973ZM30 10.801V22.3598C30 22.9354 29.6735 23.4683 29.1412 23.7615L17.5555 30.1428V17.4849C17.5837 17.4718 17.6117 17.4578 17.6394 17.4429L30 10.801Z" fill="#007AFF"/>
                        <path opacity="0.499209" fill-rule="evenodd" clip-rule="evenodd" d="M2.37817 7.83274C2.52524 7.65989 2.71087 7.51381 2.92729 7.40652L15.1773 1.33401C15.6915 1.07909 16.3084 1.07909 16.8226 1.33401L29.0726 7.40652C29.2395 7.48922 29.388 7.59498 29.5147 7.71828L16.0839 14.9353C15.9956 14.9827 15.9141 15.037 15.8399 15.097C15.7657 15.037 15.6843 14.9827 15.596 14.9353L2.37817 7.83274Z" fill="#007AFF"/>
                        </svg>
                    </div>
                </div>

                <div class="admin-dashboard-couter-box">
                    <div class="admin-content-side">
                        <p class="stat-title">{{ __('Total Sales') }}</p>
                        {{-- generate form js  --}}
                        <p class="admin-content-num" id="total_sales">0</p>
                        <p id="sale_update"></p>
                    </div>
                    <div class="admin-icons-3">
                      <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.99996 4C4.73633 4 5.33329 4.59696 5.33329 5.33333V24C5.33329 24.7364 5.93025 25.3333 6.66663 25.3333H28C28.7364 25.3333 29.3333 25.9303 29.3333 26.6667C29.3333 27.4031 28.7364 28 28 28H6.66663C4.45749 28 2.66663 26.2092 2.66663 24V5.33333C2.66663 4.59696 3.26359 4 3.99996 4Z" fill="#07AA83"/>
                        <path d="M27.0493 8.40946C27.4231 8.56425 27.6667 8.92889 27.6667 9.33334V22.6666C27.6667 23.2189 27.2189 23.6666 26.6667 23.6666H8C7.44772 23.6666 7 23.2189 7 22.6666V18.6666C7 18.4014 7.10536 18.147 7.29289 17.9596L13.9596 11.2929C14.3501 10.9024 14.9832 10.9024 15.3737 11.2929L19.3333 15.2525L25.9596 8.62623C26.2456 8.34023 26.6757 8.25469 27.0493 8.40946Z" fill="#07AA83"/>
                        </svg>
                    </div>
                </div>

                <div class="admin-dashboard-couter-box">
                    <div class="admin-content-side">
                        <p class="stat-title">{{ __('Total Expense') }}</p>
                        {{-- generate form js  --}}
                        <p class="admin-content-num" id="total_expense">0</p>
                        <p id="expense_update"></p>
                    </div>
                    <div class="admin-icons-4">
                       <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M26.5927 8.07363C25.9855 7.46634 25.2298 7.21878 24.3927 7.10624C23.5996 6.99962 22.6007 6.99966 21.4027 6.9997H5.33338C4.7811 6.9997 4.33338 7.4474 4.33338 7.9997V24.0691C4.33334 25.2671 4.33331 26.266 4.43992 27.059C4.55247 27.896 4.80003 28.6517 5.40732 29.2591C6.01462 29.8664 6.77035 30.1139 7.6074 30.2264C8.40046 30.3331 9.39942 30.3331 10.5974 30.3331H21.4027C22.6007 30.3331 23.5996 30.3331 24.3927 30.2264C25.2298 30.1139 25.9855 29.8664 26.5927 29.2591C27.2 28.6517 27.4476 27.896 27.5602 27.059C27.6668 26.266 27.6667 25.267 27.6667 24.0691V13.2637C27.6667 12.0657 27.6668 11.0668 27.5602 10.2737C27.4476 9.43667 27.2 8.68092 26.5927 8.07363ZM10.6667 22.9998C10.1144 22.9998 9.66671 23.4473 9.66671 23.9998C9.66671 24.552 10.1144 24.9998 10.6667 24.9998H21.3334C21.8856 24.9998 22.3334 24.552 22.3334 23.9998C22.3334 23.4473 21.8856 22.9998 21.3334 22.9998H10.6667ZM17 13.333C17 12.7807 16.5523 12.333 16 12.333C15.4478 12.333 15 12.7807 15 13.333V13.7664C13.0409 14.164 11.4977 15.7072 11.1001 17.6664H10.6667C10.1144 17.6664 9.66671 18.114 9.66671 18.6664C9.66671 19.2187 10.1144 19.6664 10.6667 19.6664H21.3334C21.8856 19.6664 22.3334 19.2187 22.3334 18.6664C22.3334 18.114 21.8856 17.6664 21.3334 17.6664H20.9C20.5023 15.7072 18.9591 14.164 17 13.7664V13.333Z" fill="#F25900"/>
                        <path d="M9.10986 5.33239H22.2785C22.2728 5.25786 22.2664 5.18518 22.2592 5.11435C22.1837 4.36033 22.02 3.65775 21.5756 3.07633C21.084 2.43289 20.3939 1.96982 19.612 1.76033C18.9043 1.57067 18.1932 1.68962 17.4708 1.90989C16.7631 2.12569 15.8999 2.48737 14.8433 2.93006L9.10986 5.33239Z" fill="#F25900"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gpt-dashboard-chart">
            <div class="col-xxl-8 col-xl-8 col-lg-8">
                <div class="cards dashboard-card new-card border-0 p-0">
                    <div class="card-header subscription-header">
                        <h4>{{ __('Money In & Money Out') }}</h4>
                        <div class="gpt-up-down-arrow position-relative">
                            <select class="form-control dropdown-design moneyFlow-yearly">
                                @for ($i = date('Y'); $i >= 2022; $i--)
                                    <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <span></span>
                        </div>
                    </div>
                    <div class="card-body pt-0 min-h-auto">
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                            <div class="d-flex align-items-center justify-content-center gap-1 mb-2">
                                <div class="money-in"></div>
                                @usercan ('moneyIn.view')
                                <p>{{__('Money In')}}:<strong class="profit-value label-value">$0</strong></p>
                                @endusercan
                            </div>
                              <div class="d-flex align-items-center justify-content-center gap-1 mb-2">
                                <div class="money-out"></div>
                                @usercan ('moneyOut.view')
                                <p>{{__('Money Out')}}:<strong class="profit-value label-value">$0</strong></p>
                                @endusercan
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas class="chart-css" id="moneyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 income-expense-container">
                <div class="cards dashboard-card new-card border-0 p-0">
                    <div class="card-header subscription-header">
                        <h4>{{__('Income & Expense')}}</h4>
                        <div class="gpt-up-down-arrow position-relative">
                            <select class="form-control dropdown-design loss-profit">
                                @for ($i = date('Y'); $i >= 2022; $i--)
                                    <option @selected($i == date('Y')) value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <span></span>
                        </div>
                    </div>
                    <div class="card-body pt-0 min-h-auto">
                     <div class="lossProfit-chart-container">
                        <canvas class="chart-css" id="profitLossChart"></canvas>
                    </div>
                           <div class="d-flex align-items-center justify-content-center gap-3 mt-2">
                            <div class="d-flex align-items-center justify-content-center gap-1 mb-2">
                                <div class="profit profit-label"></div>
                                <p>{{__('Income')}}: <strong class="profit-value label-value">$0</strong></p>
                            </div>
                              <div class="d-flex align-items-center justify-content-center gap-1 mb-2">
                                <div class="loss loss-label"></div>
                                <p>{{__('Expense')}}: <strong class="profit-value label-value">$0</strong></p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-all-items">
            <div class="row">
                <div class="col-lg-6">
                    <div class="">
                        <div class="selling dashboard-items-card mt-4">
                            <div class="item-header">
                                <h4> {{__('Top Selling Item')}} ({{__('Today')}})</h4>
                                <a href="{{ route('business.sales.index') }}"> {{__('View All')}} </a>
                            </div>
                            <div class="all-items">
                                @foreach ($top_sales as $top_sale)
                                    <div class="item">
                                        <div class="left">
                                            <div class="image">
                                                <img src="{{ asset($top_sale->product->images[0] ?? 'assets/img/icon/no-image.svg') }}" alt="product img">
                                            </div>
                                            <div class="descrip">
                                                <h4 class="item-name"> {{ $top_sale->product->productName ?? '' }} </h4>
                                                <p> {{ $top_sale->product->category->categoryName ?? '' }} </p>
                                            </div>
                                        </div>
                                        <div class="right">
                                            @if ($top_sale->product?->price_type === 'single')
                                                <p>{{ currency_format($top_sale->product?->sales_price, currency: business_currency()) }}</p>
                                            @else
                                                <p>{{ currency_format($top_sale->product?->variations?->first()->price, currency: business_currency()) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="selling dashboard-items-card mt-4">
                        <div class="item-header">
                            <h4>{{__('Today Orders')}}</h4>
                            <a href="{{ route('business.sales.index') }}"> {{__('View All')}} </a>
                        </div>

                        <div class="all-items">
                            <div class="table-responsive">
                                <table class="table border-0 today-order-table">
                                    <thead class="">
                                        <tr class="table-header-container">
                                            <th class=" text-start order-table-header rounded-left" scope="col"> {{__('Customer Name')}} </th>
                                            <th class=" text-start order-table-header" scope="col"> {{__('Amount')}} </th>
                                            <th class=" text-center order-table-header rounded-right" scope="col"> {{__('Payment')}} </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($todays_order as $order)
                                            <tr>
                                                <td class="text-start">
                                                    <div class="item-table">
                                                        <div class="left">
                                                            <div class="image-text">
                                                                <p># {{ $loop->iteration }} </p>
                                                            </div>
                                                            <div class="d-flex flex-column align-items-start">
                                                                <h4 class="customer-name"> {{ $order->party->name ?? '' }} </h4>
                                                                <p> {{ formatted_date($order->saleDate) }} - {{ formatted_time($order->saleDate) }} </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-start"> {{ currency_format($order->totalAmount, currency: business_currency())}} </td>
                                                <td class="">
                                                    <div class="paid-status">{{ $order->payment_type->name ?? '' }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" value="{{ route('business.dashboard.data') }}" id="get-dashboard-data">
    <input type="hidden" value="{{ route('business.dashboard.money.flow') }}" id="get-money-flow-data">
    <input type="hidden" value="{{ route('business.dashboard.loss.profit') }}" id="get-loss-profit-data">
    @php
    $currency = business_currency();
    @endphp
    {{-- Hidden input fields to store currency details --}}
    <input type="hidden" id="currency_symbol" value="{{ $currency->symbol }}">
    <input type="hidden" id="currency_position" value="{{ $currency->position }}">
    <input type="hidden" id="currency_code" value="{{ $currency->code }}">
@endsection

@push('js')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/businessDashboard.js') }}"></script>
@endpush




