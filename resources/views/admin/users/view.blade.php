<div class="modal fade" id="User-view">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('View Details') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <div class="personal-info ">
                    <div class="row align-items-center mt-4">
                        <div class="col-12">
                            <img class="view-img" src="" id="staffImage" alt="">
                        </div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col-5"><span>{{ __('Name') }}</span></div>
                        <div class="col-1"><span>:</span></div>
                        <div class="col-6"><span id="staff_view_name"></span></div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col-5"><span>{{ __('Phone') }}</span></div>
                        <div class="col-1"><span>:</span></div>
                        <div class="col-6"><span id="staff_view_phone_number"></span></div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col-5"><span>{{ __('Email') }}</span></div>
                        <div class="col-1"><span>:</span></div>
                        <div class="col-6"><span id="staff_view_email_number"></span></div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col-5"><span>{{ __('Role') }}</span></div>
                        <div class="col-1"><span>:</span></div>
                        <div class="col-6"><span id="staff_view_role"></span></div>
                    </div>
                    <div class="row align-items-center mt-4">
                        <div class="col-5"><span>{{ __('Status') }}</span></div>
                        <div class="col-1"><span>:</span></div>
                        <div class="col-6"><span>  <div id="staff_view_status"></div> </span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
