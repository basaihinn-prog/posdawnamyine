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
        @foreach ($sales as $sale)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $sale->invoiceNumber }}</td>
                <td>{{ $sale->party?->name }}</td>
                <td>{{ currency_format($sale->totalAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($sale->discountAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($sale->paidAmount, currency: business_currency()) }}</td>
                <td>{{ currency_format($sale->dueAmount, currency: business_currency()) }}</td>
                <td>{{ $sale->payment_type->name ?? '' }}</td>
                <td>{{ formatted_date($sale->saleDate) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

