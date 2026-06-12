<div class="modal fade common-validation-modal" id="ingredient-create-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Add New Ingredient') }}</h1>
                <button type="button" class="btn-close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="{{ route('business.purchases.store.ingredient') }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <label class="custom-top-label">{{ __('Name') }}</label>
                                <input type="text" name="name" placeholder="{{ __('Enter Ingredient Name') }}" required class="form-control"/>
                            </div>
                        </div>
                        @usercan('ingredients.create')
                        <div class="offcanvas-footer mt-2">
                            <div class="button-group text-center mt-3">
                                <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                            </div>
                        </div>
                        @endusercan
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
