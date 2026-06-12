<div class="modal fade" id="message-view-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('View Details') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="message-info">
                    <div class="row">
                        <div class="col-md-4">
                            <p>{{ __('Name') }}</p>
                        </div>
                        <div class="col-1">
                            <p>:</p>
                        </div>
                        <div class="col-md-7">
                            <p id="name"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p>{{ __('Email') }}</p>
                        </div>
                        <div class="col-1">
                            <p>:</p>
                        </div>
                        <div class="col-md-7">
                            <p id="email"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p>{{ __('Phone') }}</p>
                        </div>
                        <div class="col-1">
                            <p>:</p>
                        </div>
                        <div class="col-md-7">
                            <p id="phone"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p>{{ __('Company Name') }}</p>
                        </div>
                        <div class="col-1">
                            <p>:</p>
                        </div>
                        <div class="col-md-7">
                            <p id="company_name"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p>{{ __('Message') }}</p>
                        </div>
                        <div class="col-1">
                            <p>:</p>
                        </div>
                        <div class="col-md-7">
                            <p id="message"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
