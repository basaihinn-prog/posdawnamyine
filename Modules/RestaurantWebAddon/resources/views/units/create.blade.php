<div class="modal fade common-validation-modal" id="unit-create-modal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Create Unit') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="{{ route('business.units.store') }}" method="post" enctype="multipart/form-data"
                        class="ajaxform_instant_reload">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Unit Name') }}</label>
                                <input type="text" name="unitName" required class="form-control" placeholder="{{ __('Enter Unit Name') }}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            @usercan('units.create')
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
