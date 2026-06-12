<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Unit Name') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($units as $unit)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $unit->unitName }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
