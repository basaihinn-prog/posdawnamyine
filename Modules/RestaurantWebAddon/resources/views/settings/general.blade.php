@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Settings') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
                <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                    <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                        <a href="{{ route('business.manage-settings.index') }}">/ {{ __('Settings') }}</a>
                        <span>/ {{ __('General Settings') }}</span>
                    </h2>
                </div>
            <div class="card shadow-sm">
                <div class="card-bodys">
                    <div class="order-form-section p-16">

                        <form action="{{ route('business.settings.update', $setting->id ?? 0) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf
                            @method('put')

                            <div class="add-suplier-modal-wrapper d-block">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label class="custom-top-label">{{ __('Business Category') }}</label>
                                        <div class="gpt-up-down-arrow position-relative">
                                            <select name="business_category_id" class="form-control form-selected">
                                                <option value="">{{ __('Select a category') }}</option>
                                                @foreach ($business_categories as $category)
                                                    <option value="{{ $category->id }}" @selected($business->business_category_id == $category->id)>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <span></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label>{{ __('Company / Business Name') }}</label>
                                        <input type="text" name="companyName" value="{{ $business->companyName }}" class="form-control" placeholder="{{ __('Enter Title') }}">
                                    </div>

                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Phone Number') }}</label>
                                        <input type="number" name="phoneNumber" value="{{ $business->phoneNumber }}" class="form-control" placeholder="{{ __('Enter Phone') }}">
                                    </div>

                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Address') }}</label>
                                        <input type="text" name="address" value="{{ $business->address }}" class="form-control" placeholder="{{ __('Enter Address') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Email') }}</label>
                                        <input type="email" name="email" value="{{ $setting->value['email'] ?? '' }}" class="form-control" placeholder="{{ __('Enter Email') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('VAT/GST Title') }}</label>
                                        <input type="text" name="vat_name" value="{{ $business->vat_name }}" class="form-control" placeholder="{{ __('Enter VAT/GST Title') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('VAT/GST Number') }}</label>
                                        <input type="text" name="vat_no" value="{{ $business->vat_no }}" class="form-control" placeholder="{{ __('Enter VAT/GST Number') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Invoice Label') }}</label>
                                        <input type="text" name="note_label" value="{{ $setting->value['note_label'] ?? '' }}" class="form-control" placeholder="{{ __('Enter label') }}">
                                    </div>

                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Invoice Note') }}</label>
                                        <input type="text" name="note" value="{{ $setting->value['note'] ?? '' }}" class="form-control" placeholder="{{ __('Enter note') }}">
                                    </div>

                                    <div class="col-lg-6 mt-2">
                                        <label>{{ __('Post Sale Message') }}</label>
                                        <input type="text" name="gratitude_message" value="{{ $setting->value['gratitude_message'] ?? '' }}" class="form-control" placeholder="{{ __('Enter message') }}">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label for="cart_sound">{{__('Cart Sound')}}</label>
                                        <input type="file" class="form-control" name="cart_sound" value="{{ $setting->value['cart_sound'] ?? '' }}" accept="audio/*">
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="audio-container">
                                            <audio controls>
                                                <source src="{{ asset($setting->value['cart_sound'] ?? '') }}" type="audio/mpeg">
                                                {{__('Your browser does not support the audio element.')}}
                                            </audio>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label for="notification_sound">{{__('Notification Sound')}}</label>
                                        <input type="file" class="form-control" name="notification_sound" value="{{ $setting->value['notification_sound'] ?? '' }}" accept="audio/*">
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="audio-container">
                                            <audio controls>
                                                <source src="{{ asset($setting->value['notification_sound'] ?? '') }}" type="audio/mpeg">
                                                {{__('Your browser does not support the audio element.')}}
                                            </audio>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 settings-image-upload">
                                        <label class="">{{ __('Invoice Logo') }}</label>
                                        <div class="upload-img-v2">
                                            <label class="upload-v4 settings-upload-v4">
                                                <div class="img-wrp">
                                                    <img src="{{ asset($setting->value['invoice_logo'] ?? 'assets/images/icons/upload-icon.svg') }}" alt="user" id="invoice_logo">
                                                </div>
                                                <input type="file" name="invoice_logo"  accept="image/*" onchange="document.getElementById('invoice_logo').src = window.URL.createObjectURL(this.files[0])" class="form-control d-none">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 settings-image-upload">
                                        <label class="">{{ __('Notification Favicon') }}</label>
                                        <div class="upload-img-v2">
                                            <label class="upload-v4 settings-upload-v4">
                                                <div class="img-wrp">
                                                    <img src="{{ asset($setting->value['notification_favicon'] ?? 'assets/images/icons/upload-icon.svg') }}" alt="user" id="notification_favicon">
                                                </div>
                                                <input type="file" name="notification_favicon"  accept="image/*" onchange="document.getElementById('notification_favicon').src = window.URL.createObjectURL(this.files[0])" class="form-control d-none">
                                            </label>
                                        </div>
                                    </div>
                                    @usercan('settings.update')
                                    <div class="col-lg-12">
                                        <div class="text-center mt-5">
                                            <button type="submit" class="theme-btn m-2 submit-btn">{{ __('Update') }}</button>
                                        </div>
                                    </div>
                                    @endusercan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
