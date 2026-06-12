@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Payment Gateway') }}
@endsection

@section('main_content')

<div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title d-flex align-items-center justify-content-between flex-wrap">
                  <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                      <span>/ {{__('Payment Gateway')}}</span>
                  </h2>
              </div>
            <div class="card shadow-sm  payment-gateway">
                <div class="card-bodys">
                    <ul class="nav nav-tabs " id="settingsTab" role="tablist">
                        @foreach ($gateways as $gateway)
                        <li class="nav-item settings-item" role="presentation">
                            <button class="nav-link settings-link {{$loop->first ? 'active' : ''}}"
                                id="{{ str_replace(' ', '-', $gateway->name) }}-tab"
                                data-bs-toggle="tab"
                                data-bs-target="#{{ str_replace(' ', '-', $gateway->name) }}" type="button" role="tab">
                                <img src="{{ asset($gateway->image ?? '') }}">
                                {{ $gateway->name }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content mt-3" id="settingsTabContent">
                        @foreach ($gateways as $gateway)
                        @php
                            $business_gateway = $gateway->businessGateway;
                        @endphp
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ str_replace(' ', '-', $gateway->name) }}" role="tabpanel" aria-labelledby="{{ str_replace(' ', '-', $gateway->name) }}-tab">
                            <form action="{{ route('business.payment-gateway.store') }}" method="post" class="ajaxform">
                            @csrf
                            <input type="hidden" name="gateway_id" value="{{ $gateway->id }}">
                            <div class="order-form-section">
                                <div class="add-suplier-modal-wrapper p-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label for="basic-url" class="">{{__('Gateway Image')}}</label>
                                            <input class="form-control" type="file" name="image">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="basic-url" class="">{{__('Gateway Name')}}</label>
                                            <input class="form-control" type="text" name="name" value="{{ $business_gateway->name ?? '' }}" placeholder="{{__('Enter gateway name')}}" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="basic-url" class="">{{__('Gateway Charge')}}</label>
                                            <input class="form-control" type="number" name="charge" value="{{ $business_gateway->charge ?? '' }}" placeholder="{{__('Enter gateway charge')}}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="basic-url" class="">{{__('Gateway Currency')}}</label>
                                            <select  name="currency_id" class="form-control table-select" aria-label="Default select example" required>
                                                @foreach ($currencies as $currency)
                                                    <option @selected(($business_gateway->currency_id ?? '') == $currency->id)
                                                        value="{{ $currency->id }}">
                                                        {{ $currency->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @if (!($gateway->is_manual ?? ''))
                                        @foreach (($gateway->data ?? []) as $key => $data)
                                        <div class="col-lg-6">
                                            <label for="basic-url" class="">{{ strtoupper(str_replace('_', ' ', $key)) }}</label>
                                            <input class="form-control" type="text" name="data[{{ $key }}]" value="{{ $business_gateway->data[$key] ?? '' }}" placeholder="{{__('Enter here')}}" required>
                                        </div>
                                        @endforeach

                                        <div class="col-lg-6">
                                            <label for="basic-url" class="">{{__('Gateway Mode')}}</label>
                                            <select  name="mode" class="form-control table-select" aria-label="Default select example" required>
                                            <option @selected(($business_gateway->mode ?? '') == 1) value="1">{{ __('Sandbox') }}</option>
                                            <option @selected(($business_gateway->mode ?? '') == 0) value="0">{{ __('Live') }}</option>
                                            </select>
                                        </div>
                                        @endif

                                        <div class="col-lg-6">
                                            <label for="basic-url" class="">{{__('Status')}}</label>
                                            <select  name="status" class="form-control table-select" aria-label="Default select example" required>
                                                <option @selected(($business_gateway->status ?? '') == 1) value="1">{{ __('Active') }}</option>
                                                <option @selected(($business_gateway->status ?? '') == 0) value="0">{{ __('Deactive') }}</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="basic-url" class="">{{__('Is Manual')}}</label>
                                            <select  name="is_manual" class="form-control table-select" aria-label="Default select example" required>
                                                <option @selected(($business_gateway->is_manual ?? '') == 1) value="1">{{ __('Yes') }}</option>
                                                <option @selected(($business_gateway->is_manual ?? '') == 0) value="0">{{ __('No') }}</option>
                                            </select>
                                            <span></span>
                                        </div>

                                        <div class="col-lg-6 {{ ($business_gateway->is_manual ?? '') ? '' : 'd-none' }}">
                                            <label for="basic-url" class="">{{__('Accept Image')}}</label>
                                            <select  name="accept_img" class="form-control table-select" aria-label="Default select example" required>
                                                <option @selected(($business_gateway->accept_img ?? '') == 1) value="1">{{ __('Yes') }}</option>
                                                <option @selected(($business_gateway->accept_img ?? '') == 0) value="0">{{ __('No') }}</option>
                                            </select>
                                            <span></span>
                                        </div>

                                        <div class="col-12 mb-2 {{ ($business_gateway->is_manual ?? $gateway->is_manual) ? '' : 'd-none' }}">
                                        <div class="manual-rows">
                                            @foreach (($business_gateway->manual_data['label'] ?? []) as $key => $row)
                                                <div class="row row-items">
                                                    <div class="col-sm-5">
                                                        <label for="">{{ __('Label') }}</label>
                                                        <input type="text" name="manual_data[label][]" value="{{ $row }}"class="form-control" required placeholder="{{ __('Enter label name') }}">
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <label for="">{{ __('Select Required/Optionl') }}</label>
                                                        <div class="gpt-up-down-arrow position-relative">
                                                            <select class="form-control choices-select" required name="manual_data[is_required][]">
                                                                <option @selected($business_gateway->manual_data['is_required'][$key] == 1) value="1">{{ __('Required') }}
                                                                </option>
                                                                <option @selected($business_gateway->manual_data['is_required'][$key] == 0) value="0">{{ __('Optional') }}
                                                                </option>
                                                            </select>
                                                        <span></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 align-self-center mt-3">
                                                        <button type="button" class="btn text-danger trash remove-btn-features"><i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mt-2">
                                                <a href="javascript:void(0)" class="fw-bold primary add-new-item d-flex align-items-center gap-2"><i class="fas fa-plus-circle"></i>{{ __('Add new row') }}</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="basic-url">{{__('INSTRUCTIONS')}}</label>
                                        <textarea name="instructions" class="form-control" placeholder="{{ __('Enter payment instructions here') }}">{{ $business_gateway->instructions ?? '' }}</textarea>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pyment-getway-btn">
                                <a class="theme-btn border-btn" href="#">{{__('Reset')}}</a>
                                <button type="submit" class="theme-btn submit-btn">{{__('Save')}}</button>
                            </div>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


