<table>
    <thead>
        <tr>
            <th>{{ __('SL') }}.</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Rate') }}</th>
            <th>{{ __('Sub Taxs') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vats as $vat)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $vat->name }}</td>
                <td>{{ $vat->rate }}%</td>
                <td>
                    @if (!empty($vat->sub_tax))
                        {{ collect($vat->sub_tax)->pluck('name')->implode(', ') }}
                    @else
                        {{__('N/A')}}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
