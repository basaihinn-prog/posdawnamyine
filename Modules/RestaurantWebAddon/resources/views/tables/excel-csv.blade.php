<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Table') }}</th>
            <th>{{ __('Capacity') }}</th>
            <th>{{ __('Availability') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tables as $table)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $table->name }}</td>
                <td>{{ $table->capacity }}</td>
                @if ($table->is_booked == '1')
                    <td>{{ __('Booked') }}</td>
                @else
                    <td>{{ __('Avaiable') }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
