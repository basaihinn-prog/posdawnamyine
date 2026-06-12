<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Phone') }}</th>
            <th>{{ __('Type') }}</th>
            <th>{{ __('Due Amount') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dues as $due)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $due->name }}</td>
                <td>{{ $due->email }}</td>
                <td>{{ $due->phone }}</td>
                <td>{{ ucfirst($due->type) }}</td>
                <td>{{ currency_format($due->due, currency: business_currency()) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
