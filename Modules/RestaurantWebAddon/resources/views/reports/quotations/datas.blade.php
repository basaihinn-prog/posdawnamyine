@foreach($quotations as $quotation)
    <tr>
        <td>{{ ($quotations->currentPage() - 1) * $quotations->perPage() + $loop->iteration }}</td>
        <td class="text-start">{{ $quotation->invoiceNumber }}</td>
        <td class="text-start">{{ $quotation->party?->name }}</td>
        <td class="text-start">{{ currency_format($quotation->totalAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($quotation->discountAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($quotation->paidAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($quotation->dueAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($quotation->tax_amount, currency: business_currency()) }}</td>
        <td class="text-start">{{ $quotation->payment_type->name ?? '' }}</td>
        <td class="text-start">{{ formatted_date($quotation->quotationDate) }}</td>
    </tr>
@endforeach

@if ($quotations->count() > 0)
    <tr class="fw-bold">
        <td class="fw-bold">{{__('Total')}}:</td>
        <td colspan="2"></td>
        <td class="fw-bold text-start">{{ currency_format($quotations->sum('totalAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($quotations->sum('discountAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($quotations->sum('paidAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($quotations->sum('dueAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($quotations->sum('tax_amount'), currency: business_currency()) }}</td>
        <td colspan="2"></td>
    </tr>
@endif
