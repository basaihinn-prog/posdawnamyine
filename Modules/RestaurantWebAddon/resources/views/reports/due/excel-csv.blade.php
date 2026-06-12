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
        @foreach ($due_lists as $due_list)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $due_list->name }}</td>
                <td>{{ $due_list->email }}</td>
                <td>{{ $due_list->phone }}</td>
                <td>{{ ucfirst($due_list->type) }}</td>
                <td>{{ currency_format($due_list->due, currency: business_currency()) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
