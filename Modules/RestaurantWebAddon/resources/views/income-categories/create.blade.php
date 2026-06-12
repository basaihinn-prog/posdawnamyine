
<div class="modal fade common-validation-modal" id="income-categories-create-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Create Income Category') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="{{ route('business.income-categories.store') }}" method="post" enctype="multipart/form-data"
                        class="ajaxform_instant_reload">
                        @csrf

                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Category Name') }}</label>
                                <input type="text" name="categoryName" required class="form-control" placeholder="{{ __('Enter category name') }}">
                            </div>

                        <div class="col-lg-12">
                            @usercan('incomeCategory.create')
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
