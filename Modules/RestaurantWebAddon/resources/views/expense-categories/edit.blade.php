<div class="modal fade common-validation-modal" id="expense-categories-edit-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Edit Expense Category') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="" method="post" enctype="multipart/form-data"
                        class="ajaxform_instant_reload expenseCategoryUpdateForm">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-lg-12 mb-1">
                                <label>{{ __('Category Name') }}</label>
                                <input type="text" name="categoryName" id="expense_categories_name" required class="form-control" placeholder="{{ __('Enter category name') }}">
                            </div>

                        <div class="col-lg-12">
                            @usercan('expenseCategory.update')
                            <div class="button-group text-center mt-3">
                                <a href="{{ route('business.expense-categories.index') }}" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
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
