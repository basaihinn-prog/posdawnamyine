@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Order Type Setting') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="order-type-setings">
            <ul class="type-menu">
                <li><a href="{{ route('business.dashboard.index') }}">{{__('Dashboard')}}</a></li>
                <li class="active"><a href="{{ route('business.order-type-setting.index') }}"> / {{__('Customer Order Types')}}</a></li>
            </ul>
            <div class="order-type-seting">
             <form action="{{ route('business.order-type-setting.update', $order_type->id ?? 0) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                @csrf
                @method('put')
                <div class="allow-custom">
                    <div class="left-side">
                        <div class="form-check">
                            <input class="form-check-input" id="orderTypeChecked" type="checkbox" value="" id="flexCheckChecked">
                        </div>
                    </div>
                    <div class="right-side">
                        <h6>{{__('Allow Custom Order Type Options')}}</h6>
                        <span>{{__('Enable this to allow customers to select custom order type options.')}}</span>
                    </div>
                </div>
                <div class="custom-area table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>{{__('Order Type Name')}}</th>
                                <th>{{__('Order Type')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody class="duplicateOrderType">
                            @foreach ($order_type->value['name'] ?? [] as $key => $name)
                            <tr class="cloneRow">
                                <td>
                                    <input type="text" name="name[{{ $key }}]" value="{{ $name }}" class="product-input" placeholder="enter name">
                                </td>
                                <td>
                                    <select name="type[{{ $key }}]">
                                        <option {{ (isset($order_type->value['type'][$key]) && $order_type->value['type'][$key] == 'dine_in') ? 'selected' : '' }} value="dine_in">{{__('Dine In')}}</option>
                                        <option {{ (isset($order_type->value['type'][$key]) && $order_type->value['type'][$key] == 'pick_up') ? 'selected' : '' }} value="pick_up">{{__('Pick Up')}}</option>
                                        <option {{ (isset($order_type->value['type'][$key]) && $order_type->value['type'][$key] == 'delivery') ? 'selected' : '' }} value="delivery">{{__('Delivery')}}</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="status[{{ $key }}]" value="0">
                                        <input name="status[{{ $key }}]" value="1" class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" {{ isset($order_type->value['status'][$key]) && $order_type->value['status'][$key] ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td class="d-print-none">
                                    <div class="align-items-center">
                                        <div class="icon-buttons">
                                            <a title="{{__('Delete')}}" data-bs-placement="top"
                                                class="action-btn delete remove-btn-ordertype">
                                                <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M18.6273 15.5714C18.5653 16.5711 18.5161 17.3644 18.4154 17.998C18.3119 18.6479 18.1464 19.1891 17.8154 19.6625C17.5125 20.0955 17.1226 20.4611 16.6704 20.7357C16.1761 21.036 15.6242 21.1673 14.9672 21.2301L10.0171 21.23C9.35943 21.167 8.80689 21.0355 8.31224 20.7347C7.85974 20.4595 7.46969 20.0934 7.16693 19.6596C6.83598 19.1854 6.671 18.6435 6.56838 17.9927C6.46832 17.3581 6.42023 16.5636 6.35961 15.5625L5.83337 6.87109H19.1667L18.6273 15.5714ZM10.4798 17.4097C10.1451 17.4097 9.87378 17.1419 9.87378 16.8114V12.0251C9.87378 11.6947 10.1451 11.4268 10.4798 11.4268C10.8146 11.4268 11.0859 11.6947 11.0859 12.0251V16.8114C11.0859 17.1419 10.8146 17.4097 10.4798 17.4097ZM15.1263 12.0251C15.1263 11.6947 14.8549 11.4268 14.5202 11.4268C14.1855 11.4268 13.9142 11.6947 13.9142 12.0251V16.8114C13.9142 17.1419 14.1855 17.4097 14.5202 17.4097C14.8549 17.4097 15.1263 17.1419 15.1263 16.8114V12.0251Z"
                                                        fill="#979797" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M13.6746 2.79397C14.1673 2.83166 14.6303 2.96028 15.0279 3.21397C15.322 3.4016 15.5262 3.63127 15.7008 3.88001C15.8625 4.11051 16.0253 4.39789 16.21 4.72389L16.5821 5.38044H20.3462C20.8277 5.38044 21.218 5.71436 21.218 6.12627C21.218 6.53818 20.8277 6.8721 20.3462 6.8721C15.1153 6.8721 9.88483 6.8721 4.6539 6.8721C4.17242 6.8721 3.7821 6.53818 3.7821 6.12627C3.7821 5.71436 4.17242 5.38044 4.6539 5.38044H8.49831L8.80849 4.7983C8.98857 4.46029 9.14716 4.1626 9.30646 3.92373C9.4783 3.66606 9.68172 3.42762 9.97935 3.23228C10.3819 2.96811 10.8542 2.83419 11.3579 2.79495C11.7371 2.76542 12.1194 2.76948 12.5001 2.77001C12.9454 2.77062 13.3457 2.7688 13.6746 2.79397ZM10.4145 5.38044H14.6444C14.4468 5.03185 14.3204 4.81091 14.2058 4.64767C14.0382 4.40876 13.8373 4.30403 13.5193 4.27969C13.2932 4.2624 12.9986 4.2617 12.5301 4.2617C12.0499 4.2617 11.7477 4.26242 11.516 4.28046C11.1898 4.30586 10.9865 4.41458 10.8209 4.66293C10.7124 4.82568 10.5942 5.04353 10.4145 5.38044Z"
                                                        fill="#979797" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a class="add-btnse">+ {{__('Add New')}}</a>
                </div>
                <div class="customs-btn">
                    <a class="theme-btn border-btn">{{__('Reset')}}</a>
                    <button type="submit" class="theme-btn submit-btn">{{__('Save')}}</button>
                </div>
             </form>
            </div>
        </div>
    </div>
</div>
@endsection


