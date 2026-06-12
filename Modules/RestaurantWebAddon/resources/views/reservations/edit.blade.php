@extends('restaurantwebaddon::layouts.master')

@section('title')
    {{ __('Reservation Edit') }}
@endsection

@section('main_content')
<div class="erp-table-section">
    <div class="container-fluid">
        <div class="section-title mb-3 d-flex align-items-center justify-content-between flex-wrap">
                <h2> <a href="https://restaurantapp.acnoo.com/business/dashboard">{{__('Dashboard')}}</a>
                    <span>/ {{ __('Reservation Edit') }}</span>
                </h2>
            </div>
        <div class="card shadow-sm">
            <div class="card-bodys">
                @if ($reservation_setting?->value['is_restaurant'] == 1)
                <div class="booking-box order-form-section">
                    <form action="{{ route('business.reservations.update', $reservation->id) }}" method="post" class="ajaxform_instant_reload">
                        @csrf
                        @method('put')
                        <h3 class="mt-0">{{__('Select Your Booking Details')}}</h3>
                        <div class="form-row">
                            <input class="form-control" type="date" name="date"  value="{{ $reservation->date ?? date('Y-m-d') }}" id="reservationDate">
                            <div class="gpt-up-down-arrow position-relative">
                                <select class="form-control table-select w-100 " name="type" id="reservationType">
                                    <option @selected($reservation->type == 'breakfast') value="breakfast">{{__('Breakfast')}}</option>
                                    <option @selected($reservation->type == 'lunch') value="lunch">{{__('Lunch')}}</option>
                                    <option @selected($reservation->type == 'dinner') value="dinner">{{__('Dinner')}}</option>
                                </select>
                                <span></span>
                            </div>
                            <input type="hidden" name="time" id="selectedTime" value="{{ $reservation->time ?? '' }}">

                            <div class="gpt-up-down-arrow position-relative">
                                <select class="form-control table-select w-100" name="guest">
                                    <option @selected($reservation->guest == '1') value="1">{{__('1 Guest')}}</option>
                                    <option @selected($reservation->guest == '2') value="2">{{__('2 Guest')}}</option>
                                    <option @selected($reservation->guest == '3') value="3">{{__('3 Guest')}}</option>
                                    <option @selected($reservation->guest == '4') value="4">{{__('4 Guest')}}</option>
                                    <option @selected($reservation->guest == '5') value="5">{{__('5 Guest')}}</option>
                                    <option @selected($reservation->guest == '6') value="6">{{__('6 Guest')}}</option>
                                    <option @selected($reservation->guest == '7') value="7">{{__('7 Guest')}}</option>
                                    <option @selected($reservation->guest == '8') value="8">{{__('8 Guest')}}</option>
                                    <option @selected($reservation->guest == '9') value="9">{{__('9 Guest')}}</option>
                                    <option @selected($reservation->guest == '10') value="10">{{__('10 Guest')}}</option>
                                    <option @selected($reservation->guest == '11') value="11">{{__('11 Guest')}}</option>
                                    <option @selected($reservation->guest == '12') value="12">{{__('12 Guest')}}</option>
                                    <option @selected($reservation->guest == '13') value="13">{{__('13 Guest')}}</option>
                                    <option @selected($reservation->guest == '14') value="14">{{__('14 Guest')}}</option>
                                    <option @selected($reservation->guest == '15') value="15">{{__('15 Guest')}}</option>
                                    <option @selected($reservation->guest == '16') value="16">{{__('16 Guest')}}</option>
                                </select>
                                <span></span>
                            </div>
                        </div>
                        <div id="availableTimeArea">
                            <h3>{{__('Select Time')}}</h3>
                            <div class="time-row" id="timeSlotsArea">
                                {{-- dynamic time slot come from js --}}
                            </div>
                            <div class="add-suplier-modal-wrapper d-block mt-3">
                                <div class="row">
                                    <div class="col-lg-4 mb-2">
                                        <label>{{__('Customer Name')}}</label>
                                        <input type="text" name="name" value="{{ $reservation->name }}" class="form-control" placeholder="Enter Customer Name">
                                    </div>
                                    <div class="col-lg-4 mb-2">
                                        <label>Phone Number</label>
                                        <input type="number" name="phone" value="{{ $reservation->phone }}" class="form-control" placeholder="Enter Phone No.">
                                    </div>
                                    <div class="col-lg-4 mb-2">
                                        <label>{{__('Email Address')}}</label>
                                        <input type="email" name="email" value="{{ $reservation->email }}" class="form-control" placeholder="Enter Email Address">
                                    </div>
                                </div>
                            </div>
                            <h3>{{__('Special Request')}}</h3>
                            <textarea class="mb-4" name="description" placeholder="Enter your special request">{{ $reservation->description }}</textarea>
                            @usercan('reservations.create')
                            <div class="action-row">
                                <button type="reset" class="border-btn theme-btn">{{__('Reset')}}</button>
                                <button class="theme-btn submit-btn">{{__('Reserve Now')}}</button>
                            </div>
                            @endusercan
                        </div>

                        <div class="empty-time d-none" id="noTimeArea">
                            <img src="{{ asset('assets/images/icons/empty.png') }}" alt="" srcset="">
                            <h3>{{__('Sorry! No time slots left. Try a different date or meal time.')}}</h3>
                        </div>
                    </form>
                </div>
                @else
                <div class="empty-time">
                    <img src="{{ asset('assets/images/icons/empty.png') }}" alt="" srcset="">
                    <h3>{{__('Sorry! Reservation not available at this moment.')}}</h3>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="getTimeSlots" value="{{ route('business.reservations.getTimeSlots') }}">
@endsection
