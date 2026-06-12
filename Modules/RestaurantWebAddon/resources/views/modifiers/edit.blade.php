<div class="modal fade common-validation-modal" id="modifiers-edit-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">{{ __('Edit Modifier') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="personal-info">
                    <form action="" method="post" enctype="multipart/form-data"
                        class="ajaxform_instant_reload modifierUpdateForm">
                        @csrf
                        @method('put')
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label> {{ __('Item') }} </label>
                                <div class="gpt-up-down-arrow position-relative">
                                    <select class="form-control form-selected" name="product_id" id="modif_product_id" required>
                                        <option value="">{{ __('Select a item') }}</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->productName }}</option>
                                        @endforeach
                                    </select>
                                    <span></span>
                                </div>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label> {{ __('Modifier Group') }} </label>
                                <div class="gpt-up-down-arrow position-relative">
                                    <select class="form-control form-selected" name="modifier_group_id" id="modif_group_id" required>
                                        <option value="">{{ __('Select a group') }}</option>
                                        @foreach ($modifier_groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    <span></span>
                                </div>
                            </div>

                            <div class="option-row mt-3">
                                <div class="row col-md-12 mx-auto">
                                    <div class="form-check d-flex align-items-center">
                                        <input type="hidden" name="is_multiple" value="0">
                                        <input type="checkbox" id="modif_is_multiple" name="is_multiple" value="1"
                                            class="form-check-input text-success me-2">
                                        <label for="modif_is_multiple"
                                            class="form-check-label p-0">{{ __('Allow Multiple Section For Sale') }}</label>
                                    </div>
                                </div>
                                <div class="row col-md-12 mx-auto">
                                    <div class="form-check d-flex align-items-center"       >
                                        <input type="hidden" name="is_required" value="0">
                                        <input type="checkbox" id="modif_is_required" name="is_required" value="1"
                                            class="form-check-input text-success me-2">
                                        <label for="modif_is_required"
                                            class="form-check-label p-0">{{ __('Is Required') }}</label>
                                    </div>
                                </div>
                            </div>
                         </div>
                        <div class="col-lg-12">
                            @usercan('itemModifiers.update')
                            <div class="button-group text-center mt-3">
                                <a href="{{ route('business.modifiers.index') }}" class="theme-btn border-btn m-2">{{ __('Cancel') }}</a>
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
