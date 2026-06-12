<div class="modal fade p-0" id="parties-view">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h5 class="modal-title">{{ __('View Details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Profile Section -->
                <div class="d-flex align-items-center mb-3">
                    <img id="parties_image" src="" alt="Profile" class="details-img" width="60" height="60">
                  
                </div>

                <!-- Details -->
                <div class="costing-list">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-bold p-2 border-0 text-start">{{ __('Name') }}</td>
                                <td class="text-start p-2 border-0">:</td>
                                <td class="text-start p-2 border-0"><span id="parties_name"></span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold p-2 border-0 text-start">{{ __('Phone') }}</td>
                                <td class="text-start p-2 border-0">:</td>
                                <td class="text-start p-2 border-0"><span id="parties_phone"></span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold p-2 border-0 text-start">{{ __('Email') }}</td>
                                <td class="text-start p-2 border-0">:</td>
                                <td class="text-start p-2 border-0"><span id="parties_email"></span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold p-2 border-0 text-start">{{ __('Address') }}</td>
                                <td class="text-start p-2 border-0">:</td>
                                <td class="text-start p-2 border-0"><span id="parties_address"></span></td>
                            </tr>
                            <tr>
                                <td class="fw-bold p-2 border-0 text-start">{{ __('Due') }}</td>
                                <td class="text-start p-2 border-0">:</td>
                                <td class="text-start p-2 border-0"><span id="parties_due" class="due-text"></span></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                @if (request('type') != 'supplier' )
                <h6 class="fw-bold mt-3">{{ __('Delivery Address') }}</h6>
                @endif
                <div id="delivery_addresses_container" class="delivery-scroll mt-2">
                    <!-- JS will append cards here -->
                </div>
            </div>
        </div>
    </div>
</div>

