@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Create Item') }}
@endsection

@section('main_content')
    <div class="">
        <div class="section-main-container">
            <div class="section-title">
                <h2> <a href="{{ route('business.dashboard.index') }}"> {{ __('Dashboard') }} </a>
                    <span> <a href="{{ route('business.products.index') }}">/ {{ __('Items List') }}</a></span>
                    <span>/ {{ __('Add Item') }}</span>
                </h2>
            </div>
          <form action="{{ route('business.products.store') }}" method="POST" class="ajaxform_instant_reload" enctype="multipart/form-data">
            @csrf
          <div class="items-grid">
              <div>
                  <div class="section-main-content min-h-0">
                          @csrf
                          <div class="row g-2">
                              <div class="col-lg-12">
                                  <div class="card product-card border-0 min-h-0">
                                      <div class="card-bodys">
                                          <div class="order-form-section">
                                              <div class="add-suplier-modal-wrapper d-block">
                                                  <div class="row">
                                                      <div class="col-lg-6 mb-2">
                                                          <label>{{ __('Item Name') }}</label>
                                                          <input type="text" name="productName" required class="form-control" placeholder="{{ __('Enter Item Name') }}">
                                                      </div>
                                                      <div class="col-lg-6 mb-2">
                                                          <label>{{ __('Menu') }}</label>
                                                          <div class="gpt-up-down-arrow position-relative">
                                                              <select name="menu_id" class="form-control table-select w-100 role" required>
                                                                  <option value=""> {{ __('Select Menu') }}</option>
                                                                  @foreach ($menus as $menu)
                                                                      <option value="{{ $menu->id }}"> {{ ucfirst($menu->name) }}</option>
                                                                  @endforeach
                                                              </select>
                                                              <span></span>
                                                          </div>
                                                      </div>
                                                      <div class="col-lg-6 mb-2">
                                                          <label>{{ __('Category') }}</label>
                                                          <div class="gpt-up-down-arrow position-relative">
                                                              <select name="category_id" class="form-control table-select w-100 role">
                                                                  <option value=""> {{ __('Select category') }}</option>
                                                                  @foreach ($categories as $category)
                                                                      <option value="{{ $category->id }}"> {{ ucfirst($category->categoryName) }}</option>
                                                                  @endforeach
                                                              </select>
                                                              <span></span>
                                                          </div>
                                                      </div>
                                                      <div class="col-lg-6 mb-2">
                                                          <label> {{ __('Modifier Items') }} </label>
                                                          <div class="mt-md-1">
                                                            <select id="modifier_group_id" name="modifier_group_id[]" multiple>
                                                                @foreach ($modifier_groups as $modifier_group)
                                                                    <option value="{{ $modifier_group->id }}">{{ $modifier_group->name }}</option>
                                                                @endforeach
                                                            </select>
                                                          </div>
                                                      </div>
                                                      <div class="col-lg-6 mb-2">
                                                          <label>{{ __('Preparation Time(Minutes)') }}</label>
                                                          <input type="text" name="preparation_time" class="form-control" placeholder="{{ __('Ex:30') }}">
                                                      </div>
                                                      <div class="col-lg-6 mb-2">
                                                          <label>{{ __('Description') }}</label>
                                                          <input type="text" name="description" class="form-control" placeholder="{{ __('Enter description') }}">
                                                      </div>
                                                      <div class="col-lg-12 mb-3">
                                                          <label class="fw-bold">{{ __('Food Type') }}</label>
                                                        <div class="d-flex gap-2 flex-wrap mt-2 custom-radio">
                                                        <input type="radio" class="btn-check" name="food_type" id="veg" value="veg" autocomplete="off">
                                                        <label class="btn px-3 py-2" for="veg">{{__('Veg')}}</label>

                                                        <input type="radio" class="btn-check" name="food_type" id="non_veg" value="non_veg" autocomplete="off">
                                                        <label class="btn px-3 py-2" for="non_veg">{{__('Non Veg')}}</label>

                                                        <input type="radio" class="btn-check" name="food_type" id="egg" value="egg" autocomplete="off">
                                                        <label class="btn px-3 py-2" for="egg">{{__('Egg')}}</label>

                                                        <input type="radio" class="btn-check" name="food_type" id="drink" value="drink" autocomplete="off">
                                                        <label class="btn px-3 py-2" for="drink">{{__('Drink')}}</label>

                                                        <input type="radio" class="btn-check" name="food_type" id="others" value="others" autocomplete="off">
                                                        <label class="btn px-3 py-2" for="others">{{__('Others')}}</label>
                                                        </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                       </div>

                    <div class="section-main-content min-h-0">
                   <div class="variation-section order-form-section">
                        <div class="d-flex align-items-center gap-3 mb-3 single-variation-check">
                            <input class="form-check-input" type="radio" name="price_type" id="single" value="single" autocomplete="off" checked>
                            <label for="single">{{__('Single')}}</label>

                            <input class="form-check-input" type="radio" name="price_type" id="variation" value="variation" autocomplete="off">
                            <label for="variation">{{__('Variation')}}</label>
                        </div>

                        <div class="col-lg-6 mb-2 singlePriceFiled">
                            <label>{{ __('Sales Price') }}</label>
                            <input type="number" name="sales_price" required class="form-control" placeholder="{{ __('Enter sale price') }}">
                        </div>
                        <!-- Variation Fields -->
                        <div class="variationPriceFiled d-none d-flex w-100 align-items-end">
                            <div class="duplicateVariation w-100">
                                <div class="variation-row row mb-2">
                                    <div class="col-md-6 mb-2">
                                        <label>{{ __('Name') }}</label>
                                        <input type="text" name="variation_names[]" class="form-control"
                                            placeholder="{{ __('Enter Name') }}">
                                    </div>
                                    <div class="col-md-5 mb-2">
                                        <label>{{ __('Price') }}</label>
                                        <input type="number" name="variation_prices[]" class="form-control"
                                            placeholder="{{ __('Enter Price') }}">
                                    </div>
                                    <div class="col-md-1 mt-md-4 d-flex align-items-center">
                                        <button type="button" class="btn dynamic-delete-btn remove-btn-variation">
                                            <svg class="text-danger" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class=" mt-md-4 mb-3 d-flex align-items-center">
                                <button type="button" class="btn btn-lg dynamic-add-btn variationPrice">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.8" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                  </div>
              </div>

              <div class="">
                <div class="section-main-content min-h-0">
                    <h4 class="title">{{__('Publish')}}</h4>
                    @usercan('products.create')
                    <div class="row mt-3">
                        <div class="col-6">
                            <button type="reset" class="reset-btn w-100">{{__('Reset')}}</button>
                        </div>
                        <div class="col-6">
                            <button class="published-btn w-100 submit-btn">{{__('Published')}}</button>
                        </div>
                    </div>
                    @endusercan
                </div>
                <div class="upload__box">
                    <div class="upload__btn-box">
                      <label class="upload__btn">
                        {{__('Upload Images')}}
                        <input type="file" name="images[]" multiple="" data-max_length="20" class="upload__inputfile">
                      </label>
                    </div>
                    <div class="upload__img-wrap"></div>
                  </div>
              </div>
          </div>
        </form>
        </div>
    </div>
@endsection


