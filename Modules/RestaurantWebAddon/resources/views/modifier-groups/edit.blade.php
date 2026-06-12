@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Edit Modifier Group') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="section-title mb-3">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <span> <a href="{{ route('business.modifier-groups.index') }}">/
                            {{ __('Modifier Group') }}</a></span>
                    <span>/ {{ __('Edit Modifier Group') }}</span>
                </h2>
            </div>
            <div class="card border-0">
                <div class="card-bodys">
                    <div class="order-form-section p-16">
                        <form action="{{ route('business.modifier-groups.update', $modifier_group->id) }}" method="post"
                            enctype="multipart/form-data" class="ajaxform_instant_reload incomeUpdateForm">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label>{{ __('Group Name') }}</label>
                                    <input type="text" name="name" required class="form-control"
                                        value="{{ $modifier_group->name }}" placeholder="{{ __('Enter Group Name') }}">
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label>{{ __('Description') }}</label>
                                    <textarea type="text" name="description" class="form-control" placeholder="{{ __('Enter Description') }}">{{ $modifier_group->description }}</textarea>
                                </div>

                                <h5 class="my-3"> {{ __('Modifier Options') }} </h5>
                                <div class="modifier-options">
                                    @foreach ($modifier_group->modifier_group_option as $index => $option)
                                        <div class="option-row row mb-2">
                                            <div class="col-md-6 mb-2">
                                                <label>{{ __('Name') }}</label>
                                                <input type="text" name="option_name[]" class="form-control"
                                                    value="{{ $option->name }}" placeholder="{{ __('Enter Name') }}">
                                            </div>
                                            <div class="col-md-5 mb-2">
                                                <label>{{ __('Price') }}</label>
                                                <input type="number" name="option_price[]" class="form-control"
                                                    value="{{ $option->price }}" placeholder="{{ __('Enter Price') }}">
                                            </div>
                                            <div class="col-md-1 mt-md-4 d-flex align-items-center justify-content-end">
                                                @if ($loop->first)
                                                    <button type="button" class="btn btn-lg dynamic-add-btn add-option">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
                                                            class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M12 4.5v15m7.5-7.5h-15" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn dynamic-delete-btn remove-option">
                                                        <svg class="text-danger" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="row col-md-12 mx-auto">
                                                <div class="form-check d-flex align-items-center">
                                                    <input type="checkbox" id="checks_{{ $loop->iteration }}"
                                                        name="is_available[]" value="1" class="form-check-input me-2"
                                                        {{ $option->is_available ? 'checked' : '' }}>
                                                    <label for="checks_{{ $loop->iteration }}"
                                                        class="form-check-label">{{ __('Is Available') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <div class="col-lg-12">
                                @usercan('modifierGroups.update')
                                <div class="button-group text-center mt-3">
                                    <a href="{{ route('business.modifier-groups.index') }}"
                                        class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
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
