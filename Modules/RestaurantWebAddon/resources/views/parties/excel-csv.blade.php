<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th> {{ __('Name') }} </th>
            <th> {{ __('Email') }} </th>
            <th> {{ __('Type') }} </th>
            <th> {{ __('Phone') }} </th>
            <th> {{ __('Due') }} </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($parties as $party)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $party->name }}</td>
                <td>{{ $party->email }}</td>
                <td>{{ ucfirst($party->type) }}</td>
                <td>{{ $party->phone }}</td>
                <td>{{ currency_format($party->due, currency: business_currency()) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
