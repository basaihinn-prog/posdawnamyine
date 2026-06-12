<div class="modal fade common-validation-modal" id="item-create-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Add Item') }}</h1>
                <button type="button" class="btn-close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="{{ route('business.items.store') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="custom-top-label">{{ __('Item Name') }}</label>
                                <input type="text" name="name" placeholder="{{ __('Enter Item Name') }}" required class="form-control"/>
                            </div>
                        </div>
                        <div class="offcanvas-footer mt-3">
                            @usercan ('ingredients.create')
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
