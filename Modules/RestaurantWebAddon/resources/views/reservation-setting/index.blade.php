@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Reservation Settings') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid ">
        <ul class="deshbord-menu">
            <li ><a class="active" href="{{ route('business.reservations.index') }}">{{__('Dashboard')}}</a></li>
            <li><a href=""> / {{__('Reservation Settings')}}</a></li>
        </ul>
        <div class="setting-desbord">
            <form action="{{ route('business.reservation-settting.store') }}" method="post" class="ajaxform_instant_reload">
            @csrf
            <div class="deshbord-check">
                <div class="deshbord-input-left">
                    <div class="form-check">
                        <label class="form-check-label" for="flexCheckDefault">{{__('Enable Restaurant Reservations')}}</label>
                        <input class="form-check-input checked" name="is_restaurant"
                        {{ ($reservation_setting->value['is_restaurant'] ?? '') == 1 ? 'checked' : ''}} type="checkbox" value="1" id="flexCheckDefault">
                    </div>
                    <div class="full">
                        <label class="reservations">{{__('Let your team create and manage customer reservations directly in the restaurant panel.')}}</label>
                    </div>
                </div>
                <div class="deshbord-input-right">
                    <div class="form-check">
                        <label class="form-check-label" for="flexCheckChecked">{{__('Enable Customer Reservations')}}</label>
                        <input class="form-check-input checked" name="is_customer"
                        {{ ($reservation_setting->value['is_customer'] ?? '') == 1 ? 'checked' : ''}} type="checkbox" value="1" id="flexCheckChecked">
                    </div>
                    <div class="full">
                        <label class="reservations">{{__('Enable users to make reservations instantly from the customer portal.')}}</label>
                    </div>
                </div>
            </div>
            <div class="desbord-size">
                <div class="left-side">
                    <label class="size">{{__('Minimum Guest Size')}}</label>
                    <input type="number" name="minimum_party_size" value="{{ $reservation_setting->value['minimum_party_size'] ?? '' }}" class="size-numbar" placeholder="1">
                </div>
                <div class="right-side">
                    <div class="half">
                        <label class="size">{{__('Disable Slot Minutes')}} <span>: {{__('Set minutes before today’s slots close')}}</span></label>
                        <div class="flex select">
                            <input type="number" class="size-numbar" name="disable_slot" value="{{ $reservation_setting->value['disable_slot'] ?? '' }}" placeholder="1">
                            <select>
                                <option>{{__('Minutes')}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slot-time">
                <h6>{{__('Time Slots Settings')}}</h6>
                <div class="day">
                    <button type="buton" class="active">{{__('Monday')}}</button>
                    <button type="buton">{{__('Tuesday')}}</button>
                    <button type="buton">{{__('Wednesday')}}</button>
                    <button type="buton">{{__('Thursday')}}</button>
                    <button type="buton">{{__('Friday')}}</button>
                    <button type="buton">{{__('Saturday')}}</button>
                    <button type="buton">{{__('Sunday')}}</button>
                </div>
                <input type="hidden" name="day" id="selectedDay">
            </div>
                <div class="slot-type table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th class="type">{{__('Slot Type')}}</th>
                                <th>{{__('Start Time')}}</th>
                                <th>{{__('End Time')}}</th>
                                <th class="text-center">{{__('Time Difference')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="tr">
                                <td class="eting">{{__('Breakfast')}}</td>
                                <td class="eting-time">
                                    <input type="time" class="startTimeVal" name="slots[breakfast][start_time]" value="08:00">
                                </td>
                                <td class="eting-time">
                                    <input type="time" class="endTimeVal" name="slots[breakfast][end_time]" value="11:00">
                                </td>
                                <td class="eting-number text-center">
                                    <input type="number" class="timeDifferenceVal" name="slots[breakfast][time_difference]" max="60" placeholder="Minute">
                                </td>
                                <td>
                                    <div class="form-check form-switch switch-btn">
                                        <input class="form-check-input slot-check-input" type="checkbox" name="slots[breakfast][is_available]" id="flexSwitchCheckChecked" value="1" checked>
                                    </div>
                                </td>
                            </tr>
                            <tr class="tr">
                                <td class="eting">{{__('Lunch')}}</td>
                                <td class="eting-time">
                                    <input type="time" class="startTimeVal" name="slots[lunch][start_time]" value="12:00">
                                </td>
                                <td class="eting-time">
                                    <input type="time" class="endTimeVal" name="slots[lunch][end_time]" value="05:00">
                                </td>
                                <td class="eting-number text-center">
                                    <input type="number" class="timeDifferenceVal" name="slots[lunch][time_difference]" max="60" placeholder="Minute">
                                </td>
                                <td>
                                    <div class="form-check form-switch switch-btn">
                                        <input class="form-check-input slot-check-input" type="checkbox" name="slots[lunch][is_available]" id="flexSwitchCheckChecked" value="1" checked>
                                    </div>
                                </td>
                            </tr>
                            <tr class="tr">
                                <td class="eting">{{__('Dinner')}}</td>
                                <td class="eting-time">
                                    <input type="time" class="startTimeVal" name="slots[dinner][start_time]" value="06:00">
                                </td>
                                <td class="eting-time">
                                    <input type="time" class="endTimeVal" name="slots[dinner][end_time]" value="10:00">
                                </td>
                                 <td class="eting-number text-center">
                                    <input type="number" class="timeDifferenceVal" name="slots[dinner][time_difference]" max="60" placeholder="Minute">
                                </td>
                                <td>
                                    <div class="form-check form-switch switch-btn">
                                        <input class="form-check-input slot-check-input" type="checkbox" name="slots[dinner][is_available]" id="flexSwitchCheckChecked" value="1" checked>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="setting-btn">
                    <button class="theme-btn border-btn">{{__('Reset')}}</button>
                    <button class="theme-btn submit-btn">{{__('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="getTimeSlotSetting" value="{{ route('business.reservation-setting.getData') }}">

@endsection
