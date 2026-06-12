@foreach($due_collections as $due_collection)
    <tr>
        <td>{{ ($due_collections->currentPage() - 1) * $due_collections->perPage() + $loop->iteration }}</td>
        <td class="text-start">{{ $due_collection->invoiceNumber }}</td>
        <td class="text-start">{{ formatted_date($due_collection->paymentDate) }}</td>
        <td class="text-start">{{ currency_format($due_collection->totalDue, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($due_collection->payDueAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ $due_collection->payment_type?->name }}</td>
        @if ($due_collection->dueAmountAfterPay == 0)
            <td>
                <div class="status-badge paid-badge">{{ __('Paid') }}</div>
            </td>
        @else
            <td>
                <div class="status-badge partial-paid">{{ __('Partial') }}</div>
            </td>
        @endif
    </tr>
@endforeach
