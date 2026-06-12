<div class="modal fade p-0 view-kot-modal" id="view-kot-report">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex justify-content-between">
                    <h1 class="modal-title fs-5">{{ __('View Details') }}</h1>
                </div>
                <div class="kot-modal-deader-right">
                    <h1 class="kot-status fs-6 kot-view-modal-status" id="status"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body order-form-section">
                <div class="order-info">
                    <div class="kot-body-top">
                        <div class="left-side">
                            <span class="fw-bold text-dark">{{__('KOT')}}: <h6 id="kot"></h6></span>
                            <span>{{__('Items')}}: <span id="item" class="d-inline"></span></span>
                            <span id="date"></span>
                        </div>
                        <div class="right-side">
                            <span>{{__('Order')}}: <h6 id="order"></h6></span>
                            <span>{{__('Table')}}: <span id="table" class="d-inline"></span></span>
                            <span id="customer"></span>
                        </div>
                    </div>
                    <div class="kot-middle">
                        <div class="kot-middle-head">
                            <h6>{{__('Item Name')}}</h6>
                        </div>
                        <div class="items" id="kot-items">
                            {{-- loaded by js  --}}
                        </div>
                    </div>
                </div>
                <div class="kot-modal-footer">
                    <div class="footer-label">{{__('Cancelled Reason')}}</div>
                    <div class="footer-content" id="reason"></div>
                </div>
            </div>
        </div>
    </div>
</div>
