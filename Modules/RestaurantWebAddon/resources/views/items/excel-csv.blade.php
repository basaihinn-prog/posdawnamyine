<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Item Name') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $item->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

