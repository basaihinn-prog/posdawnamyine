<div class="modal fade common-validation-modal" id="deliveryAddress-create-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Add New Address') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="{{ route('business.delivery-address.store') }}" method="post" enctype="multipart/form-data"
                        class="ajaxform_instant_reload">
                        @csrf
                        <input type="hidden" name="party_id" id="modalPartyId"> <!-- hidden field -->
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Name') }}</label>
                                <input type="text" name="name" required class="form-control" placeholder="{{ __('Enter Name') }}">
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Phone') }}</label>
                                <input type="number" name="phone" required class="form-control" placeholder="{{ __('Enter phone') }}">
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Address') }}</label>
                                <input type="text" name="address" required class="form-control" placeholder="{{ __('Enter address') }}">
                            </div>
                         </div>
                        <div class="col-lg-12">
                            <div class="button-group text-center mt-3">
                                <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
