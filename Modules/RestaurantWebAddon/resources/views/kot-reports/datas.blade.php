<div class="responsive-table m-0">
    <table class="table" id="datatable">
        <thead>
            <tr>
                <th>{{ __('SL') }}.</th>
                <th class="text-start">{{ __('Kot') }}</th>
                <th class="text-start">{{ __('Order') }}</th>
                <th class="text-start">{{ __('Date & Time') }}</th>
                <th class="text-start">{{ __('Customers') }}</th>
                <th class="text-center">{{ __('Items') }}</th>
                <th class="text-start">{{ __('Table') }}</th>
                <th class="text-center">{{ __('Status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kots as $kot)
                <tr>
                    <td>{{ $kots->firstItem() + $loop->index }}</td>
                    <td class="text-start">
                        <a href="#view-kot-report" class="kot-report-view-btn kots-btn" data-bs-toggle="modal"
                            data-status="{{ ucfirst($kot->kot_ticket?->status) }}" data-kot="{{ $kot->kot_ticket?->kot_number }}"
                            data-item="{{ $kot->total_item }}" data-date="{{ formatted_date($kot->saleDate, 'd/m/Y') }} {{ formatted_time($kot->saleDate) }}"
                            data-order="{{ $kot->invoiceNumber }}" data-customer="{{ $kot->party?->name }}"
                            data-reason="{{ $kot->kot_ticket?->cancel_reason?->reason ?? 'N/A' }}" data-table="{{ $kot->table?->name }}"
                            data-details='@json($kot->details)'>
                            {{ $kot->kot_ticket?->kot_number }}
                        </a>
                    </td>
                    <td class="text-start">{{ $kot->invoiceNumber }}</td>
                    <td class="text-start">{{ formatted_date($kot->saleDate) }} {{ formatted_time($kot->saleDate) }}</td>
                    <td class="text-start">{{ $kot->party?->name }}</td>
                    <td class="text-center">{{ $kot->total_item }}</td>
                    <td class="text-start">{{ $kot->table?->name }}</td>
                    <td class="text-center">
                        <span class="
                            {{
                                $kot->kot_ticket?->status === 'pending' ? 'kot-pending' :
                                ($kot->kot_ticket?->status === 'preparing' ? 'kot-preparing' :
                                ($kot->kot_ticket?->status === 'ready' ? 'kot-ready' :
                                ($kot->kot_ticket?->status === 'served' ? 'kot-served' :
                                ($kot->kot_ticket?->status === 'cancelled' ? 'kot-cancelled' : ''))))
                            }}">
                            {{ ucfirst($kot->kot_ticket?->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-3">
    {{ $kots->links('vendor.pagination.bootstrap-5') }}
</div>
