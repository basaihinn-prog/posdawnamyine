
<div class="modal fade common-validation-modal" id="tables-edit-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Edit Table') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="" method="post" enctype="multipart/form-data"
                        class="ajaxform_instant_reload tableUpdateForm">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Area') }}</label>
                                <div class="gpt-up-down-arrow position-relative">
                                    <select name="area_id" id="area_id" class="form-control table-select w-100 role" required>
                                        <option value=""> {{ __('Select area') }}</option>
                                        @foreach ($areas as $area)
                                            <option value="{{ $area->id }}"> {{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                    <span></span>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Name') }}</label>
                                <input type="text" name="name" id="table_name" required class="form-control" placeholder="{{ __('Enter Name') }}">
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Capacity') }}</label>
                                <input type="Number" name="capacity" id="table_capacity" required class="form-control" placeholder="{{ __('Ex: 6') }}">
                            </div>
                            <div class="col-lg-12 mb-2">
                                <label>{{ __('Status') }}</label>
                                <div class="gpt-up-down-arrow position-relative">
                                    <select class="form-control form-selected" id="table_status" name="is_booked">
                                        <option value="0">{{__('Available')}}</option>
                                        <option value="1">{{__('Booked')}}</option>
                                    </select>
                                    <span></span>
                                </div>
                            </div>
                         </div>
                         @usercan('tables.update')
                        <div class="col-lg-12">
                            <div class="button-group text-center mt-3">
                                <a href="{{ route('business.tables.index') }}" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
                                <button class="theme-btn m-2 submit-btn">{{ __('Save') }}</button>
                            </div>
                        </div>
                        @endusercan
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
