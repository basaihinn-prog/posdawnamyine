@extends('layouts.web.blank')

@section('main_content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <a class="theme-btn d-block text-center" href="{{ route('order.status', ['status' => 'failed']) }}"><i class="fas fa-arrow-left"></i> {{ __('Go Back') }}</a>
                            </div>
                            <div class="col-sm-6 mt-3 mt-sm-0">
                                <button class="theme-btn d-block w-100 pay-button" id="rzp-button1">{{ __('Pay Now') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="post" class="status" action="{{ route('paystack.status') }}">
        @csrf
        <input type="hidden" name="ref_id" id="ref_id" value="{{ 'ps_' . Str::random(15) }}">
        <input type="hidden" value="{{ $Info['currency'] }}" id="currency">
        <input type="hidden" value="{{ $Info['amount'] }}" id="amount">
        <input type="hidden" value="{{ $Info['public_key'] }}" id="public_key">
        <input type="hidden" value="{{ $Info['email'] ?? Auth::user()->email }}" id="email">
    </form>
@endsection

@push('js')
    <script src="{{ asset('assets/js/paystack/inline.js') }}"></script>
    <script src="{{ asset('assets/js/paystack/custom.js') }}"></script>
@endpush
