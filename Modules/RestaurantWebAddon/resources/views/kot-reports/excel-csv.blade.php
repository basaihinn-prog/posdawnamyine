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
                <td>{{ $loop->index + 1 }}</td>
                <td class="text-start">{{ $kot->kot_ticket?->kot_number}}</td>
                <td class="text-start">{{ $kot->invoiceNumber }}</td>
                <td class="text-start">{{ formatted_date($kot->saleDate) }} {{ formatted_time($kot->saleDate) }}</td>
                <td class="text-start">{{ $kot->party?->name }}</td>
                <td class="text-center">{{ $kot->total_item }}</td>
                <td class="text-start">{{ $kot->table?->name }}</td>
                <td class="text-center">{{ ucfirst($kot->kot_ticket?->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

