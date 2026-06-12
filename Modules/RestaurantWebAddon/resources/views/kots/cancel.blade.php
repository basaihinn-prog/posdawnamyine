
<div class="modal fade common-validation-modal" id="cancel-reason-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Cancel Kot') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="" method="post">
                        @csrf

                        <div class="row">
                            <input type="hidden" name="status" value="cancelled">
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Cancel Reason') }}</label>
                                <div class="gpt-up-down-arrow position-relative">
                                    <select name="cancel_reason_id" class="form-control select-dropdown">
                                        <option value="">{{ __('Select one') }}</option>
                                        @foreach ($cancel_reasons as $reason)
                                            <option value="{{ $reason->id }}">{{ $reason->reason }}</option>
                                        @endforeach
                                    </select>
                                    <span></span>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Comment') }}</label>
                                <textarea type="text" name="notes" class="form-control" placeholder="{{ __('Enter comment') }}"></textarea>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="button-group text-center mt-3">
                                <button type="reset" class="theme-btn border-btn m-2">{{ __('Reset') }}</button>
                                <button class="theme-btn m-2 submit-btn">{{ __('Apply') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
