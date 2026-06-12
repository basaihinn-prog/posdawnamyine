@foreach($transactions as $transcation)
    <tr>
        <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
        <td>{{ $transcation->invoiceNumber }}</td>
        <td>{{ formatted_date($transcation->date) }}</td>
        @if ($transcation->type == 'debit')
            <td>{{__('Purchase')}}</td>
        @else
            <td>{{__('Sales')}}</td>
        @endif
        <td>{{ currency_format($transcation->total_amount, currency: business_currency()) }}</td>
        <td>{{ currency_format($transcation->paid_amount, currency: business_currency()) }}</td>
        <td>{{ currency_format($transcation->due_amount, currency: business_currency()) }}</td>
        <td>{{ $transcation->payment_type?->name }}</td>
        <td>
            @if($transcation->due_amount == 0)
                <div class="status-badge paid-badge">{{ __('Paid') }}</div>
            @elseif($transcation->due_amount > 0 && $transcation->due_amount < $transcation->total_amount)
                <div class="status-badge partial-paid">{{ __('Partial') }}</div>
            @else
                <div class="status-badge unpaid-badge">{{ __('Unpaid') }}</div>
            @endif
        </td>
    </tr>
@endforeach
