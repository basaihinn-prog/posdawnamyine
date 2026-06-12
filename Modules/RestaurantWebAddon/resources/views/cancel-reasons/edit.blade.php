<div class="modal fade common-validation-modal" id="cancel-reason-edit-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cancelastion-modal">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Edit Cancel Reason') }}</h1>
                <button type="button" class="btn-close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload cancelReasonUpdateForm">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Reason') }}</label>
                                <textarea type="text" name="reason" id="reason" required class="form-control" placeholder="{{ __('Enter Reason') }}"></textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="button-group text-center mt-3">
                                <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                @usercan('cancelReason.update')
                                    <button class="theme-btn m-2 submit-btn">{{ __('Update') }}</button>
                                @endusercan
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
