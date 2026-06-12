@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Add Modifier Group') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title mb-3">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <span> <a href="{{ route('business.modifier-groups.index') }}">/
                            {{ __('Modifier Group') }}</a></span>
                    <span>/ {{ __('Add Modifier Group') }}</span>
                </h2>
            </div>
            <div class="card border-0">
                <div class="card-bodys ">
                    <div class="order-form-section p-16">
                        <form action="{{ route('business.modifier-groups.store') }}" method="post"
                            enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label>{{ __('Group Name') }}</label>
                                    <input type="text" name="name" required class="form-control"
                                        placeholder="{{ __('Enter Group Name') }}">
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label> {{ __('Locations') }} </label>
                                    <div>
                                        <select class="form-control form-selected" id="choice_tag_product" name="product_id[]"
                                            multiple>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->productName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label>{{ __('Description') }}</label>
                                    <textarea type="text" name="description" class="form-control" placeholder="{{ __('Enter Description') }}"></textarea>
                                </div>

                                <!-- Modifier Options -->
                                <h5 class="my-3"> {{ __('Modifier Options') }} </h5>
                                <div class="modifier-options">
                                    <div class="option-row row mb-2">
                                        <div class="col-md-6 mb-2">
                                            <label>{{ __('Name') }}</label>
                                            <input type="text" name="option_name[]" class="form-control"
                                                placeholder="{{ __('Enter Name') }}">
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label>{{ __('Price') }}</label>
                                            <input type="number" name="option_price[]" class="form-control"
                                                placeholder="{{ __('Enter Price') }}">
                                        </div>
                                        <div class="col-md-1 mt-md-4 d-flex align-items-center justify-content-end">
                                            <button type="button" class="btn btn-lg dynamic-add-btn add-option">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.8" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 4.5v15m7.5-7.5h-15" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="row col-md-12 mx-auto">
                                            <div class="form-check d-flex align-items-center">
                                                <input type="checkbox" id="check" name="is_available[]" value="1"
                                                    class="form-check-input text-success me-2" checked>
                                                <label for="check"
                                                    class="form-check-label">{{ __('Is Available') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                @usercan('modifierGroups.create')
                                    <div class="button-group text-center mt-3">
                                        <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                        <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                                    </div>
                                @endusercan
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
