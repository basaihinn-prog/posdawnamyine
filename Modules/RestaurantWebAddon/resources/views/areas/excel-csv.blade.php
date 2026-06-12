<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('name') }}</th>
            <th>{{ __('Tables') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($areas as $area)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $area->name }}</td>
                    <td>{{ $area->total_table }}</td>
                </tr>
        @endforeach
    </tbody>
</table>
