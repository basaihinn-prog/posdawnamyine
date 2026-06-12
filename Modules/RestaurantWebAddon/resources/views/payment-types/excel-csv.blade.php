<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Name') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($paymentTypes as $type)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $type->name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
