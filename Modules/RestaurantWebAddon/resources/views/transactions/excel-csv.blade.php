<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Invoice ID') }}</th>
            <th>{{ __('Date') }}</th>
            <th>{{ __('Type') }}</th>
            <th>{{ __('Total') }}</th>
            <th>{{ __('Paid') }}</th>
            <th>{{ __('Due') }}</th>
            <th>{{ __('Payment Type') }}</th>
            <th>{{ __('Status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transcation)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $transcation->invoiceNumber }}</td>
                <td>{{ formatted_date($transcation->date) }}</td>
                @if ($transcation->type == 'debit')
                    <td>{{ __('Purchase') }}</td>
                @else
                    <td>{{ __('Sales') }}</td>
                @endif
                <td>{{ currency_format($transcation->total_amount, currency: business_currency()) }}</td>
                <td>{{ currency_format($transcation->paid_amount, currency: business_currency()) }}</td>
                <td>{{ currency_format($transcation->due_amount, currency: business_currency()) }}</td>
                <td>{{ $transcation->payment_type?->name }}</td>
                <td>
                    @if ($transcation->due_amount == 0)
                        <div>{{ __('Paid') }}</div>
                    @elseif($transcation->due_amount > 0 && $transcation->due_amount < $transcation->total_amount)
                        <div>{{ __('Partial') }}</div>
                    @else
                        <div>{{ __('Unpaid') }}</div>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
