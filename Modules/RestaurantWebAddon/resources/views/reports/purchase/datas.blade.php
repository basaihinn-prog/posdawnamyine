@foreach($purchases as $purchase)
    <tr>
        <td>{{ ($purchases->currentPage() - 1) * $purchases->perPage() + $loop->iteration }}</td>
        <td class="text-start">{{ $purchase->invoiceNumber }}</td>
        <td class="text-start">{{ $purchase->party?->name }}</td>
        <td class="text-start">{{ currency_format($purchase->totalAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($purchase->discountAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($purchase->paidAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($purchase->dueAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($purchase->tax_amount, currency: business_currency()) }}</td>
        <td class="text-start">{{ $purchase->payment_type->name ?? '' }}</td>
        <td class="text-start">{{ formatted_date($purchase->purchaseDate) }}</td>
    </tr>
@endforeach

@if ($purchases->count() > 0)
    <tr class="fw-bold">
        <td class="fw-bold">{{__('Total')}}:</td>
        <td colspan="2"></td>
        <td class="fw-bold text-start">{{ currency_format($purchases->sum('totalAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($purchases->sum('discountAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($purchases->sum('paidAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($purchases->sum('dueAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($purchases->sum('tax_amount'), currency: business_currency()) }}</td>
        <td colspan="2"></td>
    </tr>
@endif
