<div class="modal fade p-0" id="coupon-view">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('View Details') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body order-form-section">
                <div class="costing-list">
                    <ul>
                        <li>
                            <img id="coupon_image" src="" alt="">
                        </li>
                        <li><span>{{ __('Name') }}</span> <span>:</span> <span id="coupon_name"></span></li>
                        <li><span>{{ __('Code') }}</span> <span>:</span> <span id="coupon_code"></span></li>
                        <li><span>{{ __('Discount') }}</span> <span>:</span> <span id="coupon_discount"></span></li>
                        <li><span>{{ __('Start Date') }}</span> <span>:</span> <span id="coupon_start_date"></span></li>
                        <li><span>{{ __('End Date') }}</span> <span>:</span> <span id="coupon_end_date"></span></li>
                        <li><span>{{ __('Description') }}</span> <span>:</span> <span id="coupon_desc"></span></li>
                        <li><span>{{ __('Status') }}</span> <span>:</span> <span id="coupon_status"></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
