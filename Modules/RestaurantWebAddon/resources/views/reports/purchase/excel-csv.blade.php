<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Invoice No') }}</th>
            <th>{{ __('Party Name') }}</th>
            <th>{{ __('Total Amount') }}</th>
            <th>{{ __('Discount Amount') }}</th>
            <th>{{ __('Paid Amount') }}</th>
            <th>{{ __('Due Amount') }}</th>
            <th>{{ __('Payment Type') }}</th>
            <th>{{ __('Sale Date') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchases as $purchase)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $purchase->invoiceNumber }}</td>
                <td>{{ $purchase->party?->name }}</td>
                <td>{{ currency_format($purchase->totalAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($purchase->discountAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($purchase->paidAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($purchase->dueAmount, currency: business_currency()) }}</td>
                <td>{{ $purchase->payment_type->name ?? '' }}</td>
                <td>{{ formatted_date($purchase->purchaseDate) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

