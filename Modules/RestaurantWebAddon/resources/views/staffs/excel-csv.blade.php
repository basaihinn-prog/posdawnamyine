<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Phone') }}</th>
            <th>{{ __('Designation') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($staffs as $staff)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $staff->name }}</td>
                <td>{{ $staff->email }}</td>
                <td>{{ $staff->phone }}</td>
                <td>
                    @if ($staff->designation == 'delivery_boy')
                        {{ __('Delivery Boy') }}
                    @else
                        {{ ucfirst($staff->designation) }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
