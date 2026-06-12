<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Invoice No') }}</th>
            <th>{{ __('Party Name') }}</th>
            <th>{{ __('Total') }}</th>
            <th>{{ __('Discount') }}</th>
            <th>{{ __('Paid') }}</th>
            <th>{{ __('Due') }}</th>
            <th>{{ __('Payment') }}</th>
            <th>{{ __('Status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchases as $purchase)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ formatted_date($purchase->purchaseDate) }}</td>
                <td>{{ $purchase->invoiceNumber }}</td>
                <td>{{ $purchase->party->name }}</td>
                <td>{{ currency_format($purchase->totalAmount, currency: business_currency()) }}
                </td>
                <td>{{ currency_format($purchase->discountAmount, currency: business_currency()) }}
                </td>
                <td>{{ currency_format($purchase->paidAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($purchase->dueAmount, currency: business_currency()) }}</td>
                <td>{{ $purchase->payment_type->name ?? '' }}</td>
                <td>
                    @if ($purchase->dueAmount == 0)
                        <div>{{ __('Paid') }}</div>
                    @elseif($purchase->dueAmount > 0 && $purchase->dueAmount < $purchase->totalAmount)
                        <div>{{ __('Partial') }}</div>
                    @else
                        <div>{{ __('Unpaid') }}</div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
