<div class="modal fade common-validation-modal" id="coupon-create-modal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Add Coupon') }}</h1>
                <button type="button" class="btn-close modal-close-btn" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="{{ route('business.coupons.store') }}" method="post" enctype="multipart/form-data"
                        class="ajaxform_instant_reload">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <div class="row">
                                    <div class="col-9 col-md-10">
                                        <label class="img-label">{{ __('Image') }}</label>
                                        <input type="file" accept="image/*" name="image"
                                            class="form-control file-input-change" data-id="image">
                                    </div>
                                    <div class="col-3 col-md-2 align-self-center mt-4">
                                        <img src="{{ asset('assets/images/icons/upload.png') }}" id="image"
                                            class="table-img">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('Name') }}</label>
                                <input type="text" name="name" placeholder="{{ __('Enter Coupon Name') }}"
                                    required class="form-control" />
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('Code') }}</label>
                                <input type="text" name="code" placeholder="{{ __('Enter Code') }}" required
                                    class="form-control" />
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('Discount') }}</label>
                                <div class=" percentage-flat-container">
                                    <input type="number" class="form-control" id="discount" name="discount" placeholder="{{__('Enter Discount')}}">
                                    <select class="form-select percentage-flat" name="discount_type">
                                        <option value="percentage">{{__('Percentage')}}</option>
                                        <option value="flat">{{__('Flat')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('Start Date') }}</label>
                                <input type="date" name="start_date" required class="form-control" value="{{ date('Y-m-d') }}" />
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('End Date') }}</label>
                                <input type="date" name="end_date" required class="form-control" value="{{ \Carbon\Carbon::now()->addDays(3)->format('Y-m-d') }}" />
                            </div>
                            <div class="mb-2 col-lg-12">
                                <label class="custom-top-label">{{ __('Description') }}</label>
                                <textarea class="form-control" name="description" placeholder="{{ __('Enter Description') }}"></textarea>
                            </div>
                        </div>
                        <div class="offcanvas-footer mt-3">
                            @usercan('coupon.create')
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
