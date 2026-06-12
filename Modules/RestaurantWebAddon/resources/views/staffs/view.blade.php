<div class="modal fade p-0" id="staff-view">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('View Details') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <div class="costing-list">
                    <ul>
                        <div class="row mt-2">
                            <div class="col-12">
                                <img width="100px" class="rounded-circle border-2 modal-img" src="" id="staff_image" alt="">
                            </div>
                        </div>
                        <li><span>{{ __('Name') }}</span> <span>:</span> <span id="staff_name"></span></li>
                        <li><span>{{ __('Email') }}</span> <span>:</span> <span id="staff_email"></span></li>
                        <li><span>{{ __('Phone') }}</span> <span>:</span> <span id="staff_phone"></span></li>
                        <li><span>{{ __('Designation') }}</span> <span>:</span> <span id="staff_desig"></span></li>
                        <li><span>{{ __('Address') }}</span> <span>:</span> <span id="staff_address"></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
