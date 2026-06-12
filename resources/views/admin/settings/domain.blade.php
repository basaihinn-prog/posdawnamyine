@extends('layouts.master')

@section('title')
    {{ __('Domain Settings') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-bodys">
                    <div class="table-header p-16">
                        <h4>{{ __('Domain Settings') }}</h4>
                    </div>
                    <div class="order-form-section p-16">
                        <form action="{{ route('admin.domain-settings.store') }}" method="post"
                            enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf

                            <div class="row product-setting-form mt-3">
                                <div class="col-lg-12">
                                    <div class="d-flex align-items-center mb-3">
                                        <input type="radio" id="ssl_required" class="delete-checkbox-item multi-delete me-2" name="ssl_required" value="on" {{ ($domain->value['ssl_required'] ?? '') === 'on' ? 'checked' : '' }}>
                                        <label for="ssl_required" class="custom-top-label">
                                            {{ __('SSL is required.') }}
                                        </label>
                                    </div>

                                    <div class="d-flex align-items-center mb-3 mt-3">
                                        <input type="radio" id="ssl_nullable" class="delete-checkbox-item multi-delete me-2" name="ssl_required" value="off" {{ ($domain->value['ssl_required'] ?? '') === 'off' ? 'checked' : '' }}>
                                        <label for="ssl_nullable" class="custom-top-label">
                                            {{ __('SSL is not required.') }}
                                        </label>
                                    </div>

                                    <div class="d-flex align-items-center mb-3">
                                        <input type="radio" id="automatic_approve" class="delete-checkbox-item multi-delete me-2" name="automatic_approve" value="on" {{ ($domain->value['automatic_approve'] ?? '') === 'on' ? 'checked' : '' }}>
                                        <label for="automatic_approve" class="custom-top-label">
                                            {{ __('Subdomain / Custom domains are allowed automatically.') }}
                                        </label>
                                    </div>

                                    <div class="d-flex align-items-center mb-3 mt-3">
                                        <input type="radio" id="domain_not_allowed" class="delete-checkbox-item multi-delete me-2" name="automatic_approve" value="off" {{ ($domain->value['automatic_approve'] ?? '') === 'off' ? 'checked' : '' }}>
                                        <label for="domain_not_allowed" class="custom-top-label">
                                            {{ __('Subdomain / Custom domains are not allowed automatically.') }}
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-5">
                                    <div class="text-center mt-5">
                                        <button type="submit" class="theme-btn m-2 submit-btn">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
