@foreach($sales as $sale)
    <tr>
        <td>{{ ($sales->currentPage() - 1) * $sales->perPage() + $loop->iteration }}</td>
        <td class="text-start">{{ $sale->invoiceNumber }}</td>
        <td class="text-start">{{ $sale->party?->name }}</td>
        <td class="text-start">{{ currency_format($sale->totalAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($sale->discountAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($sale->paidAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($sale->dueAmount, currency: business_currency()) }}</td>
        <td class="text-start">{{ currency_format($sale->tax_amount, currency: business_currency()) }}</td>
        <td class="text-start">{{ $sale->payment_type->name ?? '' }}</td>
        <td class="text-start">{{ formatted_date($sale->saleDate) }}</td>
    </tr>
@endforeach

@if ($sales->count() > 0)
    <tr class="fw-bold">
        <td class="fw-bold">{{__('Total')}}:</td>
        <td colspan="2"></td>
        <td class="fw-bold text-start">{{ currency_format($sales->sum('totalAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($sales->sum('discountAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($sales->sum('paidAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($sales->sum('dueAmount'), currency: business_currency()) }}</td>
        <td class="fw-bold text-start">{{ currency_format($sales->sum('tax_amount'), currency: business_currency()) }}</td>
        <td colspan="2"></td>
    </tr>
@endif
