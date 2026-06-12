<div class="modal fade common-validation-modal" id="coupon-edit-modal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Edit Coupon') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload couponUpdateForm">
                        @csrf
                        @method('put')

                        <div class="row">
                            <div class="col-lg-6 mb-2">
                                <div class="row">
                                    <div class="col-10">
                                        <label class="img-label">{{ __('Image') }}</label>
                                        <input type="file" accept="image/*" name="image"
                                            class="form-control file-input-change" data-id="cpn_img">
                                    </div>
                                    <div class="col-1 align-self-center mt-4">
                                        <img src="" id="cpn_img" class="table-img">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('Name') }}</label>
                                <input type="text" name="name" id="cpn_name" placeholder="{{ __('Enter Coupon Name') }}"
                                    required class="form-control" />
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('Code') }}</label>
                                <input type="text" name="code" id="cpn_code" placeholder="{{ __('Enter Code') }}" required
                                    class="form-control" />
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('Discount') }}</label>
                                <div class="percentage-flat-container">
                                    <input type="number" class="form-control" id="cpn_discount" name="discount"
                                        placeholder="{{ __('Enter Discount') }}">
                                    <select class="form-select percentage-flat" name="discount_type" id="cpn_disc_type">
                                        <option value="percentage">{{ __('Percentage') }}</option>
                                        <option value="flat">{{ __('Flat') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('Start Date') }}</label>
                                <input type="date" name="start_date" id="cpn_st_date" required class="form-control" />
                            </div>
                            <div class="mb-2 col-lg-6">
                                <label class="custom-top-label">{{ __('End Date') }}</label>
                                <input type="date" name="end_date" id="cpn_end_date" required class="form-control" />
                            </div>
                            <div class="mb-2 col-lg-12">
                                <label class="custom-top-label">{{ __('Description') }}</label>
                                <textarea class="form-control" name="description"  id="cpn_desc" placeholder="{{ __('Enter Description') }}"></textarea>
                            </div>
                        </div>
                        <div class="offcanvas-footer mt-3">
                            @usercan('coupon.update')
                            <div class="button-group text-center mt-3">
                                <a href="{{ route('business.categories.index') }}"
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
