<div class="modal fade" id="VariationsDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content rounded-4">
        <div class="modal-header border-0">
          <h5 class="modal-title fw-bold">{{__('Edit Variations')}}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="personal-info">
                <form action="" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload variationUpdateForm">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <label>{{ __('Variation Name') }}</label>
                            <input type="text" name="name" id="editVariationName" required class="form-control" placeholder="{{ __('Enter variation Name') }}">
                        </div>
                        <div class="col-lg-12 mb-2">
                            <label>{{ __('Price') }}</label>
                            <input type="text" name="price" id="editVariationPrice" required class="form-control" placeholder="{{ __('Enter amount') }}">
                        </div>
                     </div>
                    <div class="col-lg-12">
                        @usercan ('products.update')
                        <div class="button-group text-center mt-3">
                            <a href="" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
                            <button class="theme-btn m-2 submit-btn">{{ __('Update') }}</button>
                        </div>
                        @endusercan
                    </div>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
